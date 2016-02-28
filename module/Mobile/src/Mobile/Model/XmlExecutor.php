<?php
/**
 * Created by PhpStorm.
 * User: tatenda
 * Date: 12/9/15
 * Time: 4:36 PM
 */

namespace Mobile\Model;


use Application\Entity\Branches;
use Application\Entity\UserRelationshipTypes;
use Application\Model\Constants;
use Application\Model\DoctrineInitialization;

class XmlExecutor extends DoctrineInitialization
{
    private $request_id;
    private $field_a;
    private $field_b;
    private $field_c;
    private $field_d;
    private $app_version;

    /**
     * @return mixed
     */
    public function getAppVersion()
    {
        return $this->app_version;
    }

    /**
     * @param mixed $app_version
     */
    public function setAppVersion($app_version)
    {
        $this->app_version = $app_version;
    }

    /**
     * @return mixed
     */
    public function getFieldA()
    {
        return $this->field_a;
    }

    /**
     * @param mixed $field_a
     */
    public function setFieldA($field_a)
    {
        $this->field_a = $field_a;
    }

    /**
     * @return mixed
     */
    public function getFieldB()
    {
        return $this->field_b;
    }

    /**
     * @param mixed $field_b
     */
    public function setFieldB($field_b)
    {
        $this->field_b = $field_b;
    }

    /**
     * @return mixed
     */
    public function getFieldC()
    {
        return $this->field_c;
    }

    /**
     * @param mixed $field_c
     */
    public function setFieldC($field_c)
    {
        $this->field_c = $field_c;
    }

    /**
     * @return mixed
     */
    public function getFieldD()
    {
        return $this->field_d;
    }

    /**
     * @param mixed $field_d
     */
    public function setFieldD($field_d)
    {
        $this->field_d = $field_d;
    }
    private $encoded_json;

    /**
     * @return mixed
     */
    public function getEncodedJson()
    {
        return $this->encoded_json;
    }

    /**
     * @param mixed $encoded_json
     */
    public function setEncodedJson($encoded_json)
    {
        $this->encoded_json = $encoded_json;
    }

    /**
     * @return mixed
     */
    public function getRequestId()
    {
        return $this->request_id;
    }

    /**
     * @param mixed $request_id
     */
    public function setRequestId($request_id)
    {
        $this->request_id = $request_id;
    }

    function __construct($service_locator)
    {
        parent::__construct($service_locator);
    }

    /**
     * @return string
     */
    public function runXmlFileService()
    {
        try {
            $this->decryptJsonData();
        } catch (\Exception $e) {
            return Constants::xmlError("DEC:-(" . $e->getMessage());
        }

        switch ($this->request_id) {
            case Constants::PHP_REGISTER_ACCOUNT_ID:
                return $this->registerUser();
                break;
            case Constants::PHP_CHECK_ACCOUNT_ID:
                return $this->checkAccount();
                break;
            case Constants::PHP_DELETE_DEPENDANT_ID:
                return $this->deleteDependant();
                break;
            case Constants::PHP_VERIFY_REFERER_ID:
                return $this->verifyReferer();
                break;
            case Constants::PHP_RETURN_ALL_USER_NETWORK:
                return $this->userNetWork();
                break;
            case Constants::PHP_RETURN_BRANCHES:
                return $this->returnBranches();
                break;
            case Constants::PHP_RETURN_ALL_DEPENDENTS:
                return $this->returnAllDependants();
                break;
            case Constants::PHP_LOGIN_ID:
                return $this->userLogin();
                break;
            case Constants::PHP_USER_NOTIFICATION:
                return $this->userMessages();
                break;
            case Constants::PHP_USER_INFO_UPDATES:
                return $this->userActivitydata();
                break;
            case Constants::PHP_RETURN_USER_DATA:
                return $this->userAccountInformation();
                break;
            case Constants::PHP_VERIFY_NUMBER:
                return $this->verifyNumber();
                break;
            case Constants::PHP_PROCESS_PINCODE:
                return $this->processPincode();
                break;
            case Constants::PHP_FIRST_ACCOUNT_CHECK:
                return $this->firstAccountCheck();
                break;
            case Constants::PHP_RETURN_USER_MESSAGES:
                return $this->returnActivityData();
                break;
            case Constants::PHP_ADD_DEPENDANT:
                return $this->addDependants();
                break;
            case Constants::PHP_RETURN_RELATIONS:
                return $this->returnRelations();
                break;
            case Constants::PHP_RETURN_USER_PYDATA:
                return $this->userPaymentData();
                break;
            case Constants::PHP_USER_PAYMENT:
                return $this->saveuserPaymentData();
                break;
            case Constants::PHP_RETRIEVE_OWING_BALANCE:
                return $this->retrieveBalance();
                break;
            case Constants::PHP_NETTCASH_PAYMENT:
                return $this->userPaymments();
                break;
            case Constants::PHP_FRIENDS_JOINED:
                return $this->friendsJoined();
                break;
            case Constants::PHP_RETURN_ALL_STATUS:
                return $this->accountStatus();
                break;
            case Constants::PHP_RETURN_MONTHLY_REBATES:
                return $this->returnMonthlyRebates();
                break;;
            case Constants::PHP_INSERT_PENDING_PHP_RECORD:
                return $this->insertPendingRecords();
                break;

            default:
                return Constants::xmlError("NDAKUBATA!!" . $this->request_id);
        }
    }

