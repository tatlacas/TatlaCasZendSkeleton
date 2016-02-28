<?php
/**
 * Created by PhpStorm.
 * User: alois - mumeraalois@gmail.com
 * Date: 9/13/2015
 * Time: 10:32 PM
 */

namespace Mobile\Model;


use Application\Entity\PackagePlansFigures;
use Application\Entity\SubscribedPackages;
use Application\Entity\UserActivitiesData;
use Application\Entity\UserPolicies;
use Application\Entity\Users;
use Application\Model\Constants;
use Application\Model\DoctrineInitialization;
use Application\Model\PasswordCompatibilityLibrary;

class UserActivityData extends DoctrineInitialization
{

    function __construct($service_locator)
    {
        parent::__construct($service_locator);
    }

    public function insertData($json)
    {

        $this->setEntityManager();
        $db = new DBUtils($this->service_locator);
        if ($json != NULL) {
            $request = json_decode($json, true);
        date_default_timezone_set("UTC");
        $now = new \DateTime();
        $timestamp = $now->getTimestamp();
            $update_state = $request[Constants::UPDATE_STATE];

            $phone_number = $request[Constants::PHONE_NUMBER];
            $location = $request[Constants::LOCATION];
            $gcmid = $request[Constants::GCM_ID];
            $pincode = $request[Constants::PINCODE];
            $b_and_cbenefits = $request[Constants::BENEFIT_STATE];

        if (strlen($phone_number) <= Constants::PHONE_NUMBER_LENGTH) {
            $phone_number = htmlspecialchars($phone_number);
            $user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($phone_number);// "SELECT * FROM users WHERE phone_number = '".$phone_number."'";
            if ($user == null) {
                //no user
                return $this->return_results(Constants::ON_FAILURE_CONST);
            }
          // $t = new Users();
          //  $t->setPincode($phone_number);
            $sql = "";
            if($update_state === Constants::BUNDLE_SEVEN){

                $changed_phone_number = $request[Constants::CHANGED_PHONE_NUMBER];
                $user->setPhoneNumber($changed_phone_number);
                $this->entity_manager->persist($user);

            }else if($update_state === Constants::BUNDLE_ONE){
                list($part1, $part2) = explode(':', $location);
                $branch_res = $this->entity_manager->getRepository(Constants::ENTITY_BRANCHES)->findOneByBranchId((int)$part2);
                if ($branch_res == null) {
                    // $Branches->setBranchName($branch_res -> getBranchName());
                    return $this->return_results(Constants::BRANCH_NOT_SET);
                }

                $user->setBranch($branch_res);
                $this->entity_manager->persist($user);
               // $sql = "UPDATE users SET nearest_branch = '$location' WHERE phone_number = '".$phone_number."'";

            }else if($update_state === Constants::BUNDLE_TWO){
               // $sql = "UPDATE users SET gcm_regid = '$gcmid' WHERE phone_number = '".$phone_number."'";
                $user->setGcmRegid($gcmid);
                $this->entity_manager->persist($user);

            }else if($update_state === Constants::BUNDLE_THREE){
                //pincode reserting
                if (strlen($pincode) !== Constants::PINCODE_MIN_LENGTH) {
                    return $this->return_results(Constants::ON_FAILURE_CONST);
                }
                if (version_compare(PHP_VERSION, '5.5.0', '<')) {

                    $passwrd = new PasswordCompatibilityLibrary(Constants::PASSWORD_DEFAULT);
                    $user_pincode_hash = $passwrd->password_hash($pincode, Constants::PASSWORD_DEFAULT);
                    ///  die($user_pincode_hash);
                } else
                    $user_pincode_hash = password_hash($pincode, Constants::PASSWORD_DEFAULT);

                //  $sql = "UPDATE users SET pincode = '$user_pincode_hash' WHERE phone_number = '".$phone_number."'";
                $user->setPincode($user_pincode_hash);
                $this->entity_manager->persist($user);

            }else if($update_state === Constants::BUNDLE_FOUR){
                  //BANDC
//                $userPol = $this->entity_manager->getRepository(Constants::ENTITY_USER_POLICIES)->findOneByUser($user);
//                if ($userPol == null) {
//
//                    return $this->return_results(Constants::POLICY_NOT_SET);
//                }
                if($b_and_cbenefits === Constants::BUNDLE_TWO) {
                    $value = Constants::BUS_AND_CASH_ADITIONAL_BEN;
                }else{
                    $value = Constants::PREMIUM_POLICY;
                }
                $plan_name_res = $this->entity_manager->getRepository(Constants::ENTITY_PACKAGE_PLANS)->findOneById($value);
                if ($plan_name_res == null) {
                    return $this->return_results(Constants::PLAN_NOT_SET);
                }
                //$money = new PackagePlansFigures();

                $plan_figures = $this->entity_manager->getRepository(Constants::ENTITY_PACKAGEPLANFIGURES)->findOneByPackagePlan($plan_name_res);
                //  return $this->return_response('test');
                if ($plan_figures == null) {
                    //plan figures not set
                    return $this->return_results(Constants::ON_FAILURE_CONST);
                }

                if($b_and_cbenefits === Constants::BUNDLE_TWO){
                    $subscribed_packages = $this->entity_manager->getRepository(Constants::ENTITY_SUBSCRIBED_PACKAGES)->findOneBy(array('user' => $user, 'packagePlan' => $plan_name_res));
                    if($subscribed_packages != null){
                      //  $subscribed_packages = new SubscribedPackages();
                        $subscribed_packages->setStatus(true);
                        $timestamp = $subscribed_packages->getDateActivated();
                        $timestamp = $timestamp->getTimestamp()*1000;
                        $subscribed_packages->setDateDeactivated(null);

                        $xml_output = "<?xml version=\"1.0\"?>\n";
                        $xml_output .= "<entries>\n";
                        $xml_output .= "\t<entry>\n";
                        $xml_output .= "\t\t<result>" . Constants::ON_SUCCESS_CONST . "</result>\n";
                        $xml_output .= "\t\t<createdAt>" .$timestamp . "</createdAt>\n";
                        $xml_output .= "\t\t<amount>" . $plan_figures->getAmount() . "</amount>\n";
                        $xml_output .= "\t</entry>\n";
                        $xml_output .= "</entries>";
                        $this->entity_manager->flush();
                        return $xml_output;
                    }else{
                        $value = Constants::BUS_AND_CASH_ADITIONAL_BEN;
                        $res = $db->save_individual_client_messages(Constants::ACCOUNT_INFORMATION_UPDATES,
                            Constants::YOU_HAVE_ACTIVATED_BUS_AND_CASH_BENEFIT,$phone_number );

                        //  $userPol = new UserPolicies();
                        //  $userPol->setPackagePlan($plan_name_res);
                        //  $this->entity_manager->persist($userPol);
                        // $this->entity_manager->flush();
                        $package = new SubscribedPackages();
                        date_default_timezone_set('UTC');
                        $date = new \DateTime("now");

                        $package->setDateActivated($date);
                        $package->setUser($user);
                        $package->setPackagePlan($plan_name_res);
                        $package->setStatus(true);
                        $package->setIsDependent(false);
                        // $package->setDependent($dependant);
                        $this->entity_manager->persist($package);
                        $this->entity_manager->flush();

                        $timestamp = $date->getTimestamp()*1000;

                        $xml_output = "<?xml version=\"1.0\"?>\n";
                        $xml_output .= "<entries>\n";
                        $xml_output .= "\t<entry>\n";
                        $xml_output .= "\t\t<result>" . Constants::ON_SUCCESS_CONST . "</result>\n";
                        $xml_output .= "\t\t<createdAt>" .$timestamp . "</createdAt>\n";
                        $xml_output .= "\t\t<amount>" . $plan_figures->getAmount() . "</amount>\n";
                        $xml_output .= "\t</entry>\n";
                        $xml_output .= "</entries>";

                        return $xml_output;
                    }

                }else {
                    $plan_name_res = $this->entity_manager->getRepository(Constants::ENTITY_PACKAGE_PLANS)->findOneById(Constants::BUS_AND_CASH_ADITIONAL_BEN);
                    if ($plan_name_res == null) {
                        return $this->return_results(Constants::PLAN_NOT_SET);
                    }
                    $value = Constants::PREMIUM_POLICY;
                    $res = $db->save_individual_client_messages(Constants::ACCOUNT_INFORMATION_UPDATES,
                        Constants::YOU_HAVE_REMOVED_BUS_AND_CASH_BENEFIT, $phone_number);
                    $subscribed_packages = $this->entity_manager->getRepository(Constants::ENTITY_SUBSCRIBED_PACKAGES)->findOneBy(array('user' => $user, 'packagePlan' => $plan_name_res));
if($subscribed_packages != null){
    $query = $this->entity_manager->createQueryBuilder();
                    date_default_timezone_set('UTC');
                    $date = new \DateTime("now");
                    $result = $date->format('Y-m-d 00:00:00');
                    $date = new \DateTime($result);
                    // $subscribed_packages = new SubscribedPackages();
                    $subscribed_packages->setDateDeactivated($date);
                    $subscribed_packages->setStatus(false);

                    $this->entity_manager->flush();
                    return $this->return_results(Constants::ON_SUCCESS_CONST);
                }else {
    return $this->return_results(Constants::ON_FAILURE_CONST);
}

                }

              //  return $this->return_results(Constants::ON_SUCCESS_CONST);

            }else if($update_state === Constants::BUNDLE_FIVE){
               //user activities
                $message = $request[Constants::MESSAGE];
             //  $phone_number = $request['userMsgid'];
                $title = $request[Constants::TITLE];
                $dtimetamp = $request[Constants::DATE_TIME];
                $activityData = new UserActivitiesData();
                $activityData->setTitle($title)
               ->setUser($user)
                ->setMessage($message)
                ->setDateTime($dtimetamp)
                ->setMsgId(Constants::USER_MSG);
                $this->entity_manager->persist($activityData);

//                $sql="INSERT INTO user_activities_data (message,title ,phone_number, date_time)
//VALUES ('$message','$title' ,'$phone_number', $timestamp)";

            }else if($update_state === Constants::BUNDLE_SIX){
              //  $user_pincode_hash = password_hash($pincode, PASSWORD_DEFAULT);
              //  $sql = "UPDATE users SET pincode = '$user_pincode_hash' WHERE phone_number = '".$phone_number."'";
                if (version_compare(PHP_VERSION, '5.5.0', '<')) {

                    $passwrd = new PasswordCompatibilityLibrary(Constants::PASSWORD_DEFAULT);
                    $user_pincode_hash = $passwrd->password_hash($pincode, Constants::PASSWORD_DEFAULT);
                    ///  die($user_pincode_hash);
                } else
                    $user_pincode_hash = password_hash($pincode, Constants::PASSWORD_DEFAULT);

                //  $sql = "UPDATE users SET pincode = '$user_pincode_hash' WHERE phone_number = '".$phone_number."'";
                $user->setPincode($user_pincode_hash);
                $this->entity_manager->persist($user);

            }else{
                return $this->return_results(Constants::ON_FAILURE_CONST);
            }

            $this->entity_manager->flush();
        //    $result = mysql_query($sql)  or die(mysql_error());
          //  if($result){
          //      return $this->return_results("1");
//            }else{
//                return_results("failed");
//            }





            return $this->return_results(Constants::ON_SUCCESS_CONST);
        } else {
            return $this->return_results(Constants::ON_FAILURE_CONST);
        }
    }

    }

