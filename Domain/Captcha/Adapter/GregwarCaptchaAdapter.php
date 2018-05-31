<?php

namespace Victoire\Widget\FormBundle\Domain\Captcha\Adapter;

use Gregwar\Captcha\CaptchaBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class GregwarCaptchaAdapter extends AbstractCaptcha implements CaptchaCodeInterface {

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

    public function __construct(SessionInterface $session, $env)
    {
        $this->session = $session;

        if ($this->canBeUsed()) {
            $phrase = ($env === 'ci' || $env === 'test') ? "correctly" : null;
            $this->captchaBuilder = new CaptchaBuilder($phrase);
            $this->generateNewCaptcha();
        }
    }

    /**
     * Check if the captcha is valid or not
     * @param Request $request
     * @param bool $clear
     * @return bool
     */
    public function validateCaptcha($request, $clear = true)
    {
        $code = $request->get('captcha_code');
        $namespace = $request->get('captcha_namespace');
        $codeDisplay = $this->getCaptchaCode($namespace);
        if ($clear) {
            $this->session->remove($namespace);
        }

        return strtolower($code) === strtolower($codeDisplay);
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
     * Check if current configuration allow to use this captcha
     * @return boolean
     */
    public function canBeUsed()
    {
        return extension_loaded('gd');
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

    /**
     * Get captcha Code by namespace
     * @param $namespace
     * @return mixed
     */
    public function getCaptchaCode($namespace)
    {
        return $this->session->get($namespace);
    }
}