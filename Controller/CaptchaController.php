<?php

namespace Victoire\Widget\FormBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Victoire\Widget\FormBundle\Domain\Captcha\Adapter\CaptchaInterface;
use Victoire\Widget\FormBundle\Domain\Captcha\Adapter\SecurimageAdapter;
use Victoire\Widget\FormBundle\Entity\WidgetForm;

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

    /**
     * @Route("/render/captcha/{widget_form_id}", name="victoire_form_captcha_render")
     * @ParamConverter("widget", class="VictoireWidgetFormBundle:WidgetForm", options={"id" = "widget_form_id"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function rendererCaptcha(Request $request, WidgetForm $widget) {

        if(!$request->isXmlHttpRequest()) {
            throw $this->createNotFoundException();
        }

        $captchaHandler = $this->get('victoire.form_widget.domain.captcha.handler');
        try {
            /** @var $captchaAdapter CaptchaInterface */
            $captchaAdapter = $captchaHandler->getCaptcha($widget->getCaptcha(), true, true);
        } catch (\Exception $e) {
            throw $this->createNotFoundException();
        }

        return $this->render($captchaAdapter->getViewPath(), array_merge(['widget' => $widget], $captchaAdapter->getTwigParameters()));
    }
}