    public function returnMessages($phone_number){
        if ($phone_number) {
            if (strlen($phone_number) <= Constants::PHONE_NUMBER_LENGTH) {
                $phone_number = htmlspecialchars($phone_number);
                $this->setEntityManager();

                $user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($phone_number);// "SELECT * FROM users WHERE phone_number = '".$phone_number."'";

                if ($user == null) {
                   //no user
                    return $this->return_results(Constants::ON_FAILURE_CONST);
                }
              //  $data = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($phone_number);
                $query = $this->entity_manager->createQueryBuilder();
                $query->select(array('d'))
                    ->from('Application\Entity\UserActivitiesData', 'd')
                    // ->where('d.user_id = ?1')
                    ->where($query->expr()->orX(
                        $query->expr()->eq('d.user', '?1')
                    ))
                    ->setParameter(1, $user);
                $query = $query->getQuery();
                $data_result = $query->getResult();
                if ($data_result == null) {
                    return $this->return_results(Constants::ON_FAILURE_CONST);
                }

                $xml_output = "<?xml version=\"1.0\"?>\n";
                $xml_output .= "<entries>\n";
                foreach ($data_result as $user_activity) {
                   // $user_activity = new UserActivitiesData();
                    $xml_output .= "\t<entry>\n";
                    $xml_output .= "\t\t<title>" . $user_activity->getTitle(). "</title>\n";
                    $xml_output .= "\t\t<msgId>" . $user_activity->getMsgId() . "</msgId>\n";
                    $xml_output .= "\t\t<message>" . $user_activity->getMessage() . "</message>\n";
                    $xml_output .= "\t\t<dateTime>" .  $user_activity->getDateTime() . "</dateTime>\n";
                    $xml_output .= "\t\t<result>" .  Constants::ON_SUCCESS_CONST. "</result>\n";
                    $xml_output .= "\t</entry>\n";
                }
                $xml_output .= "</entries>";
                return $xml_output;

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
        return $xml_output;
    }

}