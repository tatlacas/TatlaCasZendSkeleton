<?php
/**
 * Created by PhpStorm.
 * User: alois - Email : mumeraalois@gmail.com
 * Date: 9/13/2015
 * Time: 4:52 PM
 */

namespace Mobile\Model;


use Application\Entity\SubscribedPackages;
use Application\Entity\UserDependents;
use Application\Entity\UserRelationshipTypes;
use Application\Model\Constants;
use Application\Model\DoctrineInitialization;
use Doctrine\ORM\NoResultException;

class UserDependants  extends DoctrineInitialization
{

    function __construct($service_locator)
    {
        parent::__construct($service_locator);

    }
    public function addDependant($request){
        $this->setEntityManager();

        //  $request = true;
        if ($request !== NULL) {
            $request = json_decode($request, true);
            $id = $request['id'];
            $firstName = $request['firstName'];
            $lastName = $request['lastName'];
            $rel = $request['relation'];
            $idnmbr = $request['idNumber'];
            $dt_birth = $request['dateOfBirth'];
            $serverId = $request['serverId'];
            $phone_number = $request['userId'];
            $gender = $request['gender'];
            $joined_at = $request['joinedAt'];

            if (strlen($phone_number) <= Constants::PHONE_NUMBER_LENGTH) {
                $phone_number = htmlspecialchars($phone_number);

                $user= $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($phone_number);

                if ($user !== null) {
                    //todo use also selection with user id
                    $result = $this->entity_manager->getRepository(Constants::ENTITY_USERDEPENDENTS)->findOneById($serverId);
                    if ($result !== null) {
//                    $result = new UserDependents();

                        //Dependant exist
                        return $this->onInsertResults(Constants::ON_SUCCESS_CONST,$result->getId(),$id);

                    }

                    if($gender === Constants::MALE){
                        $sex = true;
                    }else{
                        $sex = false;
                    }
                    $user_rel = $this->entity_manager->getRepository(Constants::ENTITY_USER_RELATION_TYPES)->findOneById($rel);
                    if ($user_rel == null) {
                        /// $rel = new UserRelationshipTypes();
                        //  $rel->getDescription();
                        //relation not set
                        return $this->onInsertResults(Constants::ON_FAILURE_CONST,Constants::BUNDLE_ZERO,$id);
                    }

                    $dependant = new UserDependents();
                    $dependant->setUser($user)
                        ->setFirstName($firstName)
                        ->setLastName($lastName)
                        ->setIdNumber($idnmbr)
                        ->setDateOfBirth($dt_birth)
                        ->setRelationType($user_rel)
                        ->setJoinedAt($joined_at)
                        ->setGender($sex);
                    $this->entity_manager->persist($dependant);
                    if($rel >= Constants::INT_BUNDLE_THREE){
                        $planId = Constants::ADITIONAL_DEP_PACKAGE_ID;
                    }else{
                        $planId = Constants::INT_BUNDLE_FOUR;
                    }
                    $package_plan = $this->entity_manager->getRepository(Constants::ENTITY_PACKAGE_PLANS)->findOneById($planId);
                    if ($package_plan == null) {
                        return $this->return_results(Constants::PLAN_NOT_SET);
                    }
                    // $user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByIdNumber($user_id);
                    $package = new SubscribedPackages();
                    $mil = (int)$joined_at;
                    $seconds = $mil / 1000;
                    $dt = date("Y-m-d 00:00:00", $seconds);
                    $date = new \DateTime($dt);


                    $package->setDateActivated($date);
                    $package->setUser($user);
                    $package->setPackagePlan($package_plan);
                    $package->setIsDependent(true);
                    $package->setDependent($dependant);
                    $package->setStatus(true);
                    $this->entity_manager->persist($package);

                    $this->entity_manager->flush();
                    $resulting_id = $dependant->getId();


                    return $this->onInsertResults(Constants::ON_SUCCESS_CONST,$resulting_id,$id);


                }else{
                    //user does not exist
                    return $this->onInsertResults(Constants::ON_FAILURE_CONST,Constants::BUNDLE_ZERO,$id);
                }

            }else{
                //wrong number
                return $this->onInsertResults(Constants::ON_FAILURE_CONST,Constants::BUNDLE_ZERO,$id);
            }

        }else{
            //no data set
            return $this->onInsertResults(Constants::ON_FAILURE_CONST,Constants::BUNDLE_ZERO,'');
        }

    }
    public function onInsertResults($results, $resulting_id,$id)
    {
        $xml_output = "<?xml version=\"1.0\"?>\n";
        $xml_output .= "<entries>\n";
        $xml_output .= "\t<entry>\n";
        // $xml_output .= "\t\t<resultingId>" . $resulting_id . "</resultingId>\n";
        // $xml_output .= "\t\t<policyNumber>" . $policy_number . "</policyNumber>\n";
        $xml_output .= "\t\t<result>" . $results.'-'.$resulting_id.'-'.$id. "</result>\n";

        $xml_output .= "\t</entry>\n";
        $xml_output .= "</entries>";
        return $xml_output;
    }

