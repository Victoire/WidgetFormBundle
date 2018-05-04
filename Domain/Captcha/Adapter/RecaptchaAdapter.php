<?php

namespace Victoire\Widget\FormBundle\Domain\Captcha\Adapter;


use ReCaptcha\ReCaptcha;

class RecaptchaAdapter implements CaptchaInterface {

    private $recaptchaPrivateKey;
    private $recaptchaPublicKey;

    public function __construct($recaptchaPrivateKey, $recaptchaPublicKey)
    {
        $this->recaptchaPrivateKey = $recaptchaPrivateKey;
        $this->recaptchaPublicKey = $recaptchaPublicKey;
    }

    public function validateCaptcha()
    {
//        $recaptcha = new ReCaptcha($this->recaptchaPrivateKey);
//        $resp = $recaptcha->verify($request->request->get('g-recaptcha-response'), $request->getClientIp());
//
//        return $resp->isSuccess();
        return true;
    }

    public function getName()
    {
        return 'recaptcha';
    }

    public function canBeUsed()
    {
        return $this->recaptchaPublicKey && $this->recaptchaPrivateKey;
    }

    /**
     * @return mixed
     */
    public function getRecaptchaPrivateKey()
    {
        return $this->recaptchaPrivateKey;
    }

    /**
     * @return mixed
     */
    public function getRecaptchaPublicKey()
    {
        return $this->recaptchaPublicKey;
    }
}