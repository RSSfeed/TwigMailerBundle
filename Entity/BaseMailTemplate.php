<?php
namespace ITF\TwigMailerBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use ITF\TwigMailerBundle\Mailer\MailInterface;
use ITF\TwigMailerBundle\Mailer\MailTemplateInterface;

/**
 */
abstract class BaseMailTemplate implements MailTemplateInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", unique=true, length=50, nullable=false)
     */
    protected $identifier;
    
    /**
     * @ORM\Column(type="string", length=250, nullable=true)
     */
    protected $subject;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $message;
    
    /**
     * ORM\OneToMany(targetEntity="ITF\TwigMailerBundle\Entity\Mail", mappedBy="mailTemplate")
     * @var MailInterface
     */
    protected $mail;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->mail = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Set identifier
     *
     * @param string $identifier
     * @return MailTemplate
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
        
        return $this;
    }
    
    /**
     * Get identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }
    
    /**
     * Set subject
     *
     * @param string $subject
     * @return MailTemplate
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        
        return $this;
    }
    
    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }
    
    /**
     * Set message
     *
     * @param string $message
     * @return MailTemplate
     */
    public function setMessage($message)
    {
        $this->message = $message;
        
        return $this;
    }
    
    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
    
    /**
     * Add mail
     *
     * @param MailInterface $mail
     * @return MailTemplate
     */
    public function addMail(MailInterface $mail)
    {
        $this->mail[] = $mail;
        
        return $this;
    }
    
    /**
     * Remove mail
     *
     * @param MailInterface $mail
     */
    public function removeMail(MailInterface $mail)
    {
        $this->mail->removeElement($mail);
    }
    
    /**
     * Get mail
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMail()
    {
        return $this->mail;
    }
    
    public function __toString()
    {
        return (string) $this->getIdentifier();
    }
}
