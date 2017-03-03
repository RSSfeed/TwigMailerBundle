<?php
namespace ITF\TwigMailerBundle\Mailer;

interface MailInterface
{
    public function setMailTemplate(MailTemplateInterface $mailTemplate);
    public function getMailTemplate();
    public function getUser();
    public function getParams();
    public function setSentAt(\DateTime $sentAt);
    public function setContent($content);
    public function setEmail($email);
}