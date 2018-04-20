<?php

namespace Victoire\Widget\FormBundle\Helper;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\Translation\TranslatorInterface;

class RecaptchaHelper {

    private $container;
    private $translator;

    public function __construct(Container $container, TranslatorInterface $translator) {
        $this->container = $container;
        $this->translator = $translator;
    }

    public function canUseReCaptcha () {
        if($this->container->getParameter('victoire_widget_form.recaptcha_public_key')
            && $this->container->getParameter('victoire_widget_form.recaptcha_private_key')) {
            return true;
        } else {
            return false;
        }
    }
}