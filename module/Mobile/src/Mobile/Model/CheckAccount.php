<?php
/**
 * Created by PhpStorm.
 * User: alois
 * Date: 9/10/2015
 * Time: 12:00 PM
 */

namespace Mobile\Model;


use Application\Entity\Branches;
use Application\Entity\EcocashPayments;
use Application\Entity\NettcashPayments;
use Application\Entity\PackagePlans;
use Application\Entity\PackagePlansFigures;
use Application\Entity\SubscribedPackages;
use Application\Entity\UserDependents;
use Application\Entity\UserPayments;
use Application\Entity\UserPolicies;
use Application\Entity\Users;
use Application\Model\Constants;
use Application\Model\DoctrineInitialization;
use Application\Model\infobipSMSMessaging;
use Application\Model\PasswordCompatibilityLibrary;
use Zend\Http\Client;
use Zend\Http\Request;

class CheckAccount extends DoctrineInitialization
{


    function __construct($service_locator)
    {
        parent::__construct($service_locator);
    }

    public function checkAccount($phone_number)
    {

        if ($phone_number) {

            if (strlen($phone_number) <= Constants::PHONE_NUMBER_LENGTH) {
                $phone_number = htmlspecialchars($phone_number);
                $this->setEntityManager();

                $result = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($phone_number);

                if ($result != null) {

                    return $this->return_results(Constants::ON_USER_EXIST_CONST);
                } else {
                    //todo solve reponse
                    //user does not exist
                    return $this->return_results(Constants::ON_FAILURE_CONST);
                }

            } else {
                //wrong number
                return $this->return_results(Constants::ON_FAILURE_CONST);
            }

        } else {
            //phoneNumber null
            return $this->return_results(Constants::ON_FAILURE_CONST);
        }


    }

    public function return_all_user_data($json)
    {
        $this->setEntityManager();
        $util = new Utils();
        //     $json = true;
        if ($json != NULL) {
            $request = json_decode($json, true);
            $phone_number = $request['phoneNumber'];
            $info_update = $request['amountUpdate'];
            //  $phone_number = '+263783211562';
            if (strlen($phone_number) <= Constants::PHONE_NUMBER_LENGTH) {
                $phone_number = htmlspecialchars($phone_number);
                $user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($phone_number);// "SELECT * FROM users WHERE phone_number = '".$phone_number."'";
                $plan_name_res = $this->entity_manager->getRepository(Constants::ENTITY_PACKAGE_PLANS)->findOneById(Constants::BUS_AND_CASH_ADITIONAL_BEN);
                if ($plan_name_res == null) {
                    return $this->return_results(Constants::PLAN_NOT_SET);
                }
                if ($user != null) {
                    $query = $this->entity_manager->createQueryBuilder();
                    $query->select(array('pack'))
                        ->from('Application\Entity\SubscribedPackages', 'pack')
                        ->where($query->expr()->orX(
                            $query->expr()->eq('pack.user', '?1')
                        ))
                        ->andWhere($query->expr()->orX(
                            $query->expr()->eq('pack.isDependent', '?2')
                        ))
                        ->setParameters(array(1 => $user, 2 => false))
                        ->orderBy('pack.id', 'ASC')
                        ->setMaxResults(1);
                    $query = $query->getQuery();
                    $user_data = $query->getResult();
                    if ($user_data != null) {
//$count = 0;
                        foreach ($user_data as $package) {
                            // $count+=1;
                            //  $package = new SubscribedPackages();
                            $plan = $package->getPackagePlan();
                            //find bandc benefit

                            $query = $this->entity_manager->createQueryBuilder();
                            $query->select(array('pack'))
                                ->from('Application\Entity\SubscribedPackages', 'pack')
                                ->where($query->expr()->orX(
                                    $query->expr()->eq('pack.user', '?1')
                                ))
                                ->andWhere($query->expr()->orX(
                                    $query->expr()->eq('pack.packagePlan', '?2')
                                ))
                                ->setParameters(array(1 => $user, 2 => $plan_name_res))
                                ->orderBy('pack.id', 'DESC')
                                ->setMaxResults(1);
                            $query = $query->getQuery();
                            $bc_data = $query->getResult();
                            $bc_available = '';
                            $bc_datetime = '';
                            if ($bc_data != null) {

                                foreach ($bc_data as $bc_package) {
                                    // $bc_package = new SubscribedPackages();
                                    if ($bc_package->getStatus()) {
                                        // $plan = $bc_package->getPackagePlan();
                                        $bc_datetime = $bc_package->getDateActivated();
                                        $bc_datetime = $bc_datetime->getTimestamp() * 1000;
                                        $bc_available = Constants::BC_BENEFIT_ACTIVE;//active
                                    } else {
                                        $bc_available = Constants::BC_BENEFIT_NOT_ACTIVE;//not active
                                        date_default_timezone_set('UTC');
                                        $date = new \DateTime("now");
                                        $bc_datetime = $date->getTimestamp() * 1000;
                                    }

                                }
                            } else {
                                $bc_available = 1;//not active
//                                date_default_timezone_set('UTC');
//                                $date = new \DateTime("now");
                                $bc_datetime = $util->returnCurrentMilliseconds();
                            }


//                    $userPol = $this->entity_manager->getRepository(Constants::ENTITY_USER_POLICIES)->findOneByUser($user);
//                    if ($userPol == null) {
//                        //policy not set
//                        return $this->return_results(Constants::ON_FAILURE_CONST);
//                    }
//
//                    $plan = $userPol->getPackagePlan();
//                    $userPol->getPolicyStatus();
                            $plan_figures = $this->entity_manager->getRepository(Constants::ENTITY_PACKAGEPLANFIGURES)->findOneByPackagePlan($plan);
                            //  return $this->return_results('test');
                            if ($plan_figures == null) {
                                //plan figures not set
                                return $this->return_results(Constants::ON_FAILURE_CONST);
                            }

                            $user_payments = $this->entity_manager->getRepository(Constants::ENTITY_USER_PAYMENTS)->findBy(array('subscribedPackage' => $package), array('monthPaidFor' => 'DESC'), 1);
//                        $query = $this->entity_manager->getRepository(Constants::ENTITY_USER_PAYMENTS)->createQueryBuilder('n')s
//                            ->where('n.payee = :p_id')
//                            ->setParameter('p_id', $user)
//                            ->orderBy('n.id', 'DESC')
//                            ->setMaxResults(Constants::DB_LIMIT)
//                            ->getQuery();
//                        $user_payments = $query->getResult();
                            $date_paid = '';
                            $activatedAt = 0;
                            $policy_status = Constants::POLICY_NOT_ACTIVE;
                            if ($user_payments != null) {
                                $policy_status = Constants::POLICY_ACTIVE;
                                foreach ($user_payments as $payment) {
                                    //$payment = new UserPayments();
                                    //  $timestamp   = $payment->getDatePaid();
                                    $date_paid = $payment->getMonthPaidFor();
                                    $timeMillis = $date_paid->getTimestamp();
                                    $date_paid = $timeMillis * 1000;//$date_paid->format('Y-m-d H:i:s');
                                }
                            } else {
                                $date_paid = $user->getCreatedAt();

                            }
                            //   $user = new Users();
                            $user_Fpayments = $this->entity_manager->getRepository(Constants::ENTITY_USER_PAYMENTS)->findBy(array('subscribedPackage' => $package), array('monthPaidFor' => 'ASC'), 1);
                            if ($user_Fpayments != null) {
                                // foreach ($user_Fpayments as $fpayment) {
                                // $package = new SubscribedPackages();
                                //  $timestamp   = $payment->getDatePaid();
                                $activatedAt = $package->getDateActivated();
                                $timeMillis = $activatedAt->getTimestamp();
                                $activatedAt = $timeMillis * 1000;//$date_paid->format('Y-m-d H:i:s');
                                // }
                            } else {

                                $activatedAt = $user->getCreatedAt();
                            }

                            $nearest_branch = $user->getBranch();
                            $ref = $user->getReferer();
                            $ref_phone_number = $ref->getPhoneNumber();
                            if ($ref_phone_number === Constants::SHIRI_DEFAULT_NUMBER) {
                                $ref_phone_number = Constants::SHIRI_CODE;
                            } elseif ($ref_phone_number === Constants::DOVES_DEFAULT_NUMMBER) {
                                $ref_phone_number = Constants::DOVES_CODE;
                            }
                            if ($user->getGender()) {
                                $gender = Constants::MALE;
                            } else {
                                $gender = Constants::FEMALE;
                            }


                            if (strcmp($info_update, Constants::GET_NEXT_DUE) == 0) {

                                $xml_output = '<nextDue duedate = "' . $date_paid . '" />';//"<nextDue>" . $date_paid . "</nextDue>\n";
                                $xml_output .= '<policyStatus state = "' . $policy_status . '" />';
                                return $xml_output;
                            }
                            $xml_output = "<?xml version=\"1.0\"?>\n";
                            $xml_output .= "<entries>\n";
                            $xml_output .= "\t<entry>\n";
                            $xml_output .= "\t\t<referer>" . $ref_phone_number . "</referer>\n";
                            $xml_output .= "\t\t<firstName>" . $user->getFirstName() . "</firstName>\n";
                            $xml_output .= "\t\t<id>" . $user->getUserId() . "</id>\n";
                            $xml_output .= "\t\t<lastName>" . $user->getLastName() . "</lastName>\n";
                            $xml_output .= "\t\t<gender>" . $gender . "</gender>\n";
                            $xml_output .= "\t\t<phoneNumber>" . $user->getPhoneNumber() . "</phoneNumber>\n";
                            $xml_output .= "\t\t<idNumber>" . $user->getIdNumber() . "</idNumber>\n";
                            $xml_output .= "\t\t<dateOfBirth>" . $user->getDateOfBirth() . "</dateOfBirth>\n";
                            $xml_output .= "\t\t<createdAt>" . $user->getCreatedAt() . "</createdAt>\n";
                            $xml_output .= "\t\t<ActivatedAt>" . $activatedAt . "</ActivatedAt>\n";
                            $xml_output .= "\t\t<nearestBranch>" . $nearest_branch->getBranchName() . ':' . $nearest_branch->getBranchId() . "</nearestBranch>\n";
                            $xml_output .= "\t\t<amount>" . $plan_figures->getAmount() . "</amount>\n";
                            //  $xml_output .= "\t\t<nextDue>" . $user->getCreatedAt() . "</nextDue>\n";
                            $xml_output .= "\t\t<nextDue>" . $date_paid . "</nextDue>\n";
                            $xml_output .= "\t\t<gcmRegid>" . $user->getGcmRegid() . "</gcmRegid>\n";
                            $xml_output .= "\t\t<packagePlan>" . $plan->getId() . "</packagePlan>\n";
                            $xml_output .= "\t\t<bcAvailable>" . $bc_available . "</bcAvailable>\n";
                            $xml_output .= "\t\t<bcDatetime>" . $bc_datetime . "</bcDatetime>\n";
                            $xml_output .= "\t\t<policyStatus>" . $policy_status . "</policyStatus>\n";
                            $xml_output .= "\t\t<result>" . Constants::ON_SUCCESS_CONST . "</result>\n";
                            $xml_output .= "\t</entry>\n";
                            $xml_output .= "</entries>";

                            return $xml_output;
                        }
                        //end user_deps
                    } else {
                        //no sub package
                        return $this->return_results(Constants::ON_FAILURE_CONST);
                    }

                } else {
                    //no user
                    return $this->return_results(Constants::ON_FAILURE_CONST);
                }
            } else {
                //wrong number
                return $this->return_results(Constants::ON_FAILURE_CONST);
            }
        } else {
            //no data set
            return $this->return_results(Constants::ON_FAILURE_CONST);
        }
    }

