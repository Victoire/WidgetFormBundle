<?php

namespace Victoire\Widget\FormBundle\Domain\Captcha;

use Victoire\Widget\FormBundle\Domain\Captcha\Adapter\CaptchaInterface;

class CaptchaHandler
{
    /**
     * @var CaptchaInterface[]
     */
    private $adapters;

    public function __construct($adapters)
    {
        $this->adapters = $adapters;
    }

    /**
     * Return an instance of Captcha based on the captcha name
     * @param $name
     * @param bool $onlyAvailable
     * @return CaptchaInterface
     */
    public function getCaptcha($name, $onlyAvailable = true)
    {
        foreach ($this->adapters as $adapter) {
            if ($adapter->getName() === $name && (!$onlyAvailable || $adapter->canBeUsed())) {
                return $adapter;
            }
        }

        throw new \InvalidArgumentException('The requested adapter is not declared or not available : '.$name);
    }

    /**
     * Return all Available Captcha
     * @return CaptchaInterface[]
     */
    public function getAvailableCaptcha()
    {
        $listAvailableCaptcha = [];

        foreach ($this->adapters as $adapter) {
            if ($adapter->canBeUsed()) {
                $listAvailableCaptcha[] = $adapter;
            }
        }

        return $listAvailableCaptcha;
    }
}
