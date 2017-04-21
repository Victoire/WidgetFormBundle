<?php

namespace Victoire\Widget\FormBundle\Tests\Context;

use Knp\FriendlyContexts\Context\RawMinkContext;

class WidgetContext extends RawMinkContext
{
    /**
     * @When /^I add a question to the form$/
     */
    public function iAddAQuestionToTheForm()
    {
        $page = $this->getSession()->getPage();

        $link = $page->find('xpath', 'descendant-or-self::a[contains(@class, "add_question_link")]');

        if (null === $link) {
            $message = 'The link to add a question could not be found.';
            throw new \Behat\Mink\Exception\ResponseTextException($message, $this->getSession());
        }

        $link->click();
    }

    /**
     * @Then /^I should see submit button "(.+)" with icon "(.+)" and style "(.+)"$/
     */
    public function iShouldSeeSubmitButtonWithIconAndStyle($text, $icon, $style)
    {
        $page = $this->getSession()->getPage();

        $button = $page->find('xpath', sprintf(
            'descendant-or-self::button[contains(@class, "btn-%s") and @type = "submit" and  contains(normalize-space(.), "%s")]/span[contains(@class, "%s")]',
            strtolower($style),
            $text,
            $icon
        ));

        if (null === $button) {
            $message = sprintf(
                'Submit button with text "%s", icon "%s" and style "%s" could not be found.',
                $text,
                $icon,
                $style
            );
            throw new \Behat\Mink\Exception\ResponseTextException($message, $this->getSession());
        }
    }

    /**
     * @When /^I add a choice for question "(.+)"$/
     */
    public function iAddAChoiceForQuestion($questionNb)
    {
        $page = $this->getSession()->getPage();

        $link = $page->find('xpath', sprintf(
            'descendant-or-self::a[contains(@class, "add_proposal%s_link")]',
            $questionNb
        ));

        if (null === $link) {
            $message = 'The link to add a question could not be found.';
            throw new \Behat\Mink\Exception\ResponseTextException($message, $this->getSession());
        }

        $link->click();
    }

    /**
     * @When /^I should see (a simple input|a textarea|a date input|an email input|a checkbox|a multiple choice|a single choice) for question "(.+)"$/
     */
    public function iShouldSeeAFormElementForQuestion($formElement, $questionNb)
    {
        $questionNb--;

        $page = $this->getSession()->getPage();

        switch ($formElement) {
            case 'a simple input':
                $xpaths = ['descendant-or-self::input[@type = "text" and @name = "cms_form_content[questions][%s][]"]'];
                break;
            case 'a textarea':
                $xpaths = ['descendant-or-self::textarea[@type = "textarea" and @name = "cms_form_content[questions][%s][]"]'];
                break;
            case 'a date input':
                $xpaths = [
                    'descendant-or-self::select[@name = "cms_form_content[questions][%s][Day]"]',
                    'descendant-or-self::select[@name = "cms_form_content[questions][%s][Month]"]',
                    'descendant-or-self::select[@name = "cms_form_content[questions][%s][Year]"]',
                ];
                break;
            case 'an email input':
                $xpaths = ['descendant-or-self::input[@type = "email" and @name = "cms_form_content[questions][%s][]"]'];
                break;
            case 'a checkbox':
                $xpaths = ['descendant-or-self::input[@type = "checkbox" and @name = "cms_form_content[questions][%s][]"]'];
                break;
            case 'a multiple choice':
                $xpaths = ['descendant-or-self::select[@multiple and @name = "cms_form_content[questions][%s][proposal][]"]'];
                break;
            case 'a single choice':
                $xpaths = ['descendant-or-self::select[not(@multiple) and @name = "cms_form_content[questions][%s][proposal][]"]'];
                break;
        }

        foreach ($xpaths as $xpath) {
            $formElement = $page->find('xpath', sprintf($xpath, $questionNb));

            if (null === $formElement) {
                $message = sprintf(
                    'The form element for question %s could not be found.',
                    $questionNb + 1
                );
                throw new \Behat\Mink\Exception\ResponseTextException($message, $this->getSession());
            }
        }
    }

    /**
     * @When /^I should see choice "(.+)" for (multiple|single) choice "(.+)"$/
     */
    public function iShouldSeeMultipleChoiceForQuestion($text, $formElement, $questionNb)
    {
        $questionNb--;

        $page = $this->getSession()->getPage();

        switch ($formElement) {
            case 'multiple':
                $xpath = 'descendant-or-self::select[@multiple and @name = "cms_form_content[questions][%s][proposal][]"]//option[contains(normalize-space(.), "%s")]';
                break;
            case 'single':
                $xpath = 'descendant-or-self::select[not(@multiple) and @name = "cms_form_content[questions][%s][proposal][]"]//option[contains(normalize-space(.), "%s")]';
                break;
        }

        $formElement = $page->find('xpath', sprintf(
            $xpath,
            $questionNb,
            $text
        ));

        if (null === $formElement) {
            $message = sprintf(
                'The choice element for question %s could not be found.',
                $questionNb + 1
            );
            throw new \Behat\Mink\Exception\ResponseTextException($message, $this->getSession());
        }
    }
}
