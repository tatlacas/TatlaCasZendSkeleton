<?php
/**
 * Created by PhpStorm.
 * User: alois
 * Date: 9/13/2015
 * Time: 3:35 PM
 */

namespace Mobile\Model;


use Application\Entity\NettcashAccounts;
use Application\Entity\RebateReferralMultiplierSettings;
use Application\Entity\SubscribedPackages;
use Application\Entity\UserPayments;
use Application\Entity\Users;
use Application\Model\Constants;
use Application\Model\DoctrineInitialization;
use Application\Model\infobipSMSMessaging;
use Application\Model\PasswordCompatibilityLibrary;
use Application\Entity\AdminUpdates;

class UserAccountNetwork  extends DoctrineInitialization
{

    private $Xmlresult;
    private $f_1 = 0;
    private $f_2 = 0;
    private $f_3 = 0;
    private $f_4 = 0;
    private $f_5 = 0;
    private $f_6 = 0;
    private $f_7 = 0;
    private  $rebateMonth = '';
    private  $genArry = array();
    private  $allNetworkArry = array();
    private  $genPricesArry = array();


    function __construct($service_locator)
    {
        parent::__construct($service_locator);
    }

    public function returnUserNetwork($phone_number,$policy_number)
    {
        $this->setEntityManager();
        //  if ($phone_number && $policy_number ) {

        if(strlen($phone_number) <= Constants::PHONE_NUMBER_LENGTH){
            $phone_number = htmlspecialchars($phone_number);
            $result = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($phone_number);// "SELECT * FROM users WHERE phone_number = '".$phone_number."'";
            if($result == null){
                //no user
                return $this->return_results(Constants::ON_FAILURE_CONST);
            }
            $query = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->createQueryBuilder('n')
                ->where('n.referer = :ref')
                ->setParameter('ref', $result)
                ->getQuery();
            $result = $query->getResult();
            if ($result != null) {
                $xml_output = "<?xml version=\"1.0\"?>\n";
                $xml_output .= "<entries>\n";
                foreach($result as $user){


                    $query = $this->entity_manager->createQueryBuilder();
                    $query->select(array('pack'))
                        ->from('Application\Entity\SubscribedPackages', 'pack')
                        ->where($query->expr()->orX(
                            $query->expr()->eq('pack.user', '?1')
                        ))
                        ->andWhere($query->expr()->orX(
                            $query->expr()->eq('pack.isDependent', '?2')
                        ))
                        ->setParameters(array(1=> $user,2 =>false))
                        ->orderBy('pack.id', 'DESC')
                        ->setMaxResults(1);
                    $query = $query->getQuery();
                    $user_network = $query->getResult();
                    if ($user_network != null) {

                        foreach ($user_network as $package) {

                            $user_payments = $this->entity_manager->getRepository(Constants::ENTITY_USER_PAYMENTS)->findBy(array('subscribedPackage' => $package), array('monthPaidFor' => 'DESC'), 1);
                            //   $user_payments = $this->entity_manager->getRepository(Constants::ENTITY_USER_PAYMENTS)->findByPayee($user);
//                                $query = $this->entity_manager->getRepository(Constants::ENTITY_USER_PAYMENTS)->createQueryBuilder('n')
//                                    ->where('n.payee = :p_id')
//                                    ->setParameter('p_id', $user)
//                                    ->orderBy('n.id', 'DESC')
//                                    ->setMaxResults(Constants::DB_LIMIT)
//                                    ->getQuery();
//                                $user_payments = $query->getResult();
                            $date_paid ='';
                            if($user_payments != null && is_array($user_payments)){
                                //$user_payments = new UserPayments();

                                foreach($user_payments as $pay) {

                                    $date_paid = $pay->getDatePaid();
                                    $timeMillis = $date_paid->getTimestamp();
                                    $date_paid = $timeMillis*1000;
                                }
                            }else{
                                $date_paid = Constants::BUNDLE_ZERO;
                            }
//                        $user = new Users();
//                        $user->getUserId();
//                        $user->getFirstName();
//                        $user->getLastName();
//                        $user->getPhoneNumber();

                            $xml_output .= "\t<entry>\n";
                            $xml_output .= "\t\t<firstName>" . $user->getFirstName() . "</firstName>\n";
                            $xml_output .= "\t\t<lastName>" .  $user->getLastName() . "</lastName>\n";
                            $xml_output .= "\t\t<phoneNumber>" .$user->getPhoneNumber() . "</phoneNumber>\n";
                            $xml_output .= "\t\t<nextDue>" . $date_paid . "</nextDue>\n";
                            $xml_output .= "\t\t<result>" . Constants::ON_SUCCESS_CONST. "</result>\n";
                            $xml_output .= "\t</entry>\n";
                        }
                    }




                }
                $xml_output .= "</entries>";
                return $xml_output;
            }else
            {
                return $this->return_results(Constants::ON_FAILURE_CONST);
            }
        } else {
            //Wrong Number
            return $this->return_results(Constants::ON_FAILURE_CONST);
        }


        //   }

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

    /**
     * @param $json
     * @return string
     */
    public function insertAllFriends($json){
        // return 'hie alois';
        if (version_compare(PHP_VERSION, '5.3.7', '<')) {
            return Constants::xmlError(Constants::SORRY_SHIRI_DOES_NOT_RUN);
        }

        $this->setEntityManager();
        $utils = new Utils();

        $result = '<results>';
        $db = new DBUtils($this->service_locator);
        if($json != NULL){
            $request = json_decode($json,true);
            $count = $request['count'];

            if($count >0){
                for($i = 1; $i<= $count;$i++){

                    $nettcash_user = new NettcashAccounts();
                    $user = new Users();
                    $json_data = $request[$i];

                    $user_data = json_decode($json_data, true);
                    $referer = $user_data[Constants::REFERER];
                    $name = $user_data[Constants::FIRST_NAME];
                    $sname = $user_data[Constants::LAST_NAME];
                    $phone_number = $user_data[Constants::PHONE_NUMBER];
                    $id_number = $user_data[Constants::ID_NUMBER];
                    $dateOfBirth = $user_data[Constants::DATE_OF_BIRTH];
                    $createdAt = $user_data[Constants::CREATED_AT];
                    $nearest_branch = $user_data[Constants::NEAREST_BRANCH];
                    $user_server_id = $user_data[Constants::SERVER_ID];
                    $pincode = $user_data[Constants::PINCODE];
                    $gender = $user_data[Constants::GENDER];
                    $plan_name = $user_data[Constants::PLAN_NAME];
                    $gcm_reg_id = $user_data[Constants::GCM_REGID];
                    $joiningState = $user_data[Constants::IS_JOIN_ANOTHER];
                    $localId = $user_data[Constants::LOCAL_ID];
                    $nettcash_reg_state = "0";


                    if (strlen($phone_number) <= Constants::PHONE_NUMBER_LENGTH && strcmp($phone_number,$referer) !== 0) {
                        $phone_number = htmlspecialchars($phone_number);

                        $user_result = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($phone_number);
                        if ($user_result != null) {
                            //user exits

                            $result.='<status >'. $localId.'</status>';
                            continue;

                        }
                    } else {
                        //Wrong Number
                        $result.='<result>'. Constants::ON_WRONG_NUMBER_CONST.'</result>';
                        continue;
                    }


                    $account_created = false;
                    $resulting_id = 0;
                    if ($pincode === Constants::SHIRI_DEFAULT_PASSWORD) {
                        $pincode = $utils->generatePin(Constants::PINCODE_MIN_LENGTH);

                        //todo finish and enable this
                        //==========> notify who refered <==========
                        $referedBy = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($referer);
                        if ($referedBy!=null) {
                            // $referedBy = new Users();
                            $InitialFname = substr($referedBy->getFirstName(), 0, 1);
                            $ref_message = Constants::WELCOME.' '.$InitialFname .' '.$referedBy->getLastName().' '. Constants::HAS_JOINED_YOU_TO_SHIRI_YOUR_ACCOUNT_PINCODE_IS .$pincode;
                        }

                        //todo enable this
                        $infobipSMSMessaging = new infobipSMSMessaging();
                        //    $phone_number  = "+263783211562";
                        $sms_result = $infobipSMSMessaging->sendmsg($phone_number, Constants::SHIRI_NAME,  $ref_message);//Constants::WELCOME_YOUR_ACCOUNT_PINCODE_IS . $pincode);

                        $pincode = (string)$pincode;
                    }
                    //   else {
                    if ($referer !== Constants::SHIRI_CODE && $referer !== Constants::DOVES_CODE) {

                        $res = $db->save_individual_client_messages(Constants::YOU_HAVE_JOINED_A_FRIEND,
                            ''.$name.' '.$sname
                            , $referer);

                        $ref_gcm_reg_id = "";
                        $ref_referer = $referer;
                        $ref_phone_number = "";

                        do {
                            $ref_user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($ref_referer);

                            if ($ref_user!=null) {

                                $ref_referer = $ref_user->getPhoneNumber();
                                $ref_gcm_reg_id =$ref_user->getGcmRegid();
                                if($ref_gcm_reg_id === Constants::GCM_REG_ID_DEFAULT){
                                    $infobipSMSMessaging = new infobipSMSMessaging();
                                    //    $phone_number  = "+263783211562";
                                    //todo process sms response
                                    $sms_result = $infobipSMSMessaging->sendmsg($ref_referer, Constants::SHIRI_GOOD_NEWS,
                                        Constants::CONGRATULATIONS .$name. Constants::JUST_JOINED_YOUR_NETWORK );
                                    $res = $db->save_individual_client_messages(Constants::SHIRI_GOOD_NEWS_STR,
                                        Constants::CONGRATULATIONS .$name. Constants::JUST_JOINED_YOUR_NETWORK
                                        , $ref_referer);
                                }else {
                                    //todo process gcm response
                                    $db->send_notification($ref_gcm_reg_id,
                                        Constants::SHIRI_GOOD_NEWS, Constants::CONGRATULATIONS .$name.' '.$sname.' '. Constants::JUST_JOINED_YOUR_NETWORK);
                                    $res = $db->save_individual_client_messages(Constants::SHIRI_GOOD_NEWS_STR,
                                        Constants::CONGRATULATIONS .$name.' '.$sname.' '. Constants::JUST_JOINED_YOUR_NETWORK
                                        , $ref_referer);
                                }
                                //taking the results
                                //   $user = new Users();
                                $ref = $ref_user->getReferer();
                                $checking = $ref_referer;
                                $ref_referer = $ref->getPhoneNumber();
                                if($checking === $ref_referer)
                                    $ref_referer = Constants::SHIRI_DEFAULT_NUMBER ;


                            }

                        } while ($ref_referer !== Constants::SHIRI_DEFAULT_NUMBER && $ref_referer !== Constants::DOVES_DEFAULT_NUMMBER);

                    }
                    //  }
                    if ($referer !== Constants::SHIRI_CODE && $referer !== Constants::DOVES_CODE) {
                        $ref_user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($referer);

                        if ($ref_user == null) {
                            //Referer not found
                            $result.='<result>'. Constants::ON_FAILURE_CONST.'</result>';
                            $result .= '</results>';
                            return $result;

                            //   return $this->return_die(Constants::ON_FAILURE_CONST);
                        }
                    } else if ($referer === Constants::SHIRI_CODE) {
                        $ref_user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber(Constants::SHIRI_DEFAULT_NUMBER);

                    } else if ($referer === Constants::DOVES_CODE) {
                        $ref_user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber(Constants::DOVES_DEFAULT_NUMMBER);

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
                        //branch not set
                        $result.='<result>'. Constants::ON_FAILURE_CONST.'</result>';
                        continue;
                        // return $this->return_die(Constants::BRANCH_NOT_SET);
                    }
                    if($gender == Constants::MALE){
                        $sex = true;
                    }else{
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

                    $this->entity_manager->persist($user);
                    $account_created = true;


                    //        todo use plan id instead of plan name
                    $plan_name_res = $this->entity_manager->getRepository(Constants::ENTITY_PACKAGE_PLANS)->findOneById($plan_name);
                    if ($plan_name_res == null) {
                        //plan not set
                        $result.='<result>'. Constants::ON_FAILURE_CONST.'</result>';
                        continue;
                    }

                    $mil = (int)$user->getCreatedAt();
                    $seconds = $mil / Constants::TO_MILLISECONDS;
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
                    $nettcash_user->setDateCreated($timestamp* Constants::TO_MILLISECONDS);
                    $this->entity_manager->persist($nettcash_user);

                    $ad_updates = new AdminUpdates();
                    $ad_updates->setUser($user);
                    $ad_updates->setSendState(false);
                    $this->entity_manager->persist($ad_updates);
                    //todo enable this
                    $this->entity_manager->flush();

                    if ($joiningState === Constants::JOINING_MYSELF_STATE) {
                        $res = $db->save_individual_client_messages($name. " " . $sname, Constants::WELCOME_TO_SHIRI_FUNERAL_PLAN
                            , $phone_number);
                        $db->send_notification($gcm_reg_id, $name . " " . $sname, Constants::SHIRI_WELCOME_MSG);
                    }else{
                        $res = $db->save_individual_client_messages(Constants::SHIRI_NAME,
                            $referer . Constants::HAVE_REFERRED_YOU_TO_SHIRI_ACCOUNT_PINCODE . $pincode,$phone_number );
                    }

                    if ($account_created) {
                        //registering nettcash
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
                            $net_result = curl_exec($ch);
                            header("Content-type: text/xml");

                            if (curl_errno($ch)) {

                                $this->nettcashRegInProgress($joiningState, $db, $phone_number, $gcm_reg_id);
                                $this->createJobOnError( $db, $phone_number);

                            } else {

                                $xml = simplexml_load_string($net_result);
                                $net_result = $xml->result;


                                if ((strpos($net_result, Constants::CLIENT_ALREADY_EXISTS) !== false) || (strpos($net_result, Constants::SUCCESS) !== false))
                                {
                                    $nettcash_reg_state = Constants::REGISTERED;
                                    $_message = Constants::NETTCASH_ACCOUNT_REGISTERED_SUCCESSFULLY;
                                    $code = Constants::NETTCASH_ACC_REG_SUCCESS_STR;

                                    if (strpos($net_result, Constants::CLIENT_ALREADY_EXISTS) !== false) {
                                        $_message = Constants::THANK_YOU_NETT_CASH_ACCOUNT_ALREADY_EXISTS;
                                        $code = Constants::NETTCASH_ACC_EXITS_STR;
                                    }

                                    $res = $db->save_individual_client_messages(Constants::NETT_CASH_WALLET_FEEBACK, $_message
                                        , $phone_number);
                                    if ($joiningState === Constants::JOINING_MYSELF_STATE) {
                                        //  $db->send_notification($gcm_reg_id, Constants::NETT_CASH_WALLET_FEEBACK, $code);
                                    }else{
                                        $infobipSMSMessaging = new infobipSMSMessaging();
                                        //todo process sms response
                                        $sms_result = $infobipSMSMessaging->sendmsg($phone_number, Constants::SHIRI_NAME,
                                            $_message);
                                    }
                                    $raw_results = $this->entity_manager->getRepository(Constants::ENTITY_USER_NETTCASH_ACCOUNT)->findOneByUser($user);
                                    if ($raw_results != null) {

                                        $raw_results->setActivated(true);
                                        //todo enable this
                                        $this->entity_manager->flush();
                                    }

                                }else {
                                    $this->nettcashRegInProgress($joiningState, $db, $phone_number, $gcm_reg_id);
                                    $this->createJobOnError( $db, $phone_number);
                                }

                            }
                        } catch (\Exception $ex) {
                            //  return $this->return_results(Constants::ON_FAILURE_CONST, "", "");
                            //return $this->return_results($ex, "", "");
                            $this->nettcashRegInProgress($joiningState, $db, $phone_number, $gcm_reg_id);
                            $this->createJobOnError( $db, $phone_number);
                        }

                        $result.='<status >'. $localId.'</status>';

                        //  return $this->return_results(Constants::ON_SUCCESS_CONST, $resulting_id, $policy_number);
                        //   }
                    }else {
                        //account not created
                        $result.='<result >'. Constants::ON_FAILURE_CONST.'</result>';
                        //  $result .= '</results>';
                        continue;
                        // return $this->return_results(Constants::ON_FAILURE_CONST, "", "");

                    }

                }

                $result .= '</results>';
                return $result;
            }else{
                $result.='<result >'. Constants::ON_FAILURE_CONST.'</result>';
                $result .= '</results>';
                return $result;
            }

        }else {
            $result.='<result >'. Constants::ON_FAILURE_CONST.'</result>';
            $result .= '</results>';
            return $result;
        }

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
        $status = $obj->status;
        if (strcmp($status, Constants::SUCCESS) == 0) {
            $res = $db->save_cron_job(Constants::NETT_CASH_ACCOUNT, $obj->cron_job_id, $phone_number);

        }

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

    /**
     * @param $phone_number
     * @param $rebateMonth
     * @return string
     */
    public function getmonthlyRebates($phone_number, $rebateMonth)
    {
      //  $phone_number = '+263775774573';
        $this->setEntityManager();
        $this->Xmlresult = '<results>';
        $this->genArry = array(
            Constants::FIRST_GENERATION =>0,
            Constants::SECOND_GENERATION =>0,
            Constants::THIRD_GENERATION =>0,
            Constants::FORTH_GENERATION =>0,
            Constants::FIFTH_GENERATION =>0,
            Constants::SIXTH_GENERATION =>0,
            Constants::SEVENTH_GENERATION =>0
        );
        $this->allNetworkArry = array(
            'TotalAmount'=>0
        );
        $rank = 0;
        if (strlen($phone_number) <= Constants::PHONE_NUMBER_LENGTH) {
            $rebateMonth = new \DateTime($rebateMonth);
            $rebateMonth = $rebateMonth->format('Y-m-d 00:00:00');
            $this->rebateMonth = $rebateMonth;
            $rebatePricesPerGen =  $this->entity_manager->getRepository(Constants::ENTITY_REBATE_REFERRAL_MULTIPLIER_PRICES)->findAll($phone_number);
            if($rebatePricesPerGen != null){
                $i = 0;
                foreach($rebatePricesPerGen as $entry){
                    ++$i;
                   // $entry = new RebateReferralMultiplierSettings();
                    $this->genPricesArry[$i] = $entry->getRebate();
                }
            }
            $user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($phone_number);
            if ($user != null) {

                $package = $this->entity_manager->getRepository(Constants::ENTITY_SUBSCRIBED_PACKAGES)->findOneByUser($user);
                // $package = new SubscribedPackages();

                //  $sql = 'SELECT * FROM subscribed_packages sub WHERE sub.user_id = "'.$user->getUserId().'" AND sub.';
                $sql = 'SELECT * FROM user_payments pay WHERE pay.subscribed_package_id = "'.$package->getId().'" AND pay.month_paid_for = "'.$rebateMonth.'"';

                $conn = $this->getEntityManager()->getConnection();
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $payment = $stmt->fetch();
               $check_generations = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findByReferer($user);
                if($payment != null ){
                    if( $check_generations != null) {
                        $this->getReferredFriends(0, $phone_number);
                        //$this->Xmlresult.= '<fgeneration = "'.$this->f_1.'"/>';
                        if ($this->genArry[Constants::FIRST_GENERATION] > 0) {
                            $rank = Constants::FIRST_GENERATION;
                            $this->Xmlresult .= '<firstGeneration users = "' . $this->genArry[Constants::FIRST_GENERATION] . '" price = "'.$this->genPricesArry[Constants::FIRST_GENERATION].'" />';
                        }
                        if ($this->genArry[Constants::SECOND_GENERATION] > 0) {
                            $rank = Constants::SECOND_GENERATION;
                            $this->Xmlresult .= '<secondGeneration users = "' . $this->genArry[Constants::SECOND_GENERATION] . '" price = "'.$this->genPricesArry[Constants::SECOND_GENERATION].'" />';
                        }
                        if ($this->genArry[Constants::THIRD_GENERATION] > 0) {
                            $rank = Constants::THIRD_GENERATION;
                            $this->Xmlresult .= '<thirdGeneration users = "' . $this->genArry[Constants::THIRD_GENERATION] . '" price = "'.$this->genPricesArry[Constants::THIRD_GENERATION].'" />';
                        }
                        if ($this->genArry[Constants::FORTH_GENERATION] > 0) {
                            $rank = Constants::FORTH_GENERATION;
                            $this->Xmlresult .= '<forthGeneration users = "' . $this->genArry[Constants::FORTH_GENERATION] . '" price = "'.$this->genPricesArry[Constants::FORTH_GENERATION].'" />';
                        } if ($this->genArry[Constants::FIFTH_GENERATION] > 0) {
                            $rank = Constants::FIFTH_GENERATION;
                            $this->Xmlresult .= '<fifthGeneration users = "' . $this->genArry[Constants::FIFTH_GENERATION] . '" price = "'.$this->genPricesArry[Constants::FIFTH_GENERATION].'" />';
                        } if ($this->genArry[Constants::SIXTH_GENERATION] > 0) {
                            $rank = Constants::SIXTH_GENERATION;
                            $this->Xmlresult .= '<sixthGeneration users = "' . $this->genArry[Constants::SIXTH_GENERATION] . '" price = "'.$this->genPricesArry[Constants::SIXTH_GENERATION].'" />';
                        } if ($this->genArry[Constants::SEVENTH_GENERATION] > 0) {
                            $rank = Constants::SEVENTH_GENERATION;
                            $this->Xmlresult .= '<seventhGeneration users = "' . $this->genArry[Constants::SEVENTH_GENERATION] . '" price = "'.$this->genPricesArry[Constants::SEVENTH_GENERATION].'" />';
                        }

                        $this->Xmlresult .= '<monthlyRanking rank = "' . $rank. '"  />';
                        $this->Xmlresult .= '<totalPaidRabate amount = "' . '200.00'. '"  />';
                        $amount = number_format($this->allNetworkArry['TotalAmount'], 2);
                        $this->Xmlresult .= '<totalNetworkRabate amount = "' .$amount. '"  />';
                        $this->Xmlresult .= '<result status = "' . Constants::ON_SUCCESS_CONST. '"  />';
                        $this->Xmlresult .= '</results>';
                        return $this->Xmlresult;
                    }else {
                        $this->Xmlresult .= '<result status = "' . Constants::ON_NO_NETWORK. '"  />';
                        $this->Xmlresult.= '</results>';
                        return $this->Xmlresult;
                    }
                }else {
                    $payment = $this->entity_manager->getRepository(Constants::ENTITY_USER_PAYMENTS)->findOneBySubscribedPackage($package);
                    if($payment == null){
                        $this->Xmlresult .= '<result status = "' . Constants::ON_NO_FIRST_PAYMENT. '"  />';
                        $this->Xmlresult.= '</results>';
                        return $this->Xmlresult;
                    }
                    $this->Xmlresult .= '<result status = "' . Constants::ON_NO_PAYMENT. '"  />';
                    $this->Xmlresult.= '</results>';
                    return $this->Xmlresult;
                }
            }else {
                $this->Xmlresult .= '<result status = "' . Constants::ON_FAILURE_CONST. '"  />';
                $this->Xmlresult.= '</results>';
                return $this->Xmlresult;
            }

        }else{
            $this->Xmlresult .= '<result status = "' . Constants::ON_FAILURE_CONST. '"  />';
            $this->Xmlresult.= '</results>';
            return $this->Xmlresult;
        }

    }

    private function getReferredFriends($n,$phone_number)
    {
        $user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($phone_number);

        if ($user != null) {
            $user_generations = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findByReferer($user);
            if($user_generations != null){
                ++$n;
                foreach($user_generations as $person){
                    $package = $this->entity_manager->getRepository(Constants::ENTITY_SUBSCRIBED_PACKAGES)->findOneByUser($person);
                    // echo $person->getPhoneNumber().' '.$n.'<br/>';//$phone_number;
                    $sql = 'SELECT * FROM user_payments pay WHERE pay.subscribed_package_id = "'.$package->getId().'" AND pay.month_paid_for = "'.$this->rebateMonth.'"';
                    $conn = $this->getEntityManager()->getConnection();
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $payment = $stmt->fetch();
                    //  $payment = $this->entity_manager->getRepository(Constants::ENTITY_USER_PAYMENTS)->findOneBySubscribedPackage($package);
                    if($payment != null){
                        $this->genArry[$n] += 1;
//                        if($n == Constants::FIRST_GENERATION){
//                            $this->f_1 += 1;
//                        }elseif($n == Constants::SECOND_GENERATION){
//                            $this->f_2 += 1;
//                        }elseif($n == Constants::THIRD_GENERATION){
//                            $this->f_3 += 1;
//                        }elseif($n == Constants::FORTH_GENERATION){
//                            $this->f_4 += 1;
//                        }elseif($n == Constants::FIFTH_GENERATION){
//                            $this->f_5 += 1;
//                        }elseif($n == Constants::SIXTH_GENERATION){
//                            $this->f_6 += 1;
//                        }elseif($n == Constants::SEVENTH_GENERATION){
//                            $this->f_7 += 1;
//                        }
                    }
                    $this->allNetworkArry['TotalAmount']+= $this->genPricesArry[$n] *1;
                }
                if ($n == Constants::SHIRI_NETWORK_LIMIT) {
                    return $n - 1;
                }
                foreach ($user_generations as $networkUser) {
                    $this->getReferredFriends($n, $networkUser->getPhoneNumber());
                }

            }else{

                return ++$n;

            }
        }


    }

    public function checkErrors()
    {
        $this->setEntityManager();
        $users = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findAll();
        if ($users != null) {

            foreach($users as $user) {
                $package = $this->entity_manager->getRepository(Constants::ENTITY_SUBSCRIBED_PACKAGES)->findOneByUser($user);
                if($package == null){
                    $plan_name_res = $this->entity_manager->getRepository(Constants::ENTITY_PACKAGE_PLANS)->findOneById(Constants::PREMIUM_POLICY);
                    if ($plan_name_res == null) {
                        return $this->return_die(Constants::PLAN_NOT_SET);
                    }
                    echo $user->getPhoneNumber(). ' id ==>'.$user->getUserId().' dt==> '.$user->getCreatedAt().'<br/>';
                    if($user->getUserId() <= 2){
                        continue;
                    }
//                    $mil = $user->getCreatedAt();
//                    $seconds = $mil / Constants::TO_MILLISECONDS;
//                    $dt = date("Y-m-d 00:00:00", $seconds);
//                    $date = new \DateTime($dt);
//                    $package = new SubscribedPackages();
//                    $package->setDateActivated($date);
//                    $package->setUser($user);
//                    $package->setPackagePlan($plan_name_res);
//                    $package->setIsDependent(false);
//                    $package->setUser($user);
//                    $package->setStatus(true);
//                    $this->entity_manager->persist($package);
                    //  $this->entity_manager->flush();
                }
            }
        }
    }


}