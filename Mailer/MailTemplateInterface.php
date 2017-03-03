<?php
namespace ITF\TwigMailerBundle\Mailer;

interface MailTemplateInterface
{
    public function getMessage();
    public function getSubject();
}