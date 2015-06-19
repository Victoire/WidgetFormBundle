<?php
namespace Victoire\Widget\FormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Question
 *
 * @ORM\Table("vic_widget_form_question")
 * @ORM\Entity
 */
class WidgetFormQuestion
{
    public function __construct()
    {
        $this->proposal = array();
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="regex", type="string", length=510, nullable=true)
     */
    private $regex;

    /**
     * @var string
     *
     * @ORM\Column(name="regexTitle", type="string", length=255, nullable=true)
     */
    private $regexTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="position", type="integer", nullable=true)
     */
    private $position;

    /**
     * @var string
     *
     * @ORM\Column(name="prefix", type="string", nullable=true)
     */
    private $prefix;

    /**
     * @var boolean
     *
     * @ORM\Column(name="required", type="boolean", nullable=true)
     */
    private $required;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="Victoire\Widget\FormBundle\Entity\WidgetForm", inversedBy="questions", cascade={"persist"})
     *
     */
    private $form;

    /**
     * @var string
     *
     * @ORM\Column(name="proposal", type="string", length=255, options={"default": "N;"})
     */
    protected $proposal = null;

    /**
     * @var boolean
     *
     * @ORM\Column(name="proposal_expanded", type="boolean", nullable=true)
     */
    protected $proposalExpanded = null;

    /**
     * @var boolean
     *
     * @ORM\Column(name="proposal_inline", type="boolean", nullable=true)
     */
    protected $proposalInline = null;

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
     * Set title
     *
     * @param  string   $title
     * @return Question
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set required
     *
     * @param  boolean  $required
     * @return Question
     */
    public function setRequired($required)
    {
        $this->required = $required;

        return $this;
    }

    /**
     * Get required
     *
     * @return boolean
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * Set type
     *
     * @param  string   $type
     * @return Question
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set position
     *
     * @param  integer  $position
     * @return Question
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Get prefix
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Set prefix
     *
     * @param string $prefix
     *
     * @return $this
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * Set proposal
     *
     * @param  string   $proposal
     * @return Question
     */
    public function setProposal($proposal)
    {
        $this->proposal = $proposal;

        return $this;
    }

    /**
     * Get proposal
     *
     * @return string
     */
    public function getProposal()
    {
        return $this->proposal;
    }

    /**
     * Set form
     *
     * @param  \Victoire\Widget\FormBundle\Entity\WidgetForm $form
     * @return Question
     */
    public function setForm(\Victoire\Widget\FormBundle\Entity\WidgetForm $form = null)
    {
        $this->form = $form;

        return $this;
    }

    /**
     * Get form
     *
     * @return \Victoire\Widget\FormBundle\Entity\WidgetForm
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Get proposalExpanded
     *
     * @return string
     */
    public function getProposalExpanded()
    {
        return $this->proposalExpanded;
    }

    /**
     * Set proposalExpanded
     * @param string $proposalExpanded
     *
     * @return $this
     */
    public function setProposalExpanded($proposalExpanded)
    {
        $this->proposalExpanded = $proposalExpanded;

        return $this;
    }

    /**
     * Get proposalInline
     *
     * @return string
     */
    public function getProposalInline()
    {
        return $this->proposalInline;
    }

    /**
     * Set proposalInline
     * @param string $proposalInline
     *
     * @return $this
     */
    public function setProposalInline($proposalInline)
    {
        $this->proposalInline = $proposalInline;

        return $this;
    }
    /**
     * Get regex
     *
     * @return string
     */
    public function getRegex()
    {
        return $this->regex;
    }

    /**
     * Set regex
     *
     * @param string $regex
     *
     * @return $this
     */
    public function setRegex($regex)
    {
        $this->regex = $regex;

        return $this;
    }
    /**
     * Get regexTitle
     *
     * @return string
     */
    public function getRegexTitle()
    {
        return $this->regexTitle;
    }

    /**
     * Set regexTitle
     *
     * @param string $regexTitle
     *
     * @return $this
     */
    public function setRegexTitle($regexTitle)
    {
        $this->regexTitle = $regexTitle;

        return $this;
    }
}
