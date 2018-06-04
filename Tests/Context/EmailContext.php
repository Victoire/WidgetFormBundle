<?php

namespace Victoire\Widget\FormBundle\Tests\Context;

use Alex\MailCatcher\Behat\MailCatcherContext;

class EmailContext extends MailCatcherContext
{

    /**
     * @Given /^I should see "([^"]*)" in mail reply\-to$/
     */
    public function iShouldSeeInMailReplyTo($email)
    {
        if (null === $this->currentMessage) {
            throw new \RuntimeException('No message selected');
        }

        if ($this->currentMessage->getHeaders()->get('reply-to') !== $email) {
            throw new \InvalidArgumentException(sprintf('Unable to find reply-to header with email : "%s".', $email));
        }
    }

    /**
     * @Given /^I should see nothing in mail reply\-to$/
     */
    public function iShouldSeeNothingInMailReplyTo()
    {
        if (null === $this->currentMessage) {
            throw new \RuntimeException('No message selected');
        }

        if ($this->currentMessage->getHeaders()->has('reply-to')) {
            throw new \InvalidArgumentException(sprintf('Unable reply-to headers is defined'));
        }
    }
}
