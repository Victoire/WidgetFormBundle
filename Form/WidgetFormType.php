<?php

namespace Victoire\Widget\FormBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Victoire\Bundle\CoreBundle\Form\WidgetType;
use Victoire\Widget\FormBundle\Entity\WidgetFormQuestion;

/**
 * WidgetForm form type
 */
class WidgetFormType extends WidgetType
{
    private $formPrefill;

    /**
     * Constructor
     */
    public function __construct($formPrefill)
    {
        $this->formPrefill = $formPrefill;
    }

    /**
     * define form fields
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('title', null, array(
            'label' => 'widget_form.form.title.label',
        ))
        ->add('attachmentUrl', 'media', array(
            'label' => 'widget_form.form.attachmentUrl.label',
        ))
        ->add('attachmentUrl2', 'media', array(
            'label' => 'widget_form.form.attachmentUrl2.label',
        ))
        ->add('attachmentUrl3', 'media', array(
            'label' => 'widget_form.form.attachmentUrl3.label',
        ))
        ->add('attachmentUrl4', 'media', array(
            'label' => 'widget_form.form.attachmentUrl4.label',
        ))
        ->add('attachmentUrl5', 'media', array(
            'label' => 'widget_form.form.attachmentUrl5.label',
        ))
        ->add('attachmentUrl6', 'media', array(
            'label' => 'widget_form.form.attachmentUrl6.label',
        ))
        ->add('attachmentUrl7', 'media', array(
            'label' => 'widget_form.form.attachmentUrl7.label',
        ))
        ->add('subject', null, array(
            'label' => 'widget_form.form.subject.label',
        ))
        ->add('targetEmail', null, array(
            'label' => 'widget_form.form.targetEmail.label',
        ))
        ->add('autoAnswer', null, array(
            'label' => 'widget_form.form.autoAnswer.label',
        ))
        ->add('autoAnswer', null, array(
            'label' => 'widget_form.form.autoAnswer.label',
        ))
        ->add('message', 'ckeditor', array(
            'label' => 'widget_form.form.message.label',
            'required' => true,
            'vic_help_block' => 'widget_form.form.message.help_block',
            'config' => array(
                'toolbar' => array(
                    array(
                        'name'  => 'styles',
                        'items' => array('Font', 'FontSize'),
                    ),
                    array(
                        'name'  => 'basicstyles',
                        'items' => array('Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript'),
                    ),
                    array(
                        'name'  => 'paragraph',
                        'items' => array('NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'),
                    ),
                    '/',
                    array(
                        'name'  => 'clipboard',
                        'items' => array('Undo', 'Redo'),
                    ),
                    array(
                        'name'  => 'insert',
                        'items' => array('Table', 'HorizontalRule', 'SpecialChar'),
                    ),
                ),
            ),
        ))->add('questions', 'collection', array(
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'type'  => new WidgetFormQuestionType(),
                'label' => 'widget_form.form.questions.label',
                'vic_widget_items_attr' => array(
                    'class' => "question",
                ),
        ))
        ->add('submitLabel', null, array(
            'label' => 'widget_form.form.submitLabel.label',
            'required' => true,
        ))
        ->add('submitIcon', 'font_awesome_picker', array(
            'label' => 'widget_form.form.submitIcon.label',
            'required' => true,
        ))
        ;

        if ($this->formPrefill) {
            $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $widgetFormSlot = $event->getData();
                $form = $event->getForm();
                if (!$widgetFormSlot || null === $widgetFormSlot->getId()) {
                    $formPrefill = $this->formPrefill;
                    foreach ($formPrefill as $question) {
                        $newQuestion = new WidgetFormQuestion();
                        $newQuestion->setTitle($question['title']);
                        $newQuestion->setPosition($question['position']);
                        $newQuestion->setRequired($question['required']);
                        $newQuestion->setType($question['type']);
                        $newQuestion->setProposal($question['proposal']);
                        $widgetFormSlot->addQuestion($newQuestion);
                    }
                }
            }
            );
        }
    }

    /**
     * bind form to WidgetFormSlot entity
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(array(
            'data_class'         => 'Victoire\Widget\FormBundle\Entity\WidgetForm',
            'widget'             => 'Form',
            'translation_domain' => 'victoire',
            )
        );
    }

    /**
     * get form name
     *
     * @return string The form name
     */
    public function getName()
    {
        return 'victoire_widget_form_form';
    }
}
