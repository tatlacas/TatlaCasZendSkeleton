<?php
/**
 * Created by PhpStorm.
 * User: alois
 * Date: 9/11/2015
 * Time: 6:36 PM
 */

namespace Mobile\Model;


use Application\Entity\EcocashPayments;
use Application\Entity\FromNettcashServer;
use Application\Entity\NettcashAccounts;
use Application\Entity\NettcashPayments;
use Application\Entity\SubscribedPackages;
use Application\Entity\UserPayments;
use Application\Entity\UserPolicies;
use Application\Entity\Users;
use Application\MailMsg\Address;
use Application\MailMsg\Attachments;
use Application\MailMsg\MsgMail;
use Application\Model\Constants;
use Application\Model\DoctrineInitialization;
use Application\Model\infobipSMSMessaging;
use Doctrine\ORM\NoResultException;
use Exception;
use Zend\I18n\Validator\DateTime;
use Zend\XmlRpc\Value\String;

class AdminUpdates extends DoctrineInitialization
{
    public $selection = "";
    public $send_status = '1';
    public $send_status_send = "2";
    //const TODAY_S_PAYMENTS = self::TODAY_S_PAYMENTS1;
    const AMOUNT_PAID = " Amount Paid :";
    const USER_NAME = " User name : ";
    const PHONE_NUMBER = ", Phone Number :";
    const FINISHED = "finished";
    const NEW_USERS = "New Users";
    const ACTIVE = 'Active';
    const REGISTERED = 'Registered';
    const ADDITIONAL_DEPENDANT = 'Additional dependant';
    const IMMEDIATE_FAMILY = 'Immediate Family';
    const SPOUSE = 'Spouse';
    const BIOLOGICAL_CHILD = 'Biological Child';

    const WHONIBALL_GMAIL_EMAIL = 'whoniball@gmail.com';

    const MUMERAALOIS_GMAIL_EMAIL = 'mumeraalois@gmail.com';

    const WILLIAM_HONIBALL = 'William Honiball';

    const DEVIN_TEER = 'Devin Teer';

    const DEVINTEER_GMAIL_EMAIL = 'devinteer@gmail.com';

    const ALOIS_MUMERA = 'Alois Mumera';

    const WEBMASTER_MYSHIRI_EMAIL = 'webmaster@myshiri.com';

    function __construct($service_locator)
    {
        parent::__construct($service_locator);
    }

