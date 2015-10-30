<?php

namespace Victoire\Widget\FormBundle\Controller;

use Gedmo\Sluggable\Util\Urlizer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;
use Victoire\Bundle\MediaBundle\Entity\Media;
use Victoire\Widget\FormBundle\Entity\WidgetForm;

/**
 * FormController.
 *
 * @Route("/_victoire_form")
 */
class FormController extends Controller
{
    /**
     * Handle the form submission.
     *
     * @param Request $request
     *
     * @Route("/addFormAnswerAction", name="victoire_contact_form_result")
     *
     * @return array
     */
    public function addFormAnswerAction(Request $request)
    {
        $emailSend = false;
        $regexErrors = [];
        if ($request->getMethod() != 'POST' && $request->getMethod() != 'PUT') {
            throw $this->createNotFoundException();
        }
        $_taintedValues = $this->getRequest()->request->all()['cms_form_content'];
        /** @var WidgetForm $widget */
        $widget = $this->get('doctrine.orm.entity_manager')->getRepository('VictoireWidgetFormBundle:WidgetForm')->find($_taintedValues['id']);

        foreach ($_taintedValues['questions'] as $question) {
            if (in_array($question['type'], ['text', 'textarea', 'email']) && !empty($question[0])) {
                $data[] = [
                    'label' => $question['label'],
                    'value' => $question[0],
                ];
                if (isset($question['regex']) && !empty($question['regex'])) {
                    $regex = $question['regex'];
                    $regexTitle = null;
                    $regex = '/'.$regex.'/';
                    $isValid = preg_match($regex, $question[0]);
                    if (isset($question['regexTitle']) && !empty($question['regexTitle'])) {
                        $regexTitle = $question['regexTitle'];
                    }
                    if ($isValid !== 1) {
                        $regexErrors[] = $regexTitle;
                    }
                }
            } elseif (in_array($question['type'], ['checkbox', 'radio'])
                && !empty($question['proposal'][0])) {
                $checkboxValues = $question['proposal'];
                $data[] = [
                    'label' => $question['label'],
                    'value' => implode(', ', $checkboxValues),
                ];
            } elseif (
                $question['type'] == 'date'
                && !empty($question['Day'])
                && !empty($question['Month'])
                && !empty($question['Year'])) {
                $label = $question['label'];
                $data[] = [
                    'label' => $label,
                    'value' => $question['Day'].' '.$question['Month'].' '.$question['Year'],
                ];
            } elseif ($question['type'] == 'boolean') {
                $label = 'victoire_widget_form.boolean.false';
                if (!empty($question[0])) {
                    $label = 'victoire_widget_form.boolean.true';
                }
                $data[] = [
                    'label' => $question['label'],
                    'value' => $this->get('translator')->trans($label),
                ];
            }
        }

        ///////////////////////// SEND EMAIL TO ADMIN (set in the form or default one)  //////////////////////////////////////////

        //$isSpam = $this->testForSpam($taintedValues, $request);
        $mailer = 'mailer';
        $subject = $widget->getTitle();
        $targetEmail = $widget->getTargetEmail() ? $widget->getTargetEmail() : $this->container->getParameter(
            'victoire_widget_form.default_email_address'
        );
        if ($errors = $this->get('validator')->validateValue($widget->getTargetEmail(), new EmailConstraint())) {
            try {
                $from = [
                    $this->container->getParameter('victoire_widget_form.default_email_address') => $this->container->getParameter('victoire_widget_form.default_email_label'),
                ];
                array_push($data, ['label' => 'ip', 'value' => $_SERVER['REMOTE_ADDR']]);
                $body = $this->renderView(
                    'VictoireWidgetFormBundle::managerMailTemplate.html.twig',
                    [
                        'title' => $widget->getTitle(),
                        'url'   => $request->headers->get('referer'),
                        'data'  => $data,
                    ]
                );
                if (count($regexErrors) == 0) {
                    $emailSend = true;
                    $this->createAndSendMail($subject, $from, $targetEmail, $body, 'text/html', null, [], $mailer);
                }
            } catch (\Exception $e) {
                echo $e->getTraceAsString();
            }
        }
        ///////////////////////// AUTOANSWER (if email field exists and is filled properly)  //////////////////////////////////////////
        $email = null;
        foreach ($_taintedValues['questions'] as $question) {
            if ($question['label'] == 'Email' || $question['label'] == 'email') {
                $email = $question[0];
            }
        }
        if ($widget->isAutoAnswer() === true && $email) {
            if ($errors = $this->get('validator')->validateValue($widget->getNoReply(), new EmailConstraint())) {
                try {
                    $urlizer = new Urlizer();
                    $body = $widget->getMessage();
                    preg_match_all('/{{(.*?)}}/', $body, $variables);
                    foreach ($variables[1] as $index => $variable) {
                        $pattern = '/'.$variables[0][$index].'/';
                        foreach ($_taintedValues['questions'] as $_question) {
                            //Allow exact and urlized term (ex: for a field named Prénom => prenom, Prénom, Prenom are ok)
                            if ($_question['label'] === $variable || $urlizer->urlize($_question['label']) === $urlizer->urlize($variable)) {
                                switch ($_question['type']) {
                                    case 'radio':
                                        $body = preg_replace($pattern, $_question['proposal'][0], $body);
                                        break;
                                    case 'checkbox':
                                        $body = preg_replace($pattern, implode(', ', $_question['proposal']), $body);
                                        break;
                                    case 'date':
                                        $body = preg_replace($pattern, $_question['Day'].' '.$_question['Month'].' '.$_question['Year'], $body);
                                        break;
                                    default: //text, textarea
                                        $replacement = $_question[0];
                                        $body = preg_replace($pattern, $replacement, $body);
                                }
                            }
                        }

                        //If we didn't found the variable in any field, we cleanup by removing the variable in the body to not appear like buggy to the final user
                        $body = preg_replace($pattern, '', $body);
                    }
                    //Send an email to the customer AND to the specified email target
                    $from = [
                        $this->container->getParameter('victoire_widget_form.default_email_address') => $this->container->getParameter('victoire_widget_form.default_email_label'),
                    ];

                    $body = $this->renderView(
                        'VictoireWidgetFormBundle::customerMailTemplate.html.twig',
                        [
                            'message' => $body,
                        ]
                    );
                    $attachments = [];
                    foreach (['attachmentUrl', 'attachmentUrl2', 'attachmentUrl3', 'attachmentUrl4', 'attachmentUrl5', 'attachmentUrl6', 'attachmentUrl7'] as $field) {
                        $getAttachment = 'get'.ucfirst($field);
                        /** @var Media $attachment */
                        if ($attachment = $widget->$getAttachment()) {
                            $filePath = $this->container->getParameter('kernel.root_dir').'/../web'.$attachment->getUrl();
                            $attachment = new UploadedFile($filePath, $attachment->getName());
                            $attachments[] = $attachment;
                        }
                    }
                    if (count($regexErrors) == 0) {
                        $emailSend = true;
                        $this->createAndSendMail($widget->getSubject(), $from, $email, $body, 'text/html', $widget->getNoReply(), $attachments, $mailer);
                    }
                } catch (\Exception $exc) {
                    echo $exc->getTraceAsString();
                }
            }
        }

        ///////////////////////// BUILD REDIRECT URL ACCORDING TO SUCCESS CALLBACK /////////////////////////////////////
        $redirectUrl = null;
        if ($emailSend) {
            if ($widget->getSuccessCallback() == 'notification') {
                $message = $widget->getSuccessMessage() != '' ? $widget->getSuccessMessage() : $this->get('translator')->trans('victoire_widget_form.alert.send.email.success.label');
                $this->container->get('appventus_alertifybundle.helper.alertifyhelper')->congrat($message);
            } else {
                if ($link = $widget->getLink()) {
                    $redirectUrl = $this->get('victoire_widget.twig.link_extension')->victoireLinkUrl($link->getParameters());
                }
            }
        } else {
            if ($widget->getErrorNotification() == true) {
                $message = $widget->getErrorMessage() != '' ? $widget->getErrorMessage() : $this->get('translator')->trans('victoire_widget_form.alert.send.email.error.label');
                $this->container->get('appventus_alertifybundle.helper.alertifyhelper')->scold($message);
            }
        }
        foreach ($regexErrors as $key => $error) {
            if ($error != '') {
                $this->container->get('appventus_alertifybundle.helper.alertifyhelper')->scold($error);
            }
        }
        $redirectUrl = $redirectUrl ?: $request->headers->get('referer');

        return $this->redirect($redirectUrl);
    }

