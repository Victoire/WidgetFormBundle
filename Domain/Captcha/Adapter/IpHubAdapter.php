<?php

namespace Victoire\Widget\FormBundle\Domain\Captcha\Adapter;

use Symfony\Component\HttpFoundation\RequestStack;

class IpHubAdapter implements CaptchaInterface {

    private $iphubPrivateKey;
    private $requestStack;

    public function __construct($iphubPrivateKey, RequestStack $requestStack)
    {
        $this->iphubPrivateKey = $iphubPrivateKey;
        $this->requestStack = $requestStack;
    }

    /**
     * Check if the captcha is valid or not
     * @return boolean
     */
    public function validateCaptcha()
    {
        $request = $this->requestStack->getCurrentRequest();
        return !$this->isBadIP($request->getClientIp(), $this->iphubPrivateKey);
    }

    /**
     * Get the captcha name
     * @return string
     */
    public function getName()
    {
        return 'iphub';
    }

    /**
     * Check if current configuration allow to use this captcha
     * @return boolean
     */
    public function canBeUsed()
    {
        return $this->iphubPrivateKey;
    }

    /**
     * Return all parameters necessary in the view
     * @return array
     */
    public function getTwigParameters()
    {
        return [];
    }

    private function isBadIP($ip, $key, $strict = false) {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => "http://v2.api.iphub.info/ip/{$ip}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ["X-Key: {$key}"]
        ]);

        try {
            $block = json_decode(curl_exec($ch))->block;
        } catch (Exception $e) {
            throw $e;
        }
        if ($block) {
            if ($strict) {
                return true;
            } elseif (!$strict && $block === 1) {
                return true;
            }
        }
        return false;
    }

    /**
     * Return the view path to render the widget
     */
    public function getViewPath()
    {
        // TODO: Implement getViewPath() method.
    }
}