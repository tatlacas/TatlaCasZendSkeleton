<?php
/**
 * TatlaCas Customized
 *
 *
 * @copyright Copyright (c) 20013-2014 Fundamental Technologies (Private) Limited (http://www.funtechno.com)
 * @author   Tatenda Caston Hove <tathove@gmail.com> on 01/09/2015.
 *
 */


namespace Application\Model;


use Zend\Session\Container;

class Constants
{

    //Environment Type

    const TEST_ENVIRONMENT = 21;
    const LIVE_ENVIRONMENT = 22;


    const ENVIRONMENT_TYPE = Constants::TEST_ENVIRONMENT;
//Cluster Transaction Type
    const CLUSTER_TRANSACTION_PAID = 1;
    const CLUSTER_TRANSACTION_NOT_PAID = 0;
    const REFERRAL_TRANSACTION_TYPE = 1;
    const ADMIN_TRANSACTION_TYPE = 2;

//App Version
    const APP_VERSION_21 = 21;
    const APP_VERSION_22 = 22;
    const APP_VERSION_23 = 23;
    //Entities
    const ENTITY_ADMINUSERS = 'Application\Entity\AdminUsers';
    const ENTITY_PENDING_PAYMENTS = 'Application\Entity\PendingPayments';
    const ENTITY_CLUSTERSPAYMENTS = 'Application\Entity\ClustersPayments';
    const ENTITY_REFERRALS = 'Application\Entity\Referrals';
    const ENTITY_USER_CAPTURER = 'Application\Entity\UserCapturer';
    const ENTITY_BRANCHES = 'Application\Entity\Branches';
    const ENTITY_CRON_JOBS = 'Application\Entity\CronJobs';
    const ENTITY_PACKAGE_PLANS = 'Application\Entity\PackagePlans';
    const ENTITY_PACKAGEPLANFIGURES = 'Application\Entity\PackagePlansFigures';
    const ENTITY_TESTUSERPAYMENTS = 'Application\Entity\TestUserPayments';
    const ENTITY_TESTUSERS = 'Application\Entity\TestUsers';
    const ENTITY_USERDEPENDENTS = 'Application\Entity\UserDependents';
    const ENTITY_USER_PAYMENTS = 'Application\Entity\UserPayments';
    const ENTITY_USERS = 'Application\Entity\Users';
    const ENTITY_USER_POLICIES = 'Application\Entity\UserPolicies';
    const ENTITY_USER_POLICY_STATUS = 'Application\Entity\UserPolicyStatus';
    const ENTITY_USER_NETTCASH_ACCOUNT = 'Application\Entity\NettcashAccounts';
    const ENTITY_ADMIN_UPDATES = 'Application\Entity\AdminUpdates';
    const ENTITY_MESSAGES = 'Application\Entity\Messages';
    const ENTITY_USER_ACTIVITIES_DATA = 'Application\Entity\UserActivitiesData';
    const ENTITY_USER_RELATION_TYPES = 'Application\Entity\UserRelationshipTypes';
    const ENTITY_PAYMENT_TYPES = 'Application\Entity\PaymentTypes';
    const ENTITY_SUBSCRIBED_PACKAGES = 'Application\Entity\SubscribedPackages';
    const ENTITY_FROM_NETTCASH_SERVER = 'Application\Entity\FromNettcashServer';
    const ENTITY_FR = 'Application\Entity\FriendsPayments';
    const ENTITY_ECOCASH = 'Application\Entity\EcocashPayments';
    const ENTITY_NETTCASH_PAYMENTS = 'Application\Entity\NettcashPayments';
    const ENTITY_USER_REBATES = 'Application\Entity\UserRebate';
    const ENTITY_REBATE_REFERRAL_MULTIPLIER_PRICES = 'Application\Entity\RebateReferralMultiplierSettings';

    const REFERRAL_LOCK_PERIOD = 60;