    public function process_pincode($user_new_pincode, $user_old_pincode, $phone_number)
    {
        $this->setEntityManager();

        $pin_code = "";
        if (strlen($user_old_pincode) === Constants::PINCODE_MIN_LENGTH && strlen($phone_number) <= Constants::PHONE_NUMBER_LENGTH &&
            strlen($user_new_pincode) === Constants::PINCODE_MIN_LENGTH
        ) {
            $phone_number = htmlspecialchars($phone_number);
            $user_old_pincode = htmlspecialchars($user_old_pincode);
            $user_new_pincode = htmlspecialchars($user_new_pincode);

            //     $sql = "SELECT pincode FROM users WHERE phone_number = '".$phone_number."'";
            $user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($phone_number);// "SELECT * FROM users WHERE phone_number = '".$phone_number."'";
            if ($user == null) {
                //no user
                return $this->return_results(Constants::ON_FAILURE_CONST);
            }

            if ($user != null) {
                // $user = new Users();


                $pin_code = $user->getPincode();
                $verified = false;
                if (version_compare(PHP_VERSION, '5.5.0', '<')) {

                    $passwrd = new PasswordCompatibilityLibrary(Constants::PASSWORD_DEFAULT);
                    $verified = $passwrd->password_verify($user_old_pincode, $pin_code);
                } else {
                    $verified = password_verify($user_old_pincode, $pin_code);
                }

                if ($verified) {
                    if (version_compare(PHP_VERSION, '5.5.0', '<')) {

                        $passwrd = new PasswordCompatibilityLibrary(Constants::PASSWORD_DEFAULT);
                        $user_pincode_hash = $passwrd->password_hash($user_new_pincode, Constants::PASSWORD_DEFAULT);
                    } else
                        $user_pincode_hash = password_hash($user_new_pincode, Constants::PASSWORD_DEFAULT);
                    $user->setPincode($user_pincode_hash);
                    $this->entity_manager->persist($user);
                    $this->entity_manager->flush();

                    return $this->return_results(Constants::ON_SUCCESS_CONST);
                } else {
                    //failed, wrong pincode
                    return $this->return_results(Constants::ON_FAILURE_CONST);
                }

            } else {
                //wrong phone number
                return $this->return_results(Constants::ON_FAILURE_CONST);
            }

        } else {
            return $this->return_results(Constants::ON_FAILURE_CONST);
        }
    }

    function return_results($value)
    {

        $xml_output = "<?xml version=\"1.0\"?>\n";
        $xml_output .= "<entries>\n";
        $xml_output .= "\t<entry>\n";
        $xml_output .= "\t\t<result>" . $value . "</result>\n";
        $xml_output .= "\t</entry>\n";
        $xml_output .= "</entries>";
        return ($xml_output);
    }