    public function returnAllDepsdata($request)
    {

        $this->setEntityManager();
        $util = new Utils();
        //
        //  $request = true;
        if ($request != NULL) {
            $request = json_decode($request,true);
            $ret_dep_count = 0;
            $phone_number = $request['phoneNumber'];
            $state = $request['state'];
            if ($state === 1) {
                $ret_dep_count = $request['ret_dep_count'];
            }

            if (strlen($phone_number) <= Constants::PHONE_NUMBER_LENGTH) {

                $phone_number = htmlspecialchars($phone_number);
                $user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($phone_number);
                if ($user == null) {
                    //no user
                    return $util->return_results(Constants::ON_FAILURE_CONST);
                }

                $query = $this->entity_manager->createQueryBuilder();
                $query->select('count(d.user)')
                    ->from('Application\Entity\UserDependents', 'd')
                    ->where('d.user = ?1')
                    ->setParameter(1, $user);

                $count = 0;
                try{
                    $count = $query->getQuery()->getSingleScalarResult();
                }
                catch(NoResultException $e) {

                }
                // return $this->return_results($count);
                if($count > 0) {

//                $query->select('d')
//                    ->from('Application\Entity\UserDependents', 'd')
//                    ->where('d.user_id = ?1')
//                    ->setParameter(1, $user->getUserId());
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
                        $xml_output = "<?xml version=\"1.0\"?>\n";
                        $xml_output .= "<entries>\n";
                        foreach ($user_deps as $package) {
                            if($package->getStatus()){
                                $state = 1;
                                //   $package =  new SubscribedPackages();
                                if ($package->getIsDependent()) {
                                    $dependant = $package->getDependent();
                                    // $dependant = new UserDependents();

                                    if ($dependant->getGender()) {
                                        $gender = 1;
                                    } else {
                                        //female
                                        $gender = 2;
                                    }
                                    $dep_relation = $dependant->getRelationType();
                                    //  $dep_relation = new UserRelationshipTypes();

                                    $rel = $dep_relation->getId();
                                    if($rel > Constants::ADDITIONAL_DEPENDANT_INT){
                                        $user_Fpayments = $this->entity_manager->getRepository(Constants::ENTITY_USER_PAYMENTS)->findBy(array('subscribedPackage' => $package), array('monthPaidFor' => 'ASC'), 1);
                                        if ($user_Fpayments != null) {
                                            $isActive = Constants::ACTIVE;
                                           // $package = new SubscribedPackages();
                                            //  $timestamp   = $payment->getDatePaid();
                                            $activatedAt = $package->getDateActivated();
                                            $timeMillis = $activatedAt->getTimestamp();
                                            $activatedAt = $timeMillis*1000;//$date_paid->format('Y-m-d H:i:s');
                                            // }
                                        } else {
                                            $isActive = Constants::NOT_ACTIVE; //not active
                                            date_default_timezone_set('UTC');
                                            $date = new \DateTime("now");
                                            $activatedAt = $date->getTimestamp()*1000;
                                           // $activatedAt = $user->getCreatedAt();
                                        }
                                    }else{
                                        $isActive = Constants::ACTIVE;
                                        $activatedAt =  $dependant->getJoinedAt();
                                    }

                                    $xml_output .= "\t<entry>\n";
                                    $xml_output .= "\t\t<firstName>" . $dependant->getFirstName() . "</firstName>\n";
                                    $xml_output .= "\t\t<lastName>" . $dependant->getLastName() . "</lastName>\n";
                                    $xml_output .= "\t\t<gender>" . $gender . "</gender>\n";
                                    $xml_output .= "\t\t<id>" . $dependant->getId() . "</id>\n";
                                    $xml_output .= "\t\t<joinedAt>" . $dependant->getJoinedAt() . "</joinedAt>\n";
                                    $xml_output .= "\t\t<ActivatedAt>" . $activatedAt . "</ActivatedAt>\n";
                                    $xml_output .= "\t\t<isActive>" . $isActive . "</isActive>\n";
                                    $id_number = $dependant->getIdNumber();
                                    if (isset($id_number)) {
                                        $xml_output .= "\t\t<idNumber>" . $id_number . "</idNumber>\n";
                                    }

                                    $xml_output .= "\t\t<dateOfBirth>" . $dependant->getDateOfBirth() . "</dateOfBirth>\n";
                                    $xml_output .= "\t\t<depRelation>" . $dep_relation->getId() . "</depRelation>\n";
                                    $xml_output .= "\t\t<result>" . Constants::ON_SUCCESS_CONST . "</result>\n";
                                    $xml_output .= "\t\t<count>" . $count . "</count>\n";
                                    $xml_output .= "\t</entry>\n";
                                }
                            }
                        }
                        if($state == 0){
                            $xml_output .= "\t\t<result>" .  Constants::ON_FAILURE_CONST. "</result>\n";
                        }
                        $xml_output .= "</entries>";
                        return $xml_output;

                    }else{
                        //do deps
                        return $util->return_results(Constants::ON_FAILURE_CONST);
                    }
                }else{
                    //do deps
                    return $util->return_results(Constants::ON_FAILURE_CONST);
                }

            }else{
                //wrong number
                return $util->return_results(Constants::ON_FAILURE_CONST);
            }

        }else{
            //no data set
            return $util->return_results(Constants::ON_FAILURE_CONST);
        }

    }

    public function delete_dependents($del_id, $phone_number, $rel){
        $this->setEntityManager();
        $util = new Utils();
        $str_length = 13;
        $db = new DBUtils($this->service_locator);

        if(strlen($phone_number) <= Constants::PHONE_NUMBER_LENGTH) {
            $phone_number = htmlspecialchars($phone_number);
            $user = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($phone_number);// "SELECT * FROM users WHERE phone_number = '".$phone_number."'";
            if ($user == null) {
                //no user
                return $util->return_results(Constants::ON_FAILURE_CONST);
            }
            $dependant = $this->entity_manager->getRepository(Constants::ENTITY_USERDEPENDENTS)->findOneById($del_id);// "SELECT * FROM users WHERE phone_number = '".$phone_number."'";
            if ($dependant == null) {
                //no user
                return $util->return_results(Constants::ON_FAILURE_CONST);
            }
            //$dep = new UserDependents();

            $rel_type = $dependant->getRelationType();
            //  $rel_type = new UserRelationshipTypes();

            if($dependant->getGender()){
                $gender = Constants::MALE_STR;
            }else {
                //female
                $gender = Constants::FEMALE_STR;
            }
            $res = $db->save_individual_client_messages(Constants::ADDITIONAL_DEPENDANT_REMOVED,
                $rel_type->getDescription().' : '.$dependant->getFirstName().' '.$dependant->getLastName().', '.$gender.' '.
                Constants::WAS_REMOVED
                , $phone_number);

            $query = $this->entity_manager->createQueryBuilder();
//            $query->delete('Application\Entity\UserDependents','d')
//                ->where('d.id = ?1')
//                ->andWhere('d.user = :uid')
//                ->setParameters(array(
//                    1 => $del_id,
//                    'uid' => $user->getUserId(),
//                ));//1, $rel);//$user->getUserId()

//todo update query
//            $q = $qb->update('models\User', 'u')
//                ->set('u.username', $qb->expr()->literal($username))
//                ->set('u.email', $qb->expr()->literal($email))
//                ->where('u.id = ?1')
//                ->setParameter(1, $editId)
//                ->getQuery();
//            $p = $q->execute();

//            $query = $query->getQuery();
//            $result = $query->getResult();

            $subscribed_packages = $this->entity_manager->getRepository(Constants::ENTITY_SUBSCRIBED_PACKAGES)->findOneByDependent($dependant);
            if ($subscribed_packages == null) {
                //no subscribed dep
                return $this->return_results(Constants::ON_FAILURE_CONST);
            }
            date_default_timezone_set('UTC');
            $date = new \DateTime("now");
            $result = $date->format('Y-m-d 00:00:00');
            $date = new \DateTime($result);
            // $subscribed_packages = new SubscribedPackages();
            $subscribed_packages->setDateDeactivated($date);
            $subscribed_packages->setStatus(false);

            $this->entity_manager->flush();
            //if($result != null){
            //deleted dependents
            return $util->return_results(Constants::ON_SUCCESS_CONST);
            //  }else{
            //not deleted
            //    return $util->return_results(Constants::ON_FAILURE_CONST);
            // }

        }else {
            //Wrong Number
            //todo return the right const
            return $util->return_results(Constants::ON_FAILURE_CONST);
        }

    }

    public function return_results($value){
        $xml_output = "<?xml version=\"1.0\"?>\n";
        $xml_output .= "<entries>\n";
        $xml_output .= "\t<entry>\n";
        $xml_output .= "\t\t<result>" . $value. "</result>\n";
        $xml_output .= "\t</entry>\n";
        $xml_output .= "</entries>";
        return $xml_output;
    }


}