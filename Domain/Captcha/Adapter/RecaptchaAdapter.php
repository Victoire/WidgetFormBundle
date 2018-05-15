<?php

namespace Victoire\Widget\FormBundle\Domain\Captcha\Adapter;

use ReCaptcha\ReCaptcha;
use Symfony\Component\HttpFoundation\RequestStack;

class RecaptchaAdapter implements CaptchaInterface
{
    private $recaptchaPrivateKey;
    private $recaptchaPublicKey;
    private $requestStack;

    public function __construct($recaptchaPrivateKey, $recaptchaPublicKey, RequestStack $requestStack)
    {
        $this->recaptchaPrivateKey = $recaptchaPrivateKey;
        $this->recaptchaPublicKey = $recaptchaPublicKey;
        $this->requestStack = $requestStack;
    }

    public function validateCaptcha()
    {
        $request = $this->requestStack->getCurrentRequest();
        $recaptcha = new ReCaptcha($this->recaptchaPrivateKey);
        $resp = $recaptcha->verify($request->request->get('g-recaptcha-response'), $request->getClientIp());

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