    const TWO_DECIMAL_PLACE = 2;
    const IMM_FAMILY = 4;
    const SMS_VERIFY = 'Shiri Verification Code :';
    const SHIRI_NAME = 'Shiri';
    const DAILY_UPDATES = 'Daily Updates';
    const SFP_ALL_PAYMENT = 'SFP_ALL_PAYMENT';
    const SFP_ALL_USERS = 'SFP_ALL_USERS';
    const SHIRI_DEFAULT_PASSWORD = 'shiri';
    const JOINING_MYSELF_STATE = "2";
    const SHIRI_POLICY_DEF_STATUS = 2;
    const SHIRI_WELCOME_MSG = "1010";
    const NETTCASH_AGERT_ID = "3620922730210689";
    const NETTCASH_AGERT_PASSWORD = "shiri_funeral2015";
    const NETT_REGISTER_LINK = "https://kilo-s.net/messenger/nettcashRegistrations";

    const NETTCASH_STR = 'Nettcash';
    const ECOCASH_STR = 'Ecocash';
    const NETTCASH_REG_LIVE_LINK = 'https://integrationhub.nettcash.co.zw:8444/tpapi/live/agenthub/tpenrollment.php?';
    const WELCOME_YOUR_ACCOUNT_PINCODE_IS = "Welcome ,your Account pincode is : ";

    const SHIRI_GOOD_NEWS = '100';
    const SHIRI_GOOD_NEWS_STR = 'Shiri good news';
    const A_NEW_SHIRI_JOINED = '101';
    const JUST_JOINED_YOUR_NETWORK = ' just joined your network';
    const YOU_HAVE_JOINED_A_FRIEND = 'You have joined a friend';
    const SHIRI_FUNERAL_PLAN_PAYMENT = 'Shiri Funeral Plan Payment';
    const SHIRI_FUNERAL_PLAN_DUE_PAYMENTS = 'Shiri Due Payments';
    const SHIRI_SMS_TITLE = 'Shiri';
    const CONGRATULATIONS = 'Congratulations, ';
    const CRON_JOB_ZERO = '0';
    const CRON_JOB_TEN_MINUTES = '10';
    const CRON_JOB_ADD_ACTION = 'add';
    const CRON_JOB_REMOVE_ACTION = 'delete';
    const TODAY_S_PAYMENTS = "Today's Payments";

    const PHP_REGISTER_ACCOUNT_ID = 1167;
    const PHP_DELETE_DEPENDANT_ID = 1168;
    const PHP_VERIFY_REFERER_ID = 1169;
    const PHP_CHECK_ACCOUNT_ID = 1170;
    const PHP_RETURN_ALL_USER_NETWORK = 1171;
    const PHP_RETURN_ALL_DEPENDENTS = 1172;
    const PHP_RETURN_BRANCHES = 1173;
    const PHP_LOGIN_ID = 1174;
    const PHP_USER_NOTIFICATION = 1175;
    const PHP_USER_ACTIVITY = 1176;
    const PHP_USER_INFO_UPDATES = 1177;
    const PHP_RETURN_USER_DATA = 1178;
    const PHP_VERIFY_NUMBER = 1179;
    const PHP_PROCESS_PINCODE = 1180;
    const PHP_FIRST_ACCOUNT_CHECK = 1181;
    const PHP_RETURN_USER_MESSAGES = 1182;
    const PHP_ADD_DEPENDANT = 1183;
    const PHP_RETURN_RELATIONS = 1184;
    const  PHP_USER_PAYMENT = 1185;
    const PHP_RETURN_USER_PYDATA = 1186;
    const PHP_NETTCASH_PAYMENT = 1187;
    const PHP_RETRIEVE_OWING_BALANCE = 1188;
    const PHP_FRIENDS_JOINED = 1189;
    const PHP_RETURN_ALL_STATUS = 1190;
    const PHP_RETURN_MONTHLY_REBATES = 1191;
    const PHP_INSERT_PENDING_PHP_RECORD = 1192;


    const ADMIN_LOGIN = 'Admin Login';
    const NO_ADMIN = 1;
    const ADMIN_EXISTS = 2;
    const POLICY_FIRST_GENERATION_NUMBER = 1000;
    const EXTRA_FOUR_DIGITS = 4;
    const EXTRA_FIVE_DIGITS = 5;
    const EXTRA_SIX_DIGITS = 6;
    const EXTRA_SEVEN_DIGITS = 7;

    const START_ROW_COUNT = 2;
    const MAX_RESULT_FROM_QUERY = 1;

