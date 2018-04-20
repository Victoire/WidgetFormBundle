<?php

namespace Victoire\Widget\FormBundle\Resolver;

use Victoire\Bundle\WidgetBundle\Model\Widget;
use Victoire\Bundle\WidgetBundle\Resolver\BaseWidgetContentResolver;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Victoire\Widget\FormBundle\Entity\WidgetForm;
use Victoire\Widget\FormBundle\Helper\RecaptchaHelper;

class WidgetFormContentResolver extends BaseWidgetContentResolver
{
    protected $container;
    protected $recaptchaHelper;

    public function __construct(Container $container, RecaptchaHelper $recaptchaHelper)
    {
        $this->container = $container;
        $this->recaptchaHelper = $recaptchaHelper;
    }

    /**
     * Get the static content of the widget.
     *
     * @param Widget $widget
     *
     * @return string
     */
    public function getWidgetStaticContent(Widget $widget)
    {
        $parameters = parent::getWidgetStaticContent($widget);
        /* @var WidgetForm $widget */
        if ($widget->isRecaptcha() && $this->recaptchaHelper->canUseReCaptcha()) {
            return array_merge($parameters, ['recaptcha_public_key' => $this->container->getParameter('victoire_widget_form.recaptcha_public_key')]);
        } else {
            return array_merge($parameters, ['test' => 'test']);
        }
    }

    /**
     * Get the business entity content.
     *
     * @param Widget $widget
     *
     * @return string
     */
    public function getWidgetBusinessEntityContent(Widget $widget)
    {
        $parameters = parent::getWidgetStaticContent($widget);
        /* @var WidgetForm $widget */
        if ($widget->isRecaptcha() && $this->recaptchaHelper->canUseReCaptcha()) {
            return array_merge($parameters, ['recaptcha_public_key' => $this->container->getParameter('victoire_widget_form.recaptcha_public_key')]);
        } else {
            return array_merge($parameters, ['test' => 'test']);
        }
    }

    /**
     * Get the content of the widget by the entity linked to it.
     *
     * @param Widget $widget
     *
     * @return string
     */
    public function getWidgetEntityContent(Widget $widget)
    {
        $parameters = parent::getWidgetStaticContent($widget);
        /* @var WidgetForm $widget */
        if ($widget->isRecaptcha()) {
            return array_merge($parameters, ['recaptcha_public_key' => $this->container->getParameter('victoire_widget_form.recaptcha_public_key')]);
        } else {
            return array_merge($parameters, ['test' => 'test']);
        }
    }

    /**
     * Get the content of the widget for the query mode.
     *
     * @param Widget $widget
     *
     * @return string
     */
    public function getWidgetQueryContent(Widget $widget)
    {
        $parameters = parent::getWidgetStaticContent($widget);
        /* @var WidgetForm $widget */
        if ($widget->isRecaptcha()) {
            return array_merge($parameters, ['recaptcha_public_key' => $this->container->getParameter('victoire_widget_form.recaptcha_public_key')]);
        } else {
            return array_merge($parameters, ['test' => 'test']);
        }
    }

}
