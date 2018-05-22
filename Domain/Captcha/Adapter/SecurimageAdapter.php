<?php

namespace Victoire\Widget\FormBundle\Domain\Captcha\Adapter;

use Securimage;
use Symfony\Component\HttpFoundation\Request;

class SecurimageAdapter extends AbstractCaptcha
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var Securimage
     */
    private $securimage;

    /**
     * @var string
     */
    private $namespace;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->securimage = new Securimage($this->getSecurimageParameters());
        $this->generateNewCaptcha();
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
        $securimage_namespace = $request->get('captcha_namespace');
        $code = $request->get('captcha_code');
        $this->securimage->setNamespace($securimage_namespace);

        if ($clear) {
            return $this->securimage->check($code);
        }

        return $this->getSecurimageParameters()['case_sensitive'] ? $this->securimage->getCode() === $code : strtolower($this->securimage->getCode()) === strtolower($code);
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
        return [
            'captcha_image' => 'data:image/png;base64,'.$this->getImageStr(),
            'captcha_namespace' => $this->getNamespace()
        ];
    }

    /**
     * Return a new Image encoded in base64.
     *
     * https://stackoverflow.com/questions/4401949/whats-the-use-of-ob-start-in-php?utm_medium=organic&utm_source=google_rich_qa&utm_campaign=google_rich_qa
     * ob_start() turns on output buffering.
     * ob_get_contents() grabs all of the data gathered since we called ob_start.
     * ob_end_clean() erases the buffer, and turns off output buffering.
     *
     * @return array
     */
    public function getImageStr()
    {
        ob_start();
        $this->securimage->show();
        $imageData = ob_get_contents();
        ob_end_clean();

        return base64_encode($imageData);
    }

    /**
     * Regenerate a new Captcha
     * @return mixed
     */
    public function generateNewCaptcha() {
        $this->setNamespace(md5(uniqid(rand(), true)));
        $this->securimage->setNamespace($this->getNamespace());
    }

    /**
     * Return the view path to render the widget.
     */
    public function getViewPath()
    {
        return '@VictoireWidgetForm/form/captcha/captcha.html.twig';
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
            'expiry_time' => 0,
            'send_headers' => false
        ];
    }

    /**
     * @param mixed $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * @return mixed
     */
    public function getNamespace()
    {
        return $this->namespace;
    }
}
