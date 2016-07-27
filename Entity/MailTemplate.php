<?php
namespace ITF\TwigMailerBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="mail_templates")
 */
class MailTemplate
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true, length=50, nullable=true)
     */
    private $identifier;

    /**
     * @ORM\Column(type="string", length=250, nullable=true)
     * 
     */
    private $subject;

    /**
     * @ORM\Column(type="text", nullable=true)
     * 
     */
    private $message;

    /**
     * @ORM\OneToMany(targetEntity="ITF\TwigMailerBundle\Entity\Mail", mappedBy="mailTemplate")
     */
    private $mail;

    /**
     * 
     */
    private $locale;

    public function setTranslatableLocale($locale)
    {
        $this->locale = $locale;
    }
    
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
     * @param \AppBundle\Entity\Mail $mail
     * @return MailTemplate
     */
    public function addMail(\AppBundle\Entity\Mail $mail)
    {
        $this->mail[] = $mail;

        return $this;
    }

    /**
     * Remove mail
     *
     * @param \AppBundle\Entity\Mail $mail
     */
    public function removeMail(\AppBundle\Entity\Mail $mail)
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
