<?php

namespace Victoire\Widget\FormBundle\Form;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Victoire\Bundle\CoreBundle\Form\WidgetType;
use Victoire\Bundle\FormBundle\Form\Type\FontAwesomePickerType;
use Victoire\Bundle\FormBundle\Form\Type\LinkType;
use Victoire\Bundle\MediaBundle\Form\Type\MediaType;
use Victoire\Widget\FormBundle\Domain\Captcha\CaptchaHandler;
use Victoire\Widget\FormBundle\Entity\WidgetFormQuestion;

/**
 * WidgetForm form type.
 */
class WidgetFormType extends WidgetType
{
    private $formPrefill;
    private $captchaHandler;

    /**
     * Constructor.
     */
    public function __construct($formPrefill, CaptchaHandler $captchaHandler)
    {
        $this->formPrefill = $formPrefill;
        $this->captchaHandler = $captchaHandler;
    }

    /**
     * define form fields.
     *
     * @param FormBuilderInterface $builder
     *
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('title', null, [
            'label' => 'widget_form.form.title.label',
        ])
        ->add('attachmentUrl', MediaType::class, [
            'label' => 'widget_form.form.attachmentUrl.label',
        ])
        ->add('attachmentUrl2', MediaType::class, [
            'label' => 'widget_form.form.attachmentUrl2.label',
        ])
        ->add('attachmentUrl3', MediaType::class, [
            'label' => 'widget_form.form.attachmentUrl3.label',
        ])
        ->add('attachmentUrl4', MediaType::class, [
            'label' => 'widget_form.form.attachmentUrl4.label',
        ])
        ->add('attachmentUrl5', MediaType::class, [
            'label' => 'widget_form.form.attachmentUrl5.label',
        ])
        ->add('attachmentUrl6', MediaType::class, [
            'label' => 'widget_form.form.attachmentUrl6.label',
        ])
        ->add('attachmentUrl7', MediaType::class, [
            'label' => 'widget_form.form.attachmentUrl7.label',
        ])
        ->add('subject', null, [
            'label' => 'widget_form.form.subject.label',
        ])
        ->add('adminSubject', null, [
            'label'          => 'widget_form.form.adminSubject.label',
            'vic_help_block' => 'widget_form.form.adminSubject.help_block',
        ])
        ->add('targetEmail', null, [
            'label'          => 'widget_form.form.targetEmail.label',
            'vic_help_block' => 'widget_form.form.targetEmail.help_block',
        ])
        ->add('noReply', null, [
            'label'          => 'widget_form.form.noReply.label',
            'vic_help_block' => 'widget_form.form.noReply.help_block',
        ])
        ->add('autoAnswer', null, [
            'label' => 'widget_form.form.autoAnswer.label',
        ])
        ->add('autoAnswer', null, [
            'label' => 'widget_form.form.autoAnswer.label',
        ])
        ->add('message', CKEditorType::class, [
            'label'          => 'widget_form.form.message.label',
            'required'       => true,
            'vic_help_block' => 'widget_form.form.message.help_block',
            'config'         => [
                'toolbar' => [
                    [
                        'name'  => 'styles',
                        'items' => ['Font', 'FontSize'],
                    ],
                    [
                        'name'  => 'basicstyles',
                        'items' => ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript'],
                    ],
                    [
                        'name'  => 'paragraph',
                        'items' => ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],
                    ],
                    '/',
                    [
                        'name'  => 'clipboard',
                        'items' => ['Undo', 'Redo'],
                    ],
                    [
                        'name'  => 'insert',
                        'items' => ['Table', 'HorizontalRule', 'SpecialChar'],
                    ],
                ],
            ],
        ])->add('questions', CollectionType::class, [
                'allow_add'             => true,
                'allow_delete'          => true,
                'by_reference'          => false,
                'entry_type'            => WidgetFormQuestionType::class,
                'label'                 => 'widget_form.form.questions.label',
                'vic_widget_items_attr' => [
                    'class' => 'question',
                ],
        ])
        ->add('submitLabel', null, [
            'label'    => 'widget_form.form.submitLabel.label',
            'required' => true,
        ])
        ->add('submitIcon', FontAwesomePickerType::class, [
            'label'    => 'widget_form.form.submitIcon.label',
            'required' => false,
        ])
        ->add('submitClass', ChoiceType::class, [
            'label'    => 'widget_form.form.submitClass.label',
            'choices'   => [
                'widget_form.form.choice.style.label.default' => 'default',
                'widget_form.form.choice.style.label.primary' => 'primary',
                'widget_form.form.choice.style.label.success' => 'success',
                'widget_form.form.choice.style.label.info'    => 'info',
                'widget_form.form.choice.style.label.warning' => 'warning',
                'widget_form.form.choice.style.label.danger'  => 'danger',
                ],
            'choices_as_values' => true,
        ])
        ->add('successCallback', ChoiceType::class, [
                'label'    => 'widget_form.form.successCallback.label',
                'required' => true,
                'choices'  => [
                    'victoire.widget-form.successCallback.choices.notification' => 'notification',
                    'victoire.widget-form.successCallback.choices.redirect'     => 'redirect',
                ],
                'choices_as_values' => true,
            ]
        )
        ->add('link', LinkType::class)
        ->add('successMessage', null, [
            'label'    => 'widget_form.form.successMessage.label',
            'required' => false,
            ]
        )
        ->add('errorNotification', null, [
            'label'    => 'widget_form.form.errorNotification.label',
            'required' => false,
            ]
        )
        ->add('errorMessage', null, [
            'label'    => 'widget_form.form.errorMessage.label',
            'required' => false,
            ]
        );

        $listCaptcha = [];
        foreach ($this->captchaHandler->getAvailableCaptcha() as $captcha) {
            $listCaptcha[$captcha->getName()] = $captcha->getName();
        }

        $builder->add('captcha', ChoiceType::class, [
            'label'    => 'widget_form.form.captcha.label',
            'choices'  => $listCaptcha,
            'placeholder' => 'widget_form.form.captcha.none',
            'choice_label' => function ($value) { return $value; },
        ]);

        if ($this->formPrefill) {
            $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $widgetFormSlot = $event->getData();
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
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class'         => 'Victoire\Widget\FormBundle\Entity\WidgetForm',
            'widget'             => 'Form',
            'translation_domain' => 'victoire'
            ]
        );
    }
}
