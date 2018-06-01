<?php

namespace Victoire\Widget\FormBundle\Domain\Captcha\Adapter;

use ReCaptcha\ReCaptcha;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class RecaptchaAdapter extends AbstractCaptcha
{
    private $recaptchaPrivateKey;
    private $recaptchaPublicKey;

    public function __construct($recaptchaPrivateKey, $recaptchaPublicKey)
    {
        $this->recaptchaPrivateKey = $recaptchaPrivateKey;
        $this->recaptchaPublicKey = $recaptchaPublicKey;
    }

    /**
     * Check if the captcha is valid or not
     * @param Request $request
     * @param bool $clear
     * @return bool
     */
    public function validateCaptcha($request, $clear = true)
    {
        $recaptcha = new ReCaptcha($this->recaptchaPrivateKey);
        $resp = $recaptcha->verify($request->get('g-recaptcha-response'), $request->getClientIp());

        return $resp->isSuccess();
    }

    public function getName()
    {
        return 'recaptcha';
    }

    public function canBeUsed()
    {
        return $this->recaptchaPublicKey && $this->recaptchaPrivateKey;
    }

    public function getTwigParameters()
    {
        return ['recaptcha_public_key' => $this->recaptchaPublicKey];
    }

    /**
     * Return the view path to render the widget
     */
    public function getViewPath()
    {
        return '@VictoireWidgetForm/form/captcha/recaptcha.html.twig';
    }
}