    private function returnAllDependants()
    {
        $deps = new UserDependants($this->getServiceLocator());
        return $deps->returnAllDepsdata($this->field_a);
    }

    private function returnBranches()
    {
        $value = $this->field_a;
        $value_two = $this->field_b;
        if($value === Constants::BUNDLE_ONE){
            $this->setEntityManager();
            $raw_results = $this->entity_manager->getRepository(Constants::ENTITY_BRANCHES)->findAll();
            $xml_output = "<?xml version=\"1.0\"?>\n";
            $xml_output .= "<entries>\n";
            $xml_output .= "\t\t<result>".Constants::ON_SUCCESS_CONST."</result>\n";
             if($raw_results != null && is_array($raw_results)){
                 foreach($raw_results as $branch){
//                     $t = new Branches();
//                     $t->getBranchName();
                     $xml_output .= '<branchName name = "'.$branch->getBranchName().'" />';

                 }
             }
            if($value_two == Constants::BUNDLE_TWO){
                $plan_name_res = $this->entity_manager->getRepository(Constants::ENTITY_PACKAGE_PLANS)->findOneById(Constants::ADITIONAL_DEP_PACKAGE_ID);
                if ($plan_name_res == null) {
                    return $this->return_response(Constants::PLAN_NOT_SET);
                }

                $plan_figures = $this->entity_manager->getRepository(Constants::ENTITY_PACKAGEPLANFIGURES)->findOneByPackagePlan($plan_name_res);
                //  return $this->return_response('test');
                if ($plan_figures == null) {
                    //plan figures not set
                    return $this->return_response(Constants::ON_FAILURE_CONST);
                }
                $bc_plan = $this->entity_manager->getRepository(Constants::ENTITY_PACKAGE_PLANS)->findOneById(Constants::INT_BUNDLE_TWO);
                if ($bc_plan == null) {
                    return $this->return_response(Constants::PLAN_NOT_SET);
                }

                $bc_plan_amount = $this->entity_manager->getRepository(Constants::ENTITY_PACKAGEPLANFIGURES)->findOneByPackagePlan($bc_plan);
                //  return $this->return_response('test');
                if ($bc_plan_amount == null) {
                    //plan figures not set
                    return $this->return_response(Constants::ON_FAILURE_CONST);
                }

                $xml_output .= "\t\t<amount>" . $plan_figures->getAmount() . "</amount>\n";
                $xml_output .= "\t\t<bcAmount>" . $bc_plan_amount->getAmount() . "</bcAmount>\n";

            }else{

                $xml_output .= "\t\t<bcAmount>" . Constants::BUNDLE_ZERO . "</bcAmount>\n";
                $xml_output .= "\t\t<amount>" . Constants::BUNDLE_ZERO . "</amount>\n";

            }


            $xml_output .= "</entries>";
            return $xml_output;
        }else{
            $this->return_response(Constants::ON_FAILURE_CONST);
        }
    }

    private function registerUser()
    {
        $register = new Registration($this->getServiceLocator());
        return $register->registerUser($this->field_a);
    }

    private function decryptJsonData()
    {
        $json = json_decode($this->encoded_json);
        $this->setRequestId($json->action_id);
        $this->setFieldA($json->field_a);
        $this->setFieldB($json->field_b);
        $this->setFieldC($json->field_c);
        $this->setFieldD($json->field_d);
        try{
            $this->setAppVersion($json->clientVersion==null?0:$json->clientVersion);
        }catch (\Exception $e){
            $this->setAppVersion(0);
        }

    }

    private function verifyReferer()
    {
        $check =  new CheckAccount($this->service_locator);
        return $check->checkAccount($this->field_a);
    }

    private function deleteDependant()
    {
        $deps = new UserDependants($this->getServiceLocator());
        return $deps->delete_dependents($this->field_a,$this->field_b,$this->field_c);

    }

