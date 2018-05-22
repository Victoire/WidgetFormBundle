<?php

namespace Victoire\Widget\FormBundle\Domain\Captcha\Adapter;

use Gregwar\Captcha\CaptchaBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class GregwarCaptchaAdapter extends AbstractCaptcha {

    /**
     * @var CaptchaBuilder
     */
    private $captchaBuilder;

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var Request
     */
    private $request;

    public function __construct(SessionInterface $session, Request $request)
    {
        $this->session = $session;
        $this->request = $request;
        $this->captchaBuilder = new CaptchaBuilder();
        $this->generateNewCaptcha();
    }

    /**
     * Check if the captcha is valid or not
     * @return boolean
     */
    public function validateCaptcha($clear = true)
    {
        $request = $this->request->request;
        $captcha_namespace = $request->get('captcha_namespace');
        $code = $request->get('captcha_code');

        $displayCode = $this->session->get($captcha_namespace);

        if ($clear) {
            $this->session->remove($captcha_namespace);
        }

        return strtolower($code) === strtolower($displayCode);
    }

    /**
     * Get the captcha name
     * @return string
     */
    public function getName()
    {
        return 'GregwarCaptcha';
    }

    public function getTwigParameters() {
        return [
            'captcha_image' => $this->captchaBuilder->inline(),
            'captcha_namespace' => $this->namespace
        ];
    }

    /**
     * Regenerate a new Captcha
     * @return mixed
     */
    public function generateNewCaptcha() {
        $this->setNamespace($this->generateCaptchaNamespace());
        $this->captchaBuilder->build();
        $this->session->set($this->getNamespace(), $this->captchaBuilder->getPhrase());
    }

    /**
     * Return the view path to render the widget
     */
    public function getViewPath()
    {
        return '@VictoireWidgetForm/form/captcha/captcha.html.twig';
    }

    /**
     * Generate an unique id for the captcha namespace
     * @return string
     */
    private function generateCaptchaNamespace() {
        return md5(uniqid(rand(), true));
    }

    /**
     * @param string $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }
}