<?php
namespace ITF\TwigMailerBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use ITF\TwigMailerBundle\Mailer\MailInterface;
use ITF\TwigMailerBundle\Mailer\MailTemplateInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 *
 */
abstract class BaseMail implements MailInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $created_at;
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $sent_at;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $content;
    
    /**
     * @ORM\ManyToOne(targetEntity="ITF\TwigMailerBundle\Entity\MailTemplate", inversedBy="mail")
     * @ORM\JoinColumn(name="mail_template_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $mailTemplate;
    
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="mail")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    protected $user;
    
    /**
     * @ORM\Column(type="string", length=250, nullable=true)
     */
    protected $email;
    
    /** @var array */
    protected $params;
    
    /**
     * Constructor
     *
     * @param UserInterface $user
     */
    public function __construct(UserInterface $user = NULL)
    {
        $this->created_at = new \DateTime('now');
        $this->params = array();
        
        if ($user !== NULL) {
            $this->setUser($user);
        }
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
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return self
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
        
        return $this;
    }
    
    /**
     * Get created_at
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }
    
    /**
     * Set sent_at
     *
     * @param \DateTime $sentAt
     * @return self
     */
    public function setSentAt(\DateTime $sentAt)
    {
        $this->sent_at = $sentAt;
        
        return $this;
    }
    
    /**
     * Get sent_at
     *
     * @return \DateTime
     */
    public function getSentAt()
    {
        return $this->sent_at;
    }
    
    
    /**
     * Get recipients
     *
     * @return array
     */
    public function getRecipients()
    {
        $recipients = array();
        
        if ($this->getUser() !== NULL) {
            foreach($this->getUser() as $user) {
                $recipients[] = $user->getEmail();
            }
        }
        
        return $recipients;
    }
    
    /**
     * Set mailTemplate
     *
     * @param MailTemplateInterface $mailTemplate
     * @return self
     */
    public function setMailTemplate(MailTemplateInterface $mailTemplate = NULL)
    {
        $this->mailTemplate = $mailTemplate;
        
        return $this;
    }
    
    /**
     * Get mailTemplate
     *
     * @return MailTemplateInterface
     */
    public function getMailTemplate()
    {
        return $this->mailTemplate;
    }
    
    
    /**
     * @return array
     */
    public function getParams()
    {
        $params = $this->params;
        if ($params === NULL) {
            $params = array();
        }
        
        if (!isset($params['user'])) {
            $params['user'] = $this->getUser();
        }
        
        return $params;
    }
    
    /**
     * @param array $params
     *
     * @return self
     */
    public function setParams($params)
    {
        $this->params = $params;
        
        return $this;
    }
    
    /**
     * @param $attr
     * @param $value
     *
     * @return $this
     */
    public function addParam($attr, $value)
    {
        $this->params[$attr] = $value;
        
        return $this;
    }
    
    /**
     * Set user
     *
     * @param UserInterface $user
     * @return self
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;
        
        return $this;
    }
    
    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
    
    /**
     * Set content
     *
     * @param string $content
     * @return self
     */
    public function setContent($content)
    {
        $this->content = $content;
        
        return $this;
    }
    
    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }
    
    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }
    
    /**
     * @param mixed $email
     *
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;
        
        return $this;
    }
}
