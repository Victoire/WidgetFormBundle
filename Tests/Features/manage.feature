@mink:selenium2 @alice(Page) @reset-schema
Feature: Manage a Form widget

    Background:
        Given I am on homepage

    @smartStep
    Scenario: I can create a new Form widget
        When I switch to "layout" mode
        Then I should see "New content"
        When I select "Form" from the "1" select of "main_content" slot
        Then I should see "Widget (Form)"
        And I should see "1" quantum
        When I fill in "_a_static_widget_form[title]" with "My form title"
        And I fill in "_a_static_widget_form[submitLabel]" with "Just do it !"
        And I fill in "_a_static_widget_form[submitIcon]" with "fa-hand-o-right"
        And I select "Warning" from "_a_static_widget_form[submitClass]"
        And I fill in "_a_static_widget_form[successMessage]" with "Well done !"

        #Short open response
        And I add a question to the form
        And I fill in "_a_static_widget_form[questions][0][title]" with "This is an easy one"
        And I fill in "_a_static_widget_form[questions][0][prefix]" with "Come on !"
        And I check the "_a_static_widget_form[questions][0][required]" checkbox
        And I select "Short open response" from "_a_static_widget_form[questions][0][type]"

        #Long open response
        And I add a question to the form
        And I fill in "_a_static_widget_form[questions][1][title]" with "Second question"
        And I select "Long open response" from "_a_static_widget_form[questions][1][type]"

        #Date
        And I add a question to the form
        And I fill in "_a_static_widget_form[questions][2][title]" with "Third question"
        And I select "Date" from "_a_static_widget_form[questions][2][type]"

        #Email
        And I add a question to the form
        And I fill in "_a_static_widget_form[questions][3][title]" with "Fourth question"
        And I select "Email" from "_a_static_widget_form[questions][3][type]"

        #Checkbox
        And I add a question to the form
        And I fill in "_a_static_widget_form[questions][4][title]" with "Fifth question"
        And I select "Checkbox" from "_a_static_widget_form[questions][4][type]"

        #Multiple choice
        And I add a question to the form
        And I fill in "_a_static_widget_form[questions][5][title]" with "Sixth question"
        And I select "Multiple choice" from "_a_static_widget_form[questions][5][type]"
        And I add a choice for question "5"
        And I fill in "_a_static_widget_form[questions][5][proposal][0]" with "Multiple choice 1"
        And I add a choice for question "5"
        And I fill in "_a_static_widget_form[questions][5][proposal][1]" with "Multiple choice 2"
        And I add a choice for question "5"
        And I fill in "_a_static_widget_form[questions][5][proposal][2]" with "Multiple choice 3"

        #Unique choice
        And I add a question to the form
        And I fill in "_a_static_widget_form[questions][6][title]" with "Seventh question"
        And I select "Unique choice" from "_a_static_widget_form[questions][6][type]"
        And I add a choice for question "6"
        And I fill in "_a_static_widget_form[questions][6][proposal][0]" with "Unique choice 1"
        And I add a choice for question "6"
        And I fill in "_a_static_widget_form[questions][6][proposal][1]" with "Unique choice 2"
        And I add a choice for question "6"
        And I fill in "_a_static_widget_form[questions][6][proposal][2]" with "Unique choice 3"

        And I submit the widget

        #Test display
        Then I should see the success message for Widget edit
        And I should see "My form title"
        And I should see "This is an easy one"
        And I should see "Come on !"
        And I should see a simple input for question "1"
        And I should see "Second question"
        And I should see a textarea for question "2"
        And I should see "Third question"
        And I should see a date input for question "3"
        And I should see "Fourth question"
        And I should see an email input for question "4"
        And I should see "Fifth question"
        And I should see a checkbox for question "5"
        And I should see "Sixth question"
        And I should see a multiple choice for question "6"
        And I should see choice "Multiple choice 1" for multiple choice "6"
        And I should see choice "Multiple choice 2" for multiple choice "6"
        And I should see choice "Multiple choice 3" for multiple choice "6"
        And I should see "Seventh question"
        And I should see a single choice for question "7"
        And I should see choice "Unique choice 1" for single choice "7"
        And I should see choice "Unique choice 2" for single choice "7"
        And I should see choice "Unique choice 3" for single choice "7"
        And I should see submit button "Just do it !" with icon "fa-hand-o-right" and style "Warning"

    Scenario: I can use a Form widget
        Given I can create a new Form widget
        And I fill in "cms_form_content[questions][1][]" with "My answer to question 2"
        And I select "21" from "cms_form_content[questions][2][Day]"
        And I select "April" from "cms_form_content[questions][2][Month]"
        And I select "1954" from "cms_form_content[questions][2][Year]"
        And I fill in "cms_form_content[questions][3][]" with "My answer to question 4"
        And I check the "cms_form_content[questions][4][]" checkbox
        And I select "Multiple choice 1" from "cms_form_content[questions][5][proposal][]"
        And I select "Multiple choice 3" from "cms_form_content[questions][5][proposal][]"
        And I select "Unique choice 2" from "cms_form_content[questions][6][proposal][]"
        And I press "Just do it !"
        Then I should be on "/en/"
        And I should not see "Well done !"
        When I fill in "cms_form_content[questions][0][]" with "My answer to question 1"
        And I fill in "cms_form_content[questions][3][]" with "user@domain"
        And I press "Just do it !"
        Then I should be on "/en/"
        And I should see "user@domain is not a valid email"
        When I fill in "cms_form_content[questions][3][]" with "user@domain.com"
        And I press "Just do it !"
        Then I should be on "/en/"
        And I should see "Well done !"