    /**
     * test if values is spam for a client.
     */
    private function testForSpam($values, Request $request)
    {
        $valuesToTest = ['firstname', 'Email', 'message'];
        $valuesToAkismet = [];
        foreach ($valuesToTest as $valueToTest) {
            switch ($valueToTest) {
                case 'firstname':
                    if (isset($values[$valueToTest])) {
                        $valuesToAkismet['comment_author'] = $values[$valueToTest];
                    }
                    break;

                case 'Email':
                    if (isset($values[$valueToTest])) {
                        $valuesToAkismet['comment_author_email'] = $values[$valueToTest];
                    }
                    break;

                case 'message':
                    if (isset($values[$valueToTest])) {
                        $valuesToAkismet['comment_content'] = $values[$valueToTest];
                    }
                    break;

                default:
                    break;
            }
        }
        $valuesToAkismet['comment_author_url'] = $request->headers->get('referer');
        $valuesToAkismet['permalink'] = $request->headers->get('referer');

        if ($this->container->has('ornicar_akismet')) {
            $akismet = $this->container->get('ornicar_akismet');
            $akismet->isSpam($valuesToAkismet);

            return $akismet;
        }

        return false;
    }

    protected function createAndSendMail($subject, $from, $to, $body, $contentType = 'text/html', $replyTo = null, $attachments = [], $mailer = 'mailer')
    {
        /** @var Swift_Message $message */
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($to)
            ->setBody($body, $contentType);

        foreach ($attachments as $attachment) {
            if ($attachment instanceof UploadedFile) {
                $message
                  ->attach(\Swift_Attachment::fromPath($attachment->getPathName())
                            ->setFilename($attachment->getClientOriginalName())
                    );
            }
        }
        if ($replyTo != null) {
            $message->setReplyTo($replyTo);
        }
        $this->get($mailer)->send($message);
    }
}
