<?php

namespace Victoire\Widget\FormBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Victoire\Widget\FormBundle\Domain\Captcha\Adapter\SecurimageAdapter;
use Victoire\Widget\FormBundle\Domain\Captcha\CaptchaHandler;

/**
 * Class CaptchaController.
 *
 * @Route("/_victoire_form_captcha")
 */
class CaptchaController extends Controller
{
    /**
     * @Route("/securimage", name="victoire_form_captcha_securimage")
     * @Method({"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function validateSecurimageAction(Request $request)
    {
        $securimage = new SecurimageAdapter($request);

        $data = [];
        if (!$securimage->validateCaptcha(false)) {
            $data = $securimage->generateNewImage();
            $data['valid'] = false;
        } else {
            $data['valid'] = true;
        }

        return new JsonResponse($data);
    }
}
