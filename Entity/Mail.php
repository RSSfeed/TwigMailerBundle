<?php
namespace ITF\TwigMailerBundle\Entity;
use AppBundle\Entity\User;
use Doctrine\ORM\Mapping AS ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="mails")
 */
class Mail
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $sent_at;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="ITF\TwigMailerBundle\Entity\MailTemplate", inversedBy="mail")
     * @ORM\JoinColumn(name="mail_template_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $mailTemplate;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="mail")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    private $user;

    /** @var array */
    private $params;
    
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
     * @return Mail
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
     * @return Mail
     */
    public function setSentAt($sentAt)
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
                /** @var User $user */
                $recipients[] = $user->getEmail();
            }
        }

        return $recipients;
    }

    /**
     * Set mailTemplate
     *
     * @param MailTemplate $mailTemplate
     * @return Mail
     */
    public function setMailTemplate(MailTemplate $mailTemplate)
    {
        $this->mailTemplate = $mailTemplate;

        return $this;
    }

    /**
     * Get mailTemplate
     *
     * @return MailTemplate
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

        $params['user'] = $this->getUser();

        return $params;
    }

    /**
     * @param array $params
     *
     * @return Mail
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

    public function adminSetFieldsList()
    {
        return array(
            '#' => 'id',
            'Sent' => 'sent_at',
            'Template' => 'mailTemplate.identifier',
            'E-Mail' => 'user.email'
        );
    }

    /**
     * Set user
     *
     * @param UserInterface $user
     * @return Mail
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
     * @return Mail
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
}
