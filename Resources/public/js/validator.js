$(document).ready(function () {
    $('form.widget-form').each(function () {
        $(this).submit(function (event) {
            $('input[type="email"]', this).each(function(){
                var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
                var form_group = $(this).parents('.form-group');
                if(re.test($(this).val()) !== true) {
                    form_group.addClass('has-error');
                    form_group.find('.help-block').text(Translator.trans('victoire_widget_form.email.not.valid', {'email' : $(this).val()})).show();
                    event.preventDefault();
                } else {
                    form_group.removeClass('has-error');
                    form_group.find('.help-block').text('').hide();
                }
            });
        });
    });
});