    public function processFirstCheck($phone_number, $isWhat)
    {
        if ($phone_number) {

            if (strlen($phone_number) <= Constants::PHONE_NUMBER_LENGTH) {
                $phone_number = htmlspecialchars($phone_number);
                $this->setEntityManager();

                $result = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($phone_number);// "SELECT * FROM users WHERE phone_number = '".$phone_number."'";

                if ($result == null) {
                    $code1 = mt_rand(100, 999);
                    $code2 = mt_rand(300, 888);
                    $nexmoCode = $code1 . " " . $code2;
                    $infobipSMSMessaging = new infobipSMSMessaging();
                    //    $phone_number  = "+263783211562";
                    $result = $infobipSMSMessaging->sendmsg($phone_number, Constants::SHIRI_NAME, Constants::SMS_VERIFY . $nexmoCode);

                    if (empty($result)) {
                        //Failed to send SMS
                        $this->return_results(Constants::ON_FAILURE_CONST);
                    }

                    return $this->smsResult($phone_number, $nexmoCode, Constants::ON_ACCOUNT_NOT_AVAILABLE_CONST);
                } else {
                    //ACCOUNT AVAILABLE
                    if (isset($isWhat) && $isWhat === Constants::BUNDLE_TWO) {
                        $code1 = mt_rand(100, 999);
                        $code2 = mt_rand(300, 888);
                        $nexmoCode = $code1 . " " . $code2;
                        $infobipSMSMessaging = new infobipSMSMessaging();
                        //    $phone_number  = "+263783211562";
                        $result = $infobipSMSMessaging->sendmsg($phone_number, Constants::SHIRI_NAME, Constants::SMS_VERIFY . $nexmoCode);

                        if (empty($result)) {
                            //Failed to send SMS
                            $this->return_results(Constants::ON_FAILURE_CONST);
                        }
                        return $this->smsResult($phone_number, $nexmoCode, Constants::ON_ACCOUNT_NOT_AVAILABLE_CONST);


                    }
                    return $this->return_results(Constants::ON_ACCOUNT_AVAILABLE_CONST);
                }

            } else {
//wrong number
                return $this->return_results(Constants::ON_FAILURE_CONST);
            }

        } else {

            return $this->return_results(Constants::ON_FAILURE_CONST);
        }

    }

    private function getReferrer($phone_number)
    {
        $result = $this->entity_manager->getRepository(Constants::ENTITY_REFERRALS)->findOneByReferredPhoneNumber($phone_number);
        if ($result != null) {

            $date_t = $result->getDateReferred();
            $date_t1 = $date_t / 1000;
            $date = new \DateTime();
            $date->setTimestamp($date_t1);
            $today = new \DateTime('now');
            $interval = $date->diff($today, true);
            if ($interval && $interval->days <= Constants::REFERRAL_LOCK_PERIOD) {
                $referrer = $result->getReferrer();
                $result = '<referredBy id="' . $referrer->getUserId() . '" dateReferred="' . $date_t . '">' . $referrer->getPhoneNumber() . '</referredBy>';
                return $result;
            }


        }

        $result = "<referredBy>-1</referredBy>";
        return $result;
    }

    public function returnPaymentData($phone_number)
    {
//        if ($phone_number) {
//            $this->setEntityManager();
//            if (strlen($phone_number) <= Constants::PHONE_NUMBER_LENGTH) {
//                $phone_number = htmlspecialchars($phone_number);
//                $user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($phone_number);// "SELECT * FROM users WHERE phone_number = '".$phone_number."'";
//                if ($user == null) {
//                    //no user
//                    return $this->return_results(Constants::ON_FAILURE_CONST);
//                }
//                $userPol = $this->entity_manager->getRepository(Constants::ENTITY_USER_POLICIES)->findOneByUser($user);
//                if ($userPol == null) {
//
//                    return $this->return_results(Constants::POLICY_NOT_SET);
//                }
//                //  $userPol = new UserPolicies();
//                $plan_name_res = $userPol->getPackagePlan();
//                //   $plan_name_res = new PackagePlans();
//                $plan_figures = $this->entity_manager->getRepository(Constants::ENTITY_PACKAGEPLANFIGURES)->findOneByPackagePlan($plan_name_res);
//                //  return $this->return_results('test');
//                if ($plan_figures == null) {
//                    //plan figures not set
//                    return $this->return_results(Constants::ON_FAILURE_CONST);
//                }
//                $query = $this->entity_manager->getRepository(Constants::ENTITY_USER_PAYMENTS)->createQueryBuilder('n')
//                    ->where('n.payee = :p_id')
//                    ->setParameter('p_id', $user->getUserId())
//                    ->orderBy('n.id', 'DESC')
//                    ->setMaxResults(Constants::DB_LIMIT)
//                    ->getQuery();
//                $user_payments = $query->getResult();
//                $date_paid = '';
//
//                $xml_output = "<?xml version=\"1.0\">\n";
//                $xml_output .= "<entries>\n";
//                $xml_output .= "\t<entry>\n";
//
//                if ($user_payments != null) {
//                    foreach ($user_payments as $payment) {
//                      //   $payment = new UserPayments();
//
//                        $user_policy = $payment->getUserPolicy();
//                        //  $user_policy = new UserPolicies();
//                        $paidForUser = $user_policy->getUser();
//                        //     $paidForUser = new Users();
//                        $date_paid = $payment->getDatePaid();
//
//                        if ($paidForUser->getUserId() == $user->getUserId()) {
//                            //Paid formyself
//                            $xml_output .= "\t\t<paidFor>" . Constants::BUNDLE_ONE . "</paidFor>\n";
//                        } else {
//                            //Paid for a friend
//                            $xml_output .= "\t\t<paidFor>" . Constants::BUNDLE_TWO . "</paidFor>\n";
//                        }
//
//                        // $date_paid = $user_payments->getDatePaid();
//                        $xml_output .= "\t\t<datePaid>" . $payment->getDatePaid() . "</datePaid>\n";
//                        $xml_output .= "\t\t<datePaidfor>" . $payment->getMonthPaidFor() . "</datePaidfor>\n";
//                    }
//                } else {
//                    $date_paid = Constants::BUNDLE_ZERO;
//                    //  $date_paid = 1438008061000;
//                    // $date_paid = round($date_paid/1000,3);
//
//                    $xml_output .= "\t\t<paidFor>" . Constants::BUNDLE_ZERO . "</paidFor>\n";
//                    $xml_output .= "\t\t<datePaid>" . $date_paid . "</datePaid>\n";
//                    $xml_output .= "\t\t<datePaidfor>" . $date_paid . "</datePaidfor>\n";
//                }
//                $dep_plan_name = $this->entity_manager->getRepository(Constants::ENTITY_PACKAGE_PLANS)->findOneById(Constants::ADITIONAL_DEP_PACKAGE_ID);
//                if ($dep_plan_name == null) {
//                    return $this->return_results(Constants::PLAN_NOT_SET);
//                }
//
//                $adtnl_dep_amount = $this->entity_manager->getRepository(Constants::ENTITY_PACKAGEPLANFIGURES)->findOneByPackagePlan($dep_plan_name);
//                //  return $this->return_results('test');
//                if ($adtnl_dep_amount == null) {
//                    //plan figures not set
//                    return $this->return_results(Constants::ON_FAILURE_CONST);
//                }
//
//
//                $xml_output .= "\t\t<result>" . Constants::ON_SUCCESS_CONST . "</result>\n";
//                $xml_output .= "\t\t<nextDue>" . $date_paid . "</nextDue>\n";
//                $xml_output .= "\t\t<depAmount>" . $adtnl_dep_amount->getAmount() . "</depAmount>\n";
//                $xml_output .= "\t\t<amount>" . $plan_figures->getAmount() . "</amount>\n";
//                $xml_output .= "\t\t<packagePlan>" . $plan_name_res->getId() . "</packagePlan>\n";
//                $xml_output .= "\t</entry>\n";
//                $xml_output .= "</entries>";
//                return $xml_output;
//            } else {
//                //wrong phone number
//                return $this->return_results(Constants::ON_FAILURE_CONST);
//            }
//
//        } else {
//            //phone number not set
//            return $this->return_results(Constants::ON_FAILURE_CONST);
//        }

    }


