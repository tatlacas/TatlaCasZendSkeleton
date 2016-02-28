<?php
/**
 * TatlaCas Customized
 *
 *
 * @copyright Copyright (c) 20013-2014 Fundamental Technologies (Private) Limited (http://www.funtechno.com)
 * @author   Tatenda Caston Hove <tathove@gmail.com> on 01/09/2015.
 *
 */

namespace Mobile\Model;

use Application\Entity\AdminUpdates;
use Application\Entity\Branches;
use Application\Entity\ClustersPayments;
use Application\Entity\NettcashAccounts;
use Application\Entity\PackagePlans;
use Application\Entity\Referrals;
use Application\Entity\SubscribedPackages;
use Application\Entity\UserCapturer;
use Application\Entity\UserPolicies;
use Application\Entity\Users;
use Application\Model\Constants;
use Application\Model\DoctrineInitialization;
use Application\Model\infobipSMSMessaging;
use Application\Model\PasswordCompatibilityLibrary;
use Zend\Session\Container;

class Registration extends DoctrineInitialization
{


    const TO_MILLISECONDS = 1000;

    function __construct($service_locator)
    {
        parent::__construct($service_locator);
    }

    public function registerUser($json)
    {
        $this->setEntityManager();

        if ($json != NULL) {

            $nettcash_user = new NettcashAccounts();
            $user = new Users();


            if (version_compare(PHP_VERSION, '5.3.7', '<')) {
                return Constants::xmlError(Constants::SORRY_SHIRI_DOES_NOT_RUN);
            }

            $request = json_decode($json, true);

            $referer = $request[Constants::REFERER];
            $name = $request[Constants::FIRST_NAME];
            $sname = $request[Constants::LAST_NAME];
            $phone_number = $request[Constants::PHONE_NUMBER];
            $id_number = $request[Constants::ID_NUMBER];
            $dateOfBirth = $request[Constants::DATE_OF_BIRTH];
            $createdAt = $request[Constants::CREATED_AT];
            $nearest_branch = $request[Constants::NEAREST_BRANCH];
            $referrerId = $request[Constants::REFERER_ID];
            $user_server_id = $request[Constants::SERVER_ID];
            $pincode = $request[Constants::PINCODE];
            $gender = $request[Constants::GENDER];
            $plan_name = $request[Constants::PLAN_NAME];
            $gcm_reg_id = $request[Constants::GCM_REGID];
            $joiningState = $request[Constants::IS_JOIN_ANOTHER];

            $db = new DBUtils($this->service_locator);


            if (strlen($phone_number) <= Constants::PHONE_NUMBER_LENGTH && $phone_number !== $referer) {
                $phone_number = htmlspecialchars($phone_number);

                $result = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($phone_number);


                if ($result != null) {
                    //user exits
                    if ($joiningState === Constants::JOINING_MYSELF_STATE) {
                        $resulting_id = $result->getUserId();
                        $policy_number = $resulting_id;//$userPol->getId();
                        return $this->return_results(Constants::ON_USER_EXIST_CONST, $resulting_id, $policy_number);
                    } else {

                        return $this->return_die(Constants::ON_USER_EXIST_CONST);
                    }
                }

            } else {
                return $this->return_die(Constants::ON_WRONG_NUMBER_CONST);//Wrong Number");
            }


            $account_created = false;
            $resulting_id = 0;


            $referredBy = null;
            $referredByRec = null;
            if ($referrerId != null && $referrerId != "-1") {
                $referredBy = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByUserId($referrerId);

            }
            if ($referredBy == null) {

                $referredBy = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($referer);


            }


            if ($referredBy != null) {


                $referredByRec = $this->entity_manager->getRepository(Constants::ENTITY_REFERRALS)->findOneByReferredPhoneNumber($phone_number);
                if ($referredByRec != null) {
                    if ($referredBy != $referredByRec->getReferrer()) {
                        $date_t = $referredByRec->getDateReferred();
                        $date_t1 = $date_t / 1000;
                        $date = new \DateTime();
                        $date->setTimestamp($date_t1);
                        $today = new \DateTime('now');
                        $interval = $date->diff($today, true);
                        if ($interval && $interval->days > Constants::REFERRAL_LOCK_PERIOD) {
                            //replace user in database since his lock for invitation has expired
                            $referredByRec->setReferrer($referredBy);
                        } else {
                            //Provided user is the wrong referrer, use one in database
                            $referredBy = $referredByRec->getReferrer();
                        }
                    }
                    $today = round(microtime(true) * 1000);
                    $referredByRec->setDateJoined($today);
                    $this->entity_manager->flush();
                } else {
                    //insert referrer
                    $referredByRec = new Referrals();
                    $today = round(microtime(true) * 1000);
                    $referredByRec->setDateReferred($today)
                        ->setReferredPhoneNumber($phone_number)
                        ->setReferrer($referredBy)
                        ->setDateJoined($today);
                    $this->entity_manager->persist($referredByRec);

                    $this->entity_manager->flush();

                }

            }else {
                return $this->return_die(Constants::REFERRER_DOES_NOT_EXIST);

            }

            if ($pincode === Constants::SHIRI_DEFAULT_PASSWORD) {
                $pincode = $this->generatePin(Constants::PINCODE_MIN_LENGTH);

                //==========> notify who refered <==========
                if ($referredBy != null) {
                    $InitialFname = substr($referredBy->getFirstName(), 0, 1);
                    $ref_message = Constants::WELCOME . ' ' . $InitialFname . ' ' . $referredBy->getLastName() . ' ' . Constants::HAS_JOINED_YOU_TO_SHIRI_YOUR_ACCOUNT_PINCODE_IS . $pincode;
                }
                //todo enable this
                $infobipSMSMessaging = new infobipSMSMessaging();
//                //    $phone_number  = "+263783211562";
                $result = $infobipSMSMessaging->sendmsg($phone_number, Constants::SHIRI_NAME, $ref_message);//Constants::WELCOME_YOUR_ACCOUNT_PINCODE_IS . $pincode);

                //todo process sms response
//                    if (empty($result)) {
//                        $result = json_encode($result);
//                    }

                $pincode = (string)$pincode;
            }
            //   else {
            if ($referer !== Constants::SHIRI_CODE && $referer !== Constants::DOVES_CODE) {

                $res = $db->save_individual_client_messages(Constants::YOU_HAVE_JOINED_A_FRIEND,
                    '' . $name . ' ' . $sname
                    , $referer);
                $ref_gcm_reg_id = "";
                $ref_referer = $referer;
                $ref_phone_number = "";

                do {
                    $ref_user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($ref_referer);

                    if ($ref_user != null) {

                        $ref_referer = $ref_user->getPhoneNumber();
                        $ref_gcm_reg_id = $ref_user->getGcmRegid();
                        if ($ref_gcm_reg_id === Constants::GCM_REG_ID_DEFAULT) {
                            $infobipSMSMessaging = new infobipSMSMessaging();
                            //    $phone_number  = "+263783211562";
                            //todo process sms response
                            $result = $infobipSMSMessaging->sendmsg($ref_referer, Constants::SHIRI_GOOD_NEWS,
                                Constants::CONGRATULATIONS . $name . Constants::JUST_JOINED_YOUR_NETWORK);
                            $res = $db->save_individual_client_messages(Constants::SHIRI_GOOD_NEWS_STR,
                                Constants::CONGRATULATIONS . $name . Constants::JUST_JOINED_YOUR_NETWORK
                                , $ref_referer);
                        } else {
                            //todo process gcm response
                            $db->send_notification($ref_gcm_reg_id,
                                Constants::SHIRI_GOOD_NEWS, Constants::CONGRATULATIONS . $name . ' ' . $sname . ' ' . Constants::JUST_JOINED_YOUR_NETWORK);
                            $res = $db->save_individual_client_messages(Constants::SHIRI_GOOD_NEWS_STR,
                                Constants::CONGRATULATIONS . $name . ' ' . $sname . ' ' . Constants::JUST_JOINED_YOUR_NETWORK
                                , $ref_referer);
                        }
                        //taking the results
                        //   $user = new Users();
                        $ref = $ref_user->getReferer();
                        $checking = $ref_referer;
                        $ref_referer = $ref->getPhoneNumber();
                        if ($checking === $ref_referer)
                            $ref_referer = Constants::SHIRI_DEFAULT_NUMBER;


                    }

                } while ($ref_referer !== Constants::SHIRI_DEFAULT_NUMBER && $ref_referer !== Constants::DOVES_DEFAULT_NUMMBER);

            }
            //  }
            if ($referer === Constants::SHIRI_CODE) {
                $ref_user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber(Constants::SHIRI_DEFAULT_NUMBER);

            } else if ($referer === Constants::DOVES_CODE) {
                $ref_user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber(Constants::DOVES_DEFAULT_NUMMBER);

            } else {
                $ref_user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($referer);

                if ($ref_user == null) {

                    //Referer not found
                    return $this->return_die(Constants::ON_FAILURE_CONST);
                }
            }
            //
            if (version_compare(PHP_VERSION, '5.5.0', '<')) {

                $passwrd = new PasswordCompatibilityLibrary(Constants::PASSWORD_DEFAULT);
                $user_pincode_hash = $passwrd->password_hash($pincode, Constants::PASSWORD_DEFAULT);
                ///  die($user_pincode_hash);
            } else
                $user_pincode_hash = password_hash($pincode, Constants::PASSWORD_DEFAULT);

            list($branch_name, $branch_id) = explode(':', $nearest_branch);
            //$Branches =  new Branches();
            //   $Branches->setBranchId();

            $branch_res = $this->entity_manager->getRepository(Constants::ENTITY_BRANCHES)->findOneByBranchId((int)$branch_id);
            if ($branch_res == null) {
                // $Branches->setBranchName($branch_res -> getBranchName());
                return $this->return_die(Constants::BRANCH_NOT_SET);
            }
            if ($gender === Constants::MALE) {
                $sex = true;
            } else {
                $sex = false;
            }

            $user->setFirstName($name)
                ->setLastName($sname)
                ->setPhoneNumber($phone_number)
                ->setIdNumber($id_number)
                ->setDateOfBirth($dateOfBirth)
                ->setCreatedAt($createdAt)
                ->setPincode($user_pincode_hash)
                ->setGender($sex)
                ->setBranch($branch_res)
                ->setReferer($ref_user)
                ->setGcmRegid($gcm_reg_id);
            // ->setNettcashRegistered($nettcash_reg_state);

            $this->entity_manager->persist($user);

            //if captured by someone then capture the user_capturer record
            if ($joiningState != Constants::JOINING_MYSELF_STATE) {

                $capturer_phone = $request[Constants::CAPTURER_PHONE];
                if ($capturer_phone != null) {
                    $ref_user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($capturer_phone);

                }
                if ($ref_user != null) {
                    $capturedBy = new UserCapturer();
                    $capturedBy->setCapturedUser($user)
                        ->setCapturer($ref_user)
                        ->setDateCaptured(round(microtime(true) * 1000));
                    $this->entity_manager->persist($capturedBy);


                }

            }
            $account_created = true;


//        todo use plan id instead of plan name
            $plan_name_res = $this->entity_manager->getRepository(Constants::ENTITY_PACKAGE_PLANS)->findOneById($plan_name);
            if ($plan_name_res == null) {
                return $this->return_die(Constants::PLAN_NOT_SET);
            }

            $mil = (int)$user->getCreatedAt();
            $seconds = $mil / self::TO_MILLISECONDS;
            $dt = date("Y-m-d 00:00:00", $seconds);
            $date = new \DateTime($dt);

            $package = new SubscribedPackages();
            $package->setDateActivated($date);
            $package->setUser($user);
            $package->setPackagePlan($plan_name_res);
            $package->setIsDependent(false);
            $package->setUser($user);
            $package->setStatus(true);
            $this->entity_manager->persist($package);

            date_default_timezone_set("UTC");
            $now = new \DateTime();
            $timestamp = $now->getTimestamp();

            $nettcash_user->setUser($user);
            $nettcash_user->setActivated(false);
            $nettcash_user->setDateCreated($timestamp * self::TO_MILLISECONDS);
            $this->entity_manager->persist($nettcash_user);
//            $this->entity_manager->flush();

            $ad_updates = new AdminUpdates();
            $ad_updates->setUser($user);
            $ad_updates->setSendState(false);
            $this->entity_manager->persist($ad_updates);
            $this->entity_manager->flush();
            $resulting_id = $user->getUserId();
            $policy_number = $resulting_id;//$userPol->getId();

            if ($joiningState === Constants::JOINING_MYSELF_STATE) {

                $res = $db->save_individual_client_messages("$name" . " " . $sname, Constants::WELCOME_TO_SHIRI_FUNERAL_PLAN
                    , $phone_number);
                $db->send_notification($gcm_reg_id, "$name" . " " . $sname, Constants::SHIRI_WELCOME_MSG);
            } else {
                $res = $db->save_individual_client_messages(Constants::SHIRI_NAME,
                    $referer . Constants::HAVE_REFERRED_YOU_TO_SHIRI_ACCOUNT_PINCODE . $pincode, $phone_number);
            }

            if ($account_created) {

                $url = Constants::NETTCASH_REG_LIVE_LINK;
                $net_phone_number = str_replace('+', '', $phone_number);

                $ch = curl_init(Constants::NETT_REGISTER_LINK);
                $jsonData = array(
                    'url' => $url,
                    'client' => $net_phone_number,
                    'agentid' => Constants::NETTCASH_AGERT_ID,
                    'password' => md5(Constants::NETTCASH_AGERT_PASSWORD),
                    'firstname' => $name,
                    'lastname' => $sname,
                    'idnumber' => $id_number,
                    'sms' => "yes",
                    'referenceid' => "1",
                );


                $jsonDataEncoded = json_encode($jsonData);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                try {
                    $result = curl_exec($ch);
                    header("Content-type: text/xml");

                    if (curl_errno($ch)) {

                        $this->nettcashRegInProgress($joiningState, $db, $phone_number, $gcm_reg_id);
                        $this->createJobOnError($db, $phone_number);
                        //  $db ->schedule_job($cron_data,$cronJob_url);
                    } else {

                        $xml = simplexml_load_string($result);
                        $result = $xml->result;


                        if ((strpos($result, Constants::CLIENT_ALREADY_EXISTS) !== false) || (strpos($result, Constants::SUCCESS) !== false)) {
                            $nettcash_reg_state = Constants::REGISTERED;
                            $_message = Constants::NETTCASH_ACCOUNT_REGISTERED_SUCCESSFULLY;
                            $code = Constants::NETTCASH_ACC_REG_SUCCESS_STR;

                            if (strpos($result, Constants::CLIENT_ALREADY_EXISTS) !== false) {
                                $_message = Constants::THANK_YOU_NETT_CASH_ACCOUNT_ALREADY_EXISTS;
                                $code = Constants::NETTCASH_ACC_EXITS_STR;
                            }

                            $res = $db->save_individual_client_messages(Constants::NETT_CASH_WALLET_FEEBACK, $_message
                                , $phone_number);
                            if ($joiningState === Constants::JOINING_MYSELF_STATE) {
                                $db->send_notification($gcm_reg_id, Constants::NETT_CASH_WALLET_FEEBACK, $code);
                            } else {
                                $infobipSMSMessaging = new infobipSMSMessaging();
                                //todo process sms response
                                $result = $infobipSMSMessaging->sendmsg($phone_number, Constants::SHIRI_NAME,
                                    $_message);
                            }
                            $raw_results = $this->entity_manager->getRepository(Constants::ENTITY_USER_NETTCASH_ACCOUNT)->findOneByUser($user);
                            if ($raw_results != null) {

                                $raw_results->setActivated(true);
                                $this->entity_manager->flush();
                            } else {

                            }
                        } else {
                            $this->nettcashRegInProgress($joiningState, $db, $phone_number, $gcm_reg_id);
                            $this->createJobOnError($db, $phone_number);
                        }

                    }
                } catch (\Exception $ex) {
                    $this->nettcashRegInProgress($joiningState, $db, $phone_number, $gcm_reg_id);
                    $this->createJobOnError($db, $phone_number);
                }


                return $this->return_results(Constants::ON_SUCCESS_CONST, $resulting_id, $policy_number);
                //   }
            } else {
                return $this->return_results(Constants::ON_FAILURE_CONST, "", "");

            }

        } else {

            return $this->return_results(Constants::ON_FAILURE_CONST, "", "");

        }


    }


