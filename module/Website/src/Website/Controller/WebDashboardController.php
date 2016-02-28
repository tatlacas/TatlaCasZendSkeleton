<?php
/**
 * Created by PhpStorm.
 * User: alois - mumeraalois@gmail.com
 * Date: 10/23/2015
 * Time: 11:01 PM
 */

namespace Website\Controller;


use Application\Model\Constants;
use Application\Model\infobipSMSMessaging;
use Mobile\Model\DBUtils;
use Mobile\Model\Utils;
use Website\Model\AdminLogin;
use Website\Model\AdminRegistration;
use Website\Model\ApiFormDetails;
use Website\Model\ChangePasswordFormAnnotation;
use Website\Model\NewsFormDetails;
use Website\Model\ProcessRequests;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class WebDashboardController extends AbstractActionController
{

    protected $form;
    public $ArrData;


    const _SHIRI_USERS_PHTML_VIEW = 'website/website/shiri_users.phtml';


    public function indexAction()
    {
        $objProcess = new ProcessRequests($this->getServiceLocator());
        $res = $objProcess->returnAllUsers();
        $view = new ViewModel(array('data'=>$res));
        $view->setTemplate('website/website/logged_in.phtml');
        return $view;
    }

    public function authenticatedAction()
    {
        $objProcess = new ProcessRequests($this->getServiceLocator());
        $res = $objProcess->returnAllUsers();
        $view = new ViewModel(array('data'=>$res));
        $view->setTemplate('website/website/logged_in.phtml');
        return $view;
    }

    public function dashViewAction()
    {
        $objProcess = new ProcessRequests($this->getServiceLocator());
        $res = $objProcess->returnAllUsers();
        $view = new ViewModel(array('data'=>$res));
        $view->setTemplate('website/website/logged_in.phtml');
        return $view;
    }

    public function sendNewsAction()
    {
        $form = $this->getNewsForm();
        $view = new ViewModel(array(
            'form' => $form,
            'messages' => $this->flashmessenger()->getMessages(),
            'data' => $this->ArrData
        ));
        $view->setTemplate('website/website/admin_communications.phtml');
        return $view;
    }

    public function sendNewsActionAction(){
        $form       = $this->getNewsForm();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());

            $title = $request->getPost('title');
            $message = $request->getPost('message');
            $this->ArrData = array(
                'title'=>$title,
                'message'=>$message
            );
            //$rep_password = $request->getPost('confirmPassword');
            // die($title. ' '. $message);
            if ($form->isValid()) {
                $Adminlogin = new AdminRegistration($this->getServiceLocator());
                $result = $Adminlogin->sendMessage($title, $message);
                if ($result) {
                    var_dump($result);
                    //   die($new_username . ' ' . $password);
                    $Arr_msg = $result['message'];
                    $res = $result['state'];
                    $message = '';
                    foreach($Arr_msg as $msg){
                        $message = $msg;
                    }
                    if($res == 0){

                        return $this->OnSendNewsError($message);

                    }else{

                        return $this->dashViewAction();
                    }

                }
            }else{

                return $this->OnSendNewsError(Constants::PLEASE_ENTER_CORRECT_DEATAILS);
            }
        }

        }

    public function sendNotificationAction()
    {
//         $view = new ViewModel();
//        $view->setTemplate('website/website/send_notification.phtml');
//        return $view;
        $number = $this->params()->fromRoute('number', 1);
        if($number){
            $number = str_replace(' ', '', $number);
            list($phoneNumber, $viewState) = explode(':', $number);
//
            //  echo($number);
            if ((strcmp(substr($phoneNumber, 0, 3), '263') == 0 )&& (strlen($phoneNumber) == 12)){
                //  if (substr($number, 0, 1) !== "+") {
                $phoneNumber = "+" . $phoneNumber;
                //     }
                $view = $this->returnUserNotificationDetails($phoneNumber, $viewState);
            }
        }else {

            $users = $this->getServiceLocator()->get('Website\Model\Paginator\Repository\UserRepository');

            $view = new ViewModel(
                array(
                    'users' => $users
                )
            );
        }


        $view->setTerminal(true);
        $view->setTemplate('website/website/send_notification.phtml');
      //  $UserListView = new ViewModel();
//        $UserListView->setTemplate('website/website/sendNotifUsersList.phtml');
//        $view->addChild($UserListView,'userList');
        return $view;
    }

    public function sendSMStoUserAction(){
        $result_state = Constants::ERROR;
        $request = $this->getRequest();
        if ($request->isPost()) {
           // $form->setData($request->getPost());

            $phoneNumber = $request->getPost('phoneNumber');
            $message = $request->getPost('message');

            $view = $this->returnUserNotificationDetails($phoneNumber, Constants::SEND_SMS_TO_USER);

                if (empty($message)) {
                    $Msg = "Please enter message";

                } else {

                    if (strlen($message) > 160 ) {
                        $Msg = "Message must be not be long than 160 characters";
                    }else if(strlen($message) < 15){
                        $Msg = "Message must be not be less than 15 characters";
                    }

                }

                if(!empty($message) && strlen($message) <= 160 && strlen($message) >= 15)
                {
                   $result_state = Constants::INT_SUCCESS;
//                    $db = new DBUtils($this->getServiceLocator());
//                    $res = $db->save_individual_client_messages(Constants::SHIRI_NAME,
//                        $message, $phoneNumber);
//                    $infobipSMSMessaging = new infobipSMSMessaging();
//                    $result = $infobipSMSMessaging->sendmsg($phoneNumber,
//                        Constants::SHIRI_NAME,$message);
                    $Msg = 'Message succesfully send ';

                }

            $view->setVariable('message',array('state'=>$result_state,
                'msg'=>$Msg,
                'ErrTitle'=>'','title'=> '',
                'msgTobSent'=>$message));
            
        }else {
            $users = $this->getServiceLocator()->get('Website\Model\Paginator\Repository\UserRepository');

            $view = new ViewModel(
                array(
                    'users' => $users
                )
            );
        }
        $view->setTerminal(true);
        $view->setTemplate('website/website/send_notification.phtml');
        return $view;

    }

    public function sendNotifToUserAction(){
        $result_state = Constants::ERROR;
        $request = $this->getRequest();
        if ($request->isPost()) {
            // $form->setData($request->getPost());

            $phoneNumber = $request->getPost('phoneNumber');
            $gcm_reg_id = $request->getPost('gcmId');
            $title = $request->getPost('title');
            $message = $request->getPost('message');
           // echo $phoneNumber. ' '.$title. ' '.$message;
            $errTtle = '';
            $Msg = '';
            $view = $this->returnUserNotificationDetails($phoneNumber, Constants::SEND_NOTIF_TO_USER);
            if (empty($title)) {
                $errTtle = "Please enter message title";

            } else {

                if (strlen($title) > 64 || strlen($title) < 3) {
                    $errTtle = "Title cannot be shorter than 2 or longer than 64 characters";
                }
            }

            if (empty($message)) {
                $Msg = "Please enter message";

            } else {

                if (strlen($message) > 160 ) {
                    $Msg = "Message must be not be long than 160 characters";
                }else if(strlen($message) < 15){
                    $Msg = "Message must be not be less than 15 characters";
                }

            }

            if(!empty($message)
                && strlen($message) <= 160 && strlen($message) >= 15&&
                !empty($title) && strlen($title) <= 64
                && strlen($title) >= 3 )
            {
                $result_state = Constants::INT_SUCCESS;
//                $db = new DBUtils($this->getServiceLocator());
//                $res = $db->save_individual_client_messages($title,
//                    $message
//                    , $phoneNumber);
//                $db->send_notification($gcm_reg_id, $title, $message);
                $Msg = 'Notification succesfully send ';

            }

            $view->setVariable('message',array(
                'state'=>$result_state,
                'msg'=>$Msg,
                'ErrTitle'=>$errTtle,
                'title'=> $title,
                'msgTobSent'=>$message));

        }else {
            $users = $this->getServiceLocator()->get('Website\Model\Paginator\Repository\UserRepository');

            $view = new ViewModel(
                array(
                    'users' => $users
                )
            );
        }
        $view->setTerminal(true);
        $view->setTemplate('website/website/send_notification.phtml');
        return $view;
    }



    public function changePasswordAction()
    {
        $form = $this->getPassWordForm();
        $view = new ViewModel(array(
            'form' => $form,
            'messages' => $this->flashmessenger()->getMessages(),
            'data' => $this->ArrData
        ));
        $view->setTemplate('website/website/editadmin.phtml');
        return $view;
    }

    public function messagesAction()
    {
        $view = new ViewModel();
        $view->setTemplate('website/website/messages.phtml');
        return $view;
    }

    public function nettcashPaymentAction()
    {
////        $view = new ViewModel();
////        $view->setTemplate('website/website/nettcash_transactions.phtml');
////        return $view;
//        $page = $this->params()->fromRoute('page', 1);
//        # move to service
//        $limit = 10;
//        $offset = ($page == 0) ? 0 : ($page - 1) * $limit;
//        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
//        $pagedPayments = $em->getRepository(Constants::ENTITY_FROM_NETTCASH_SERVER)->getPagedUsers($offset, $limit);
//        //var_dump($pagedPayments);
//        $paginator = $em->getRepository(Constants::ENTITY_FROM_NETTCASH_SERVER)->getPaginator($offset, $limit);
//        # end move to service
//        $viewModel = new ViewModel();
////        $viewModel = new ViewModel(array(
////            'pagedUsers' =>$pagedUsers,
////            'page'=> $page
////        ));
//         $viewModel->setVariable( 'pagedPayments', $pagedPayments );
//         $viewModel->setVariable( 'page', $page );
//        $viewModel ->setTemplate('website/website/nettcash_transactions.phtml');
//        return $viewModel;

        $payments = $this->getServiceLocator()->get('Website\Model\Paginator\Repository\NetPaymentsRepository');

        $viewModel = new ViewModel();

        $viewModel->setVariable( 'payments', $payments );
        $viewModel->setTerminal(true);
        $viewModel->setTemplate('website/website/nettcash_transactions.phtml');
        return $viewModel;

    }

    public function ecocashPaymentAction()
    {
//        $view = new ViewModel();
//        $view->setTemplate('website/website/ecocash_transactions.phtml');
//        return $view;
        $number = $this->params()->fromRoute('number', 1);

        if(strcmp($number, Constants::ECO_1432) == 0) {
            $request = $this->getRequest();
            $amount = '';
            $examount = '';
            $dtpaid = '';
            $refNumber = '';
            $phoneNumber = '';
            if ($request->isPost()) {

                $amount = $request->getPost('amount');
                $examount = $request->getPost('examount');
                $dtpaid = $request->getPost('dtpaid');
                $refNumber = $request->getPost('refNumber');
                $phoneNumber = $request->getPost('phoneNumber');
               die($amount . ' ' . $examount . ' ' . $dtpaid . ' ' . $refNumber . ' ' . $phoneNumber);


        }
            $view = new ViewModel(
                array(
                    'state' => Constants::CONFIRM_PAYMENT,
                    'ConfUserPayment' => array('amount' => $amount, 'examount' => $examount, 'dtpaid' => $dtpaid,
                        'refNumber' => $refNumber,
                        'phoneNumber' => $phoneNumber)
                )
            );
        }else if(strcmp($number, Constants::ECO_1431) == 0){
            $view = new ViewModel(
                array(
                    'state' => Constants::SAVE_ECO_PAYMENT
                )
            );
        }else{

            $payments = $this->getServiceLocator()->get('Website\Model\Paginator\Repository\EcoPaymentsRepository');

            $view = new ViewModel(
                array(
                    'payments' => $payments
                )
            );
        }

        $view->setTerminal(true);
        $view->setTemplate('website/website/ecocash_transactions.phtml');
        return $view;
    }

    public function saveEcocashpayments(){



    }

    public function paginationAction()
    {
//        $users = $this->getServiceLocator()->get(Constants::ENTITY_USERS);
//
//        $view = new ViewModel(
//            array(
//                'users' => $users
//            )
//        );
//        $view->setTerminal(true);
//        $view->setTemplate(self::_SHIRI_USERS_PHTML_VIEW);
//        return $view;

    }

    public function allPaymentsAction()
    {
        $page = $this->params()->fromRoute('page', 1);
        # move to service
        $limit = 10;
        $offset = ($page == 0) ? 0 : ($page - 1) * $limit;
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        $pagedUsers = $em->getRepository(Constants::ENTITY_FROM_NETTCASH_SERVER)->getPagedUsers($offset, $limit);
        # end move to service

        $viewModel = new ViewModel(array(
            'pagedUsers' =>$pagedUsers,
            'page'=> $page
        ));
       // $viewModel->setVariable( 'pagedUsers', $pagedUsers );
       // $viewModel->setVariable( 'page', $page );

        return $viewModel;
    }

    public function shiriUsersAction()
    {

//       $objProcess = new ProcessRequests($this->getServiceLocator());
//        $res = $objProcess->returnAllUsers();
//        $view = new ViewModel(array('data'=>$res));
//        $view->setTerminal(true);
//        $view->setTemplate(self::_SHIRI_USERS_PHTML_VIEW);
//        return $view;
        $users = $this->getServiceLocator()->get('Website\Model\Paginator\Repository\UserRepository');

        $view = new ViewModel(
            array(
                'users' => $users
            )
        );
        $view->setTerminal(true);
        $view->setTemplate(self::_SHIRI_USERS_PHTML_VIEW);
        return $view;
    }

    public function apiConnectAction()
    {
        $form       = $this->getAPIForm();
        $view = new ViewModel(array(
            'form'=>$form,
            'messages'  => $this->flashmessenger()->getMessages(),
            'data'=>$this->ArrData
        ));
        $view->setTemplate('website/website/admin_api_comm.phtml');
        return $view;
    }

    private function getAPIForm()
    {
        if (! $this->form) {
            $api_user       = new ApiFormDetails();
            $builder    = new AnnotationBuilder();
            $this->form = $builder->createForm($api_user);
        }

        return $this->form;
    }

    public function createAPIUserAction(){
        $form       = $this->getAPIForm();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());

            $new_username = $request->getPost('apiId');
            $password = $request->getPost('password');
            $rep_password = $request->getPost('confirmPassword');
            $this->ArrData = array(
                'name'=>$new_username
            );
            // die($new_username. ' '. $password);
            if ($form->isValid()) {

                    $Adminlogin = new AdminRegistration($this->getServiceLocator());
                $result = $Adminlogin->createAPIUserAccount($new_username, $password, $rep_password);
                if ($result) {
                    var_dump($result);
                //   die($new_username . ' ' . $password);
                    $Arr_msg = $result['message'];
                    $res = $result['state'];
                    $message = '';
                    foreach($Arr_msg as $msg){
                        $message = $msg;
                    }
                    if($res == 0){

                        return $this->OnAPIError($message);

                    }else{

                        return $this->dashViewAction();
                    }

                }
            }else{

                return $this->OnAPIError(Constants::PLEASE_ENTER_CORRECT_DEATAILS);
            }
        }
    }

    public function changeUserPasswordAction(){
        $form = $this->getPassWordForm();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());

            $adminName = $request->getPost('adminName');
            $newPassword = $request->getPost('newPassword');
            $rep_password = $request->getPost('confirmNewPassword');
            $this->ArrData = array(
                'name'=>$adminName
            );
            // die($new_username. ' '. $password);
            if ($form->isValid()) {

                $Adminlogin = new AdminRegistration($this->getServiceLocator());
              //  die($adminName.' '.$newPassword.' '.$rep_password);
                $result = $Adminlogin->changeAdminPassword($adminName,$newPassword, $rep_password);
                if ($result) {
                 //  var_dump($result);
                  //  die($adminName . ' ' . $newPassword);
                    $Arr_msg = $result['message'];
                    $res = $result['state'];
                    $message = '';
                    foreach($Arr_msg as $msg){
                        $message = $msg;
                    }
                    if($res == 0){

                        return $this->OnError($message);

                    }else{

                        return $this->dashViewAction();
                    }
                }
            }else{

                return $this->OnError(Constants::PLEASE_ENTER_CORRECT_DEATAILS);
            }
        }

    }

    /**
     * @param $message
     * @return ViewModel
     */
    private function OnError($message)
    {
      //  var_dump($this->ArrData);
        $this->flashmessenger()->addMessage($message);
        $form = $this->getPassWordForm();
        $view = new ViewModel(array(
            'form' => $form,
            'messages' => $this->flashmessenger()->getMessages(),
            'data' => $this->ArrData
        ));
        $view->setTemplate('website/website/editadmin.phtml');
        return $view;
    }

    /**
     * @return \Zend\Form\Form
     */
    private function getPassWordForm()
    {
        $admin_password = new ChangePasswordFormAnnotation();
        $builder = new AnnotationBuilder();
        $form = $builder->createForm($admin_password);
        return $form;
    }

    private function OnAPIError($message)
    {
        //  var_dump($this->ArrData);
        $this->flashmessenger()->addMessage($message);
        $form = $this->getAPIForm();
        $view = new ViewModel(array(
            'form' => $form,
            'messages' => $this->flashmessenger()->getMessages(),
            'data' => $this->ArrData
        ));
        $view->setTemplate('website/website/admin_api_comm.phtml');
        return $view;
    }

    /**
     * @return \Zend\Form\Form
     */
    private function getNewsForm()
    {
        $news = new NewsFormDetails();
        $builder = new AnnotationBuilder();
        $form = $builder->createForm($news);
        return $form;
    }

    private function OnSendNewsError($message)
    {
        $this->flashmessenger()->addMessage($message);
        $form = $this->getNewsForm();
        $view = new ViewModel(array(
            'form' => $form,
            'messages' => $this->flashmessenger()->getMessages(),
            'data' => $this->ArrData
        ));
        $view->setTemplate('website/website/admin_communications.phtml');
        return $view;
    }

    public function sendGroupMessageAction()
    {
//        $form       = $this->getAPIForm();
//        $view = new ViewModel(array(
//            'form'=>$form,
//            'messages'  => $this->flashmessenger()->getMessages(),
//            'data'=>$this->ArrData
//        ));
        $objProcess = new ProcessRequests($this->getServiceLocator());
        $res = $objProcess->returnAllUsers();
        $view = new ViewModel(array('data'=>$res));
        $view->setTemplate('website/website/send_group_message.phtml');
        return $view;
    }

    /**
     * @param $phoneNumber
     * @param $viewState
     * @return ViewModel
     */
    private function returnUserNotificationDetails($phoneNumber, $viewState)
    {
        $objProcess = new ProcessRequests($this->getServiceLocator());
        $res = $objProcess->returnUserData($phoneNumber);

        if ($viewState == Constants::SEND_SMS_TO_USER) {
            $view = new ViewModel(array('ViewState' => $viewState, 'UserData' => $res));
            return $view;
        } else if ($viewState == Constants::SEND_NOTIF_TO_USER) {
            $view = new ViewModel(array('ViewState' => $viewState, 'UserData' => $res));

            return $view;
        }

    }


}