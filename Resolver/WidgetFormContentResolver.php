<?php

namespace Victoire\Widget\FormBundle\Resolver;

use Victoire\Bundle\WidgetBundle\Model\Widget;
use Victoire\Bundle\WidgetBundle\Resolver\BaseWidgetContentResolver;
use Victoire\Widget\FormBundle\Entity\WidgetForm;
use Victoire\Widget\FormBundle\Helper\RecaptchaHelper;

class WidgetFormContentResolver extends BaseWidgetContentResolver
{
    protected $recaptchaPublicKey;
    protected $recaptchaHelper;

    public function __construct($recaptchaPublicKey, RecaptchaHelper $recaptchaHelper)
    {
        $this->recaptchaPublicKey = $recaptchaPublicKey;
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

        return $this->addRecaptchaKey($widget, $parameters);
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

        return $this->addRecaptchaKey($widget, $parameters);
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

        return $this->addRecaptchaKey($widget, $parameters);
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

        return $this->addRecaptchaKey($widget, $parameters);
    }

    protected function addRecaptchaKey(WidgetForm $widget, array $parameters)
    {
        if ($widget->isRecaptcha() && $this->recaptchaHelper->canUseReCaptcha()) {
            return array_merge($parameters, ['recaptcha_public_key' => $this->recaptchaPublicKey]);
        }

        return $parameters;
    }
}
