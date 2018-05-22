<?php

namespace Victoire\Widget\FormBundle\Domain\Captcha\Adapter;

abstract class AbstractCaptcha implements CaptchaInterface {

    /**
     * Check if the captcha is valid or not
     * @return boolean
     */
    abstract public function validateCaptcha();

    /**
     * Get the captcha name
     * @return string
     */
    abstract public function getName();

    /**
     * Check if current configuration allow to use this captcha
     * @return boolean
     */
    public function canBeUsed()
    {
        return true;
    }

    /**
     * Return all parameters necessary in the view
     * @return array
     */
    public function getTwigParameters()
    {
        return [];
    }

    /**
     * Return the view path to render the widget
     */
    public function getViewPath()
    {
        return;
    }

    /**
     * Regenerate a new Captcha
     * @return mixed
     */
    public function generateNewCaptcha() {
        return;
    }
}