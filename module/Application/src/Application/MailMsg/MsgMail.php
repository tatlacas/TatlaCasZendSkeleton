<?php

namespace Application\MailMsg;

use Application\MailMsg\Address;
use Application\MailMsg\Attachments;
use Zend\Mail\Message as MailMessage;
use Zend\Mail\Transport\Sendmail;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Mime as ZendMime;
use Zend\Mime\Part as MimePart;
/**
 * Created by PhpStorm.
 * User: alois - mumeraalois@gmail.com
 * Date: 10/21/2015
 * Time: 12:30 PM
 */
class MsgMail
{
    /**
     * @var \Zend\Mime\Part[]
     */
    private $attachments = array();

    /**
     * @var  Address[]
     */
    private $bcc = array();

    /**
     * @var  Address[]
     */
    private $cc = array();

    /**
     * @var  Address
     */
    private $from;

    /**
     * @var \Zend\Mime\Part
     */
    private $html;

    /**
     * @var  Address
     */
    private $replyTo;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var \Zend\Mime\Part
     */
    private $text;

    /**
     * @var  Address[]
     */
    private $to = array();

    /**
     * Adds an attachment.
     * @param  Attachments $mime
     * @return MsgMail
     */
    public function addAttachment( Attachments $mime)
    {
        $attachment = new MimePart($mime->getBinary());

        $attachment->type = ZendMime::TYPE_OCTETSTREAM;
        $attachment->disposition = ZendMime::DISPOSITION_ATTACHMENT;
        $attachment->encoding = ZendMime::ENCODING_BASE64;

        if ($mime->getFileName() !== null) {
            $attachment->filename = $mime->getFileName();
            $attachment->id = $mime->getFileName();
        }

        $this->attachments[] = $attachment;

        return $this;
    }

    /**
     * Adds a BCC destination to the message.
     * @param  Address $address
     * @return MsgMail
     */
    public function addBcc( Address $address)
    {
        $this->bcc[] = $address;
        return $this;
    }

    /**
     * Adds a CC destination to the message.
     * @param  Address $address
     * @return MsgMail
     */
    public function addCc( Address $address)
    {
        $this->cc[] = $address;
        return $this;
    }

    /**
     * Adds a "to" destination to the message.
     * @param  Address $address
     * @return MsgMail
     */
    public function addTo( Address $address)
    {
        $this->to[] = $address;
        return $this;
    }

    /**
     * Send the mail message.
     * @throws \UnexpectedValueException
     */
    public function send()
    {

        if (count($this->to) == 0 || ($this->html === null && $this->text === null)) {
            throw new \UnexpectedValueException('Missing a destination and/or message body.');
        }

        $zendMail = new MailMessage();

        foreach ($this->to as $destination) {
            $zendMail->addTo($destination->getEmailAddress(), $destination->getName());
        }

        foreach ($this->cc as $destination) {
            $zendMail->addCc($destination->getEmailAddress(), $destination->getName());
        }

        foreach ($this->bcc as $destination) {
            $zendMail->addBcc($destination->getEmailAddress(), $destination->getName());
        }

        if ($this->from !== null) {
            $zendMail->addFrom($this->from->getEmailAddress(), $this->from->getName());
        }

        if ($this->replyTo !== null) {
            $zendMail->setReplyTo($this->replyTo->getEmailAddress(), $this->replyTo->getName());
        }

        $zendMail->setSubject($this->subject);

        /*
         * MsgMail is sent out in two boundaries. The primary boundary is multipart/mixed, which supports the attachments
         * and secondary boundary; and the second boundary which supports multipart/alternative.
         */

        $bodyMessage = new MimeMessage();

        $multiPartContentMessage = new MimeMessage();

        if ($this->text !== null) {
            $multiPartContentMessage->addPart($this->text);
        }

        if ($this->html !== null) {
            $multiPartContentMessage->addPart($this->html);
        }

        $multiPartContentMimePart = new MimePart($multiPartContentMessage->generateMessage());

        $multiPartContentMimePart->type = 'multipart/alternative;' . PHP_EOL . ' boundary="' .
            $multiPartContentMessage->getMime()->boundary() . '"';

        $bodyMessage->addPart($multiPartContentMimePart);

        foreach ($this->attachments as $attachment) {
            $bodyMessage->addPart($attachment);
        }

        $zendMail->setBody($bodyMessage);

        $zendMail->setEncoding('UTF-8');

        $zendTransport = new Sendmail();

        $zendTransport->send($zendMail);
    }

    /**
     * Add a from
     * @param  Address $address
     * @return MsgMail
     */
    public function setFrom( Address $address)
    {
        $this->from = $address;
        return $this;
    }

    /**
     * Set the html part of the message.
     * @param string $html
     * @return MsgMail
     */
    public function setHtml($html)
    {
        $mime = new MimePart($html);
        $mime->type = ZendMime::TYPE_HTML;

        $this->html = $mime;

        return $this;
    }

    /**
     * Add a reply-to address to the message.
     * @param  Address $address
     * @return MsgMail
     */
    public function setReplyTo( Address $address)
    {
        $this->replyTo = $address;
        return $this;
    }

    /**
     * Add a subject to the message.
     * @param string $subject
     * @return MsgMail
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * Set the text part of the message.
     * @param string $text
     * @return MsgMail
     */
    public function setText($text)
    {
        $mime = new MimePart($text);
        $mime->type = ZendMime::TYPE_TEXT;

        $this->text = $mime;

        return $this;
    }
}