    public function generatePin($digits)
    {
        return rand(pow(10, $digits - 1) - 1, pow(10, $digits) - 1);
    }

    public function return_results($results, $resulting_id, $policy_number)
    {
        $xml_output = "<?xml version=\"1.0\"?>\n";
        $xml_output .= "<entries>\n";
        $xml_output .= "\t<entry>\n";
        $xml_output .= "\t\t<resultingId>" . $resulting_id . "</resultingId>\n";
        $xml_output .= "\t\t<policyNumber>" . $policy_number . "</policyNumber>\n";
        $xml_output .= "\t\t<result>" . $results . "</result>\n";

        $xml_output .= "\t</entry>\n";
        $xml_output .= "</entries>";
        return $xml_output;
    }


    public function return_die($value)
    {

        $xml_output = "<?xml version=\"1.0\"?>\n";
        $xml_output .= "<entries>\n";
        $xml_output .= "\t<entry>\n";
        $xml_output .= "\t\t<result>" . $value . "</result>\n";
        $xml_output .= "\t</entry>\n";
        $xml_output .= "</entries>";
        return $xml_output;
    }

    /**
     * @param $db
     * @param $phone_number
     */
    private function createJobOnError($db, $phone_number)
    {
        $conJob_Api = new CronJob(Constants::CRON_JOBS_TOKEN);
        $db = new DBUtils($this->service_locator);
        $return = $conJob_Api->call_schedule_job(Constants::CRON_JOB_ADD_ACTION, array('cron_expression' =>
            Constants::CRON_JOB_TEN_MINUTES . ' * * * *', 'url' =>
            'https://www.kilo-s.com/mobile/adUpdates/1/dmFsdWU9QkNGdkhVTmNEbk9QblV3d0J6VmxRSDBwaUp0alhsLkFuMHQxWGtBOHB3OWRNWFRwT3E%3D'
        , 'cron_job_name' => 'minutes',
            'email_me' => Constants::CRON_JOB_ZERO,
            'log_output_length' => Constants::CRON_JOB_ZERO,
            'testfirst' => Constants::CRON_JOB_ZERO));
        $obj = json_decode($return);
        $result = $obj->status;
        if (strcmp($result, Constants::SUCCESS) == 0) {
            $res = $db->save_cron_job(Constants::NETT_CASH_ACCOUNT, $obj->cron_job_id, $phone_number);

        }
//        $return = $conJob_Api->call_schedule_job(Constants::CRON_JOB_ADD_ACTION, array('cron_expression' =>
//            Constants::CRON_JOB_TEN_MINUTES.' * * * *',
//            'url' => 'https://www.kilo-s.com/mobile/adUpdates/1/dmFsdWU9QkNGdkhVTmNEbk9QblV3d0J6VmxRSDBwaUp0alhsLkFuMHQxWGtBOHB3OWRNWFRwT3E%3D'
//        , 'cron_job_name' => 'minutes',
//            'email_me' => Constants::CRON_JOB_ZERO,
//            'log_output_length' => Constants::CRON_JOB_ZERO,
//            'testfirst' => Constants::CRON_JOB_ZERO));
//        $obj = json_decode($return);
//        $result = $obj->status;
//        if ($result === Constants::SUCCESS) {
//            $res = $db->save_cron_job(Constants::NETT_CASH_ACCOUNT, $obj->cron_job_id, $phone_number);
//
//        }

    }

    /**
     * @param $joiningState
     * @param $db
     * @param $phone_number
     * @param $gcm_reg_id
     */
    private function nettcashRegInProgress($joiningState, $db, $phone_number, $gcm_reg_id)
    {
        $db = new DBUtils($this->service_locator);
        $res = $db->save_individual_client_messages(Constants::NETT_CASH_WALLET_FEEBACK, Constants::NETT_CASH_REGISTRATION_IN_PROGRESS
            , $phone_number);
        if ($joiningState === Constants::JOINING_MYSELF_STATE) {

            $db->send_notification($gcm_reg_id, Constants::NETT_CASH_WALLET_FEEBACK, Constants::NETTCASH_ACC_REG_IN_PROGRESS);
        } else {
            $infobipSMSMessaging = new infobipSMSMessaging();
            //todo process sms response
            $result = $infobipSMSMessaging->sendmsg($phone_number, Constants::SHIRI_NAME,
                Constants::NETT_CASH_REGISTRATION_IN_PROGRESS);
        }

    }




}