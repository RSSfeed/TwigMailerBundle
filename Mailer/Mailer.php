<?php
namespace ITF\TwigMailerBundle\Mailer;

use ITF\TwigMailerBundle\Entity\Mail;
use ITF\TwigMailerBundle\Entity\MailTemplate;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Mailer
{
    /** @var string */
    private $mail_template_id;
    
    /** @var ContainerInterface */
    private $container;
    
    /** @var string */
    private $sender_mail;
    
    /** @var string */
    private $mail_layout;
    
    public function __construct(ContainerInterface $container)
    {
        $this->container    = $container;
        $config             = $container->getParameter('itf_twigmailer');
        $this->sender_mail  = $config['sender_mail'];
        $this->mail_layout  = $config['layout'];
    }
    
    public function send(Mail $mail, $email = NULL)
    {
        $em = $this->container->get('doctrine')->getManager();
        
        // if template and id not set
        if ($mail->getMailTemplate() === NULL && $this->getMailTemplateId() === NULL) {
            throw new \Exception("you have to set a mail template");
        } elseif ($mail->getMailTemplate() === NULL && $this->getMailTemplateId() !== NULL) {
            // set mail template
            $mailTemplate = $em->getRepository('ITFTwigMailerBundle:MailTemplate')->findOneBy(array(
                'identifier' => $this->getMailTemplateId()
            ));
            
            if ($mailTemplate === NULL) {
                throw new \Exception(sprintf('mail template with identifier "%s" not found', $this->getMailTemplateId()));
            }
            
            $mail->setMailTemplate($mailTemplate);
        }
        
        // init
        $twig = $this->container->get('twig');
        $swiftMailer = $this->container->get('mailer');
        $mailTemplate = $mail->getMailTemplate();
        $sendingAddress = $email !== NULL ? $email : $mail->getUser()->getEmail();
        
        /** @var \Twig_Template $twigTemplate */
        $twigString = NULL;
        if ($this->getMailLayout() !== NULL) {
            $twigString .= "{% extends '".$this->getMailLayout()."' %}{% block body %}";
        }
        $twigString .= $mailTemplate->getMessage();
        if ($this->getMailLayout() !== NULL) {
            $twigString .= "{% endblock %}";
        }
        
        if (strlen($twigString) == 0) {
            throw new \Exception("Twig template may not be empty");
        }
        
        $twigTemplate = $twig->createTemplate($twigString);
        $html = $twigTemplate->render($mail->getParams());
        
        // render only message
        $twigTemplateMessage = $twig->createTemplate($mailTemplate->getMessage());
        $htmlOnlyMessage = $twigTemplateMessage->render($mail->getParams());
        
        // prepare swiftmail
        $swiftMail = new \Swift_Message();
        $swiftMail
            ->setTo($sendingAddress)
            ->setFrom($this->getSenderMail())
            ->setSubject($mailTemplate->getSubject())
            ->setBody($html, 'text/html')
        ;
        
        // send
        $swiftMailer->send($swiftMail);
        
        // save sent
        $mail->setSentAt(new \DateTime('now'));
        $mail->setContent($htmlOnlyMessage);
        $em->persist($mail);
        $em->flush();
    }
    
    /**
     * @return Mail
     */
    public function createMail()
    {
       return new Mail();
    }
    
    /**
     * @return MailTemplate|NULL
     */
    public function getMailTemplates()
    {
        return $this->container->get('doctrine')->getManager()->getRepository('ITFTwigMailerBundle:MailTemplate')->findAll();
    }
    
    /**
     * @return string
     */
    public function getMailTemplateId()
    {
        return $this->mail_template_id;
    }
    
    /**
     * @param string $mail_template_id
     *
     * @return Mailer
     */
    public function setMailTemplateId($mail_template_id)
    {
        $this->mail_template_id = $mail_template_id;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getSenderMail()
    {
        return $this->sender_mail;
    }
    
    /**
     * @param string $sender_mail
     *
     * @return Mailer
     */
    public function setSenderMail($sender_mail)
    {
        $this->sender_mail = $sender_mail;
        
        return $this;
    }
    
    /**
     * @return string
     */
    public function getMailLayout()
    {
        return $this->mail_layout;
    }
    
    /**
     * @param string $mail_layout
     *
     * @return Mailer
     */
    public function setMailLayout($mail_layout)
    {
        $this->mail_layout = $mail_layout;
        
        return $this;
    }
    
    
}