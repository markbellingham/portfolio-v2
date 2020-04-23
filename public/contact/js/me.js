let chosenIcon = {};
const objParams = { icon_id: '' };

getIcons().then( icons => {
    buildIconChooser(icons);
});

// Get icons for the icon chooser
async function getIcons() {
    const result = await fetch(`/api/v1/get/contact/icons.json`);
    return await result.json();
}

function buildIconChooser(icons) {
    chosenIcon = icons[Math.floor(Math.random() * icons.length)];
    const chosenIconHtml = `<label for="icons" class="mr-5">Select the ${chosenIcon.name} icon:</label>`;
    let iconsHtml = '';
    for(let i of icons) {
        iconsHtml += `<label for="r-${i.icon_id}" class="btn btn-warning ml-1"><input type="radio" class="mr-3" name="icon" id="r-${i.icon_id}" value="${i.icon_id}"/>${i.icon}</label>`
    }
    $('#icons').html(chosenIconHtml + iconsHtml);
}

$('#send-email-btn').click( function() {
    const form = document.getElementById('send-email-form');
    if(form.reportValidity()) {
        const formData = $(form).serializeArray();
        formData.push({name: 'send-email', value: true});
        $.ajax({
            url: `/api/v1/post/contact.php`,
            method: 'POST',
            data: formData,
            dataType: 'json',
            success: response => {
                if(response === true) {
                    form.reset();
                    getIcons().then( icons => {
                        buildIconChooser(icons);
                    });
                }
            }
        });
    }
});