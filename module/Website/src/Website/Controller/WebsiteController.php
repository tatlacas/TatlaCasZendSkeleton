<?php

namespace Website\Controller;




use Application\Entity\AdminUsers;
use Application\Model\Constants;
use Application\Model\PasswordCompatibilityLibrary;

use Website\Model\AdminLogin;
use Website\Model\AdminRegistration;
use Website\Model\RegDetails;
use Website\Model\User;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class WebsiteController extends AbstractActionController
{
    protected $form;
    public $ArrData;
    const PLEASE_ENTER_CORRECT_USERNAME = 'Please enter correct Username';
    const PLEASE_ENTER_CORRECT_DETAILS = 'Please enter correct Details';
    const PLEASE_ENTER_CORRECT_PASSWORD = 'Please enter correct Password';

    const PLEASE_ENTER_A_VALID_EMAIL = 'Please enter a valid Email';

    public function indexAction()
    {
        $Adminlogin = new AdminLogin($this->getServiceLocator());
        $form       = $this->getUserForm();
        $view = new ViewModel(array(
            'title' => "Welcome to Shiri Funeral Plan",
            'form'=>$form,
            'messages'  => $this->flashmessenger()->getMessages()
        ));
        if ($Adminlogin->isUserLoggedIn()) {

            $view->setTemplate('website/website/logged_in.phtml');
       }

        return $view;
    }

    public  function registerAdminUserAction(){
        $form   = $this->getRegDetailForm();

        $view = new ViewModel(array(
            'form'=>$form,
            'messages'  => $this->flashmessenger()->getMessages(),
            'data'=>$this->ArrData
        ));
        $view->setTemplate('website/website/register.phtml');
        return $view;
    }
    public function getUserForm()
    {
        if (! $this->form) {
            $user       = new User();
            $builder    = new AnnotationBuilder();
            $this->form = $builder->createForm($user);
        }

        return $this->form;
    }
    public function loginAction()
    {


        $form       = $this->getUserForm();
        $redirect = 'login';
        $request = $this->getRequest();
        if ($request->isPost()){
            $form->setData($request->getPost());
            if ($form->isValid()){


//                $this->getAuthService()->getAdapter()
//                    ->setIdentity($request->getPost('username'))
//                    ->setCredential($request->getPost('password'));
                $username = $request->getPost('username');
                    $password = $request->getPost('password');
               // die($username. ' '.$password);
//                $result = $this->getAuthService()->authenticate();
//                foreach($result->getMessages() as $message)
//                {
//                    //save message temporary into flashmessenger
//                    $this->flashmessenger()->addMessage($message);
//                }
                if (empty($username)) {

                    $this->flashmessenger()->addMessage('Username field was empty.');
                } elseif (empty($password)) {

                    $this->flashmessenger()->addMessage('Password field was empty.');
                } elseif (!empty($username) && !empty($password)) {
                   $Adminlogin = new AdminLogin($this->getServiceLocator());
                    $ad_user =  $Adminlogin->dologinWithPostData($username);
                   if ($ad_user) {
                       var_dump($ad_user);
                       //alois@gmail.com 123456
                    //   die($username. ' '.$password.' '.$request->getPost('rememberme'));
                       $state = $ad_user['state'];
                       if($state == Constants::ADMIN_EXISTS){
                           $user_password_hash = $ad_user['password'];
                           $verified = false;
                           if (version_compare(PHP_VERSION, '5.5.0', '<')) {

                               $passwrd = new PasswordCompatibilityLibrary(1);
                               $verified = $passwrd->password_verify($password, $user_password_hash);
                           }else {
                               $verified = password_verify($password, $user_password_hash);
                           }
                           if ($verified) {
                               $redirect = 'dashboard';
                               if ($request->getPost('rememberme') == 1 ) {

                               }
                               return $this->redirect()->toRoute($redirect);
                           }
                           return $this->onLoginFailure($username, self::PLEASE_ENTER_CORRECT_PASSWORD);
                       }
                       return $this->onLoginFailure($username, self::PLEASE_ENTER_CORRECT_USERNAME);
                    }
                    return $this->onLoginFailure($username, self::PLEASE_ENTER_CORRECT_DETAILS);


                }

//                $message = 'Wrong details Provided';
//                $this->flashmessenger()->addMessage($message);
            }
        }
        $view = new ViewModel(array(
            'form'=>$form,
            'messages'  => $this->flashmessenger()->getMessages()
        ));
        return $view;//redirect()->toRoute($redirect);
    }



    protected function getRegDetailForm(){
        $RegDetails = new RegDetails();
        $builder    = new AnnotationBuilder();
        $form = $builder->createForm($RegDetails);
        return $form;
    }
    public function createAdminUserAction(){

        $form    = $this->getRegDetailForm();
        $redirect = 'login';
        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setData($request->getPost());
         //   die('here');
            $UserName = $request->getPost('username');
            $Email = $request->getPost('email');
            $Password = $request->getPost('password');
            $ConfirmPassword = $request->getPost('confirmPassword');
            if ($form->isValid()) {


               //am@gmail.com  123456
                $Adminlogin = new AdminRegistration($this->getServiceLocator());
                $result = $Adminlogin->registerAdminNewUser($UserName,$Email,$Password,$ConfirmPassword);
                if ($result) {
//                    var_dump($result);
//                    die($UserName . ' ' . $Password);
                    $Arr_msg = $result['message'];
                    $res = $result['state'];
                    $message = '';
                    foreach($Arr_msg as $msg){
                        $message = $msg;
                    }
                    if($res == 1){
                        //die($message);
                        $this->flashmessenger()->addMessage($message);
                        return $this->continueToLogin();
                    }else{
                        $this->ArrData = array(
                            'name'=>$UserName,
                            'email'=>$Email,
                        );
                        var_dump($this->ArrData);
                        $this->flashmessenger()->addMessage($message);
                        return $this->registerAdminUserAction();
                    }

                }
//                if (empty($UserName)) {
//
//                    $this->flashmessenger()->addMessage('Username field was empty.');
//                } elseif (empty($Email )) {
//
//                    $this->flashmessenger()->addMessage('Email field was empty.');
//                } elseif (empty($Password)) {
//
//                    $this->flashmessenger()->addMessage('Password field was empty.');
//                } elseif (empty($ConfirmPassword)) {
//
//                    $this->flashmessenger()->addMessage('Password field was empty.');
//                }elseif(strcmp($Password,$ConfirmPassword) !== 0) {
//                    $this->flashmessenger()->addMessage('Password did not match.');
//                }elseif (!empty($UserName) && !empty($Password) && !empty($Email) && !empty($ConfirmPassword)) {
//
//                }

            }else{
                $this->ArrData = array(
                    'name'=>$UserName,
                    'email'=>$Email,
                );
                $this->flashmessenger()->addMessage(self::PLEASE_ENTER_A_VALID_EMAIL);
                return $this->registerAdminUserAction();
            }
        }

    }

    public function continueToLogin(){

        $form   = $this->getUserForm();
        $view = new ViewModel(array(
            'form'=>$form,
            'messages'  => $this->flashmessenger()->getMessages(),
            'data'=>$this->ArrData
        ));
        $view->setTemplate('website/website/index.phtml');
        return $view;
    }

    /**
     * @param $username
     * @return ViewModel
     */
    private function onLoginFailure($username,$msg)
    {
        $this->ArrData = array(
            'name' => $username,
        );
    //    die($msg);
        $this->flashmessenger()->addMessage($msg);
        return $this->continueToLogin();
    }

    public function logOutAction()
    {
        //clear session
        $form       = $this->getUserForm();
        $view = new ViewModel(array(
            'title' => "Welcome to Shiri Funeral Plan",
            'form'=>$form,
            'messages'  => $this->flashmessenger()->getMessages()
        ));
        $view->setTemplate('website/website/index.phtml');
        return $view;
    }


}