    private function checkAccount()
    {
        $check =  new CheckAccount($this->service_locator);
        return $check->checkAccount($this->field_a);
    }

    private function userAccountInformation()
    {
        $check =  new CheckAccount($this->service_locator);
        return $check->return_all_user_data($this->field_a);
    }

    function return_response($value)
    {
        $xml_output = "<?xml version=\"1.0\"?>\n";
        $xml_output .= "<entries>\n";
        $xml_output .= "\t<entry>\n";
        $xml_output .= "\t\t<result>" . $value . "</result>\n";
        $xml_output .= "\t</entry>\n";
        $xml_output .= "</entries>";
        return ($xml_output);
    }

    private function userNetWork()
    {
        $network = new UserAccountNetwork($this->getServiceLocator());
        return $network->returnUserNetwork($this->field_a,$this->field_b);//returnUserNetwork($this->field_a, $this->field_b);
    }

    private function userLogin()
    {
        $user_login =  new AccountLogin($this->service_locator);
        return $user_login->login($this->field_a,$this->field_b);

    }

    private function userMessages()
    {
        $msg =  new UserNotifications($this->service_locator);
        return $msg->fetchNotification($this->field_a);

    }

    private function userActivitydata()
    {
        $msg =  new UserActivityData($this->service_locator);
        return $msg->insertData($this->field_a);
    }

    private function verifyNumber()
    {

        $verify =  new NumberVerification($this->service_locator);
        return $verify->verifyNumber($this->field_a);
    }

    private function processPincode()
    {
        $check =  new CheckAccount($this->service_locator);
        return $check->process_pincode($this->field_a,$this->field_b,$this->field_c);
    }

    private function firstAccountCheck()
    {
        $check =  new CheckAccount($this->service_locator);
        return $check->processFirstCheck($this->field_a,$this->field_b);
    }

    private function returnActivityData()
    {
        $check =  new UserActivityData($this->service_locator);
        return $check->returnMessages($this->field_a);
    }

    private function addDependants()
    {
        $deps = new UserDependants($this->getServiceLocator());
        return $deps->addDependant($this->field_a);
    }

    private function returnRelations()
    {
        $value = $this->field_a;
        $value_two = $this->field_b;
        if($value === Constants::BUNDLE_ONE){
            $this->setEntityManager();
            $raw_results = $this->entity_manager->getRepository(Constants::ENTITY_USER_RELATION_TYPES)->findAll();
            $xml_output = "<?xml version=\"1.0\"?>\n";
            $xml_output .= "<entries>\n";
            $xml_output .= "\t\t<result>".Constants::ON_SUCCESS_CONST."</result>\n";
            if($raw_results != null && is_array($raw_results)){
               // $t = new UserRelationshipTypes();

                foreach($raw_results as $relation){

                 //   $xml_output .= "\t\t<relation>" . $relation->getDescription() . "</relation>\n";
                    $xml_output .= '<relation name = "'.$relation->getDescription().'" />';
                }
            }

            $xml_output .= "</entries>";
            return $xml_output;
        }else{
            $this->return_response(Constants::ON_FAILURE_CONST);
        }
    }

    private function userPaymentData()
    {
        $paymentdata =  new CheckAccount($this->service_locator);
       // return $paymentdata->returnPaymentData($this->field_a);
        return $paymentdata->getUserAccountBalance($this->field_a,$this->field_b);
    }

    private function saveuserPaymentData()
    {
        $paymentdata =  new ShiriPremiumPayments($this->service_locator);
        return $paymentdata->insertPayment($this->field_a,$this->field_b,$this->field_c);
    }

    public function retrieveBalance()
    {
        $retrive = new BalanceManagement($this->service_locator,$this->field_a,$this->field_b,$this->field_c,$this->field_d);
        return $retrive->getOwningBalance();
    }

    private function userPaymments()
    {
        $paymentdata =  new ShiriUserPayment($this->service_locator);
        return $paymentdata->savePayment($this->field_a);

    }

    private function friendsJoined()
    {
        $userNetwork = new UserAccountNetwork($this->service_locator);
        return $userNetwork->insertAllFriends($this->field_a);
    }
    private function accountStatus()
    {
        $userNetwork = new CheckAccount($this->service_locator);
        return $userNetwork->returnAllStatuses($this->field_a);
    }

   private function returnMonthlyRebates()
    {
        $userNetwork = new UserAccountNetwork($this->service_locator);
        return $userNetwork->getmonthlyRebates($this->field_a, $this->field_b);
    }

    private function insertPendingRecords()
    {
    $save_record = new UploadPendingPaymentToServer($this->service_locator);
        return $save_record->uploadPendingPayment($this->field_a,$this->app_version);
    }


}