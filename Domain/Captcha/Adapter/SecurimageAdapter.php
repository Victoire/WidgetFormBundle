<?php

namespace Victoire\Widget\FormBundle\Domain\Captcha\Adapter;

use Securimage;
use Symfony\Component\HttpFoundation\RequestStack;

class SecurimageAdapter implements CaptchaInterface
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * Checks if the captcha is valid or not.
     * Then delete it from session if captcha is valid
     *
     * @param bool $clear
     * @return bool
     */
    public function validateCaptcha($clear=true)
    {
        $request = $this->requestStack->getCurrentRequest()->request;
        $securimage_namespace = $request->get('securimage_namespace');
        $code = $request->get('securimage_code');
        $sc = $this->getSerurimageInstance($securimage_namespace);

        if($clear) {
            return $sc->check($code);
        } else {
            return $sc->getCode() === $code;
        }
    }

    /**
     * Get the captcha name.
     *
     * @return string
     */
    public function getName()
    {
        return 'securimage';
    }

    /**
     * Check if current configuration allow to use this captcha.
     *
     * @return bool
     */
    public function canBeUsed()
    {
        return true;
    }

    /**
     * Return all parameters necessary in the view.
     *
     * @return array
     */
    public function getTwigParameters()
    {
        $namespace = md5(uniqid(rand(), true));
        $sc = $this->getSerurimageInstance($namespace);

        ob_start();
        $sc->show();
        $imageData = ob_get_contents();
        ob_end_clean();

        $imageStr = base64_encode($imageData);

        return [
            'securimage_html' => $imageStr,
            'securimage_namespace' => $namespace,
        ];
    }

    /**
     * @return Securimage
     *
     * @param mixed $namespace
     */
    private function getSerurimageInstance($namespace)
    {
        $sc = new Securimage($this->getSecurimageParameters());
        $sc->setNamespace($namespace);

        return $sc;
    }

    private function getSecurimageParameters()
    {
        return [
            'no_session' => false,
            'use_database' => false,
            'no_exit' => true,
            'image_width' => 275,
            'code_length' => mt_rand(4, 6),
        ];
    }
}