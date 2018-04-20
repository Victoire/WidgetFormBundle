<?php

namespace Victoire\Widget\FormBundle\Helper;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class RecaptchaHelper {

    private $container;

    public function __construct(Container $container) {
        $this->container = $container;
    }


    public function canUseReCaptcha () {
        if($this->container->getParameter('victoire_widget_form.recaptcha_public_key') && $this->container->getParameter('victoire_widget_form.recaptcha_private_key')) {
            return true;
        } else {
            return false;
        }
    }
}