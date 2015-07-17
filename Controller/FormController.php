<?php

namespace Victoire\Widget\FormBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;

/**
 * FormController
 *
 * @Route("/_victoire_form")
 */
class FormController extends Controller
{
    /**
     * Handle the form submission
     *
     * @param Request $request
     *
     * @Route("/addFormAnswerAction", name="victoire_contact_form_result")
     * @return array
     */
    public function addFormAnswerAction(Request $request)
    {
        $emailSend = false;
        $regexErrors = [];
        if ($request->getMethod() != "POST" && $request->getMethod() != "PUT") {
            throw $this->createNotFoundException();
        }
        $taintedValues = $this->getRequest()->request->all()['cms_form_content'];

        foreach ($taintedValues['questions'] as $question) {
            if (in_array($question['type'], array("text", "textarea")) && !empty($question[0])) {
                $data[] =array(
                    'label' => $question["label"],
                    'value' => $question[0]
                );
                if (isset($question['regex']) && !empty($question['regex'])) {
                    $regex = $question['regex'];
                    $regexTitle = null;
                    $regex = "/".$regex."/";
                    $isValid = preg_match($regex, $question[0]);
                    if (isset($question['regexTitle']) && !empty($question['regexTitle'])) {
                        $regexTitle = $question['regexTitle'];
                    }
                    if($isValid !== 1){
                        $regexErrors[] = $regexTitle;
                    }
                }
            } elseif (in_array($question['type'], array("checkbox", "radio"))
                && !empty($question['proposal'][0])) {
                $checkboxValues = $question['proposal'];
                $data[] = array(
                    'label' => $question["label"],
                    'value' => implode(', ', $checkboxValues)
                );
            } elseif (
                $question['type'] == "date"
                && !empty($question['Day'])
                && !empty($question['Month'])
                && !empty($question['Year'])) {
                $label = $question["label"];
                $data[] = array(
                    'label' => $label,
                    'value' => $question['Day']." ".$question['Month']." ".$question['Year']
                );
            }else if($question['type'] == "boolean"){
                $label = "victoire_widget_form.boolean.false";
                if (!empty($question[0])) {
                    $label = "victoire_widget_form.boolean.true";
                }
                $data[] = array(
                    'label' => $question["label"],
                    'value' => $this->get('translator')->trans($label)
                );
            }
        }

        $isSpam = $this->testValues($taintedValues, $request);
        $mailer = 'mailer';
        $subject = $taintedValues['title'];
        if (isset($taintedValues['targetEmail']) && !empty($taintedValues['targetEmail']) && !$isSpam) {
            $targetEmail = !empty($taintedValues['targetEmail']) ? $taintedValues['targetEmail'] : $this->container->getParameter('victoire_widget_form.default_email_address');
            if ($errors = $this->get('validator')->validateValue($taintedValues['targetEmail'], new EmailConstraint())) {
                try {
                    $to = $targetEmail;
                    $from = array(
                        $this->container->getParameter('victoire_widget_form.default_email_address') => $this->container->getParameter('victoire_widget_form.default_email_label'),
                    );
                    array_push($data, array('label' => 'ip', 'value' => $_SERVER['REMOTE_ADDR']));
                    $body = $this->renderView(
                        'VictoireWidgetFormBundle::managerMailTemplate.html.twig',
                        array(
                            'title' => $taintedValues['title'],
                            'url' => $request->headers->get('referer'),
                            'data' => $data,
                        )
                    );
                    if(sizeof($regexErrors) == 0){
                        $emailSend = true;
                        $this->createAndSendMail($subject, $from, $to, $body, 'text/html', null, array(), $mailer);
                    }
                } catch (Exception $exc) {
                    echo $exc->getTraceAsString();
                }
            }
        }
        $email = null;
        foreach ($taintedValues['questions'] as $question) {
            if ($question['label'] == "Email" || $question['label'] == "email") {
                $email = $question[0];
            }
        }
        if (!empty($taintedValues['autoAnswer']) && $taintedValues['autoAnswer'] == true && !empty($email) && !$isSpam) {
            if ($errors = $this->get('validator')->validateValue($taintedValues['targetEmail'], new EmailConstraint())) {
                try {
                    $body = $taintedValues['message'];
                    preg_match_all("/{{.*?}}/", $body, $variables);
                    foreach ($variables[0] as $variable) {
                        if (!empty($taintedValues["questions"][$this->slugify($variable)])) {
                            if (in_array($taintedValues["questions"][$this->slugify($variable)]['type'], array("text", "textarea")) && !empty($taintedValues["questions"][$this->slugify($variable)][0])) {
                                $body = preg_replace("/$variable/", $taintedValues["questions"][$this->slugify($variable)][0], $body);
                            } elseif ($taintedValues["questions"][$this->slugify($variable)]['type'] == "radio" && !empty($taintedValues["questions"][$this->slugify($variable)]["proposal"][0])) {
                                $body = preg_replace("/$variable/", $taintedValues["questions"][$this->slugify($variable)]["proposal"][0], $body);
                            } elseif ($taintedValues["questions"][$this->slugify($variable)]['type'] == "checkbox" && !empty($taintedValues["questions"][$this->slugify($variable)]["proposal"])) {
                                $body = preg_replace("/$variable/", implode(', ', $taintedValues["questions"][$this->slugify($variable)]['proposal']), $body);
                            } elseif ($taintedValues["questions"][$this->slugify($variable)]['type'] == "date" && !empty($taintedValues["questions"][$this->slugify($variable)]['Day']) && !empty($taintedValues["questions"][$this->slugify($variable)]['Month']) && !empty($taintedValues["questions"][$this->slugify($variable)]['Year'])) {
                                $body = preg_replace("/$variable/", $taintedValues["questions"][$this->slugify($variable)]['Day']." ".$taintedValues["questions"][$this->slugify($variable)]['Month']." ".$taintedValues["questions"][$this->slugify($variable)]['Year'], $body);
                            }
                            $body = preg_replace("/$variable/", "", $body);
                        }
                    }
                    //Send an email to the customer AND to the specified email target
                    $to = $email;
                    if ($this->container->getParameter('victoire_widget_form.default_bcc_email_address', null)) {
                        $replyTo = ($this->container->getParameter('victoire_widget_form.default_bcc_email_address'));
                    }

                    $from = array(
                        $this->container->getParameter('victoire_widget_form.default_email_address') => $this->container->getParameter('victoire_widget_form.default_email_label'),
                    );

                    $subject = ($taintedValues['subject']);
                    $body = $this->renderView(
                        'VictoireWidgetFormBundle::customerMailTemplate.html.twig',
                        array(
                            'message' => $body,
                        )
                    );
                    $em = $this->getDoctrine()->getManager();
                    $mediaRepo = $em->getRepository('\Victoire\Bundle\MediaBundle\Entity\Media');
                    $attachments = array();
                    foreach (array('attachmentUrl', 'attachmentUrl2', 'attachmentUrl3', 'attachmentUrl4', 'attachmentUrl5', 'attachmentUrl6', 'attachmentUrl7') as $field) {
                        if (!empty($taintedValues[$field])) {
                            $file = $mediaRepo->findOneById($taintedValues[$field]);
                            $filePath = $this->container->getParameter('kernel.root_dir').'/../web'.$file->getUrl();
                            $file = new UploadedFile($filePath, $file->getName());
                            $attachments[] =  $file;
                        }
                    }
                    if(sizeof($regexErrors) == 0){
                        $emailSend = true;
                        $this->createAndSendMail($subject, $from, $to, $body, 'text/html', null, $attachments, $mailer);
                    }
                } catch (Exception $exc) {
                    echo $exc->getTraceAsString();
                }
            }
        }
        if ($emailSend) {
            if($taintedValues['successNotification'] == true)
            {
                $message = $taintedValues['successMessage'] != "" ? $taintedValues['successMessage'] : $this->get('translator')->trans('victoire_widget_form.alert.send.email.success.label');
                $this->container->get('appventus_alertifybundle.helper.alertifyhelper')->congrat($message);
            }
        }else{
            if($taintedValues['errorNotification'] == true)
            {
                $message = $taintedValues['errorMessage'] != "" ? $taintedValues['errorMessage'] : $this->get('translator')->trans('victoire_widget_form.alert.send.email.error.label');
                $this->container->get('appventus_alertifybundle.helper.alertifyhelper')->scold($message);
            }
        }
        foreach ($regexErrors as $key => $error) {
            if ($error != '') {
                $this->container->get('appventus_alertifybundle.helper.alertifyhelper')->scold($error);
            }
       }
        $referer = $this->getRequest()->headers->get('referer');

        return $this->redirect($referer);
    }

    /**
     * Modifies a string to remove all non ASCII characters and spaces.
     */
    private function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

        // trim
        $text = trim($text, '-');

        // transliterate
        if (function_exists('iconv')) {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }

        // lowercase
        $text = strtolower($text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    /**
     * test if values is spam for a client
     */
    private function testValues($values, Request $request)
    {
        $valuesToTest = array('firstname', 'Email', 'message');
        $valuesToAkismet = array();
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

    protected function createAndSendMail($subject, $from, $to, $body, $contentType = 'text/html', $replyTo = null, $attachments = array(), $mailer = 'mailer')
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($from)
            ->setTo($to)
            ->setBody($body, $contentType)
            ;

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
