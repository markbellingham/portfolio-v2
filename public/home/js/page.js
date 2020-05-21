import * as c from '../../common/functions/cookies.js';
import * as g from '../../common/functions/general.js';

let permission = 'no';
let uuid = '';
let username = 'Anonymous';
let users = [];

fetch(`/api/v1/users`)
    .then( response => response.json() )
    .then( response => {
        users = response.data;
    });


$('.cookie-permissions-btn').click( function() {
    permission = this.getAttribute('data-permission');
    uuid = permission === 'yes' ? g.uuidv4() : "";
    if(permission === 'yes') {
        $('#usernameModal').modal();
    } else {
        const settings = { 'permission': permission, 'uuid': uuid, 'username': username };
        c.setCookie('settings', JSON.stringify(settings));
    }
});

$('#username').on('keyup', function() {
    $('#username-warning').text('');
});

$('#save-username-btn').click( function() {
    const username = $('#username').val();
    const secret = $('#secret').val();
    if(checkConditions(username)) {
        const settings = { 'permission': permission, 'uuid': uuid, 'username': username };
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