    const  POLICY_FIRST_GEN = 'S000';
    const  POLICY_SECOND_GEN = 'S00';
    const  POLICY_THIRD_GEN = 'S0';
    const  POLICY_FORTH_GEN = 'S';
    const DEPENDANTS_COUNT = 'DEPENDANT(S)';
    const SHIRI_CLIENTS = 'Shiri Clients';
    const MONTHLY_PREMIUM = 'MONTHLY PREMIUM';
    const EXCESS_AMOUNT = 'EXCESS AMOUNT';
    const PAYMENT_TYPE = 'PAYMENT TYPE';
    const PAID_AT = 'PAID AT';
    const AMOUNT_PAID = 'AMOUNT PAID';
    const MONTH_PAID_FOR = 'MONTH PAID FOR';
    const SHIRI_PAYMENTS = 'Shiri Payments';
    const SHIRI_NEW_PAYMENTS = 'Shiri New Payments';
    const NEW_BUSINESS = "New Business";
    const POLICY_PREMIUM = 'PREMIUM';
    const DEPENDANT_FIRSTNAME = 'DEPENDANT FIRSTNAME';
    const DEPENDANT_SURNAME = 'DEPENDANT SURNAME';
    const DEPENDANT_RELATION = 'RELATION';
    const ADDITIONAL_DEPENDANT_INT = 3;
    const IS_SPOUSE = 1;
    const IS_BIO_CHILD = 2;
    const IS_B_AND_C = 3;
    const PRIVATE_MESSAGE = 1;
    const PUBLIC_MESSAGE = 2;
    const ERROR = 0;
    const INT_SUCCESS = 1;
    const ITEMS_PER_PAGE = 5;
    const SEND_SMS_TO_USER = 1;
    const SEND_NOTIF_TO_USER = 2;
    const SHOW_DATE_OF_BIRTH = 1;
    const SAVE_ECO_PAYMENT = 1;
    const CONFIRM_PAYMENT = 2;
    const NOTIFICATION_MSG = 'Notification Message should be not more than 160 characters';
    const NOTIFICATION_SMS = 'SMS Message should be not more than 160 characters';
    const SHIRI_NEW_CLIENTS = 'Shiri New Users';
    const DATA_SAVE_TEMP_FILES = './data/SaveTempFiles/';
    const FOR_STR = 'for ';
    const NOT_ACTIVE = 1;
    const ACTIVE = 2;

    public static function xmlError($code)
    {

        $xml_output = "<?xml version=\"1.0\"?>\n";
        $xml_output .= "<entries>\n";
        $xml_output .= "\t<entry>\n";
        $xml_output .= "\t\t<result>" . $code . "</result>\n";
        $xml_output .= "\t</entry>\n";
        $xml_output .= "</entries>";
        return ($xml_output);
    }

    const POLICY_NOT_ACTIVE = "1";
    const POLICY_ACTIVE = "2";

    const BANDC_ACTIVE = 'Active';
    const BANDC_NOT_ACTIVE = 'Not Active';
    const BUNDLE_ZERO = "0";
    const BUNDLE_ONE = "1";
    const BUNDLE_TWO = "2";
    const BUNDLE_THREE = "3";
    const BUNDLE_FOUR = "4";
    const BUNDLE_FIVE = "5";
    const BUNDLE_SIX = "6";
    const BUNDLE_SEVEN = "7";

    const GET_NEXT_DUE = "2";
    const INT_BUNDLE_ZERO = 0;
    const INT_BUNDLE_ONE = 1;
    const INT_BUNDLE_TWO = 2;
    const INT_BUNDLE_THREE = 3;
    const INT_BUNDLE_FOUR = 4;

    const PREMIUM_POLICY = 1;
    const BUS_AND_CASH_ADITIONAL_BEN = 2;
    const IMMEDIATE_FAMILTY = 4;


    const SERVER_MSG = 1908;
    const USER_MSG = 1909;

    const FRIEND_PAYMENT = 2;
    const MYSELF_PAYMENT = 1;
    const IMMEDIATE_FAMILY = 3;

    public static function ResultCode($message)
    {
        return '<ResultCode>' . $message . '</ResultCode>';
    }

