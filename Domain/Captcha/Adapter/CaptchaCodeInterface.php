<?php

namespace Victoire\Widget\FormBundle\Domain\Captcha\Adapter;

interface CaptchaCodeInterface
{
    /**
     * Get captcha Code by namespace
     * @param $namespace
     * @return mixed
     */
    public function getCaptchaCode($namespace);
}
