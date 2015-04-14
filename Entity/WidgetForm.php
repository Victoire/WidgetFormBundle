<?php
namespace Victoire\Widget\FormBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Victoire\Bundle\WidgetBundle\Entity\Widget;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * WidgetForm
 *
 * @ORM\Table("vic_widget_form")
 * @ORM\Entity
 */
class WidgetForm extends Widget
{
    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="\Victoire\Bundle\MediaBundle\Entity\Media")
     */
    protected $attachmentUrl;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="\Victoire\Bundle\MediaBundle\Entity\Media")
     */
    protected $attachmentUrl2;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="\Victoire\Bundle\MediaBundle\Entity\Media")
     */
    protected $attachmentUrl3;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="\Victoire\Bundle\MediaBundle\Entity\Media")
     */
    protected $attachmentUrl4;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="\Victoire\Bundle\MediaBundle\Entity\Media")
     */
    protected $attachmentUrl5;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="\Victoire\Bundle\MediaBundle\Entity\Media")
     */
    protected $attachmentUrl6;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="\Victoire\Bundle\MediaBundle\Entity\Media")
     */
    protected $attachmentUrl7;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255, nullable=true)
     */
    protected $subject;

    /**
     * @var string
     *
     * @ORM\Column(name="targetEmail", type="string", length=255, nullable=true)
     */
    protected $targetEmail;

    /**
     * @var boolean
     *
     * @ORM\Column(name="autoAnswer", type="boolean", nullable=true)
     */
    protected $autoAnswer;

    /**
     * @var text
     *
     * @ORM\Column(name="message", type="text", nullable=true)
     */
    protected $message;

    /**
     * @var text
     *
     * @ORM\Column(name="submit_icon", type="text", nullable=true, options={"default"="fa-location-arrow"})
     */
    protected $submitIcon;

    /**
     * @var text
     *
     * @ORM\Column(name="submit_label", type="string", length=255, nullable=true)
     */
    protected $submitLabel;

    /**
     * @ORM\OneToMany(targetEntity="Victoire\Widget\FormBundle\Entity\WidgetFormQuestion", mappedBy="form", cascade={"persist", "remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $questions;

    /**
     * constructor
     */
    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->submitIcon = "fa-location-arrow";
    }

    /**
     * To String function
     * Used in render choices type (Especially in VictoireWidgetRenderBundle)
     *
     * @return String
     */
    public function __toString()
    {
        return 'Form #'.$this->id.' - '.$this->title;
    }

    /**
     * Set title
     *
     * @param string $title
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
     * Set subject
     *
     * @param string $subject
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
     * Set targetEmail
     *
     * @param string $targetEmail
     */
    public function setTargetemail($targetEmail)
    {
        $this->targetEmail = $targetEmail;

        return $this;
    }

    /**
     * Get targetEmail
     *
     * @return string
     */
    public function getTargetemail()
    {
        return $this->targetEmail;
    }

    /**
     * Set autoAnswer
     *
     * @param string $autoAnswer
     */
    public function setAutoanswer($autoAnswer)
    {
        $this->autoAnswer = $autoAnswer;

        return $this;
    }

    /**
     * Get autoAnswer
     *
     * @return string
     */
    public function getAutoanswer()
    {
        return $this->autoAnswer;
    }

    /**
     * Set captcha
     *
     * @param string $captcha
     */
    public function setCaptcha($captcha)
    {
        $this->captcha = $captcha;

        return $this;
    }

    /**
     * Get captcha
     *
     * @return string
     */
    public function getCaptcha()
    {
        return $this->captcha;
    }

    /**
     * Set message
     *
     * @param string $message
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
     * Add questions
     *
     * @param  \Victoire\Widget\FormBundle\Entity\WidgetFormQuestion $questions
     * @return WidgetFormSlot
     */
    public function addQuestion(\Victoire\Widget\FormBundle\Entity\WidgetFormQuestion $question)
    {
        $question->setForm($this);
        $this->questions->add($question);

        return $this;
    }

    /**
     * Remove questions
     *
     * @param \Victoire\Widget\FormBundle\Entity\WidgetFormQuestion $questions
     */
    public function removeQuestion(\Victoire\Widget\FormBundle\Entity\WidgetFormQuestion $questions)
    {
        $this->questions->removeElement($questions);
    }

    /**
     * Get questions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * Set attachmentUrl
     *
     * @param  \Victoire\Bundle\MediaBundle\Entity\Media $attachmentUrl
     * @return WidgetFormSlot
     */
    public function setAttachmentUrl(\Victoire\Bundle\MediaBundle\Entity\Media $attachmentUrl = null)
    {
        $this->attachmentUrl = $attachmentUrl;

        return $this;
    }

    /**
     * Get attachmentUrl
     *
     * @return \Victoire\Bundle\MediaBundle\Entity\Media
     */
    public function getAttachmentUrl()
    {
        return $this->attachmentUrl;
    }

    /**
     * Set attachmentUrl2
     *
     * @param  \Victoire\Bundle\MediaBundle\Entity\Media $attachmentUrl2
     * @return WidgetFormSlot
     */
    public function setAttachmentUrl2(\Victoire\Bundle\MediaBundle\Entity\Media $attachmentUrl2 = null)
    {
        $this->attachmentUrl2 = $attachmentUrl2;

        return $this;
    }

    /**
     * Get attachmentUrl2
     *
     * @return \Victoire\Bundle\MediaBundle\Entity\Media
     */
    public function getAttachmentUrl2()
    {
        return $this->attachmentUrl2;
    }

    /**
     * Set attachmentUrl3
     *
     * @param  \Victoire\Bundle\MediaBundle\Entity\Media $attachmentUrl3
     * @return WidgetFormSlot
     */
    public function setAttachmentUrl3(\Victoire\Bundle\MediaBundle\Entity\Media $attachmentUrl3 = null)
    {
        $this->attachmentUrl3 = $attachmentUrl3;

        return $this;
    }

    /**
     * Get attachmentUrl3
     *
     * @return \Victoire\Bundle\MediaBundle\Entity\Media
     */
    public function getAttachmentUrl3()
    {
        return $this->attachmentUrl3;
    }

    /**
     * Set attachmentUrl4
     *
     * @param  \Victoire\Bundle\MediaBundle\Entity\Media $attachmentUrl4
     * @return WidgetFormSlot
     */
    public function setAttachmentUrl4(\Victoire\Bundle\MediaBundle\Entity\Media $attachmentUrl4 = null)
    {
        $this->attachmentUrl4 = $attachmentUrl4;

        return $this;
    }

    /**
     * Get attachmentUrl4
     *
     * @return \Victoire\Bundle\MediaBundle\Entity\Media
     */
    public function getAttachmentUrl4()
    {
        return $this->attachmentUrl4;
    }

    /**
     * Set attachmentUrl5
     *
     * @param  \Victoire\Bundle\MediaBundle\Entity\Media $attachmentUrl5
     * @return WidgetFormSlot
     */
    public function setAttachmentUrl5(\Victoire\Bundle\MediaBundle\Entity\Media $attachmentUrl5 = null)
    {
        $this->attachmentUrl5 = $attachmentUrl5;

        return $this;
    }

    /**
     * Get attachmentUrl5
     *
     * @return \Victoire\Bundle\MediaBundle\Entity\Media
     */
    public function getAttachmentUrl5()
    {
        return $this->attachmentUrl5;
    }

    /**
     * Set attachmentUrl6
     *
     * @param  \Victoire\Bundle\MediaBundle\Entity\Media $attachmentUrl6
     * @return WidgetFormSlot
     */
    public function setAttachmentUrl6(\Victoire\Bundle\MediaBundle\Entity\Media $attachmentUrl6 = null)
    {
        $this->attachmentUrl6 = $attachmentUrl6;

        return $this;
    }

    /**
     * Get attachmentUrl6
     *
     * @return \Victoire\Bundle\MediaBundle\Entity\Media
     */
    public function getAttachmentUrl6()
    {
        return $this->attachmentUrl6;
    }

    /**
     * Set attachmentUrl7
     *
     * @param  \Victoire\Bundle\MediaBundle\Entity\Media $attachmentUrl7
     * @return WidgetFormSlot
     */
    public function setAttachmentUrl7(\Victoire\Bundle\MediaBundle\Entity\Media $attachmentUrl7 = null)
    {
        $this->attachmentUrl7 = $attachmentUrl7;

        return $this;
    }

    /**
     * Get attachmentUrl7
     *
     * @return \Victoire\Bundle\MediaBundle\Entity\Media
     */
    public function getAttachmentUrl7()
    {
        return $this->attachmentUrl7;
    }

    /**
     * Get submitIcon
     *
     * @return string
     */
    public function getSubmitIcon()
    {
        return $this->submitIcon;
    }

    /**
     * Set submitIcon
     * @param string $submitIcon
     *
     * @return WidgetForm
     */
    public function setSubmitIcon($submitIcon)
    {
        $this->submitIcon = $submitIcon;

        return $this;
    }

    /**
     * Get submitLabel
     *
     * @return string
     */
    public function getSubmitLabel()
    {
        return $this->submitLabel;
    }

    /**
     * Set submitLabel
     * @param string $submitLabel
     *
     * @return WidgetForm
     */
    public function setSubmitLabel($submitLabel)
    {
        $this->submitLabel = $submitLabel;

        return $this;
    }
}
