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
     * @Route("/validate/captcha/{widget_form_id}", name="victoire_form_captcha_validate")
     * @ParamConverter("widget", class="VictoireWidgetFormBundle:WidgetForm", options={"id" = "widget_form_id"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function validateCaptchaAjaxAction(Request $request, WidgetForm $widget) {

        $captchaHandler = $this->get('victoire.form_widget.domain.captcha.handler');
        try {
            /** @var $captchaAdapter CaptchaInterface */
            $captchaAdapter = $captchaHandler->getCaptcha($widget->getCaptcha(), true);
        } catch (\Exception $e) {
            throw $this->createNotFoundException();
        }

        $data = [];
        if(!$captchaAdapter->validateCaptcha($request, false)) {
            $captchaAdapter->generateNewCaptcha();
            $data = $captchaAdapter->getTwigParameters();
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
    public function rendererCaptchaAction(Request $request, WidgetForm $widget) {

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
