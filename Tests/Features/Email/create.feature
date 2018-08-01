@mink:selenium2 @alice(Page)  @reset-schema
Feature: Receive an email from the form

  Background:
    Given I maximize the window
    And I am on homepage
    And I wait 3 second
    When I switch to "layout" mode
    Then I should see "New content"
    When I select "Form" from the "1" select of "main_content" slot
    And I wait 5 seconds
    Then I fill in "_a_static_widget_form[submitLabel]" with "send"
    Then I fill in "_a_static_widget_form[adminSubject]" with "Contact"

  Scenario: I simple a form email contact
    And I click the "#questions-form-list a.add_question_link" element
    Then I fill in "_a_static_widget_form[questions][0][title]" with "Email"
    And I submit the widget
    And I wait 5 seconds
    Then I switch to "readonly" mode
    When I fill in "cms_form_content[questions][0][]" with "user@domain.email"
    And I press "form-1-submit"
    And I wait 2 second
    Then 1 mail should be sent
    When I open mail from "anakin@victoire.io"
    Then I should see "user@domain.email" in mail
    And I should see "user@domain.email" in mail reply-to

  Scenario: I simple a form without email contact
    And I click the "#questions-form-list a.add_question_link" element
    Then I fill in "_a_static_widget_form[questions][0][title]" with "Name"
    And I submit the widget
    And I wait 5 seconds
    Then I switch to "readonly" mode
    When I fill in "cms_form_content[questions][0][]" with "user@domain.email"
    And I press "form-1-submit"
    And I wait 2 second
    Then 1 mail should be sent
    When I open mail from "anakin@victoire.io"
    Then I should see "user@domain.email" in mail
    And I should see nothing in mail reply-to