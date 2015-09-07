$(document).ready(function () {
    $('form.widget-form').each(function () {
        $(this).submit(function (event) {
            $('input[type="email"]', this).each(function(){
                var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
                var form_group = $(this).parents('.form-group');
                if(re.test($(this).val()) !== true) {
                    form_group.addClass('has-error');
                    form_group.find('.help-block').text($(this).val() + ' n\'est pas un email valide').show();
                    event.preventDefault();
                } else {
                    form_group.removeClass('has-error');
                    form_group.find('.help-block').text('').hide();
                }
            });
        });
    });
});