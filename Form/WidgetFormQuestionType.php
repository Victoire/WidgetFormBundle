<?php

namespace Victoire\Widget\FormBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Victoire\Bundle\CoreBundle\Form\WidgetType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

/**
 * WidgetFormQuestion form type
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
            ->add('title', 'text', array(
                    'required' => true,
                    'label'    => "widget_form.form.question.title.label",
                )
            )
            ->add('position', 'hidden', array(
                'data' => '__name__',
                'attr' => array(
                        'class' => 'question-position',
                    ),
                )
            )
            ->add('required', null, array(
                    'label'    => "widget_form.form.question.required.label",
                )
            )
            ->add('type', 'choice', array(
                    'choices' => array(
                            'text' => "widget_form.form.question.type.text",
                            'textarea' => "widget_form.form.question.type.textarea",
                            'date' => "widget_form.form.question.type.date",
                            'checkbox' => "widget_form.form.question.type.choice",
                            'radio' => "widget_form.form.question.type.radio",
                        ),
                    'required' => true,
                    'label'    => "widget_form.form.question.type.label",
                    'attr' => array(
                        'class' => 'selector-type',
                        'onchange' => 'showQuestionFormBySelect(this)',
                    ),
                )
            )
            ->add('proposalExpanded', null, array(
                'label'    => "widget_form.form.question.proposalExpanded.label",
                'required' => false,
                )
            )
            ->add('proposalInline', null, array(
                'label'    => "widget_form.form.question.proposalInline.label",
                'required' => false,
                )
            )
            ->add('proposal', 'hidden', array(
                'label'    => "widget_form.form.question.proposal.label",
                'required' => false,
                )
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
        $resolver->setDefaults(array(
            'data_class' => 'Victoire\Widget\FormBundle\Entity\WidgetFormQuestion',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'victoire_widget_form_question';
    }
}