    const ENCRYPTION_KEY = 'd0a7e7997b6d5fcd55f4b5c32611b87cd923e88837b63bf2941ef819dc8ca282';
    const CRON_JOBS_TOKEN = 'ccb56e09293c718ca639ee23b97c2bd7';
    const SALT_KEY = '$2y$07$BCFvHUNcDnOPnUwwBzVlQH0piJtjXl.0t1XkA8pw9dMXTpOq';
    const  GCM_REG_ID_DEFAULT = 'registrationId';
    const  NETTCASH_PAYMENT_SUCCESSFUL = 'Payment transaction Successul';
    const VERIFY_KEY = "14Netdla15";

    const ON_FAILURE_CONST = 0;
    const ON_SUCCESS_CONST = 1;
    const ON_USER_EXIST_CONST = 2;
    const ON_WRONG_NUMBER_CONST = 3;
    const ON_ACCOUNT_AVAILABLE_CONST = 6;
    const ON_ACCOUNT_NOT_AVAILABLE_CONST = 7;
    const ON_NO_PAYMENT = 8;
    const ON_NO_NETWORK = 9;
    const ON_NO_FIRST_PAYMENT = 10;

    const DB_LIMIT = 1;
    const REFERRER_DOES_NOT_EXIST = 11;
    const PASSWORD_DEFAULT = 1;

    const ECOCASH = 1;
    const NETTCASH = 2;
    const IF_USER = 2;

    const PHONE_NUMBER_LENGTH = 13;
    const PINCODE_MIN_LENGTH = 4;

    const  PAYMENT_WRONG_PHONENUMBER = 156;
    const PAYMENT_FAILED = 157;
    const  PAYMENT_SUCCESS = 158;
    const PAYMENT_NO_DATA_SET = 159;
    const  PAYMENT_AUTH_FAILED = 160;
    const  ADITIONAL_DEP_PACKAGE_ID = 3;
    const TO_MILLISECONDS = 1000;
    const FAILED_TO_PROCESS_YOUR_DATA_WRONG_PHONE_NUMBER = 'failed to process your data, wrong phone number';
    const ACCOUNT_DETAILS_PROVIDED_ARE_VALID = "Account Details provided are Valid";
    const SORRY_SHIRI_DOES_NOT_RUN = "Sorry, Simple Shiri System does not run on a PHP version smaller than 5.3.7 !";

    const HAVE_REFERRED_YOU_TO_SHIRI_ACCOUNT_PINCODE = " have referred you to Shiri, Account pincode : ";
    const ACCOUNT_INFORMATION_UPDATES = 'Account Information Updates';

    const YOU_HAVE_REMOVED_BUS_AND_CASH_BENEFIT = 'You have removed Bus and Cash Benefit';
    const WELCOME = 'Welcome,';
    const HAS_JOINED_YOU_TO_SHIRI_YOUR_ACCOUNT_PINCODE_IS = 'has joined you to Shiri, your Account pincode is :';
    const YOU_HAVE_ACTIVATED_BUS_AND_CASH_BENEFIT = 'You have activated Bus and Cash Benefit';

    const REFERER_NOT_FOUND = "Referer not found";

    const SHIRI_DEFAULT_NUMBER = '+26377100000007';
    const SHIRI_CODE = '07';
    const DOVES_CODE = '20';
    const DOVES_DEFAULT_NUMMBER = '+26377000000020';

    const BRANCH_NOT_SET = "Branch not set";
    const MALE = 1;
    const FEMALE = 2;
    const TRUE_STR = 'TRUE';
    const FALSE_STR = 'FALSE';
    const POLICY_STATUS_NOT_SET = "Policy status not set";
    const USER_REL_NOT_SET = "Relation not set";

    const WELCOME_TO_SHIRI_FUNERAL_PLAN = "Welcome to Shiri Funeral Plan";

    const NETT_CASH_ACCOUNT = "NettCash account";
    const NETTCASH_ACC_EXITS_STR = "10111";
    const NETTCASH_ACC_REG_SUCCESS_STR = "1011";
    const NETTCASH_ACC_REG_IN_PROGRESS = "1012";
    const NETTCASH_PAYMENT_TYPE = "1020";
    const ECOCASH_PAYMENT_TYPE = "1021";
    const CLIENT_ALREADY_EXISTS = 'client already exists';

