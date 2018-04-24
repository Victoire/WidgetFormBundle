<?php

namespace Victoire\Widget\FormBundle\Helper;

class RecaptchaHelper
{
    private $recaptchaPublicKey;
    private $recaptchaPrivateKey;

    public function __construct($recaptchaPublicKey, $recaptchaPrivateKey)
    {
        $this->recaptchaPublicKey = $recaptchaPublicKey;
        $this->recaptchaPrivateKey = $recaptchaPrivateKey;
    }

    public function canUseReCaptcha()
    {
        return $this->recaptchaPublicKey && $this->recaptchaPrivateKey;
    }
}
