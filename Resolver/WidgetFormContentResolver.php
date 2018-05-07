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
     * @var CaptchaHandler
     */
    protected $captchaHandler;

    public function __construct(CaptchaHandler $captchaHandler)
    {
        $this->captchaHandler = $captchaHandler;
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

        return $this->addCaptchaKey($widget, $parameters);
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

        return $this->addCaptchaKey($widget, $parameters);
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

        return $this->addCaptchaKey($widget, $parameters);
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

        return $this->addCaptchaKey($widget, $parameters);
    }

    /**
     * Injecting the params of the current captcha
     * @param WidgetForm $widget
     * @param array $parameters
     * @return array
     */
    protected function addCaptchaKey(WidgetForm $widget, array $parameters)
    {
        try {
            $captchaAdapter = $this->captchaHandler->getCaptcha($widget->getCaptcha());
            return array_merge($parameters, $captchaAdapter->getTwigParameters());
        } catch (\Exception $e) {
            return $parameters;
        }
    }
}
