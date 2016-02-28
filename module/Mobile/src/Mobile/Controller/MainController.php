<?php

namespace Mobile\Controller;

use Application\MailMsg\Address;
use Application\MailMsg\Attachments;
use Application\MailMsg\MsgMail;
use Application\Model\Constants;
use Application\Model\infobipSMSMessaging;
use Mobile\Model\AdminUpdates;
use Mobile\Model\BalanceManagement;
use Mobile\Model\CheckAccount;
use Mobile\Model\CronJob;
use Mobile\Model\CronJobs;
use Mobile\Model\DBUtils;
use Mobile\Model\NettCashUserRegistration;
use Mobile\Model\NettCashUserValidation;
use Mobile\Model\NumberVerification;
use Mobile\Model\Registration;
use Mobile\Model\ShiriUserPayment;
use Mobile\Model\TestRegistration;
use Mobile\Model\UserAccountNetwork;
use Mobile\Model\UserDependants;
use Mobile\Model\Utils;
use Mobile\Model\XmlExecutor;
use Zend\Mail\Message;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
Use Zend\Mime;
use Zend\Mail\Transport\Sendmail as SendmailTransport;

class MainController extends AbstractActionController
{

    public function indexAction()
    {
        return new ViewModel();
    }

    public function nettcashRegistrationAction()
    {
//        header('Content-type: text/xml');
        $json = file_get_contents('php://input');
        $request = json_decode($json, true);
        $register = new NettCashUserRegistration($this->getServiceLocator());
        $result = $register->nettcashRegistration($request);
        die($result);

    }
    public function adUpdatesAction()
    {
        $url =$this->params()->fromRoute('id1',null);
        $update = new AdminUpdates($this->getServiceLocator());
        $result = $update->adminUpdates($url);
        die($result);
    }
    public function testRegisterAction(){
//        $json = file_get_contents('php://input');
//        $request = json_decode($json, true);
        $register = new TestRegistration($this->getServiceLocator());
        $result = $register->testregisterUser();
        die($result);

    }
    public function tatlaAction(){

    $date_t = 1442829600000;
    $date_t= $date_t/1000;
    $date = new \DateTime();
    $date->setTimestamp($date_t);
    $today = new \DateTime('now');
    $interval =  $date->diff($today,true);
    if($interval){
        die($interval->days.'fgfddgdf');
    }
    die("nothid");
}
    public function sendSMSNotificationAction(){
        $adm = new AdminUpdates($this->getServiceLocator());
        $result = $adm->sendSMSNotice();
        die('INFOBIP RESULTING ID ==>'.$result);
    }
    public function testRequestPaymentAction(){
        //  header('Content-type: text/xml');
//       $field = 'test';
//        $check =  new CheckAccount($this->getServiceLocator());
//      //  $register =  $check->return_all_user_data('+263783211562');
//        $register = new Registration($this->getServiceLocator());
//        $result = $register->registerUser('test');
////        $deps = new UserDependants($this->getServiceLocator());
////        $register =  $deps->returnAllDepsdata('+263783211562');
//////        $util = new Utils();
//////        $register =  $util->return_results(Constants::ON_FAILURE_CONST);

        $paymentdata =  new CheckAccount($this->getServiceLocator());
        // return $paymentdata->returnPaymentData($this->field_a);
       $res = $paymentdata->getUserAccountBalance('+263772225902','2015-11-30 00.00.00');
        die ($res);

    }

    public function showUserNetworkAction(){
        $userNetwork = new UserAccountNetwork($this->getServiceLocator());
        $res = $userNetwork->getmonthlyRebates('+263772407426', '2015-09-30 10.00.00');
        die ($res);
    }

    public function chechWrongAccountsAction(){
        $userNetwork = new UserAccountNetwork($this->getServiceLocator());
        $res = $userNetwork->checkErrors();
    }

    public function testAction()
    {

        header('Content-type: text/xml');
        $get_file = new XmlExecutor($this->getServiceLocator());
        $get_file->setFieldA($this->params()->fromRoute('id'));
        $get_file->setFieldB($this->params()->fromRoute('id1'));
        die($get_file->retrieveBalance());
    }

    public function executeXmlAction()
    {
        header('Content-type: text/xml');
         die(Constants::xmlError(";-( NOT POST"));
        try {
            $request = $this->getRequest();
            if (!$request->isPost())
                die(Constants::xmlError(";-( NOT POST"));
            else {
                $json_string = $request->getPost("params");
                if($json_string==null)
                {
                    $json_string = file_get_contents('php://input');
                }
                $get_file = new XmlExecutor($this->getServiceLocator());
                $get_file->setEncodedJson($json_string);
                $xml_file = $get_file->runXmlFileService();
                die($xml_file);
            }
        } catch (\Exception $e) {
            die(Constants::xmlError(";-( ERROR ".$e->getMessage()));
        }
    }

    public function generateExcelReportsAction(){
        $adm = new AdminUpdates($this->getServiceLocator());
        $result = $adm->MySQLUsersDatatoExcelFile();
        $result .= $adm->MySQLUserPaymentsDatatoExcelFile();
      //  $result = $adm->monthlyPayments();
        die('Resulting Links '.'<br/>'.$result);
    }