    public function adminUpdates($url){
        $values = urldecode($url);
        $values = base64_decode($values);
        $conJob_Api = new CronJob(Constants::CRON_JOBS_TOKEN);
        $this->setEntityManager();
        $qb = $this->entity_manager->createQueryBuilder();
        $db = new DBUtils($this->service_locator);
        $util = new Utils();
        $objPHPExcel = new \PHPExcel();

        if($values === "value=BCFvHUNcDnOPnUwwBzVlQH0piJtjXl.0t1XkA8pw9dMXTpOq"){
            //  if($update_state === "yes"){
            //   die('Admin Updates');

            $objPHPExcel->getProperties()->setCreator(Constants::DEVELOPER_NAME)
                ->setLastModifiedBy(Constants::DEVELOPER_NAME)
                ->setTitle("Shiri User Document")
                ->setSubject("All Users")
                ->setDescription("Shiri Funeral Plan")
                ->setKeywords("office PHPExcel php")
                ->setCategory(Constants::SHIRI_CLIENTS);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', Constants::MEMBER_FIRSTNAME)
                ->setCellValue('B1', Constants::MEMBER_SURNAME)
                ->setCellValue('C1', Constants::MEMBER_ID)
                ->setCellValue('D1', Constants::MALE_TITLE)
                ->setCellValue('E1', Constants::MOBILE)
                ->setCellValue('F1', Constants::POLICY_PREMIUM)
                ->setCellValue('G1', Constants::BUS_AND_CASH)
                ->setCellValue('H1', Constants::DATE_OF_BIRTH_TITLE)
                ->setCellValue('I1', Constants::JOINED_AT)
                ->setCellValue('J1', Constants::STATUS)
                ->setCellValue('K1', Constants::POLICY_NUMBER)
                ->setCellValue('L1', Constants::DEPENDANTS_COUNT)
                ->setCellValue('M1', Constants::DEPENDANT_FIRSTNAME)
                ->setCellValue('N1', Constants::DEPENDANT_SURNAME)
                ->setCellValue('O1', Constants::ID_NUMBER_STR)
                ->setCellValue('P1', Constants::MALE_TITLE)
                ->setCellValue('Q1', Constants::DATE_OF_BIRTH_TITLE)
                ->setCellValue('R1', Constants::DEPENDANT_RELATION);
            $rowCount = Constants::START_ROW_COUNT;
            $user_updates = $this->entity_manager->getRepository(Constants::ENTITY_ADMIN_UPDATES)->findBySendState(false);
            if($user_updates != null && is_array($user_updates)){
                //============ creating USERS excel file =============
                $count = 0;
                $message = self::NEW_USERS;
                foreach($user_updates as $users){

                    $user = $users->getUser();
                    $count += 1;
//                    $message .= "\r\n".$count. self::USER_NAME .$user->getFirstName()
//                        ." ".$user->getLastName(). self::PHONE_NUMBER .$user->getPhoneNumber()."\r\n";

                    $query = $this->entity_manager->createQueryBuilder();
                    //  $user =  new Users();
                    $Joined_date = $util->MillisecondsToDateTimeString($user->getCreatedAt(),0);
                    $dob =  $util->MillisecondsToDateTimeString($user->getDateOfBirth(),Constants::SHOW_DATE_OF_BIRTH);
                    $policy_number = $util->generatePolicyNumber($user->getUserId());
                    //  $query = $this->entity_manager->createQueryBuilder();
                    $query->select('count(d.user)')
                        ->from(Constants::ENTITY_USERDEPENDENTS, 'd')
                        ->where('d.user = ?1')
                        ->setParameter(1, $user);

                    if ($user->getGender()) {
                        $gender = Constants::TRUE_STR;
                    } else {
                        //female
                        $gender = Constants::FALSE_STR;
                    }
                    $deps_count = 0;
                    try{
                        $deps_count = $query->getQuery()->getSingleScalarResult();
                    }
                    catch(NoResultException $e) {
                        /*Your stuffs..*/
                    }
                    $query = $this->entity_manager->createQueryBuilder();
                    $query->select('nData')
                        ->from(Constants::ENTITY_USER_PAYMENTS, 'nData')
                        ->where('nData.payee = :p_id')
                        ->setParameter('p_id', $user)
                        ->orderBy('nData.id', 'DESC')
                        ->setMaxResults(Constants::MAX_RESULT_FROM_QUERY);
                    $query_res = $query->getQuery();

                    $user_payments = $query_res->getResult();
                    //  die('here'.$deps_count. ' '.$user->getUserId());
                    $date_paid ='';
                    if($user_payments != null && is_array($user_payments)) {
                        foreach ($user_payments as $payment){
                            $date_paid = self::ACTIVE;//$payment->getDatePaid();
                        }
                    }else{
                        $date_paid= self::REGISTERED;
                    }
                    $ArrResult = $this->returnPremiumAmount($user);
                    $premium_amount = $ArrResult['amount'];
                    $bc_state = $ArrResult['state'];
                    if($bc_state == Constants::IS_B_AND_C){
                        $bc_state = Constants::BANDC_ACTIVE;
                    }else{
                        $bc_state = Constants::BANDC_NOT_ACTIVE;
                    }
                    //  die($premium_amount.' '.$user->getUserId());
                    $premium_amount = number_format($premium_amount,Constants::TWO_DECIMAL_PLACE);
                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$rowCount, $user->getFirstName())
                        ->setCellValue('B'.$rowCount,  $user->getLastName() )
                        ->setCellValue('C'.$rowCount,  $user->getIdNumber())
                        ->setCellValue('D'.$rowCount, $gender)
                        ->setCellValue('E'.$rowCount, $user->getPhoneNumber())
                        ->setCellValue('F'.$rowCount, $premium_amount)
                        ->setCellValue('G'.$rowCount, $bc_state)
                        ->setCellValue('H'.$rowCount, $dob )
                        ->setCellValue('I'.$rowCount, $Joined_date)
                        ->setCellValue('J'.$rowCount, $date_paid)
                        ->setCellValue('K'.$rowCount, $policy_number)
                        ->setCellValue('L'.$rowCount, $deps_count);
                    if($deps_count > 0){
                        $query = $this->entity_manager->createQueryBuilder();
                        $query->select(array('dep'))
                            ->from('Application\Entity\SubscribedPackages', 'dep')
                            // ->where('d.user = ?1')
                            ->where($query->expr()->orX(
                                $query->expr()->eq('dep.user', '?1')
                            ))
                            ->andWhere($query->expr()->orX(
                                $query->expr()->eq('dep.status', '?2'),
                                $query->expr()->eq('dep.isDependent', '?3')
                            ))
                            ->setParameters(array(1=> $user,2 =>true,3 => true));
                        $query = $query->getQuery();
                        $user_deps = $query->getResult();
                        $state = 0;
                        if ($user_deps != null) {
                            foreach ($user_deps as $package) {
                                if($package->getStatus()) {
                                    //==================== Loading DEPENDANTS ==================
                                    if ($package->getIsDependent()) {
                                        $dependant = $package->getDependent();
                                        // $dependant = new UserDependents();

                                        if ($dependant->getGender()) {
                                            $gender = Constants::TRUE_STR;
                                        } else {
                                            //female
                                            $gender = Constants::FALSE_STR;
                                        }
                                        $dep_relation = $dependant->getRelationType();
                                        //  $dep_relation = new UserRelationshipTypes();
                                        $id_number = $dependant->getIdNumber();
                                        if (!isset($id_number)) {
                                            $id_number = '';
                                        }
                                        $relation = self::ADDITIONAL_DEPENDANT;
                                        $rel = $dep_relation->getId();
                                        if($rel == Constants::IS_SPOUSE){
                                            $relation = self::SPOUSE;
                                        }else if($rel == Constants::IS_BIO_CHILD){
                                            $relation = self::BIOLOGICAL_CHILD;
                                        }

                                        $dob =  $util->MillisecondsToDateTimeString($dependant->getDateOfBirth(),Constants::SHOW_DATE_OF_BIRTH);
                                        $objPHPExcel->setActiveSheetIndex(0)
                                            ->setCellValue('M'.$rowCount, $dependant->getFirstName())
                                            ->setCellValue('N'.$rowCount, $dependant->getLastName())
                                            ->setCellValue('O'.$rowCount, $id_number)
                                            ->setCellValue('P'.$rowCount, $gender)
                                            ->setCellValue('Q'.$rowCount, $dob)
                                            ->setCellValue('R'.$rowCount, $relation);
                                        $rowCount++;
                                    }
                                }
                            }
                        }

                    }

                    $rowCount++;
                }
                //==================== sending mail ==================
                // Rename worksheet
                $objPHPExcel->getActiveSheet()->setTitle(Constants::SHIRI_NEW_CLIENTS);
                // Set active sheet index to the first sheet, so Excel opens this as the first sheet
                $objPHPExcel->setActiveSheetIndex(0);

                $file_name = Constants::SFP_ALL_USERS.'_'.$util->returnCurrentTimeString();
                $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                $objWriter->save(Constants::DATA_SAVE_TEMP_FILES .$file_name.'.xls');
                $file = Constants::DATA_SAVE_TEMP_FILES.$file_name.'.xls';
                // echo $file.'users files';
                $attachment1 = new Attachments();
                $attachment1->setBinary(file_get_contents($file))
                    ->setFileName($file_name.'.xls');
                $toAddress = new Address();
                $toAddress->setEmailAddress(self::MUMERAALOIS_GMAIL_EMAIL)
                    ->setName(self::ALOIS_MUMERA);
                $toWilliamAddress = new Address();
                $toWilliamAddress->setEmailAddress(self::WHONIBALL_GMAIL_EMAIL)//alois.mumera@yahoo.com
                ->setName(self::WILLIAM_HONIBALL);
                $toDevinTeerAddress = new Address();
                $toDevinTeerAddress->setEmailAddress(self::DEVINTEER_GMAIL_EMAIL)//alois.mumera@yahoo.com
                ->setName(self::DEVIN_TEER);

                $fromAddress = new Address();
                $fromAddress->setEmailAddress(self::WEBMASTER_MYSHIRI_EMAIL)
                    ->setName(self::ALOIS_MUMERA);
                $htmlPart = "<html><body><p>Today's new Business</p></body></html>";
                //  $htmlPart->type = 'text/html';
                $textPart = Constants::NEW_BUSINESS;//"Sorry, I'm going to be late today!";
                // $textPart->type = 'text/plain';

//TODO schedule deleting files after one day
                try {
                    $mail = new MsgMail();
                    $mail->addTo($toWilliamAddress)
                        ->addCc($toDevinTeerAddress)
                        ->addBcc($toAddress)
                        ->setFrom($fromAddress)
                        ->setSubject(Constants::SHIRI_NAME.' '.Constants::NEW_BUSINESS.' '. Constants::DAILY_UPDATES)
                        ->setText($textPart)
                        ->setHtml($htmlPart)
                        ->addAttachment($attachment1)
                        ->send();
//            $message = new Message();
//            $message->addTo('mumeraalois@gmail.com')
//                ->addFrom('ralph.schindler@zend.com')
//                ->setSubject('Greetings and Salutations!')
//                ->setBody("Sorry, I'm going to be late today!");
//
//            $transport = new SendmailTransport();
//            $transport->send($message);
                }catch (\Exception $ex){
                    Constants::xmlError($ex);
                }

                //return $file.'<br/>';
                //  $mail =  $this->send_Email($message, Constants::NEW_BUSINESS);
//todo enable this
                $qb = $this->entity_manager->createQueryBuilder();
                $q = $qb->update(Constants::ENTITY_ADMIN_UPDATES, 'a')
                    ->set('a.sendState', $qb->expr()->literal(true))
                    ->where('a.sendState = ?1')
                    ->setParameter(1, false)
                    ->getQuery();
                $p = $q->execute();

            }
            //====================== Processing Payments ========================

            $objPHPExcel = new \PHPExcel();
            $objPHPExcel->getProperties()->setCreator(Constants::DEVELOPER_NAME)
                ->setLastModifiedBy(Constants::DEVELOPER_NAME)
                ->setTitle("Shiri Payments Document")
                ->setSubject("All Payments")
                ->setDescription("Shiri Funeral Plan")
                ->setKeywords("office PHPExcel php")
                ->setCategory(Constants::SHIRI_CLIENTS);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', Constants::MEMBER_FIRSTNAME)
                ->setCellValue('B1', Constants::MEMBER_SURNAME)
                ->setCellValue('C1', Constants::POLICY_NUMBER)
                ->setCellValue('D1', Constants::MOBILE)
                ->setCellValue('E1', Constants::AMOUNT_PAID)
                ->setCellValue('F1', Constants::EXCESS_AMOUNT)
                ->setCellValue('G1', Constants::PAID_AT)
                ->setCellValue('H1', Constants::MONTHLY_PREMIUM )
                ->setCellValue('I1', Constants::PAYMENT_TYPE );
            $rowCount = Constants::START_ROW_COUNT;
            $net_payments = $this->entity_manager->getRepository(Constants::ENTITY_FROM_NETTCASH_SERVER)->findBySendState(false);
            $eco_payments = $this->entity_manager->getRepository(Constants::ENTITY_ECOCASH)->findBySendState(false);
            if($net_payments != null && is_array($net_payments) || $eco_payments != null) {
                //============ writing NETTCASH PAYMENTS to excel file =============
                if ($net_payments != null) {
                    $count = 0;
                    $message = Constants::TODAY_S_PAYMENTS;
                    foreach ($net_payments as $net) {
                        //$net = new FromNettcashServer();
                        $user = $net->getUser();
                        $count += 1;
//                    $message .= "\r\n".$count. self::USER_NAME .$user->getFirstName()
//                        ." ".$user->getLastName(). self::AMOUNT_PAID .$net->getAmountPaid(). self::PHONE_NUMBER .$user->getPhoneNumber()."\r\n";

                        $paidAt_date = $util->MillisecondsToDateTimeString($net->getDatePaid() * 1000, 0);
                        // $user = new Users();
                        //  $subscribed_packages = $this->entity_manager->getRepository(Constants::ENTITY_SUBSCRIBED_PACKAGES)->findBy(array('user' => $user, 'status' => true), array('id' => 'ASC'));
                        $ArrResult = $this->returnPremiumAmount($user);
                        $premium_amount = $ArrResult['amount'];

                        $policy_number = $util->generatePolicyNumber($user->getUserId());
                        $inadvance = '0.00';//$net->getExcessAmount();
                        $premium_amount = number_format($premium_amount, Constants::TWO_DECIMAL_PLACE);
//                $count += 1;
//                $message .= "\r\n" . $count . self::USER_NAME . $user->getFirstName()
//                    . " " . $user->getLastName() . self::AMOUNT_PAID . $net->getAmountPaid() . self::PHONE_NUMBER . $user->getPhoneNumber() . "\r\n";
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $rowCount, $user->getFirstName())
                            ->setCellValue('B' . $rowCount, $user->getLastName())
                            ->setCellValue('C' . $rowCount, $policy_number)
                            ->setCellValue('D' . $rowCount, $user->getPhoneNumber())
                            ->setCellValue('E' . $rowCount, $net->getAmountPaid())
                            ->setCellValue('F' . $rowCount, $inadvance)
                            ->setCellValue('G' . $rowCount, $paidAt_date)
                            ->setCellValue('H' . $rowCount, $premium_amount)
                            ->setCellValue('I' . $rowCount, Constants::NETTCASH_STR);
                        $rowCount++;
                    }
//                $exponentialBackoffStrategy = array(0, 10, 60);
//                $delay_interval = 60;
//                $args['retryAttempt'] = 1;
//                do{
//                    $mail =  $this->send_Email($message, Constants::TODAY_S_PAYMENTS);
//                    if($mail){
//
//                       // $sql = "UPDATE transactions SET send_state = '$send_status_send' WHERE send_state = '".$send_status."'";
//
//                        $q = $qb->update('Application\Entity\UserPayments', 'p')
//                            ->set('p.sendState', $qb->expr()->literal(true))
//                            ->where('p.sendState = ?1')
//                            ->setParameter(1, false)
//                            ->getQuery();
//                        $p = $q->execute();
//                        $args['retryAttempt'] = 12;
//                        //  return_results("success");
//                    }else{
//                        //  return_results("failed to send mail");
//                        sleep($delay_interval* $args['retryAttempt']);
//                        $args['retryAttempt']++;
//                    }
//                }while( $args['retryAttempt'] <= 10);

                }
                if ($eco_payments != null) {
                    $count = 0;
                    //============ writing ECOCASH PAYMENTS to excel file =============
                    foreach ($eco_payments as $eco) {
                        //   $eco = new EcocashPayments();
                        $user = $eco->getUser();
                        // $user = new Users();
                        $paidAt_date = $eco->getDatePaid();
                        $paidAt_date = $paidAt_date->format('Y-m-d H:m:s');
                        $ArrResult = $this->returnPremiumAmount($user);
                        $premium_amount = $ArrResult['amount'];

//                    $count += 1;
//                    $message .= "\r\n" . $count . self::USER_NAME . $user->getFirstName()
//                        . " " . $user->getLastName() . self::AMOUNT_PAID . $net->getAmountPaid() . self::PHONE_NUMBER . $user->getPhoneNumber() . "\r\n";
                        $policy_number = $util->generatePolicyNumber($user->getUserId());
                        $inadvance = number_format($eco->getExcessAmount(),Constants::TWO_DECIMAL_PLACE);
                        $premium_amount = number_format($premium_amount,Constants::TWO_DECIMAL_PLACE);
//                $count += 1;
//                $message .= "\r\n" . $count . self::USER_NAME . $user->getFirstName()
//                    . " " . $user->getLastName() . self::AMOUNT_PAID . $net->getAmountPaid() . self::PHONE_NUMBER . $user->getPhoneNumber() . "\r\n";
                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$rowCount, $user->getFirstName())
                            ->setCellValue('B'.$rowCount, $user->getLastName())
                            ->setCellValue('C'.$rowCount, $policy_number)
                            ->setCellValue('D'.$rowCount, $user->getPhoneNumber())
                            ->setCellValue('E'.$rowCount, $eco->getAmountPaid())
                            ->setCellValue('F'.$rowCount,$inadvance)
                            ->setCellValue('G'.$rowCount, $paidAt_date)
                            ->setCellValue('H'.$rowCount, $premium_amount)
                            ->setCellValue('I'.$rowCount, Constants::ECOCASH_STR);
                        $rowCount++;
                    }
                }
                // Rename worksheet
                $objPHPExcel->getActiveSheet()->setTitle(Constants::SHIRI_NEW_PAYMENTS);
                // Set active sheet index to the first sheet, so Excel opens this as the first sheet
                $objPHPExcel->setActiveSheetIndex(0);

