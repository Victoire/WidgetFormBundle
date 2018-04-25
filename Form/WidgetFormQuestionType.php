<?php

namespace Victoire\Widget\FormBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Victoire\Bundle\CoreBundle\Form\WidgetType;

/**
 * WidgetFormQuestion form type.
 */
class WidgetFormQuestionType extends WidgetType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                    'required' => true,
                    'label'    => 'widget_form.form.question.title.label',
                ]
            )
            ->add('position', HiddenType::class, [
                'data' => '__name__',
                'attr' => [
                        'class' => 'question-position',
                    ],
                ]
            )
            ->add('prefix', null, [
                    'label' => 'widget_form.form.question.prefix.label',
                ]
            )
            ->add('required', null, [
                    'label' => 'widget_form.form.question.required.label',
                ]
            )
            ->add('type', ChoiceType::class, [
                    'choices' => [
                        'widget_form.form.question.type.text'     => 'text',
                        'widget_form.form.question.type.textarea' => 'textarea',
                        'widget_form.form.question.type.date'     => 'date',
                        'widget_form.form.question.type.email'    => 'email',
                        'widget_form.form.question.type.boolean'  => 'boolean',
                        'widget_form.form.question.type.choice'   => 'checkbox',
                        'widget_form.form.question.type.radio'    => 'radio',
                    ],
                    'choices_as_values' => true,
                    'required'          => true,
                    'label'             => 'widget_form.form.question.type.label',
                    'attr'              => [
                        'class'    => 'selector-type',
                        'onchange' => 'showQuestionFormBySelect(this)',
                    ],
                ]
            )
            ->add('proposalExpanded', null, [
                'label'    => 'widget_form.form.question.proposalExpanded.label',
                'required' => false,
                ]
            )
            ->add('proposalInline', null, [
                'label'    => 'widget_form.form.question.proposalInline.label',
                'required' => false,
                ]
            )
            ->add('proposal', HiddenType::class, [
                'label'    => 'widget_form.form.question.proposal.label',
                'required' => false,
                ]
            )
            ->add('regex', null, [
                    'label' => 'widget_form.form.question.regex.label',
                ]
            )
            ->add('regexTitle', null, [
                    'label' => 'widget_form.form.question.regexTitle.label',
                ]
            )
            ->add('alignment', ChoiceType::class, [
                    'label' => 'widget_form.form.question.alignment.label',
                    'choices' => [
                        'widget_form.form.question.alignment.full' => 'full',
                        'widget_form.form.question.alignment.left' => 'left',
                        'widget_form.form.question.alignment.right' => 'right',
                    ],
                    'data' => 'full',
                    'choices_as_values' => true,
                    'expanded' => true
                ]
            );
        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $widget = $event->getData();
                $widget->setProposal(serialize($widget->getProposal()));
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Victoire\Widget\FormBundle\Entity\WidgetFormQuestion',
        ]);
    }
}
