<?php

namespace Victoire\Widget\FormBundle\Domain\Captcha\Adapter;

interface CaptchaInterface {
    public function validateCaptcha();
    public function getName();
    public function canBeUsed();
}