                $file_name = Constants::SFP_ALL_PAYMENT.'_'.$util->returnCurrentTimeString();//.date('m-d-Y-H-m-s');
                $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
                $objWriter->save(Constants::DATA_SAVE_TEMP_FILES .$file_name.'.xls');
                $file = Constants::DATA_SAVE_TEMP_FILES.$file_name.'.xls';
                // echo $file.'payments file';
                $attachment1 = new Attachments();
                $attachment1->setBinary(file_get_contents($file))
                    ->setFileName($file_name.'.xls');
                $toAddress = new Address();
                $toAddress->setEmailAddress(self::MUMERAALOIS_GMAIL_EMAIL)
                    ->setName(self::ALOIS_MUMERA);
                $toWilliamAddress = new Address();
                $toWilliamAddress->setEmailAddress(self::WHONIBALL_GMAIL_EMAIL)//alois.mumera@yahoo.com
                ->setName(self::WILLIAM_HONIBALL);
                $toDevinTeerAddress = new Address();
                $toDevinTeerAddress->setEmailAddress(self::DEVINTEER_GMAIL_EMAIL)//alois.mumera@yahoo.com
                ->setName(self::DEVIN_TEER);

                $fromAddress = new Address();
                $fromAddress->setEmailAddress(self::WEBMASTER_MYSHIRI_EMAIL)
                    ->setName(self::ALOIS_MUMERA);
                $htmlPart = "<html><body><p>Today's Payments</p></body></html>";
                //  $htmlPart->type = 'text/html';
                $textPart = Constants::TODAY_S_PAYMENTS;//"Sorry, I'm going to be late today!";
                // $textPart->type = 'text/plain';