    public function phpAction()
    {

        $InName = substr('Alois K Best', 0, 1);

          echo $InName. ' Mumera'.'<br/>';

        // date_default_timezone_set('UTC');
        $timestamp = new \DateTime('2015-10-27 14:52:13');
        //$timestamp->setTime(14,52,13);
        // $timestamp->setDate(2015,10,27);
        //$utc = new \DateTimeZone('UTC');
        //   $timestamp->setTimezone($utc);
        $dt = $timestamp->format('Y-m-d H:m:s');
        echo $dt.'<br/>';
        $time = strtotime('2015-10-27 14:52:13');

        $newformat = date('Y-m-d H:m:s',$time);
        echo $newformat;
        die('done');


    }


    public function updateAdminAction(){
        if (isset($_GET['q'])){
            //   $url = $_GET['q'];
            $admin = new AdminUpdates($this->getServiceLocator());
            $result =$admin->adminUpdates($_GET['q']);
            die($result);
        }
    }

    public function userPolicyPaymentAction(){
        $json = file_get_contents('php://input');
        $request = json_decode($json, true);
        $admin = new ShiriUserPayment($this->getServiceLocator());
        $result =$admin->processPayment($request);
        die($result);

    }

    public function shiriUserPaymentAction()
    {

        header('Content-type: text/xml');
        try {
            $request = $this->getRequest();
            if (!$request->isPost())
                die(Constants::xmlError(";-( NOT POST"));
            else {
                $json_string = $request->getPost("params");
                if($json_string==null)
                {
                    $json_string = file_get_contents('php://input');
                }
                $request = json_decode($json_string, true);
                $update = new ShiriUserPayment($this->getServiceLocator());
                $result = $update->processPayment($request);
                die($result);
                //die($json_string);
            }
        } catch (\Exception $e) {
            die(Constants::xmlError(";-( ERROR ".$e->getMessage()));
        }


//        $json =$this->params()->fromRoute('id1',null);
//        $request = json_decode($json, true);
//        $update = new ShiriUserPayment($this->getServiceLocator());
//        $result = $update->processPayment($request);
//        die($result);
    }

    public function nettcashUserValidationAction()
    {

        header('Content-type: text/xml');
        try {
            $request = $this->getRequest();
            if (!$request->isPost())
                die(Constants::xmlError(";-( NOT POST"));
            else {
                $json_string = $request->getPost("params");
                if($json_string==null)
                {
                    $json_string = file_get_contents('php://input');
                }
                $request = json_decode($json_string, true);
                $verify = new NettCashUserValidation($this->getServiceLocator());
                $result = $verify->validateUser($request);
                die($result);
                //die($json_string);
            }
        } catch (\Exception $e) {
            die(Constants::xmlError(";-( ERROR ".$e->getMessage()));
        }

//      //  $json = file_get_contents('php://input');
//       // $json =$this->params()->fromRoute('id1',null);
//        $json = $this->params()->fromPost('id1');
//        die($json);
//        $request = json_decode($json, true);
//        $verify = new NettCashUserValidation($this->getServiceLocator());
//        $result = $verify->validateUser($request);
//        die($result);

    }

    public function shiriTestUserPaymentsAction(){
        header('Content-type: text/xml');
        try {
            $request = $this->getRequest();
            if (!$request->isPost())
                die(Constants::xmlError(";-( NOT POST"));
            else {
                $json_string = $request->getPost("params");
                if($json_string==null)
                {
                    $json_string = file_get_contents('php://input');
                }
                $request = json_decode($json_string, true);
                $update = new ShiriUserPayment($this->getServiceLocator());
                $result = $update->processTestPayment($request);
                die($result);
                //die($json_string);
            }
        } catch (\Exception $e) {
            die(Constants::xmlError(";-( ERROR ".$e->getMessage()));
        }
    }

    public function nettcashTestUserValidationAction(){

        header('Content-type: text/xml');
        try {
            $request = $this->getRequest();
            if (!$request->isPost())
                die(Constants::xmlError(";-( NOT POST"));
            else {
                $json_string = $request->getPost("params");
                if($json_string==null)
                {
                    $json_string = file_get_contents('php://input');
                }
                $request = json_decode($json_string, true);
                $verify = new NettCashUserValidation($this->getServiceLocator());
                $result = $verify->validateUserTest($request);
                die($result);
            }
        } catch (\Exception $e) {
            die(Constants::xmlError(";-( ERROR ".$e->getMessage()));
        }
    }

    public function migrateUserDataAction(){
        $insert = new BalanceManagement($this->getServiceLocator(),'','','','');
        $result = $insert->insertMigratedData();
        echo $result.'<br/>';
        die('=========> done <==========');
    }

    public function insertEcocashPaymentsAction(){

        $json =$this->params()->fromRoute('id1',null);
        $request = json_decode($json, true);
        $verify = new BalanceManagement($this->getServiceLocator(),'','','','');
       // $result = $verify->insertTest($request);
        //$result = $verify->insertTestEcocash();
      // $result = $verify->insertEcoSendMessagesPayments();
       $result = $verify->insertEcoPayments();
        die($result);

    }

    public function aloissAction(){
        date_default_timezone_set('UTC');
        $owingMonth = new \DateTime();
        $month = date_format($owingMonth, 'm') . "";
        $year = date_format($owingMonth, 'Y') . "";
        $number = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $owingMonth = new \DateTime($year . '-' . $month . '-' . $number);
        $owingMonth = $owingMonth->format('Y-m-d 00:00:00');
        die($owingMonth);
    }

    public function notifyDueAmountsAction(){

        $verify = new BalanceManagement($this->getServiceLocator(),'','','','');
        $result = $verify-> notifyUnpaidPolicies();
        die($result);
    }

}

