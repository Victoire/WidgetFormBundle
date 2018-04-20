<?php

namespace Victoire\Widget\FormBundle\Helper;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\Translation\Loader\XliffFileLoader;
use Symfony\Component\Translation\TranslatorInterface;

class RecaptchaHelper {

    private $container;
    private $translator;

    public function __construct(Container $container, TranslatorInterface $translator) {
        $this->container = $container;
        $this->translator = $translator;
    }

    public function canUseReCaptcha () {
        if($this->container->getParameter('victoire_widget_form.recaptcha_public_key') && $this->container->getParameter('victoire_widget_form.recaptcha_private_key')) {
            return true;
        } else {
            return false;
        }
    }

    public function getFormatedError ($errorCode) {
        switch ($errorCode) {
            case 'missing-input-secret': return $this->translator->trans('widget_form.form.captcha.error.missing.input.secret', [], 'victoire'); break;
            case 'invalid-input-secret': return $this->translator->trans('widget_form.form.captcha.error.invalid.input.secret', [], 'victoire'); break;
            case 'missing-input-response': return $this->translator->trans('widget_form.form.captcha.error.missing.input.response', [], 'victoire'); break;
            case 'invalid-input-response': return $this->translator->trans('widget_form.form.captcha.error.invalid.input.response', [], 'victoire'); break;
            case 'bad-request': return $this->translator->trans('widget_form.form.captcha.error.bad.request', [], 'victoire'); break;
        }
    }

}