    public function getUserAccountBalance($phone_number, $currentDate)
    {
        //  return $this->return_results(Constants::ON_FAILURE_CONST);
        if ($phone_number) {
            $this->setEntityManager();

//            $request = json_decode($json,true);
//            $phone_number = $request[Constants::PHONE_NUMBER];
//            $currentDate = $request['currentDate'];
            $util = new Utils();
            $net_excess = false;
            $eco_excess = false;
            $do_once = false;

//            if (true) {
//                //dummy result
//                $info = null;
//                $info[0] = array('fname' => 'Tatenda Caston',
//                    'sname' => 'Hove',
//                    'state' => '2',
//                    'user_package_amount' => '12.50',
//                    'package' => '100',
//                    'yearmd' => '2015-09-30',
//                );
//                $info[1] = array('fname' => 'Tatenda Caston',
//                    'sname' => 'Hove',
//                    'state' => '2',
//                    'user_package_amount' => '12.50',
//                    'package' => '100',
//                    'yearmd' => '2015-10-30',
//                );
//                $info[2] = array('fname' => 'Ropafadzo',
//                    'sname' => 'Magwali',
//                    'state' => '1',
//                    'user_package_amount' => '3',
//                    'package' => '100',
//                    'yearmd' => '2015-10-30',
//                );
//                $info[3] = array('fname' => 'Tatenda Caston',
//                    'sname' => 'Hove',
//                    'state' => '3',
//                    'user_package_amount' => '7.50',
//                    'package' => '100',
//                    'yearmd' => '2015-10-30',
//                );
//                $info[4] = array('fname' => 'Tatenda Caston',
//                    'sname' => 'Hove',
//                    'state' => '2',
//                    'user_package_amount' => '12.50',
//                    'package' => '100',
//                    'yearmd' => '2015-11-30',
//                );
//                $info[5] = array('fname' => 'Ropafadzo',
//                    'sname' => 'Magwali',
//                    'state' => '1',
//                    'user_package_amount' => '3',
//                    'package' => '100',
//                    'yearmd' => '2015-11-30',
//                );
//                $info[6] = array('fname' => 'Tatenda Caston',
//                    'sname' => 'Hove',
//                    'state' => '3',
//                    'user_package_amount' => '7.50',
//                    'package' => '100',
//                    'yearmd' => '2015-11-30',
//                );
//                $xml_output = "";
//
//                foreach ($info as $rec) {
//                    $xml_output .= '<to_pay fname = "' . $rec['fname'] . '" sname ="' . $rec['sname'] . '" is_dep ="' . $rec['state'] . '" amount ="' . $rec['user_package_amount'] . '" server_id ="' . $rec['package'] .
//                        '" owing_date ="' . $rec['yearmd'] . '" />';
//                }
//                die($xml_output);
//            }


            if (strlen($phone_number) <= Constants::PHONE_NUMBER_LENGTH) {

                $bandc_Id = Constants::BUS_AND_CASH_ADITIONAL_BEN;
                $immediate_family_id = Constants::IMMEDIATE_FAMILTY;

                $bc_plan_name = $this->entity_manager->getRepository(Constants::ENTITY_PACKAGE_PLANS)->findOneById($bandc_Id);
                if ($bc_plan_name == null) {
                    return $this->return_results(Constants::PLAN_NOT_SET);
                }
                //  $money = new PackagePlansFigures();
                $bc_plan_amount = $this->entity_manager->getRepository(Constants::ENTITY_PACKAGEPLANFIGURES)->findOneByPackagePlan($bc_plan_name);
                //  return $this->return_response('test');
                if ($bc_plan_amount == null) {
                    //plan figures not set
                    return $this->return_results(Constants::ON_FAILURE_CONST);
                }

                // $phone_number = htmlspecialchars($phone_number);
                $user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($phone_number);
                if ($user == null) {
                    //no user

                    return $this->return_results(Constants::ON_FAILURE_CONST);
                }

                $subscribed_packages = $this->entity_manager->getRepository(Constants::ENTITY_SUBSCRIBED_PACKAGES)->findBy(array('user' => $user, 'status' => true), array('id' => 'ASC'));


                $xml_output = '<owing_balances>';
                foreach ($subscribed_packages as $package) {
                    $inadvance = 0;
                    $do_once = false;
                    $last_payment = $this->entity_manager->getRepository(Constants::ENTITY_USER_PAYMENTS)->findBy(array('subscribedPackage' => $package), array('monthPaidFor' => 'DESC'), 1);

                    if ($last_payment == null) {

                        if ($package->getStatus()) {

                            //   $package = new SubscribedPackages();
//                        // $cdate = (int)$user->getCreatedAt();
//                        $timestamp = $package->getDateActivated();//$user->getCreatedAt();

                            $fname = "";
                            $sname = "";
                            $pack = $package->getPackagePlan();
                            if ($package->getIsDependent()) {
                                if ($immediate_family_id == $pack->getId()) {
                                    continue;
                                }

                                $dep_info = $package->getDependent();
                                // $dep_info = new UserDependents();
                                $fname = $dep_info->getFirstName();
                                $sname = $dep_info->getLastName();
                                $state = 1;
                            } else {

                                $user_info = $package->getUser();
                                // $package = new Users();
                                $fname = $user_info->getFirstName();
                                $sname = $user_info->getLastName();
                                $state = 2;
                            }

                         //   $pack = $package->getPackagePlan();

                            $user_package_amount = $this->entity_manager->getRepository(Constants::ENTITY_PACKAGEPLANFIGURES)->findOneByPackagePlan($pack);
                            //    $pack = new PackagePlans();

                            if ($bandc_Id == $pack->getId()) {
                                $fname = 'Bus and cash';
                                $sname = 'benefit';
                                $user_package_amount = $bc_plan_amount;
                                $state = 3;
                            }

                            if ($user_package_amount == null) {
                                //plan figures not set
                                return $this->return_results(Constants::ON_FAILURE_CONST);
                            }

                            //$user_package_amount = new PackagePlansFigures();
                            // $user = new Users();

                            //   $timestamp = $util->firstDayOfMonth($timestamp);
                            $date = new \DateTime($currentDate);
                            // $timestamp = $now->getTimestamp();
                            // $date = date_create_from_format('Y-m-d', $currentDate);
                            $month = date_format($date, 'm') . "";
                            $year = date_format($date, 'Y') . "";
//                        if ($month == 12) {
//                            $year += 1;
//                            $month = 1;
//                        } else {
//                            $month += 1;
//                        }
                            $number = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                            //   $date = date_create_from_format('Y-m-d', $year . '-' . $month . '-' . $number);


                            $xml_output .= '<to_pay fname = "' . $fname . '" sname ="' . $sname . '" is_dep ="' . $state . '" amount ="' . $user_package_amount->getAmount() . '" server_id ="' . $package->getId() .
                                '" owing_date ="' . $year . '-' . $month . '-' . $number . '" />';


                        }

                    } else {

                        $inadvance = 0;
                        $currentDt = new \DateTime($currentDate);
                        $timestamp = new \DateTime();//'2015-08-25 00:00:00');
                        foreach ($last_payment as $pay) {
                            // $pay  = new UserPayments();
                            $timestamp = $pay->getMonthPaidFor();

                            $ptype = $pay->getPaymentType();
                            $pnameId = $ptype->getId();
                            if ($pnameId == Constants::ECOCASH) {
                                //ecocash
                                $eco_last_payment = $this->entity_manager->getRepository(Constants::ENTITY_ECOCASH)->findBy(array('user' => $user), array('id' => 'DESC'), 1);
                                if ($eco_last_payment != null) {
                                    foreach ($eco_last_payment as $eco_pay) {
                                        // $eco_pay = new EcocashPayments();
                                        if (!$eco_excess) {
                                            $eco_excess = true;
                                            $inadvance = $eco_pay->getExcessAmount();
                                        } else {
                                            $inadvance = 0;
                                        }

                                        //remove it only on user package plan not dependants
                                        // $inadvance = floatval(-0.50);
                                        // $inadvance = number_format($inadvance,2);
                                    }
                                }
                            } else if ($pnameId == Constants::NETTCASH) {
                                //nettcash
                                $net_last_payment = $this->entity_manager->getRepository(Constants::ENTITY_NETTCASH_PAYMENTS)->findBy(array('user' => $user), array('id' => 'DESC'), 1);
                                if ($net_last_payment != null) {
                                    foreach ($net_last_payment as $nett_pay) {
                                        // $nett_pay = new NettcashPayments();
                                        if (!$net_excess) {
                                            $net_excess = true;
                                            $inadvance = $nett_pay->getExcessAmount();
                                        } else {
                                            $inadvance = 0;
                                        }
                                    }
                                }
                            } else if ($pnameId == Constants::INT_BUNDLE_THREE) {
                                //telecash

                            }
                            //  die($pay->getMonthPaidFor());
                        }
                        $fname = "";
                        $sname = "";
                        if ($util->compareDates($timestamp, $currentDt)) {


                            $diff = $util->diffMonths($timestamp, $currentDt);
                            //die('diff'.$diff);
                            if (is_array($diff)) {

                                for ($i = 0; $i < count($diff); $i++) {

                                    //  $package = new SubscribedPackages();
                                    $pack = $package->getPackagePlan();
                                    if ($package->getIsDependent()) {
                                        if ($immediate_family_id == $pack->getId()) {
                                            continue;
                                        }
                                        $dep_info = $package->getDependent();
                                        // $dep_info = new UserDependents();
                                        $fname = $dep_info->getFirstName();
                                        $sname = $dep_info->getLastName();
                                        $state = 1;
                                    } else {
                                        $user_info = $package->getUser();
                                        // $package = new Users();
                                        $fname = $user_info->getFirstName();
                                        $sname = $user_info->getLastName();
                                        $state = 2;
                                    }

                                    //$timestamp = $util->firstDayOfMonth($timestamp);

                                    // $timestamp = date_create_from_format('Y-m-d 00:00:00', $diff[$i]);
                                    $date = $timestamp;

                                    $month = date_format($date, 'm') . "";
                                    $year = date_format($date, 'Y') . "";
                                    if ($month == 12) {
                                        $year += 1;
                                        $month = 1;
                                    } else {
                                        $month += 1;
                                    }
                                    $number = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                                    // die($year . '-' . $month . '-' . $number);
                                    // $timestamp = date_create_from_format('Y-m-d 00:00:00', $year . '-' . $month . '-' . $number);
                                    $timestamp = new \DateTime($year . '-' . $month . '-' . $number);
                                    //$dt = $timestamp->format('Y-m-d 00:00:00');
                                    // die($dt);

                                    //  $pack = new PackagePlans();
                                    // $fig = new PackagePlansFigures();

                                    $query = $this->entity_manager->createQueryBuilder();
                                    $query->select(array('p'))
                                        ->from(Constants::ENTITY_PACKAGEPLANFIGURES, 'p')
                                        // ->where('d.user_id = ?1')
                                        ->where($query->expr()->orX(
                                            $query->expr()->eq('p.packagePlan', '?1')
                                        ))
                                        ->andWhere($query->expr()->orX(
                                            $query->expr()->lte('p.dateEffective', '?2')
                                        ))
                                        ->setParameters(array(1 => $pack, 2 => $timestamp))
                                        ->orderBy('p.id', 'DESC')
                                        ->setMaxResults(1);
                                    $query = $query->getQuery();
                                    $data_result = $query->getResult();
                                    //die($timestamp);
                                    $dep_amount = '';
                                    if ($data_result != null && is_array($data_result)) {
                                        // $user_payments = new PackagePlansFigures();
                                        foreach ($data_result as $figure) {
                                            $dep_amount = $figure->getAmount();
                                        }

                                    } else {
                                        // die($timestamp);
                                        return $this->return_results(Constants::ON_FAILURE_CONST);
                                    }

                                    if ($bandc_Id == $pack->getId()) { // if($bc_plan_amount->getAmount() === $dep_amount){
                                        $fname = 'Bus and cash';
                                        $sname = 'benefit';
                                        $state = Constants::IS_B_AND_C;
                                    }

                                    //$user_package_amount = new PackagePlansFigures();
                                    // $user = new Users();
                                    if ($state == Constants::IF_USER) {
                                        $dep_amount = number_format($dep_amount, Constants::TWO_DECIMAL_PLACE);
                                        if (!$do_once) {
                                            $do_once = true;
                                            $dep_amount = $dep_amount - $inadvance;
                                        } else {
                                            $inadvance = 0;
                                        }

                                        $dep_amount = number_format($dep_amount, Constants::TWO_DECIMAL_PLACE);
                                    }

                                    $xml_output .= '<to_pay fname = "' . $fname . '" sname ="' . $sname . '" is_dep ="' . $state . '" amount ="' . $dep_amount . '" server_id ="' . $package->getId() .
                                        '" owing_date ="' . $diff[$i] . '" />';

                                }
                            } else {
                                ////////////////////////////////////////////start /////////////////////////////
                                $pack = $package->getPackagePlan();
                                if ($package->getIsDependent()) {
                                    if ($immediate_family_id == $pack->getId()) {
                                        continue;
                                    }
                                    $dep_info = $package->getDependent();
                                    // $dep_info = new UserDependents();
                                    $fname = $dep_info->getFirstName();
                                    $sname = $dep_info->getLastName();
                                    $state = 1;
                                } else {
                                    $user_info = $package->getUser();
                                    // $package = new Users();
                                    $fname = $user_info->getFirstName();
                                    $sname = $user_info->getLastName();
                                    $state = 2;
                                }

                                $date = $timestamp;

                                $month = date_format($date, 'm') . "";
                                $year = date_format($date, 'Y') . "";
                                if ($month == 12) {
                                    $year += 1;
                                    $month = 1;
                                } else {
                                    $month += 1;
                                }
                                $number = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                                $timestamp = new \DateTime($year . '-' . $month . '-' . $number);


                                $query = $this->entity_manager->createQueryBuilder();
                                $query->select(array('p'))
                                    ->from('Application\Entity\PackagePlansFigures', 'p')
                                    // ->where('d.user_id = ?1')
                                    ->where($query->expr()->orX(
                                        $query->expr()->eq('p.packagePlan', '?1')
                                    ))
                                    ->andWhere($query->expr()->orX(
                                        $query->expr()->lte('p.dateEffective', '?2')
                                    ))
                                    ->setParameters(array(1 => $pack, 2 => $timestamp))
                                    ->orderBy('p.id', 'DESC')
                                    ->setMaxResults(1);
                                $query = $query->getQuery();
                                $data_result = $query->getResult();
                                //die($timestamp);
                                $dep_amount = '';
                                if ($data_result != null && is_array($data_result)) {
                                    // $user_payments = new PackagePlansFigures();
                                    foreach ($data_result as $figure) {
                                        $dep_amount = $figure->getAmount();
                                    }

                                } else {
                                    // die($timestamp);
                                    return $this->return_results(Constants::ON_FAILURE_CONST);
                                }

                                if ($bandc_Id == $pack->getId()) {//if($bc_plan_amount->getAmount() === $dep_amount){
                                    $fname = 'Bus and cash';
                                    $sname = 'benefit';
                                    $state = 3;
                                }

                                //$user_package_amount = new PackagePlansFigures();
                                // $user = new Users();
                                if ($state == Constants::IF_USER) {
                                    $dep_amount = number_format($dep_amount, Constants::TWO_DECIMAL_PLACE);
                                    // $dep_amount = $dep_amount - $inadvance;
                                    if (!$do_once) {
                                        $do_once = true;
                                        $dep_amount = $dep_amount - $inadvance;
                                    } else {
                                        $inadvance = 0;
                                    }
                                    $dep_amount = number_format($dep_amount, Constants::TWO_DECIMAL_PLACE);
                                }

                                $xml_output .= '<to_pay fname = "' . $fname . '" sname ="' . $sname . '" is_dep ="' . $state . '" amount ="' . $dep_amount . '" server_id ="' . $package->getId() .
                                    '" owing_date ="' . $year . '-' . $month . '-' . $number . '" />';


                                /////////////////////////////////////end ///////////////////
                            }
                        } else {
                            $pack = $package->getPackagePlan();
                            if ($package->getIsDependent()) {
                                if ($immediate_family_id == $pack->getId()) {
                                    continue;
                                }
                                $dep_info = $package->getDependent();
                                // $dep_info = new UserDependents();
                                $fname = $dep_info->getFirstName();
                                $sname = $dep_info->getLastName();
                                $state = 1;
                            } else {
                                $user_info = $package->getUser();
                                // $package = new Users();
                                $fname = $user_info->getFirstName();
                                $sname = $user_info->getLastName();
                                $state = 2;
                            }


                            // $pack = new PackagePlans();
                            $user_package_amount = $this->entity_manager->getRepository(Constants::ENTITY_PACKAGEPLANFIGURES)->findOneByPackagePlan($pack);

                            if ($user_package_amount == null) {
                                //plan figures not set
                                return $this->return_results(Constants::ON_FAILURE_CONST);
                            }
                            if ($bandc_Id == $pack->getId()) {//  if($bc_plan_amount->getAmount() === $user_package_amount->getAmount()){
                                $fname = 'Bus and cash';
                                $sname = 'benefit';
                                $user_package_amount = $bc_plan_amount;
                                $state = 3;
                            }

                            //$user_package_amount = new PackagePlansFigures();
                            // $user = new Users();

                            //   $timestamp = $util->firstDayOfMonth($timestamp);
                            $date = $timestamp;// date_create_from_format('Y-m-d', $this->field_b);
                            $month = date_format($date, 'm') . "";
                            $year = date_format($date, 'Y') . "";
                            if ($month == 12) {
                                $year += 1;
                                $month = 1;
                            } else {
                                $month += 1;
                            }
                            $number = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                            //   $date = date_create_from_format('Y-m-d', $year . '-' . $month . '-' . $number);

                            $amount_owing = $user_package_amount->getAmount();
                            if ($state == Constants::IF_USER) {
                                $amount_owing = number_format($amount_owing, Constants::TWO_DECIMAL_PLACE);
                                // $amount_owing = $amount_owing - $inadvance;
                                if (!$do_once) {
                                    $do_once = true;
                                    $amount_owing = $amount_owing - $inadvance;
                                } else {
                                    $inadvance = 0;
                                }
                                $amount_owing = number_format($amount_owing, Constants::TWO_DECIMAL_PLACE);
                            }
                            $xml_output .= '<to_pay fname = "' . $fname . '" sname ="' . $sname . '" is_dep ="' . $state . '" amount ="' . $amount_owing . '" server_id ="' . $package->getId() .
                                '" owing_date ="' . $year . '-' . $month . '-' . $number . '" />';

                        }
                    }
                    //  die($currentDate.'');


                }

//=====================>>>> DEACTIVATED ACCOUNTS OR DEPS OR B&C <<<<==============================================
                $net_excess = false;
                $eco_excess = false;
                $do_once = false;
                $inadvance = 0;
                $subscribed_packages = $this->entity_manager->getRepository(Constants::ENTITY_SUBSCRIBED_PACKAGES)->findBy(array('user' => $user, 'status' => false));
                foreach ($subscribed_packages as $package) {
                    $do_once = false;
                    $last_payment = $this->entity_manager->getRepository(Constants::ENTITY_USER_PAYMENTS)->findBy(array('subscribedPackage' => $package), array('monthPaidFor' => 'DESC'), 1);

                    if ($last_payment == null) {

//                    // $cdate = (int)$user->getCreatedAt();
//                  //  $timestamp = $package->getDateActivated();//$package->getCreatedAt();
//                    $fname = "";
//                    $sname = "";
//                    if ($package->getIsDependent()) {
//                        $dep_info = $package->getDependent();
//                        // $dep_info = new UserDependents();
//                        $fname = $dep_info->getFirstName();
//                        $sname = $dep_info->getLastName();
//                        $state = 1;
//                    } else {
//                        $user_info = $package->getUser();
//                        // $package = new Users();
//                        $fname = $user_info->getFirstName();
//                        $sname = $user_info->getLastName();
//                        $state = 2;
//                    }
//
//                    $pack = $package->getPackagePlan();
//                    // $pack = new PackagePlans();
//                    $user_package_amount = $this->entity_manager->getRepository(Constants::ENTITY_PACKAGEPLANFIGURES)->findOneByPackagePlan($pack);
//
//                    if ($user_package_amount == null) {
//                        //plan figures not set
//                        return $this->return_results(Constants::ON_FAILURE_CONST);
//                    }
//
//                    //$user_package_amount = new PackagePlansFigures();
//                    // $user = new Users();
//
//                    //   $timestamp = $util->firstDayOfMonth($timestamp);
//                    $date = new \DateTime($currentDate);//$date = date_create_from_format('Y-m-d', $currentDate);
//                    $month = date_format($date, 'm') . "";
//                    $year = date_format($date, 'Y') . "";
////                    if ($month == 12) {
////                        $year += 1;
////                        $month = 1;
////                    } else {
////                        $month += 1;
////                    }
//                    $number = cal_days_in_month(CAL_GREGORIAN, $month, $year);
//                    //   $date = date_create_from_format('Y-m-d', $year . '-' . $month . '-' . $number);
//
//
//                    $xml_output .= '<to_pay fname = "' . $fname . '" sname ="' . $sname . '" is_dep ="' . $state . '" amount ="' . $user_package_amount->getAmount() . '" server_id ="' . $package->getId() .
//                        '" owing_date ="' . $year . '-' . $month . '-' . $number . '" />';
//


                    } else {
                        foreach ($last_payment as $payment) {
                            // $last_payment  = new UserPayments();
                            $timestmp = $payment->getDatePaid();
                            $dateCancelled = $package->getDateDeactivated();
                            //    foreach ($last_payment as $pay) {
                            // $payment  = new UserPayments();
                            //  $timestamp = $pay->getMonthPaidFor();

                            $ptype = $payment->getPaymentType();
                            $pnameId = $ptype->getId();
                            if ($pnameId == Constants::ECOCASH) {
                                //ecocash
                                $eco_last_payment = $this->entity_manager->getRepository(Constants::ENTITY_ECOCASH)->findBy(array('user' => $user), array('id' => 'DESC'), 1);
                                if ($eco_last_payment != null) {
                                    foreach ($eco_last_payment as $eco_pay) {
                                        // $eco_pay = new EcocashPayments();
                                        if (!$eco_excess) {
                                            $eco_excess = true;
                                            $inadvance = $eco_pay->getExcessAmount();
                                        } else {
                                            $inadvance = 0;
                                        }

                                    }
                                }
                            } else if ($pnameId == Constants::NETTCASH) {
                                //nettcash
                                $net_last_payment = $this->entity_manager->getRepository(Constants::ENTITY_NETTCASH_PAYMENTS)->findBy(array('user' => $user), array('id' => 'DESC'), 1);
                                if ($net_last_payment != null) {
                                    foreach ($net_last_payment as $nett_pay) {
                                        // $nett_pay = new NettcashPayments();
                                        if (!$net_excess) {
                                            $net_excess = true;
                                            $inadvance = $nett_pay->getExcessAmount();
                                        } else {
                                            $inadvance = 0;
                                        }
                                    }
                                }
                            } else if ($pnameId == Constants::INT_BUNDLE_THREE) {
                                //telecash

                            }
                            //  die($pay->getMonthPaidFor());
                            //  }
                            if ($util->compareDates($timestmp, $dateCancelled)) {

                                $diff = $util->diffMonths($timestmp, $dateCancelled);
                                if (is_array($diff)) {
                                    for ($i = 0; $i < count($diff); $i++) {

                                        //  $package = new SubscribedPackages();
                                        $fname = "";
                                        $sname = "";
                                        $pack = $package->getPackagePlan();
                                        if ($package->getIsDependent()) {
                                            if ($immediate_family_id == $pack->getId()) {
                                                continue;
                                            }
                                            $dep_info = $package->getDependent();
                                            // $dep_info = new UserDependents();
                                            $fname = $dep_info->getFirstName();
                                            $sname = $dep_info->getLastName();
                                            $state = 1;
                                        } else {
                                            $user_info = $package->getUser();
                                            // $package = new Users();
                                            $fname = $user_info->getFirstName();
                                            $sname = $user_info->getLastName();
                                            $state = 2;
                                        }

                                        //  $timestamp = $util->firstDayOfMonth($timestamp);
                                        $date = $timestmp;// date_create_from_format('Y-m-d', $this->field_b);
                                        $month = date_format($date, 'm') . "";
                                        $year = date_format($date, 'Y') . "";
                                        if ($month == 12) {
                                            $year += 1;
                                            $month = 1;
                                        } else {
                                            $month += 1;
                                        }
                                        $number = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                                        //  $timestmp = date_create_from_format('Y-m-d 00:00:00', $year . '-' . $month . '-' . $number);
                                        $timestmp = new \DateTime($year . '-' . $month . '-' . $number);
                                        // $timestmp = $timestmp->format('Y-m-d 00:00:00');
                                        // die($timestmp);

                                        //  $pack = new PackagePlans();

                                        $query = $this->entity_manager->createQueryBuilder();
                                        $query->select(array('p'))
                                            ->from('Application\Entity\PackagePlansFigures', 'p')
                                            // ->where('d.user_id = ?1')
                                            ->where($query->expr()->orX(
                                                $query->expr()->eq('p.packagePlan', '?1')
                                            ))
                                            ->andWhere($query->expr()->orX(
                                                $query->expr()->lte('p.dateEffective', '?2')
                                            ))
                                            ->setParameters(array(1 => $pack, 2 => $timestmp))
                                            ->orderBy('p.id', 'DESC')
                                            ->setMaxResults(1);
                                        $query = $query->getQuery();
                                        $data_result = $query->getResult();
                                        $dep_amount = '';
                                        if ($data_result != null && is_array($data_result)) {
                                            // $user_payments = new PackagePlansFigures();
                                            foreach ($data_result as $figure) {
                                                $dep_amount = $figure->getAmount();
                                            }
                                        } else {
                                            return $this->return_results(Constants::ON_FAILURE_CONST);
                                        }
                                        if ($bandc_Id == $pack->getId()) {// if ($bc_plan_amount->getAmount() === $dep_amount) {
                                            $fname = 'Bus and cash';
                                            $sname = 'benefit';
                                            $state = 3;
                                        }

                                        //$user_package_amount = new PackagePlansFigures();
                                        // $user = new Users();

                                        if ($state == Constants::IF_USER) {
                                            $dep_amount = number_format($dep_amount, Constants::TWO_DECIMAL_PLACE);
                                            if (!$do_once) {
                                                $do_once = true;
                                                $dep_amount = $dep_amount - $inadvance;
                                            } else {
                                                $inadvance = 0;
                                            }
                                            //$dep_amount = $dep_amount - $inadvance;
                                            $dep_amount = number_format($dep_amount, Constants::TWO_DECIMAL_PLACE);
                                        }
                                        $xml_output .= '<to_pay fname = "' . $fname . '" sname ="' . $sname . '" is_dep ="' . $state . '" amount ="' . $dep_amount . '" server_id ="' . $package->getId() .
                                            '" owing_date ="' . $year . '-' . $month . '-' . $number . '" />';
                                    }
                                }

                            } else {


                            }
                        }
                    }

                    //  $package = new SubscribedPackages();


                }


                $xml_output .= '</owing_balances>';
                return $xml_output;
            } else {
                //wrong phone number format
                return $this->return_results(Constants::ON_FAILURE_CONST);
            }
        } else {
            //phone number not set
            return $this->return_results(Constants::ON_FAILURE_CONST);
        }
    }