                try {
                    $mail = new MsgMail();
                    $mail->addTo($toWilliamAddress)
                        ->addCc($toDevinTeerAddress)
                        ->addBcc($toAddress)
                        ->setFrom($fromAddress)
                        ->setSubject(Constants::SHIRI_NAME.' '.Constants::TODAY_S_PAYMENTS.' '. Constants::DAILY_UPDATES)
                        ->setText($textPart)
                        ->setHtml($htmlPart)
                        ->addAttachment($attachment1)
                        ->send();

                }catch (\Exception $ex){
                    Constants::xmlError($ex);
                }
                //todo enable this
                $qb = $this->entity_manager->createQueryBuilder();
                $q = $qb->update(Constants::ENTITY_FROM_NETTCASH_SERVER, 'p')
                    ->set('p.sendState', $qb->expr()->literal(true))
                    ->where('p.sendState = ?1')
                    ->setParameter(1, false)
                    ->getQuery();
                $p = $q->execute();
                //todo enable this
                $qb = $this->entity_manager->createQueryBuilder();
                $q = $qb->update(Constants::ENTITY_ECOCASH, 'p')
                    ->set('p.sendState', $qb->expr()->literal(true))
                    ->where('p.sendState = ?1')
                    ->setParameter(1, false)
                    ->getQuery();
                $p = $q->execute();


            }


        }else if($values === "value=BCFvHUNcDnOPnUwwBzVlQH0piJtjXl.An0t1XkA8pw9dMXTpOq"){

            //reregister nettcash users
            $url = Constants::NETTCASH_REG_LIVE_LINK;//'https://integrationhub.nettcash.co.zw:8444/tpapi/live/agenthub/tpenrollment.php?';
            $ch = curl_init(Constants::NETT_REGISTER_LINK);
            $nettcash_state = "0";

            $nettcash_users = $this->entity_manager->getRepository(Constants::ENTITY_USER_NETTCASH_ACCOUNT)->findByActivated(false);
            if($nettcash_users != null && is_array($nettcash_users)){
              $count = 0;
                foreach($nettcash_users as $net_user){
                    //    $net = new NettcashAccounts();
                    //  $net->getUser();
                    $user = $net_user->getUser();
                    //todo enable when testing
//                    $count  +=1;
//                    echo $count.'<br/>';
//                    if($count == 2)
//                        die('done');

                    $phone_number = $user->getPhoneNumber();
                    $name = $user->getFirstName();
                    $sname = $user->getLastName();
                    $id_number = $user->getIdNumber();
                    $gcm_reg_id = $user->getGcmRegid();

                    $net_phone_number = str_replace('+', '', $phone_number);
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

//Encode the array into JSON.
                    $jsonDataEncoded = json_encode($jsonData);

//Tell cURL that we want to send a POST request.
                    curl_setopt($ch, CURLOPT_POST, 1);

//Attach our encoded JSON string to the POST fields.
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

//Set the content type to application/json
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

                    try{
                        echo 'now process ==>'.$phone_number.'<br/>';
                        $result = curl_exec($ch);
                        header("Content-type: text/xml");
                        if(curl_errno($ch)){

                            echo 'Curl error: ' . curl_error($ch);

//                            $return = $conJob_Api->call_schedule_job(Constants::CRON_JOB_ADD_ACTION, array('cron_expression'=>
//                                Constants::CRON_JOB_TEN_MINUTES.' * * * *','url'=>'https://www.kilo-s.com/shusersconnect/shiri_logging/views/scripts/admin_updates.php?q=dmFsdWU9QkNGdkhVTmNEbk9QblV3d0J6VmxRSDBwaUp0alhsLkFuMHQxWGtBOHB3OWRNWFRwT3E%3D'
//                            ,'cron_job_name' =>'minutes','email_me' => Constants::CRON_JOB_ZERO,
//                                'log_output_length'=>Constants::CRON_JOB_ZERO,
//                                'testfirst'=>Constants::CRON_JOB_ZERO));
//                            $obj = json_decode($return);
//                            $result = $obj->status;
//                            if($result === Constants::SUCCESS){
//                                $res = $db->save_cron_job(Constants::NETT_CASH_ACCOUNT,$obj->cron_job_id,$phone_number);
//
//                            }


                        }else {

                            //todo delete failed stored data
                            $xml = simplexml_load_string($result);
                            $result =  $xml->result;
//                            if (isset($result)) {
//                                echo 'decoding result ==> ***'.$result.' *** PHONE NUMBER ==>'.$net_phone_number.'<br/>';
//                            }
                            if ((strpos($result,Constants::CLIENT_ALREADY_EXISTS) !== false) || (strpos($result,Constants::SUCCESS) !== false))
                            {
                                $nettcash_reg_state = Constants::REGISTERED;
                                $_message = Constants::NETTCASH_ACCOUNT_REGISTERED_SUCCESSFULLY;
                                $code = Constants::NETTCASH_ACC_REG_SUCCESS_STR;

                                if (strpos($result, Constants::CLIENT_ALREADY_EXISTS) !== false) {
                                    $_message = Constants::THANK_YOU_NETT_CASH_ACCOUNT_ALREADY_EXISTS;
                                    $code = Constants::NETTCASH_ACC_EXITS_STR;
                                }
                                $res = $db->save_individual_client_messages(Constants::NETT_CASH_WALLET_FEEBACK, $_message
                                    , $phone_number);
                                if($gcm_reg_id !== Constants::GCM_REG_ID_DEFAULT){
                                    $db->send_notification($gcm_reg_id, Constants::NETT_CASH_WALLET_FEEBACK, $code);
                                }else{
                                    //todo use SMS sending here
                                    $infobipSMSMessaging = new infobipSMSMessaging();
                                    //todo process sms response
                                    $result = $infobipSMSMessaging->sendmsg($phone_number, Constants::SHIRI_NAME,
                                        $_message);
                                }

//                                $sql = "UPDATE users SET nettcash_state = '$nettcash_reg_state' WHERE phone_number = '".$phone_number."'";
//                                $result = mysql_query($sql)  or die(mysql_error());
//                                if($result){
//                                    //  return_results("success");
//                                }
                                $raw_results = $this->entity_manager->getRepository(Constants::ENTITY_USER_NETTCASH_ACCOUNT)->findOneByUser($user);
                                if($raw_results!=null)
                                {

                                    $raw_results->setActivated(true);
                                    $this->entity_manager->flush();
                                }
                                //$sql = "SELECT * FROM cron_jobs WHERE phone_number = '".$phone_number."'";
                                // $result = mysql_query($sql)  or die(mysql_error());
                                $job_results = $this->entity_manager->getRepository(Constants::ENTITY_CRON_JOBS)->findBy(array('user' => $user, 'jobState' => true));//findOneByUser($user);
                                if($job_results!=null)
                                {
                                    $job_id = "";
                                    foreach($job_results as $job){

                                        $user = $job->getUser();
                                        $phone_number = $user->getPhoneNumber();
                                        $job_id = $job->getJobId();

                                        $return = $conJob_Api->call_schedule_job(Constants::CRON_JOB_REMOVE_ACTION, array('id' => $job_id));
                                        $obj = json_decode($return);
                                        $result = $obj->status;
                                        $cron_job_id = $obj->cron_job_id;
                                        if($result === Constants::SUCCESS && $cron_job_id === $job_id){
                                            $job->setJobState(false);
                                            $this->entity_manager->flush();

                                        }
                                    }

                                }

                            }else {
                                die("NIL NCSRVER");
                            }

                        }
                    }catch (Exception $ex)
                    {
                        //  die("eror");
                    }
                    sleep(3);
                }
            }else {
                die("Nothing XXXXXXXXXXXX");
            }

        }

    }

    function return_results($value){
        $xml_output = "<?xml version=\"1.0\"?>\n";
        $xml_output .= "<entries>\n";
        $xml_output .= "\t<entry>\n";
        $xml_output .= "\t\t<result>" . $value. "</result>\n";
        $xml_output .= "\t</entry>\n";
        $xml_output .= "</entries>";
        echo($xml_output);
    }

    function send_Email($message, $subject){
        $to = self::MUMERAALOIS_GMAIL_EMAIL;//  = 'whoniball@gmail.com'  ;// = 'mumeraalois@gmail.com';
        //  $subject = 'New Business';
        // $message = 'hello';
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: webmaster@myshiri.com' . "\r\n" .
            'Reply-To: webmaster@myshiri.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

        return mail($to, $subject, $message, $headers);
    }

    public function MySQLUsersDatatoExcelFile(){
      //  die('Wabatwa!!');
        $this->setEntityManager();
        $util = new Utils();

        $objPHPExcel = new \PHPExcel();

        $users = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findAll();
        if ($users == null) {
            //no users
            return $util->return_results(Constants::ON_FAILURE_CONST);
        }
        $objPHPExcel->getProperties()->setCreator(Constants::DEVELOPER_NAME)
            ->setLastModifiedBy(Constants::DEVELOPER_NAME)
            ->setTitle("Shiri User Document")
            ->setSubject("All Users")
            ->setDescription("Shiri Funeral Plan")
            ->setKeywords("office PHPExcel php")
            ->setCategory(Constants::SHIRI_CLIENTS);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', Constants::MEMBER_FIRSTNAME)
            ->setCellValue('B1', Constants::MEMBER_SURNAME)
            ->setCellValue('C1', Constants::MEMBER_ID)
            ->setCellValue('D1', Constants::MALE_TITLE)
            ->setCellValue('E1', Constants::MOBILE)
            ->setCellValue('F1', Constants::POLICY_PREMIUM)
            ->setCellValue('G1', Constants::BUS_AND_CASH)
            ->setCellValue('H1', Constants::DATE_OF_BIRTH_TITLE)
            ->setCellValue('I1', Constants::JOINED_AT)
            ->setCellValue('J1', Constants::STATUS)
            ->setCellValue('K1', Constants::POLICY_NUMBER)
            ->setCellValue('L1', Constants::DEPENDANTS_COUNT)
            ->setCellValue('M1', Constants::DEPENDANT_FIRSTNAME)
            ->setCellValue('N1', Constants::DEPENDANT_SURNAME)
            ->setCellValue('O1', Constants::ID_NUMBER_STR)
            ->setCellValue('P1', Constants::MALE_TITLE)
            ->setCellValue('Q1', Constants::DATE_OF_BIRTH_TITLE)
            ->setCellValue('R1', Constants::DEPENDANT_RELATION);
        $rowCount = Constants::START_ROW_COUNT;


        foreach($users as $user){
            if($user->getUserId() == 1 || $user->getUserId() == 2 ||
                $user->getUserId() ==3 || $user->getUserId() == 9|| $user->getUserId() ==10
                || $user->getUserId() ==100 || $user->getUserId() ==790
                || $user->getUserId() ==4
                || $user->getUserId() ==12 || $user->getUserId() ==824){
                continue;
            }
            $query = $this->entity_manager->createQueryBuilder();
            //  $user =  new Users();
            $Joined_date = $util->MillisecondsToDateTimeString($user->getCreatedAt(),0);
            $dob =  $util->MillisecondsToDateTimeString($user->getDateOfBirth(),Constants::SHOW_DATE_OF_BIRTH);
            $policy_number = $util->generatePolicyNumber($user->getUserId());
            //  $query = $this->entity_manager->createQueryBuilder();
            $query->select('count(d.user)')
                ->from(Constants::ENTITY_USERDEPENDENTS, 'd')
                ->where('d.user = ?1')
                ->setParameter(1, $user);

            if ($user->getGender()) {
                $gender = Constants::TRUE_STR;
            } else {
                //female
                $gender = Constants::FALSE_STR;
            }
            $deps_count = 0;
            try{
                $deps_count = $query->getQuery()->getSingleScalarResult();
            }
            catch(NoResultException $e) {
                /*Your stuffs..*/
            }
            $query = $this->entity_manager->createQueryBuilder();
            $query->select('nData')
                ->from(Constants::ENTITY_USER_PAYMENTS, 'nData')
                ->where('nData.payee = :p_id')
                ->setParameter('p_id', $user)
                ->orderBy('nData.id', 'DESC')
                ->setMaxResults(Constants::MAX_RESULT_FROM_QUERY);
            $query_res = $query->getQuery();

            $user_payments = $query_res->getResult();
            //  die('here'.$deps_count. ' '.$user->getUserId());
            $date_paid ='';
            if($user_payments != null && is_array($user_payments)) {
                foreach ($user_payments as $payment){
                    $date_paid = self::ACTIVE;//$payment->getDatePaid();
                }
            }else{
                $date_paid= self::REGISTERED;
            }
            $ArrResult = $this->returnPremiumAmount($user);
            $premium_amount = $ArrResult['amount'];
            $bc_state = $ArrResult['state'];
            if($bc_state == Constants::IS_B_AND_C){
                $bc_state = Constants::BANDC_ACTIVE;
            }else{
                $bc_state = Constants::BANDC_NOT_ACTIVE;
            }
            //  die($premium_amount.' '.$user->getUserId());
            $premium_amount = number_format($premium_amount,Constants::TWO_DECIMAL_PLACE);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$rowCount, $user->getFirstName())
                ->setCellValue('B'.$rowCount,  $user->getLastName() )
                ->setCellValue('C'.$rowCount,  $user->getIdNumber())
                ->setCellValue('D'.$rowCount, $gender)
                ->setCellValue('E'.$rowCount, $user->getPhoneNumber())
                ->setCellValue('F'.$rowCount, $premium_amount)
                ->setCellValue('G'.$rowCount, $bc_state)
                ->setCellValue('H'.$rowCount, $dob )
                ->setCellValue('I'.$rowCount, $Joined_date)
                ->setCellValue('J'.$rowCount, $date_paid)
                ->setCellValue('K'.$rowCount, $policy_number)
                ->setCellValue('L'.$rowCount, $deps_count);
            if($deps_count > 0){
                $query = $this->entity_manager->createQueryBuilder();
                $query->select(array('dep'))
                    ->from('Application\Entity\SubscribedPackages', 'dep')
                    // ->where('d.user = ?1')
                    ->where($query->expr()->orX(
                        $query->expr()->eq('dep.user', '?1')
                    ))
                    ->andWhere($query->expr()->orX(
                        $query->expr()->eq('dep.status', '?2'),
                        $query->expr()->eq('dep.isDependent', '?3')
                    ))
                    ->setParameters(array(1=> $user,2 =>true,3 => true));
                $query = $query->getQuery();
                $user_deps = $query->getResult();
                $state = 0;
                if ($user_deps != null) {
                    foreach ($user_deps as $package) {
                        if($package->getStatus()) {
                            if ($package->getIsDependent()) {
                                $dependant = $package->getDependent();
                                // $dependant = new UserDependents();

                                if ($dependant->getGender()) {
                                    $gender = Constants::TRUE_STR;
                                } else {
                                    //female
                                    $gender = Constants::FALSE_STR;
                                }
                                $dep_relation = $dependant->getRelationType();
                                //  $dep_relation = new UserRelationshipTypes();
                                $id_number = $dependant->getIdNumber();
                                if (!isset($id_number)) {
                                    $id_number = '';
                                }
                                $relation = self::ADDITIONAL_DEPENDANT;
                                $rel = $dep_relation->getId();
                                if($rel == Constants::IS_SPOUSE){
                                    $relation = self::SPOUSE;
                                }else if($rel == Constants::IS_BIO_CHILD){
                                    $relation = self::BIOLOGICAL_CHILD;
                                }

                                $dob =  $util->MillisecondsToDateTimeString($dependant->getDateOfBirth(),Constants::SHOW_DATE_OF_BIRTH);
                                $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('M'.$rowCount, $dependant->getFirstName())
                                    ->setCellValue('N'.$rowCount, $dependant->getLastName())
                                    ->setCellValue('O'.$rowCount, $id_number)
                                    ->setCellValue('P'.$rowCount, $gender)
                                    ->setCellValue('Q'.$rowCount, $dob)
                                    ->setCellValue('R'.$rowCount, $relation);
                                $rowCount++;
                            }
                        }
                    }
                }

                // $rowCount++;
            }

//            $objPHPExcel->getActiveSheet()->setCellValue('A8',"Hello\nWorld");
//            $objPHPExcel->getActiveSheet()->getRowDimension(8)->setRowHeight(-1);
//            $objPHPExcel->getActiveSheet()->getStyle('A8')->getAlignment()->setWrapText(true);

            $rowCount++;
        }


        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle(Constants::SHIRI_CLIENTS);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        $file_name = Constants::SFP_ALL_USERS.'_'.$util->returnCurrentTimeString();
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save(Constants::DATA_SAVE_TEMP_FILES .$file_name.'.xls');
        $file = Constants::DATA_SAVE_TEMP_FILES .$file_name.'.xls';
        // echo $file.'users files';
        $attachment1 = new Attachments();
        $attachment1->setBinary(file_get_contents($file))
            ->setFileName($file_name.'.xls');
        $toAddress = new Address();
        $toAddress->setEmailAddress(self::MUMERAALOIS_GMAIL_EMAIL)
            ->setName(self::ALOIS_MUMERA);

        $fromAddress = new Address();
        $fromAddress->setEmailAddress(self::WEBMASTER_MYSHIRI_EMAIL)
            ->setName(self::ALOIS_MUMERA);
        $htmlPart = "<html><body><p>Today's new Business</p></body></html>";
        //  $htmlPart->type = 'text/html';
        $textPart = Constants::NEW_BUSINESS;//"Sorry, I'm going to be late today!";
        // $textPart->type = 'text/plain';

