<?php

namespace Victoire\Widget\FormBundle\Domain\Captcha\Adapter;

use Securimage;
use Symfony\Component\HttpFoundation\Request;

class SecurimageAdapter implements CaptchaInterface
{
    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Checks if the captcha is valid or not.
     * Then delete it from session if captcha is valid.
     *
     * @param bool $clear
     *
     * @return bool
     */
    public function validateCaptcha($clear = true)
    {
        $request = $this->request->request;
        $securimage_namespace = $request->get('securimage_namespace');
        $code = $request->get('securimage_code');
        $sc = $this->getSerurimageInstance($securimage_namespace);

        if ($clear) {
            return $sc->check($code);
        }

        return $this->getSecurimageParameters()['case_sensitive'] ? $sc->getCode() === $code : strtolower($sc->getCode()) === strtolower($code);
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
        return $this->generateNewImage();
    }

    /**
     * Return a new Image encoded in base64 and his namespace.
     *
     * https://stackoverflow.com/questions/4401949/whats-the-use-of-ob-start-in-php?utm_medium=organic&utm_source=google_rich_qa&utm_campaign=google_rich_qa
     * ob_start() turns on output buffering.
     * ob_get_contents() grabs all of the data gathered since we called ob_start.
     * ob_end_clean() erases the buffer, and turns off output buffering.
     * @return array
     */
    public function generateNewImage()
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

    /**
     * Return all parameters to instanciate Securimage.
     *
     * @return array
     */
    private function getSecurimageParameters()
    {
        return [
            'no_session' => false,
            'use_database' => false,
            'no_exit' => true,
            'image_width' => 275,
            'code_length' => mt_rand(4, 6),
            'case_sensitive' => false,
        ];
    }
}
