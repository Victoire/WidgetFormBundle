@mink:selenium2 @alice(Page) @reset-schema
Feature: Create a Form widget With Captcha GregwarCaptcha

  Scenario: I valid the captcha form correctly
    Given I maximize the window
    And I am on homepage
    And I wait 3 second
    When I switch to "layout" mode
    Then I should see "New content"
    When I select "Form" from the "1" select of "main_content" slot
    And I wait 5 seconds
    Then I fill in "_a_static_widget_form[submitLabel]" with "send"
    When I select "GregwarCaptcha" from "_a_static_widget_form[captcha]"
    Then I should see "GregwarCaptcha"
    And I click the "#questions-form-list > li > a" element
    Then I fill in "_a_static_widget_form[questions][0][title]" with "Name"
    And I submit the widget
    And I wait 5 seconds
    Then I switch to "readonly" mode
    When I fill the captcha "captcha-code-1" "correctly"
    Then I press "form-1-submit"
    And I wait 2 second
    Then I should see "Thank you for your participation"

  Scenario: I valid the captcha form badly
    Given I maximize the window
    And I am on homepage
    And I wait 3 second
    When I switch to "layout" mode
    Then I should see "New content"
    When I select "Form" from the "1" select of "main_content" slot
    And I wait 5 seconds
    Then I fill in "_a_static_widget_form[submitLabel]" with "send"
    When I select "GregwarCaptcha" from "_a_static_widget_form[captcha]"
    Then I should see "GregwarCaptcha"
    And I click the "#questions-form-list > li > a" element
    Then I fill in "_a_static_widget_form[questions][0][title]" with "Name"
    And I submit the widget
    And I wait 5 seconds
    Then I switch to "readonly" mode
    When I fill the captcha "captcha-code-1" "baldy"
    Then I press "form-1-submit"
    And I wait 2 second
    Then I should see "Captcha is not valid"


  Scenario: I valid two form with captcha correctly
    Given I maximize the window
    And I am on homepage
    And I wait 3 second
    When I switch to "layout" mode
    Then I should see "New content"
    When I select "Form" from the "1" select of "main_content" slot
    And I wait 5 seconds
    Then I fill in "_a_static_widget_form[submitLabel]" with "send"
    When I select "GregwarCaptcha" from "_a_static_widget_form[captcha]"
    Then I should see "GregwarCaptcha"
    And I click the "#questions-form-list > li > a" element
    Then I fill in "_a_static_widget_form[questions][0][title]" with "Name"
    And I submit the widget
    And I wait 5 seconds
    Then I should see "New content"
    When I select "Form" from the "1" select of "main_content" slot
    And I wait 5 seconds
    Then I fill in "_a_static_widget_form[submitLabel]" with "send"
    When I select "GregwarCaptcha" from "_a_static_widget_form[captcha]"
    Then I should see "GregwarCaptcha"
    And I click the "#questions-form-list > li > a" element
    Then I fill in "_a_static_widget_form[questions][0][title]" with "Name"
    And I submit the widget
    And I wait 5 seconds
    Then I switch to "readonly" mode
    When I fill the captcha "captcha-code-1" "correctly"
    Then I press "form-1-submit"
    And I wait 2 second
    Then I should see "Thank you for your participation"
    When I fill the captcha "captcha-code-2" "correctly"
    Then I press "form-2-submit"
    And I wait 2 second
    Then I should see "Thank you for your participation"