    const PLAN_NOT_SET = "Plan not set";
    const POLICY_NOT_SET = "Policy not set";
    const SUCCESS = 'success';
    const PLAN_FIGURE_NOT_SET = "Plan figure not set";
    const NETTCASH_ACCOUNT_REGISTERED_SUCCESSFULLY = "Nettcash account registered successfully";

    const THANK_YOU_NETT_CASH_ACCOUNT_ALREADY_EXISTS = "Thank you, NettCash account already Exists";

    const NETT_CASH_WALLET_FEEBACK = "NettCash Wallet Feeback";

    const NETT_CASH_REGISTRATION_IN_PROGRESS = "NettCash registration in progress";
    const REGISTERED = "registered";
    const FAILED_FIELD_REQUIRED = "failed-field required";
    const LOCAL_ID = 'localId';
    //json const string
    const CHANGED_PHONE_NUMBER = 'changedPhoneNumber';
    const UPDATE_STATE = 'updateState';
    const PHONE_NUMBER = 'phoneNumber';
    const LOCATION = 'location';
    const GCM_ID = 'gcmId';
    const PINCODE = 'pincode';
    const BENEFIT_STATE = 'benefitState';
    const MESSAGE = 'message';
    const TITLE = 'title';
    const DATE_TIME = 'dateTime';
    const IS_JOIN_ANOTHER = 'isJoinAnother';
    const CAPTURER_PHONE = 'capturerPhone';
    const GCM_REGID = 'gcmRegid';
    const PLAN_NAME = 'planName';
    const GENDER = 'gender';
    // const PINCODE = 'pincode';
    const SERVER_ID = 'serverId';
    const NEXT_DUE = 'nextDue';
    const AMOUNT = 'amount';
    const NEAREST_BRANCH = 'nearestBranch';
    const REFERER_ID = 'refererId';
    const CREATED_AT = 'createdAt';
    const DATE_OF_BIRTH = 'dateOfBirth';
    const ID_NUMBER = 'idNumber';
    const ID_NUMBER_STR = 'ID NUMBER';
    const LAST_NAME = 'lastName';
    const FIRST_NAME = 'firstName';
    const REFERER = 'referer';

    const ADDITIONAL_DEPENDANT_REMOVED = 'Additional dependant removed';
    const WAS_REMOVED = 'was removed';
    const FEMALE_STR = 'Female';
    const MALE_STR = 'Male';

    const SHIRI_MESSAGE = "shiri_message";
    const YOU_HAVE_PAID = "You have paid $";
    const FOR_YOUR_POLICY_ACCOUNT = " for your policy account";
    const FOR_YOUR_POLICY_PREMIUM = " for your Policy Premium";

    //excel header titles
    const DEVELOPER_NAME = "Alois Mumera";
    const MEMBER_FIRSTNAME = 'MEMBER FIRSTNAME';
    const MEMBER_SURNAME = 'MEMBER SURNAME';
    const MEMBER_ID = 'MEMBER ID';
    const MALE_TITLE = 'MALE';
    const MOBILE = 'MOBILE';
    const STATUS = 'STATUS';
    const JOINED_AT = 'JOINED AT';
    const DATE_OF_BIRTH_TITLE = 'DATE OF BIRTH';
    const POLICY_NUMBER = 'POLICY NUMBER';

    const SHIRI_ADMIN_PANEL = 'Shiri Admin Panel';