//TODO schedule deleting files after one day
        try {
            $mail = new MsgMail();
            $mail->addTo($toAddress)
                ->setFrom($fromAddress)
                ->setSubject(Constants::SHIRI_NAME.' '.Constants::NEW_BUSINESS.' '. Constants::DAILY_UPDATES)
                ->setText($textPart)
                ->setHtml($htmlPart)
                ->addAttachment($attachment1)
                ->send();
//            $message = new Message();
//            $message->addTo('mumeraalois@gmail.com')
//                ->addFrom('ralph.schindler@zend.com')
//                ->setSubject('Greetings and Salutations!')
//                ->setBody("Sorry, I'm going to be late today!");
//
//            $transport = new SendmailTransport();
//            $transport->send($message);
        }catch (\Exception $ex){
            Constants::xmlError($ex);
        }

        return $file.'<br/>';
    }

    public function MySQLUserPaymentsDatatoExcelFile()
    {
       // die('Wabatwa!!');
        $this->setEntityManager();
        $util = new Utils();

        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getProperties()->setCreator(Constants::DEVELOPER_NAME)
            ->setLastModifiedBy(Constants::DEVELOPER_NAME)
            ->setTitle("Shiri Payments Document")
            ->setSubject("All Payments")
            ->setDescription("Shiri Funeral Plan")
            ->setKeywords("office PHPExcel php")
            ->setCategory(Constants::SHIRI_CLIENTS);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', Constants::MEMBER_FIRSTNAME)
            ->setCellValue('B1', Constants::MEMBER_SURNAME)
            ->setCellValue('C1', Constants::POLICY_NUMBER)
            ->setCellValue('D1', Constants::MOBILE)
            ->setCellValue('E1', Constants::AMOUNT_PAID)
            ->setCellValue('F1', Constants::EXCESS_AMOUNT)
            ->setCellValue('G1', Constants::PAID_AT)
            ->setCellValue('H1', Constants::MONTHLY_PREMIUM )
            ->setCellValue('I1', Constants::PAYMENT_TYPE );

//        $user_payments = $this->entity_manager->getRepository(Constants::ENTITY_USER_PAYMENTS)->findAll();
//        if($user_payments != null) {
//
//        }
        $rowCount = Constants::START_ROW_COUNT;
        $user_payments = $this->entity_manager->getRepository(Constants::ENTITY_FROM_NETTCASH_SERVER)->findAll();
        if ($user_payments != null) {


            foreach ($user_payments as $net) {
                // $net = new FromNettcashServer();
                $user = $net->getUser();
                $paidAt_date = $util->MillisecondsToDateTimeString($net->getDatePaid() * 1000, 0);
                // $user = new Users();
                //  $subscribed_packages = $this->entity_manager->getRepository(Constants::ENTITY_SUBSCRIBED_PACKAGES)->findBy(array('user' => $user, 'status' => true), array('id' => 'ASC'));
                $ArrResult = $this->returnPremiumAmount($user);
                $premium_amount = $ArrResult['amount'];

                $policy_number = $util->generatePolicyNumber($user->getUserId());
                $inadvance = '0.00';//$net->getExcessAmount();
                $premium_amount = number_format($premium_amount,Constants::TWO_DECIMAL_PLACE);
//                $count += 1;
//                $message .= "\r\n" . $count . self::USER_NAME . $user->getFirstName()
//                    . " " . $user->getLastName() . self::AMOUNT_PAID . $net->getAmountPaid() . self::PHONE_NUMBER . $user->getPhoneNumber() . "\r\n";
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$rowCount, $user->getFirstName())
                    ->setCellValue('B'.$rowCount, $user->getLastName())
                    ->setCellValue('C'.$rowCount, $policy_number)
                    ->setCellValue('D'.$rowCount, $user->getPhoneNumber())
                    ->setCellValue('E'.$rowCount, $net->getAmountPaid())
                    ->setCellValue('F'.$rowCount,$inadvance )
                    ->setCellValue('G'.$rowCount, $paidAt_date )
                    ->setCellValue('H'.$rowCount, $premium_amount)
                    ->setCellValue('I'.$rowCount, Constants::NETTCASH_STR);
                $rowCount++;
            }

        }
        $user_payments = $this->entity_manager->getRepository(Constants::ENTITY_ECOCASH)->findAll();
        if ($user_payments != null) {
            $count = 0;

            foreach ($user_payments as $eco) {
                //   $eco = new EcocashPayments();
                $user = $eco->getUser();
                // $user = new Users();
                $paidAt_date = $eco->getDatePaid();
                $paidAt_date = $paidAt_date->format('Y-m-d H:m:s');
                $ArrResult = $this->returnPremiumAmount($user);
                $premium_amount = $ArrResult['amount'];

//                    $count += 1;
//                    $message .= "\r\n" . $count . self::USER_NAME . $user->getFirstName()
//                        . " " . $user->getLastName() . self::AMOUNT_PAID . $net->getAmountPaid() . self::PHONE_NUMBER . $user->getPhoneNumber() . "\r\n";
                $policy_number = $util->generatePolicyNumber($user->getUserId());
                $inadvance = number_format($eco->getExcessAmount(),Constants::TWO_DECIMAL_PLACE);
                $premium_amount = number_format($premium_amount,Constants::TWO_DECIMAL_PLACE);
//                $count += 1;
//                $message .= "\r\n" . $count . self::USER_NAME . $user->getFirstName()
//                    . " " . $user->getLastName() . self::AMOUNT_PAID . $net->getAmountPaid() . self::PHONE_NUMBER . $user->getPhoneNumber() . "\r\n";
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A'.$rowCount, $user->getFirstName())
                    ->setCellValue('B'.$rowCount, $user->getLastName())
                    ->setCellValue('C'.$rowCount, $policy_number)
                    ->setCellValue('D'.$rowCount, $user->getPhoneNumber())
                    ->setCellValue('E'.$rowCount, $eco->getAmountPaid())
                    ->setCellValue('F'.$rowCount,$inadvance)
                    ->setCellValue('G'.$rowCount, $paidAt_date)
                    ->setCellValue('H'.$rowCount, $premium_amount)
                    ->setCellValue('I'.$rowCount, Constants::ECOCASH_STR);
                $rowCount++;
            }
        }

        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle(Constants::SHIRI_PAYMENTS);
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        $file_name = Constants::SFP_ALL_PAYMENT.'_'.$util->returnCurrentTimeString();//.date('m-d-Y-H-m-s');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save(Constants::DATA_SAVE_TEMP_FILES .$file_name.'.xls');
        $file = Constants::DATA_SAVE_TEMP_FILES.$file_name.'.xls';
        // echo $file.'payments file';
        $attachment1 = new Attachments();
        $attachment1->setBinary(file_get_contents($file))
            ->setFileName($file_name.'.xls');
        $toAddress = new Address();
        $toAddress->setEmailAddress(self::MUMERAALOIS_GMAIL_EMAIL)
            ->setName(self::ALOIS_MUMERA);

        $fromAddress = new Address();
        $fromAddress->setEmailAddress(self::WEBMASTER_MYSHIRI_EMAIL)
            ->setName(self::ALOIS_MUMERA);
        $htmlPart = "<html><body><p>Today's Payments</p></body></html>";
        //  $htmlPart->type = 'text/html';
        $textPart = Constants::TODAY_S_PAYMENTS;//"Sorry, I'm going to be late today!";
        // $textPart->type = 'text/plain';


        try {
            $mail = new MsgMail();
            $mail->addTo($toAddress)
                ->setFrom($fromAddress)
                ->setSubject(Constants::SHIRI_NAME.' '.Constants::TODAY_S_PAYMENTS.' '. Constants::DAILY_UPDATES)
                ->setText($textPart)
                ->setHtml($htmlPart)
                ->addAttachment($attachment1)
                ->send();
//            $message = new Message();
//            $message->addTo('mumeraalois@gmail.com')
//                ->addFrom('ralph.schindler@zend.com')
//                ->setSubject('Greetings and Salutations!')
//                ->setBody("Sorry, I'm going to be late today!");
//
//            $transport = new SendmailTransport();
//            $transport->send($message);
        }catch (\Exception $ex){
            Constants::xmlError($ex);
        }

        return $file.'<br/>';
    }

    /**
     * @param $user
     * @return string
     */
    public function returnPremiumAmount($user)
    {
        $subscribed_packages = $this->entity_manager->getRepository(Constants::ENTITY_SUBSCRIBED_PACKAGES)->findBy(array('user' => $user, 'status' => true), array('id' => 'ASC'));
        $premium_amount = '0.00';
        $state = 1;
        if($subscribed_packages != null){
            foreach ($subscribed_packages as $package) {
                //  $package = new SubscribedPackages();
                $timestamp = $package->getDateActivated();
                $pack = $package->getPackagePlan();
                // $pack = new PackagePlans();
                if ($pack->getId() == Constants::IMM_FAMILY) {
                    continue;
                }
                $bandc_Id = Constants::BUS_AND_CASH_ADITIONAL_BEN;
//                    $bc_plan_name = $this->entity_manager->getRepository(Constants::ENTITY_PACKAGE_PLANS)->findOneById($bandc_Id);
//                    if ($bc_plan_name == null) {
//                        $premium_amount = '0.00';
//                        continue;//return $this->return_results(Constants::PLAN_NOT_SET);
//                    }
//                    //  $money = new PackagePlansFigures();
//                    $bc_plan_amount = $this->entity_manager->getRepository(Constants::ENTITY_PACKAGEPLANFIGURES)->findOneByPackagePlan($bc_plan_name);
//                    //  return $this->return_response('test');
//                    if ($bc_plan_amount == null) {
//                        //plan figures not set
//                        $premium_amount = '0.00';
//                        continue;//return $this->return_results(Constants::ON_FAILURE_CONST);
//                    }
//
//                    $user_package_amount = $this->entity_manager->getRepository(Constants::ENTITY_PACKAGEPLANFIGURES)->findOneByPackagePlan($pack);
//                    //$pack = new PackagePlans();
                if ($bandc_Id == $pack->getId()) {
                    //  $user_package_amount = $bc_plan_amount;
                    $state = Constants::IS_B_AND_C;
                }
                //  $user_package_amount->getAmount();

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

                if ($data_result != null && is_array($data_result)) {
                    // $user_payments = new PackagePlansFigures();
                    foreach ($data_result as $figure) {
                        $premium_amount += $figure->getAmount();
                    }

                }


            }
        }
        return array('state'=>$state, 'amount'=>$premium_amount);
    }


    public function sendSMSNotice(){
        $this->setEntityManager();
        $db = new DBUtils($this->service_locator);
        $util = new Utils();
        $new_payments = array(
            "+263772432529",
            "+263772453689",
            "+263773373536",
            "+263773444975",
            "+263779578418",
            "+263772581472",
            "+263777783088"
        );
       // $phoneNumber = '+263774381141';
        $amount_pd = '12.50';
       die($amount_pd);
        foreach($new_payments as $phoneNumber){
            $user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($phoneNumber);

            if($user != null) {
                $gcm_reg_id = $user->getGcmRegid();
                //=========== SENDIND SMS ONLY OPTION ==================
                echo('==========> Sending Message ' . '<br/>');
                $message = 'We highly apologise for the wrong month paid for notification, the correct month Paid for is Nov-2015';
                echo '' . '<br/>' . 'Sending Message ==>' . $message . '<br/><br/>' . 'To ==>: ' . $phoneNumber . '<br/>';

                sleep(2);
                $res = $db->save_individual_client_messages(Constants::SHIRI_NAME,
                    $message, $phoneNumber);


                if ($gcm_reg_id === Constants::GCM_REG_ID_DEFAULT) {
                    echo('SMS =====>' .$message .'<br/>');

                    $infobipSMSMessaging = new infobipSMSMessaging();
                    $result = $infobipSMSMessaging->sendmsg($phoneNumber,
                        Constants::SHIRI_NAME, $message);

                } else {
                    echo('notif =====>' . $message. '<br/>');
                    $util->notifyPayments($gcm_reg_id,
                        $message, $amount_pd);
                }


                echo('==========> Message sent to ==> ' . $phoneNumber . '<br/>');
               // return ($result);
            }else{
                echo 'NO USER AVAILABLE ==>'.$phoneNumber;
            }

        }

//        $phoneNumber = '+263773232761';
//        die($phoneNumber);
//        $user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($phoneNumber);
//        $amount_paid = '40';
//        $amount_pd = number_format($amount_paid, 2);
//
//        $monthPaidForDt = 'Nov-2015';
//        if ($user != null) {
//            $gcm_reg_id = $user->getGcmRegid();
//            $res = $db->save_individual_client_messages(Constants::SHIRI_FUNERAL_PLAN_PAYMENT,
//                Constants::YOU_HAVE_PAID . $amount_pd . ' ' . Constants::FOR_STR . $monthPaidForDt . ' ' . Constants::FOR_YOUR_POLICY_PREMIUM
//                , $phoneNumber);
//
//            if ($gcm_reg_id === Constants::GCM_REG_ID_DEFAULT) {
//                echo('SMS =====>' . Constants::YOU_HAVE_PAID . $amount_pd . ' ' . Constants::FOR_STR . $monthPaidForDt . ' ' . Constants::FOR_YOUR_POLICY_PREMIUM . ' ' . $phoneNumber . '<br/>');
//
//                $infobipSMSMessaging = new infobipSMSMessaging();
//
//                $result = $infobipSMSMessaging->sendmsg($phoneNumber,
//                    Constants::SHIRI_NAME, Constants::YOU_HAVE_PAID . $amount_pd . ' ' . Constants::FOR_STR . $monthPaidForDt . ' ' . Constants::FOR_YOUR_POLICY_PREMIUM);
//
//            } else {
//                echo('notif =====>' . Constants::YOU_HAVE_PAID . $amount_pd . ' ' . Constants::FOR_STR . $monthPaidForDt . ' ' . Constants::FOR_YOUR_POLICY_PREMIUM . ' ' . $phoneNumber . '<br/>');
//                $util->notifyPayments($gcm_reg_id,
//                    Constants::YOU_HAVE_PAID . $amount_pd . ' ' . Constants::FOR_STR . $monthPaidForDt . ' ' . Constants::FOR_YOUR_POLICY_PREMIUM,
//                    $amount_pd);
//            }
//            echo('==========> Message sent to ==> '.$phoneNumber.'<br/>');
//            return('==> Done <==');
//        }else{
//            echo('==========> USER does not EXIST ==> '.$phoneNumber.'<br/>');
//        }

//        $user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($phoneNumber);
//       if($user != null) {
//           //=========== SENDIND SMS ONLY OPTION ==================
//           echo('==========> Sending Message ' . '<br/>');
//           $message = 'Your Shiri account pincode is : 0000';
//           //$message = '+263773232761 have referred you to Shiri, Account pincode : 0000';
//           echo '' . '<br/>' . 'Sending Message ==>' . $message . '<br/><br/>' . 'To ==>: ' . $phoneNumber . '<br/>';
//
//           $infobipSMSMessaging = new infobipSMSMessaging();
//           $res = $db->save_individual_client_messages(Constants::SHIRI_NAME,
//               $message, $phoneNumber);
//
//           $result = $infobipSMSMessaging->sendmsg($phoneNumber,
//               Constants::SHIRI_NAME, $message);
//           echo('==========> Message sent to ==> ' . $phoneNumber . '<br/>');
//           return ($result);
//       }else{
//           echo 'NO USER AVAILABLE ==>'.$phoneNumber;
//       }
    }

    public function monthlyPayments(){
    $this->setEntityManager();
    $util = new Utils();
    $month_paid_for = '2015-10-31 00:00:00';
    $plan_id = 1;
    $sql = "SELECT p.* FROM user_payments  p INNER JOIN subscribed_packages sub ON p.subscribed_package_id = sub.id
INNER JOIN package_plans pk ON pk.id = sub.package_plan_id WHERE p.month_paid_for = '".$month_paid_for."' AND pk.id = ".$plan_id." ";
   // $sql = "SELECT * FROM user_payments LIMIT 10";
    $stmt = $this->getEntityManager()->getConnection()->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    die($month_paid_for);
    if($result != null){
        $count = 0;
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getProperties()->setCreator(Constants::DEVELOPER_NAME)
            ->setLastModifiedBy(Constants::DEVELOPER_NAME)
            ->setTitle("Shiri Payments Document")
            ->setSubject("All Payments")
            ->setDescription("Shiri Funeral Plan")
            ->setKeywords("office PHPExcel php")
            ->setCategory(Constants::SHIRI_CLIENTS);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', Constants::MEMBER_FIRSTNAME)
            ->setCellValue('B1', Constants::MEMBER_SURNAME)
            ->setCellValue('C1', Constants::POLICY_NUMBER)
            ->setCellValue('D1', Constants::MOBILE)
            ->setCellValue('E1', Constants::AMOUNT_PAID)
            ->setCellValue('F1', Constants::EXCESS_AMOUNT)
            ->setCellValue('G1', Constants::MONTH_PAID_FOR)
            ->setCellValue('H1', Constants::PAID_AT)
            ->setCellValue('I1', Constants::MONTHLY_PREMIUM )
            ->setCellValue('J1', Constants::PAYMENT_TYPE );
        $rowCount = Constants::START_ROW_COUNT;
        foreach($result as $payment){
            $count += 1;
            $month_paid_for = $payment['month_paid_for'];
            $external_ref = $payment['external_ref'];
            $payee = $payment['payee'];
            $paidAt_date = $payment['date_paid'];
            $subscribed_package_id = $payment['subscribed_package_id'];
            $payment_type = $payment['payment_type'];
            $send_state = $payment['send_state'];

            if($subscribed_package_id == 847 || $subscribed_package_id == 886
                || $subscribed_package_id == 122 || $subscribed_package_id == 16){
              continue;
            }
            $package = $this->entity_manager->getRepository(Constants::ENTITY_SUBSCRIBED_PACKAGES)->findOneById($subscribed_package_id);
            if ($package != null) {
                //$package = new SubscribedPackages();
                $user = $package->getUser();
                $ArrResult = $this->returnPremiumAmount($user);
                $premium_amount = $ArrResult['amount'];

            }

            if($payment_type == Constants::ECOCASH){
                $_payment = $this->entity_manager->getRepository(Constants::ENTITY_ECOCASH)->findOneByReferenceId($external_ref);

            }else{
                    $_payment = $this->entity_manager->getRepository(Constants::ENTITY_NETTCASH_PAYMENTS)->findOneByTransactionId($external_ref);

            }

            if($_payment != null){
                $inadvance = $_payment->getExcessAmount();
                $amount_paid = $_payment->getAmountPaid();
           }else{
                $inadvance = 0;
                 $amount_paid = 0;
            }

            $month_paid_for = new \DateTime($month_paid_for);
            $month_paid_for =  $util->MillisecondsToDateTimeString($month_paid_for->getTimestamp()*Constants::TO_MILLISECONDS,Constants::SHOW_DATE_OF_BIRTH);
            $policy_number = $util->generatePolicyNumber($user->getUserId());
            $inadvance = number_format($inadvance,Constants::TWO_DECIMAL_PLACE);
            $premium_amount = number_format($premium_amount,Constants::TWO_DECIMAL_PLACE);
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$rowCount, $user->getFirstName())
                ->setCellValue('B'.$rowCount, $user->getLastName())
                ->setCellValue('C'.$rowCount, $policy_number)
                ->setCellValue('D'.$rowCount, $user->getPhoneNumber())
                ->setCellValue('E'.$rowCount, $amount_paid)
                ->setCellValue('F'.$rowCount,$inadvance)
                ->setCellValue('G'.$rowCount, $month_paid_for)
                ->setCellValue('H'.$rowCount, $paidAt_date)
                ->setCellValue('I'.$rowCount, $premium_amount)
                ->setCellValue('J'.$rowCount, Constants::ECOCASH_STR);
            $rowCount++;
           // echo $count.' ==> PAYMENTS'.'<br/>'.$month_paid_for.' '.$external_ref;
        }
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Shiri October Payemnts');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);

        $file_name = 'SHIRI OCTOBER PAYMENTS'.'_'.$util->returnCurrentTimeString();//.date('m-d-Y-H-m-s');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save(Constants::DATA_SAVE_TEMP_FILES .$file_name.'.xls');
        $file = Constants::DATA_SAVE_TEMP_FILES.$file_name.'.xls';
         return $file;
    }else{
        echo ' ==> NO DATA'.'<br/>';
    }
 return '====> done <====';

}

}