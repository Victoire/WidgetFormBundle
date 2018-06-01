<?php

namespace Victoire\Widget\FormBundle\Tests\Context;

use Knp\FriendlyContexts\Context\RawMinkContext;

class WidgetContext extends RawMinkContext{

    /**
     * @return DocumentElement
     */
    private function getPage()
    {
        $session = $this->getSession();
        return $session->getPage();
    }

    /**
     * @When /^I fill the captcha "([^"]*)" "([^"]*)"$/
     */
    public function iFillTheCaptcha($field, $code)
    {
        $this->getPage()->fillField($field, $code);
    }
}