    //admin reg constants
    const YOUR_PASSWORD_HAS_BEEN_CHANGED = "Your password has been changed";
    const SORRY_THAT_USERNAME_DOES_NOT_EXIST = "Sorry, that username does not exist";
    const PLEASE_ENTER_MESSAGE_TITLE = "Please enter message title";
    const TITLE_CANNOT_BE_SHORTER_THAN_2_OR_LONGER_THAN_64_CHARACTERS = "Title cannot be shorter than 2 or longer than 64 characters";
    const PLEASE_ENTER_MESSAGE = "Please enter message";
    const MESSAGE_MUST_BE_NOT_BE_LONG_THAN_160_CHARACTERS = "Message must be not be long than 160 characters";
    const MESSAGE_MUST_BE_NOT_BE_LESS_THAN_15_CHARACTERS = "Message must be not be less than 15 characters";
    const NOTIFICATION_NUMBER = 'Notification number :';
    const WAS_SUCCESFULLY_SEND = ', was succesfully send !';
    const NOTIFICATION_NOT_SEND = 'Notification not send !';
    const USERNAME_DOES_NOT_FIT_THE_NAME_SCHEME = "Username does not fit the name scheme: only a-Z and numbers are allowed, 2 to 64 characters";
    const USERNAME_CANNOT_BE_SHORTER_THAN_2_OR_LONGER_THAN_64_CHARACTERS = "Username cannot be shorter than 2 or longer than 64 characters";
    const PASSWORD_HAS_A_MINIMUM_LENGTH_OF_6_CHARACTERS = "Password has a minimum length of 6 characters";
    const PASSWORD_DID_NOT_THE_MATCH = "Password did not the match";
    const EMPTY_PASSWORD = "Empty Password";
    const EMPTY_USERNAME = "Empty Username";
    const YOUR_ACCOUNT_HAS_BEEN_CREATED_SUCCESSFULLY_YOU_CAN_NOW_LOG_IN = "Your account has been created successfully. You can now log in.";
    const SORRY_THAT_USERNAME_IS_ALREADY_TAKEN = "Sorry, that username is already taken.";
    const PASSWORD_DID_ARE_NOT_THE_MATCH = "Password did are not the match";
    const YOUR_EMAIL_ADDRESS_IS_NOT_IN_A_VALID_EMAIL_FORMAT = "Your email address is not in a valid email format";
    const EMAIL_CANNOT_BE_LONGER_THAN_64_CHARACTERS = "Email cannot be longer than 64 characters";
    const EMAIL_CANNOT_BE_EMPTY = "Email cannot be empty";
    const PASSWORD_AND_PASSWORD_REPEAT_ARE_NOT_THE_SAME = "Password and password repeat are not the same";
    const PLEASE_ENTER_CORRECT_DEATAILS = 'Please enter correct details';
    //developers
    const DEVELOPERS_MYSHIRI_COM = 'developers@myshiri.com';
    //todo remove error it shld be shiriTopic
    const TOPICS_SHIRI_TOPIC = "/topics/shiriTopics";
    const BUS_AND_CASH = 'BUS AND CASH BENEFIT';

//php actions
    const DASH_VIEW = 'dashView';
    const SHIRI_USERS = 'shiriUsers';
    const ECOCASH_PAYMENT = 'ecocashPayment';
    const NETTCASH_PAYMENT = 'nettcashPayment';
    const MESSAGES = 'messages';
    const CHANGE_PASSWORD = 'changePassword';
    const API_CONNECT = 'apiConnect';
    const SEND_NOTIFICATION = 'sendNotification';
    const SEND_NEWS = 'sendNews';
    const DASH_BOARD = 'dashBoard';
    const LOG_OUT = 'logOut';
    const PAGE_0 = '/page/0/';
    const ECO_1432 = 'ECO-1432';
    const ECO_1431 = 'ECO-1431';

    const CHECK_POLICY_STATUS = 1;
    const CHECK_ADDITIONAL_DEPS_STATUS = 1;
    const CHECK_BUSANDCASH_STATUS = 1;

    const BC_BENEFIT_NOT_ACTIVE = 1;
    const BC_BENEFIT_ACTIVE = 2;

    const FIRST_GENERATION = 1;
    const SECOND_GENERATION = 2;
    const THIRD_GENERATION = 3;
    const FORTH_GENERATION = 4;
    const FIFTH_GENERATION = 5;
    const SIXTH_GENERATION = 6;
    const SEVENTH_GENERATION = 7;
    const SHIRI_NETWORK_LIMIT = 7;


    static function randomCapsString($length)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randstring = '';
        for ($i = 0; $i < $length; $i++) {
            $randstring = $characters[rand(0, strlen($characters))];
        }
        return $randstring;
    }

//    const FIRST_GENERATION = "firstGeneration";
//    const SECOND_GENERATION = "secondGeneration";
//    const THIRD_GENERATION = "thirdGeneration";
//    const FORTH_GENERATION = "forthGeneration";
//    const FIFTH_GENERATION = "fifthGeneration";
//    const SIXTH_GENERATION = "sixthGeneration";
//    const SEVENTH_GENERATION = "seventhGeneration";
//    const MONTHLY_RANKING = "monthlyRanking";


}
