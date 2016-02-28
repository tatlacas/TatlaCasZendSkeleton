<?php
namespace User\Controller;

use User\Model\UserAuthorization;
use Zend\ModuleManager\ModuleEvent;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use User\Model\UserAuthentication;
use Application\SysConfigs\UserTypes;
use Zend\Crypt\Password\Bcrypt;

/**
 *
 * @author Tatenda Caston Hove
 *
 *
 */
class AuthenticationController extends AbstractActionController
{

    protected $Configs;

    private $service_locator;




    const CONTROLLER = "Authentication";
    const MODULE = "Users";

    public function Construct()
    {
    }

    /**
     *
     * @return the unknownType
     */
    public function getConfigs()
    {
        return $this->Configs;
    }

    /**
     * Default Users Login page
     *
     * @return \Zend\View\Model\ViewModel
     */



    public function indexAction()
    {
        $this->service_locator = $this->getServiceLocator();
        //check if user already logged in. If so, then redirect to dashboard!
        $this->isUserLoggedIn();
        /**
         * Check if login form submitted and attempt login!
         */

        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $signin = new UserAuthentication($this->service_locator);
            $setVariables= $signin->setUsername($request->getPost('Username'))
                ->setPassword($request->getPost('Passwd'));
            //jQuery Disabled?
            $isjQueryEnabled =$request->getPost('fromjQuery');
            if($setVariables->signinSuccessful())
            {

                if(!$isjQueryEnabled)
                {
                    return  $this->redirect()->toRoute('user' , array(
                        'controller' => 'Authentication',
                        'action' => 'dashboard'
                    ));
                }
                $response->setContent(\Zend\Json\Json::encode(array('response' => true)));
            }
            else
            {
                if(!$isjQueryEnabled)
                {

                    $view = new ViewModel(array('incorrect_credentials'=>true));
                    $view->setTerminal(true);
                    return $view;
                }
                // Error supplied info invalid
                $response->setContent(\Zend\Json\Json::encode(array('response' => false,)));
            }
            return $response;
        }
        $view = new ViewModel();
        $view->setTerminal(true);
        return $view;
    }

    private function isUserLoggedIn()
    {
        $prepareAuthentication = new UserAuthentication($this->service_locator);
        if($prepareAuthentication->authenticationPassed())
        {
            return $this->redirect()->toRoute('user' , array(
                'controller' => 'Authentication',
                'action' => 'dashboard'
            ));
        }
    }

    public function manageAction()
    {

    }
    /**
     * Method to return the User Object of current logged in user
     *
     * @return \Zend\View\Model\ViewModel
     */
    private function authenticateCurrentUser()
    {
        $prepareAuthentication = new UserAuthentication($this->service_locator);
        if(! $prepareAuthentication->authenticationPassed())
        {
            return $this->redirect()->toRoute('user' , array(
                'controller' => 'Authentication',
                'action' => 'index'
            ));
        }
        $this->Configs['userType']=$prepareAuthentication->getUserType();
    }

    private function authorizeCurrentUser($resource)
    {
        $prepareAuthorise=new UserAuthorization($this->service_locator);
        $prepareAuthorise->setModuleToBeAuthorized(AuthenticationController::MODULE);
        $prepareAuthorise->setControllerToBeAuthorized(AuthenticationController::CONTROLLER);
        $prepareAuthorise->setResourceToBeAuthorized($resource);
        if(!$prepareAuthorise->grantAccess())
        {
            //redirect to dashboard or default action!
            die('Access Denied!');
            return $this->redirect()->toRoute('user' , array(
                'controller' => 'Authentication',
                'action' => 'dashboard'
            ));
        }
        die('Access Granted');
    }

    /**
     * Action for initiating the password change of user
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function changePasswordAction()
    {
        return new ViewModel();
    }

    /**
     * Method for choosing action to login page / forgot password page /
     * dashboard according to user request
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function chooseLayoutAction()
    {
        return new ViewModel();
    }

    /**
     * Method to create User Object
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function createAction()
    {
        $this->layout()->Configs = $this->getConfigs();
        return new ViewModel();
    }



    /**
     * Shows links to all the modules based on the user's permission
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function dashboardAction()
    {
        $sidebar =$this->sidebarAction();

        $view = new ViewModel();

        switch ($this->Configs['userType']) {
            case UserTypes::ADMIN_USER:
                $view->setTemplate('dashboard/administrator');
                break;
            case UserTypes::FINANCE_USER:
                $view->setTemplate('dashboard/finance');
                break;
            case UserTypes::HR_USER:
                $view->setTemplate('dashboard/hr');
                break;
            case UserTypes::TEACHER_USER:
                $view->setTemplate('dashboard/lecturer');
                break;
            case UserTypes::GUARDIAN_USER:
                $view->setTemplate('dashboard/guardian');
                break;
            case UserTypes::EMPLOYEE_USER:
                $view->setTemplate('dashboard/employee');
                break;
            case UserTypes::STUDENT_USER:
                $view->setTemplate('dashboard/student');
                break;

            default:
                $view->setTemplate('dashboard/student');
                break;
        }
        $view->addChild($sidebar,'sidebar');
        return $view;
    }

    public function navbarAction()
    {
        $this->service_locator = $this->getServiceLocator();
        $this->authenticateCurrentUser();
        $view = new ViewModel();

        switch ($this->Configs['userType']) {
            case UserTypes::ADMIN_USER:
                $view->setTemplate('navbar/administrator');
                break;
            case UserTypes::FINANCE_USER:
                $view->setTemplate('navbar/finance');
                break;
            case UserTypes::HR_USER:
                $view->setTemplate('navbar/hr');
                break;
            case UserTypes::TEACHER_USER:
                $view->setTemplate('navbar/lecturer');
                break;
            case UserTypes::GUARDIAN_USER:
                $view->setTemplate('navbar/guardian');
                break;
            case UserTypes::EMPLOYEE_USER:
                $view->setTemplate('navbar/employee');
                break;
            case UserTypes::STUDENT_USER:
                $view->setTemplate('navbar/student');
                break;

            default:
                $view->setTemplate('navbar/student');
                break;
        }
        return $view;
    }


    /**
     * Deletes a User object
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function deleteAction()
    {
        // Access to admin only

        $this->service_locator = $this->getServiceLocator();
        $this->authenticateCurrentUser();
        $this->authorizeCurrentUser('delete');
        return new ViewModel();
    }

    /**
     * Edit a User Object
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function editAction()
    {
        // Access to owner and admit. Access to owner limited
        $this->authenticateCurrentUser();
        return new ViewModel();
    }

    /**
     * Edit privileges of a user
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function editPrivilegeAction()
    {
        // Access to admin and management only
        return new ViewModel();
    }

    /**
     * Finds the FinanceU manager(s)U for the User
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function findFinanceManagersAction()
    {
        return new ViewModel();
    }

    /**
     * Intiate the forgotPassword view
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function forgotPasswordAction()
    {
        // Access to owner of account only
        return new ViewModel();
    }

    /**
     * List all users with role Employee
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function listEmployeeUsersAction()
    {
        return new ViewModel();
    }

    /**
     * List all users with role student
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function listStudentUsersAction()
    {
        return new ViewModel();
    }

    /**
     * List all users with role parent
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function listGuardianUsersAction()
    {
        return new ViewModel();
    }

    /**
     * List all users
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function listUsersAction()
    {
        return new ViewModel();
    }

    /**
     * Action for destroying the current user's session
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function logoutAction()
    {
        return new ViewModel();
    }

    /**
     * Show a user's profile
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function profileAction()
    {
        return new ViewModel();
    }

    /**
     * Proceed to reset password the current user's password after checking the
     * passwordResetFeild
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function resetPasswordAction()
    {
        return new ViewModel();
    }

    /**
     * Ajax search of users
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function searchUserAction()
    {
        return new ViewModel();
    }

    /**
     * Set a new password after a valid forgot password attemp on a
     * password change action
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function setNewPasswordAction()
    {
        return new ViewModel();
    }

    /**
     * initiate change password action for a users
     *
     * @return \Zend\View\Model\ViewModel
     */
    public function userChangePasswordAction()
    {
        return new ViewModel();
    }

    /**
     * Sets session variables after successful login
     *
     * @param $user -
     *            The user who just signed in!
     * @return nothing
     */
    private function successfulLogin($user)
    {}
}
