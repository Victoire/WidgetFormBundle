<?php

namespace Victoire\Widget\FormBundle\Domain\Captcha;

use Symfony\Component\Translation\TranslatorInterface;
use Victoire\Widget\FormBundle\Domain\Captcha\Adapter\CaptchaInterface;

class CaptchaHandler {

    /**
     * @var CaptchaInterface[]
     */
    private $adapters;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct($adapters, TranslatorInterface $translator)
    {
        $this->adapters = $adapters;
        $this->translator = $translator;
    }

    public function getCaptcha($name) {
        foreach ($this->adapters as $adapter) {
            if($adapter->getName() === $name) {
                return $adapter;
            }
        }
        return null;
    }

    public function getNameOfAllAvailableCaptcha () {
        $listAvailableCaptcha = [];
        $labelNone = $this->translator->trans('widget_form.form.captcha.none', [], 'victoire');
        $listAvailableCaptcha[$labelNone] = $labelNone;

        foreach ($this->adapters as $adapter) {
            if($adapter->canBeUsed()) {
                $listAvailableCaptcha[$adapter->getName()] = $adapter->getName();
            }
        }
        return $listAvailableCaptcha;
    }
}