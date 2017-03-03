<?php
namespace ITF\TwigMailerBundle\Mailer;

use Doctrine\ORM\EntityManager;

class Mailer
{
    /** @var string */
    private $mail_template_identifier;
    
    /**
     * @var EntityManager
     */
    private $entityManager;
    
    /**
     * @var \Twig_Environment
     */
    private $twig;
    
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    
    /**
     * @var array
     */
    private $config;
    
    public function __construct(EntityManager $entityManager, \Twig_Environment $twig, \Swift_Mailer $mailer, $config = array())
    {
        $this->entityManager    = $entityManager;
        $this->twig             = $twig;
        $this->mailer           = $mailer;
        $this->config           = $config;
    }
    
    /**
     * @param MailInterface $mail
     * @param string|null $useCustomEmail - custom email to send message
     * @throws \Exception
     */
    public function send(MailInterface $mail, $useCustomEmail = null)
    {
        if ($mail->getMailTemplate() === NULL) {
            if ($this->getMailTemplateIdentifier() === NULL) {
                throw new \Exception("you have to set a mail template");
            }
            
            /** @var MailTemplateInterface $mailTemplate */
            $mailTemplate = $this->entityManager->getRepository($this->getMailTemplateEntityFQN())->findOneBy(array(
                'identifier' => $this->getMailTemplateIdentifier()
            ));
            
            if ($mailTemplate === NULL) {
                throw new \Exception(sprintf('mail template with identifier "%s" not found', $this->getMailTemplateIdentifier()));
            }
            
            $mail->setMailTemplate($mailTemplate);
        }
        
        // init
        $mailTemplate = $mail->getMailTemplate();
        $user = $mail->getUser();
        $emailAddress = NULL;
        
        /** @var \Twig_Template $twigTemplate */
        $twigString = "
            {% extends '" . $this->config['layout'] . "' %}
            {% block body %}".$mailTemplate->getMessage()."{% endblock %}
        ";
        $twigTemplate = $this->twig->createTemplate($twigString);
        $html = $twigTemplate->render($mail->getParams());
        
        // render only message
        $twigTemplateMessage = $this->twig->createTemplate($mailTemplate->getMessage());
        $htmlOnlyMessage = $twigTemplateMessage->render($mail->getParams());
        
        if ($useCustomEmail !== NULl) {
            if (!filter_var($useCustomEmail, FILTER_VALIDATE_EMAIL)) {
                throw new \InvalidArgumentException('email address is not valid');
            }
            $emailAddress = $useCustomEmail;
        } elseif ($user !== NULL && method_exists($user, 'getEmail')) {
            $emailAddress = $user->getEmail();
        }
        
        if ($emailAddress === NULL) {
            throw new \Exception("cannot send email to an empty recipient");
        }
        
        if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception(sprintf("cannot send mail to invalid email address '%s'", $emailAddress));
        }
        
        // prepare swiftmail
        $swiftMail = new \Swift_Message();
        $swiftMail
            ->setTo($emailAddress)
            ->setFrom($this->config['sender'])
            ->setSubject($mailTemplate->getSubject())
            ->setBody($html, 'text/html')
        ;
        
        // send
        $this->mailer->send($swiftMail);
        
        // save sent
        $mail->setSentAt(new \DateTime('now'));
        $mail->setContent($htmlOnlyMessage);
        $mail->setEmail($emailAddress);
        
        $this->entityManager->persist($mail);
        $this->entityManager->flush();
    }
    
    
    
    /**
     * @return string
     */
    public function getMailTemplateIdentifier()
    {
        return $this->mail_template_identifier;
    }
    
    private function getMailTemplateEntityFQN()
    {
        return $this->config['mail_template_class'];
    }
    
    /**
     * @param string $identifier
     *
     * @return Mailer
     */
    public function setMailTemplateIdentifier($identifier)
    {
        $this->mail_template_identifier = $identifier;
        
        return $this;
    }
}
