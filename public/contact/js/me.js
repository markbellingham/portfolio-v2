import { buildCaptchaIcons } from '../../common/functions/general.js';
let chosenIcon = {};

buildCaptchaIcons(6, icons => {
    $('#contact-icons').html(icons.chosenIconHtml + icons.iconsHtml);
    chosenIcon = icons.chosenIcon;
});

$('#send-email-btn').click( function() {
    const form = document.getElementById('send-email-form');
    if(form.reportValidity()) {
        const formData = $(form).serializeArray();
        formData.push({name: 'send-email', value: true});
        $.ajax({
            url: `/api/v1/contact.php`,
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: response => {
                if(response === true) {
                    form.reset();
                    const icons = buildCaptchaIcons(6);
                    $('#contact-icons').html(icons);
                }
            }
        });
    }
});