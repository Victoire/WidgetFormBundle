<?php

namespace Victoire\Widget\FormBundle\Domain\Captcha\Adapter;

interface CaptchaInterface
{
    /**
     * Check if the captcha is valid or not
     * @return boolean
     */
    public function validateCaptcha();

    /**
     * Get the captcha name
     * @return string
     */
    public function getName();

    /**
     * Check if current configuration allow to use this captcha
     * @return boolean
     */
    public function canBeUsed();

    /**
     * Return all parameters necessary in the view
     * @return array
     */
    public function getTwigParameters();
}
