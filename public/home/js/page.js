import * as c from '../../common/functions/cookies.js';
import * as g from '../../common/functions/general.js';

const settings = JSON.parse($('#cookie-settings').text());
let users = [];

fetch(`/api/v1/users`)
    .then( response => response.json() )
    .then( response => {
        users = response.data;
    });



$('.cookie-permissions-btn').click( function() {
    settings.permission = this.getAttribute('data-permission');
    settings.uuid = settings.permission === 'yes' ? g.uuidv4() : "";
    if(settings.permission === 'yes') {
        $('#usernameModal').modal();
    } else {
        c.setCookie('settings', JSON.stringify(settings));
    }
});

$('#username').on('keyup', function() {
    $('#username-warning').text('');
});

$('#save-username-btn').click( function() {
    settings.username = $('#username').val();
    const secret = $('#secret').val();
    if(checkConditions(settings.username)) {
        c.setCookie('settings', JSON.stringify(settings));
        $('#usernameModal').modal('hide');
        $('#username').val('');
        saveUser(settings, secret);
    }
});

function checkConditions(username) {
    if(users.find( u => u.name.toLowerCase() === username.toLowerCase() )) {
        $('#username-warning').text('Sorry, that name is already taken');
        return false;
    }
    if(username.trim() === "") {
        $('#username-warning').text("Input is blank");
        return false;
    }
    return true;
}

/**
 * Save a new user
 * @param settings
 * @param secret
 */
function saveUser(settings, secret) {
    settings.secret = secret;
    fetch(`/api/v1/users`, {
        method: 'POST',
        body: JSON.stringify(settings),
        headers: { 'Content-Type': 'application/json' }
    })
        .then( res => res.json() )
        .then( response => {
            console.log(response);
            if(response.data.id !== null) {
                users.push(response.data);
                $('#cookie-permissions').addClass('d-none');
                $('#change-cookie-permissions-div').removeClass('d-none');
            }
        });
}