    public function returnAllStatuses($json)
    {
        $this->setEntityManager();
        $result = '<results>';
        if ($json != NULL) {
            $request = json_decode($json, true);
            $checkUser = $request['checkUserStatus'];
            $checkDeps = $request['checkDepsStatus'];
            $checkBc = $request['checkBcStatus'];
            $phoneNumber = $request[Constants::PHONE_NUMBER];
            $count = $request['count'];
            $depsIds = $request['additionalDepsIds'];

            $user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($phoneNumber);// "SELECT * FROM users WHERE phone_number = '".$phone_number."'";
            if ($user == null) {
                //no user
                $result .= '<result>' . Constants::ON_FAILURE_CONST . '</result>';
                $result .= '</results>';
                return $result;
            }
            if ($checkUser == Constants::CHECK_POLICY_STATUS) {
                //  $result.= '</results>';
                //  return $result;
                $plan = $this->entity_manager->getRepository(Constants::ENTITY_PACKAGE_PLANS)->findOneById(Constants::PREMIUM_POLICY);
                $query = $this->entity_manager->createQueryBuilder();
                $query->select(array('p'))
                    ->from(Constants::ENTITY_SUBSCRIBED_PACKAGES, 'p')
                    ->where($query->expr()->orX(
                        $query->expr()->eq('p.user ', '?1')
                    ))
                    ->andWhere($query->expr()->orX(
                        $query->expr()->eq('p.packagePlan', '?2')
                    ))
                    ->setParameters(array(1 => $user, 2 => $plan))
                    ->setMaxResults(1);
                $query = $query->getQuery();
                $data_result = $query->getResult();

                if ($data_result != null && is_array($data_result)) {
                    $last_payment = $this->entity_manager->getRepository(Constants::ENTITY_USER_PAYMENTS)->findBy(array('subscribedPackage' => $data_result), array('monthPaidFor' => 'ASC'), 1);
                    if ($last_payment != null) {
                        foreach ($data_result as $pay) {
                            $dtActivated = $pay->getDateActivated();
                            $dtActivated = $dtActivated->getTimestamp() * Constants::TO_MILLISECONDS;
                            $state = Constants::ACTIVE;//active
                            $result .= '<userActivatedAt state = "' . $state . '" dtActivated = "' . $dtActivated . '"/>';// . $dtActivated . '</userActivatedAt>';

                        }
                    } else {
                        $state = Constants::NOT_ACTIVE;//not active
                        date_default_timezone_set('UTC');
                        $date = new \DateTime("now");
                        $dtActivated = $date->getTimestamp() * 1000;
                        $result .= '<userActivatedAt state = "' . $state . '" dtActivated = "' . $dtActivated . '"/>';// . $dtActivated . '</userActivatedAt>';
                    }
                }
            }
            $request = json_decode($depsIds, true);
            if ($checkDeps == Constants::CHECK_ADDITIONAL_DEPS_STATUS) {
                for ($i = 1; $i <= $count; $i++) {
                    $depJson = $request[$i];
                    $dep = json_decode($depJson, true);
                    $dep_id = $dep['id'];

                    $dependant = $this->entity_manager->getRepository(Constants::ENTITY_USERDEPENDENTS)->findOneById($dep_id);// "SELECT * FROM users WHERE phone_number = '".$phone_number."'";
                    if ($dependant == null) {
                        //no user
                        $result .= '<result>' . Constants::ON_FAILURE_CONST . '</result>';
                        $result .= '</results>';
                        return $result;
                    }
                    //  $dependant = new UserDependents();
                    //  $dependant->getId();
                    $subscribed_package = $this->entity_manager->getRepository(Constants::ENTITY_SUBSCRIBED_PACKAGES)->findOneByDependent($dependant);
                    if ($subscribed_package == null) {
                        //no subscribed dep
                        $result .= '<result>' . Constants::ON_FAILURE_CONST . '</result>';
                        $result .= '</results>';
                        return $result;
                    }
                    $last_payment = $this->entity_manager->getRepository(Constants::ENTITY_USER_PAYMENTS)->findBy(array('subscribedPackage' => $subscribed_package), array('monthPaidFor' => 'ASC'), 1);
                    if ($last_payment != null) {
//                        $result .= '<depStatus id = "' . $dep_id . '">' . Constants::NOT_ACTIVE . '</depStatus>';
//                    }else {
                        //  $subscribed_packages = new SubscribedPackages();
                        $state = Constants::ACTIVE;//active
                        $dtActivated = $subscribed_package->getDateActivated();
                        $dtActivated = $dtActivated->getTimestamp() * Constants::TO_MILLISECONDS;
                        $result .= '<depActivatedAt id = "' . $dep_id . '" state = "' . $state . '" dtActivated = "' . $dtActivated . '"/>';// . $dtActivated . '</depActivatedAt>';
                    } else {
                        $state = Constants::NOT_ACTIVE;//not active
                        date_default_timezone_set('UTC');
                        $date = new \DateTime("now");
                        $dtActivated = $date->getTimestamp() * 1000;
                        $result .= '<depActivatedAt id = "' . $dep_id . '" state = "' . $state . '" dtActivated = "' . $dtActivated . '"/>';// . $dtActivated . '</depActivatedAt>';
                    }
                }

            }
            if ($checkBc == Constants::CHECK_BUSANDCASH_STATUS) {
                $Bc_plan = $this->entity_manager->getRepository(Constants::ENTITY_PACKAGE_PLANS)->findOneById(Constants::BUS_AND_CASH_ADITIONAL_BEN);
                $query = $this->entity_manager->createQueryBuilder();
                $query->select(array('pack'))
                    ->from('Application\Entity\SubscribedPackages', 'pack')
                    ->where($query->expr()->orX(
                        $query->expr()->eq('pack.user', '?1')
                    ))
                    ->andWhere($query->expr()->orX(
                        $query->expr()->eq('pack.packagePlan', '?2')
                    ))
                    ->setParameters(array(1 => $user, 2 => $Bc_plan))
                    ->orderBy('pack.id', 'DESC')
                    ->setMaxResults(1);
                $query = $query->getQuery();
                $bc_data = $query->getResult();
                $BcActivatedAt = '';
                if ($bc_data != null) {
                    foreach ($bc_data as $bc_package) {
                        // $bc_package = new SubscribedPackages();
                        if ($bc_package->getStatus()) {

                            // $plan = $bc_package->getPackagePlan();
                            $BcActivatedAt = $bc_package->getDateActivated();
                            $BcActivatedAt = $BcActivatedAt->getTimestamp() * 1000;
                            //active
                            $last_payment = $this->entity_manager->getRepository(Constants::ENTITY_USER_PAYMENTS)->findBy(array('subscribedPackage' => $bc_package), array('monthPaidFor' => 'ASC'), 1);
                            if ($last_payment != null) {
                                $bc_available = Constants::BC_BENEFIT_ACTIVE;//active
                                $result .= '<bcActivateddAt status = "' . $bc_available . '" dtActivated = "' . $BcActivatedAt . '" />';// . $BcActivatedAt . '</bcActivateddAt>';

                            } else {
                                $bc_available = Constants::BC_BENEFIT_NOT_ACTIVE;//not active
                                date_default_timezone_set('UTC');
                                $date = new \DateTime("now");
                                $BcActivatedAt = $date->getTimestamp() * 1000;
                                $result .= '<bcActivateddAt status = "' . $bc_available . '"dtActivated = "' . $BcActivatedAt . '" />';//. $BcActivatedAt . '</bcActivateddAt>';
                            }
                        }

                    }

                }

            }
            $result .= '</results>';
            return $result;
        }


    }

    /**
     * @param $phone_number
     * @param $nexmoCode
     * @return string
     */
    public function smsResult($phone_number, $nexmoCode, $accountAvailability)
    {
        $xml_output = "<?xml version=\"1.0\"?>\n";
        $xml_output .= "<entries>\n";
        $xml_output .= "\t<entry>\n";
        $xml_output .= "\t\t<result>" . $accountAvailability . "</result>\n";
        $xml_output .= "\t\t<smsKey>" . $nexmoCode . "</smsKey>\n";
        $xml_output .= "\t</entry>\n";
        $xml_output .= $this->getReferrer($phone_number);
        $xml_output .= "</entries>";
        return $xml_output;
    }


}