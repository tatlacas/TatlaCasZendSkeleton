<?php
/**
 * Created by PhpStorm.
 * User: tatlacas
 * Date: 19/11/2015
 * Time: 10:03 PM
 */

namespace Mobile\Model;


use Application\Entity\PendingPayments;
use Application\Model\Constants;
use Application\Model\DoctrineInitialization;

class UploadPendingPaymentToServer extends DoctrineInitialization
{
    function __construct($service_locator)
    {
        parent::__construct($service_locator);
    }

    public function uploadPendingPayment($field_a, $app_version)
    {
        $xml_output = "<?xml version=\"1.0\"?>\n";
        if ($field_a) {
            $this->setEntityManager();
            $json = json_decode($field_a, true);
            $amount = $json['amount'];
            $payingForPhone = $json['payingForPhone'];
            $payingForSelf = $json['payingForSelf'];
            $paymentType = $json['paymentType'];
            $includesJoin = $json['includesJoin'];
            $user_paid_for = $this->entity_manager->getRepository(Constants::ENTITY_USERS)->findOneByPhoneNumber($payingForPhone);
            $paymentTypeRecord =  $this->entity_manager->getRepository(Constants::ENTITY_PAYMENT_TYPES)->findOneById($paymentType);
            if ($user_paid_for && $paymentTypeRecord) {
                $pending_payment = new PendingPayments();
                $pending_payment->setAmount($amount)
                ->setDateUploaded(round(microtime(true)*1000))
                ->setIncludesJoiningFee($includesJoin)
                ->setUser($user_paid_for)
                ->setPaymentType($paymentTypeRecord);
                $this->entity_manager->persist($pending_payment);
                $this->entity_manager->flush();
                $xml_output .= "<Result>".Constants::ON_SUCCESS_CONST."</Result>";
            } else {
                $xml_output .= "<Result>".Constants::ON_FAILURE_CONST."</Result>";
            }
        }else  $xml_output .= "<Result>".Constants::ON_FAILURE_CONST."</Result>";

      return $xml_output;

    }

}