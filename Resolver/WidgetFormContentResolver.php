<?php

namespace Victoire\Widget\FormBundle\Resolver;

use Victoire\Bundle\WidgetBundle\Model\Widget;
use Victoire\Bundle\WidgetBundle\Resolver\BaseWidgetContentResolver;
use Victoire\Widget\FormBundle\Domain\Captcha\Adapter\CaptchaInterface;
use Victoire\Widget\FormBundle\Domain\Captcha\Adapter\RecaptchaAdapter;
use Victoire\Widget\FormBundle\Domain\Captcha\CaptchaHandler;
use Victoire\Widget\FormBundle\Entity\WidgetForm;

class WidgetFormContentResolver extends BaseWidgetContentResolver
{
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
        return $parameters;
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
        return $parameters;
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
        return $parameters;
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
        return $parameters;
    }

}
