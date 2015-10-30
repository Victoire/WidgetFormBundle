<?php

namespace Victoire\Widget\FormBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
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
            ->add('title', 'text', [
                    'required' => true,
                    'label'    => 'widget_form.form.question.title.label',
                ]
            )
            ->add('position', 'hidden', [
                'data' => '__name__',
                'attr' => [
                        'class' => 'question-position',
                    ],
                ]
            )
            ->add('prefix', null, [
                    'label'    => 'widget_form.form.question.prefix.label',
                ]
            )
            ->add('required', null, [
                    'label'    => 'widget_form.form.question.required.label',
                ]
            )
            ->add('type', 'choice', [
                    'choices' => [
                            'text'     => 'widget_form.form.question.type.text',
                            'textarea' => 'widget_form.form.question.type.textarea',
                            'date'     => 'widget_form.form.question.type.date',
                            'email'    => 'widget_form.form.question.type.email',
                            'boolean'  => 'widget_form.form.question.type.boolean',
                            'checkbox' => 'widget_form.form.question.type.choice',
                            'radio'    => 'widget_form.form.question.type.radio',
                        ],
                    'required' => true,
                    'label'    => 'widget_form.form.question.type.label',
                    'attr'     => [
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
            ->add('proposal', 'hidden', [
                'label'    => 'widget_form.form.question.proposal.label',
                'required' => false,
                ]
            )
            ->add('regex', null, [
                    'label'    => 'widget_form.form.question.regex.label',
                ]
            )
            ->add('regexTitle', null, [
                    'label'    => 'widget_form.form.question.regexTitle.label',
                ]
            );
        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $widget = $event->getData();
                $form = $event->getForm();
                $widget->setProposal(serialize($widget->getProposal()));
            }
        );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Victoire\Widget\FormBundle\Entity\WidgetFormQuestion',
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'victoire_widget_form_question';
    }
}
