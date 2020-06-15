import { photos, userFaves, userId } from './application-data.js';
import * as c from '../../common/functions/cookies.js';
import { buildCaptchaIcons, formToJSON } from '../../common/functions/general.js';

let chosenIcon = {};
let timeout = null;

const userSettings = JSON.parse(c.getCookie('settings'));

$('#photo-search-input').on('keyup', function() {
    clearTimeout(timeout);
    let input = this;
    timeout = setTimeout(function() {
        getPhotos(input.value).then( response => {
            const photoMarkup = formatPhotoGrid(response.data);
            $('#photos').html(photoMarkup);
        });
    }, 500);
});

getPhotos().then( response => {
        const photoMarkup = formatPhotoGrid(response.data);
        $('#photos').html(photoMarkup);
    });

/**
 * Get a list of photos from the database
 * @returns {Promise<any>}
 */
async function getPhotos(searchTerm = null) {
    const url = searchTerm ? `/api/v1/photos/${searchTerm}` : '/api/v1/photos';
    const result = await fetch(url);
    return await result.json();
}

/**
 * Get full information about one photo
 * @param {int} photoId
 * @returns {Promise<any>}
 */
async function getPhotoDetails(photoId) {
    const result = await fetch(`/api/v1/photo/${photoId}`);
    return await result.json();
}

/**
 *
 * @param {int} userId
 * @returns {Promise<any>}
 */
async function getUserDetails(userId) {
    const result = await fetch(`/api/v1/user/${userId}`);
    return result.json();
}

/**
 * Format the photo grid on Gallery tab
 * @param {array} data
 * @returns {string}
 */
function formatPhotoGrid(data) {
    let markup = '';
    photos.length = 0;
    for(let [i, p] of data.entries()) {
        photos.push(p);
        const itemClass = i === 0 ? 'grid-item' : 'grid-item mt-2';
        markup += `
        <div class="${itemClass}">
            <img loading="lazy" width="${p.width}" height="${p.height}" src="/Resources/Pictures/${p.directory}/thumbs_md/${p.filename}" alt="${p.title}" data-id="${p.id}"/>
            <div class="img-overlay">
                <h5 class="ml-2">${p.title}</h5>
                <p class="ml-2" style="float: left;">${p.town}, ${p.country}</p>
                <p class="mr-2" style="float: right;">
                    <span id="pcounts-${p.id}">`;
                    if(p.fave_count > 0) {
                        markup += `<i class="fas fa-heart"></i> ${p.fave_count} `;
                    }
                    if(p.comment_count > 0) {
                        markup += `<i class="fas fa-comment-alt"></i> ${p.comment_count}`;
                    }
                markup += `</span></p>
            </div>
        </div>
        `;
    }
    return markup;
}

/**
 * Format the comments for the photo modal
 * @param {array} comments
 */
function formatComments(comments) {
    let markup = ``;
    if(comments.length > 0) {
        for(let c of comments) {
            markup += `
            <div class="text-left mt-2">
                <h5>
                    <span class="text-primary">Comments:</span>
                </h5>
            </div>
            <div class="col-md-12">
                <small class="text-left"><span class="text-primary">${c.name}</span> - ${c.created}</small>
                <p class="text-left">${c.comment}</p>
            </div>
        `;
        }
    }
    return markup;
}

/**
 * Adds icon and number to the photo thumbnail if it has comments and/or favourites
 * @param {object} response
 * @param {int} photoId
 */
function setThumbnailFaveCommentCount(response, photoId) {
    let markup = '';
    if(response.fave_count > 0) {
        markup += `<i class="fas fa-heart"></i> ${response.fave_count} `;
    }
    if(response.comment_count > 0) {
        markup += `<i class="fas fa-comment-alt"></i> ${response.comment_count}`;
    }
    $(`#pcounts-${photoId}`).html(markup);
}

/**
 * Event handler to show large modal when clicking on a photo thumbnail
 */
$('#photos').on('click', 'img', function() {
    const photoId = Number(this.getAttribute('data-id'));
    getPhotoDetails(photoId).then( response => {
        const commentMarkup = formatComments(response.comments);
        $('#comments').html(commentMarkup);
        $('#fave-count').text(response.fave_count);
        setThumbnailFaveCommentCount(response, photoId);
    });
    buildCaptchaIcons(4, icons => {
        $('#gallery-icons').html(icons.chosenIconHtml + icons.iconsHtml);
        chosenIcon = icons.chosenIcon;
    });
    const photo = photos.find(p => p.id === photoId );
    if(photo.width/photo.height < 0.90) {
        $('#modal-image-container').removeClass('col-md-9').addClass('col-md-7');
        $('#modal-text-container').removeClass('col-md-3').addClass('col-md-5');
    } else {
        $('#modal-image-container').removeClass('col-md-7').addClass('col-md-9');
        $('#modal-text-container').removeClass('col-md-5').addClass('col-md-3');
    }
    $('#modal-image').attr({'src': `/Resources/Pictures/${photo.directory}/${photo.filename}`, 'alt': photo.title});
    $('#modal-photo-title').text(photo.title);
    $('#modal-photo-location').text(photo.town + ', ' + photo.country);
    if(userFaves.indexOf(photoId) > -1) {
        $('#make-favourite').addClass('text-danger').attr('data-photoId', photoId.toString());
    } else {
        $('#make-favourite').removeClass('text-danger').attr('data-photoId', photoId.toString());
    }
    $('#full-size-photo').attr('data-photoid', photoId.toString());
    $('#comment-photoId').val(photoId);
    $('#photo-comment').val('');
    $('#modalIMG').modal();
});

/**
 * Event handler when clicking on a heart on an image modal
 */
$('#make-favourite').on('click', function() {
    const photoId = Number(this.getAttribute('data-photoid'));
    const photo = photos.find(p => p.id === photoId );
    const secret = $('#server-secret').val();
    if(userFaves.indexOf(photoId) < 0) {
        fetch(`/api/v1/photo/${photoId}`, {
            method: 'POST',
            body: JSON.stringify({'task': 'addFave', 'secret': secret}),
            credentials: 'include'
        })
            .then( res => res.json() )
            .then( response => {
                if(response.success) {
                    this.classList.add('text-danger');
                    userFaves.push(photoId);
                    photo.fave_count = response.fave_count;
                    setThumbnailFaveCommentCount(response, photoId);
                }
            });
    }
});

/**
 * Event handler for full size photo button on image modal
 */
$('#full-size-photo').on('click', function() {
    const photoId = Number(this.getAttribute('data-photoid'));
    const photo = photos.find(p => p.id === photoId );
    window.open('/Resources/Pictures/Favourites/' + photo.filename);
});

/**
 * Event handler for submitting a comment
 */
$('#photo-comment-submit').click( function(e) {
    e.preventDefault();
    const form = document.getElementById('photo-comment-form');
    if(form.reportValidity()) {
        const formData = formToJSON(form);
        formData.chosenIcon = chosenIcon;
        formData.task = 'addComment';
        fetch(`/api/v1/photo/${form.photo_id.value}`, {
            method: 'POST',
            body: JSON.stringify(formData),
            credentials: 'include'
        })
            .then( res => res.json() )
            .then( response => {
                if(response.success) {
                    const commentMarkup = formatComments(response.comments);
                    $('#comments').html(commentMarkup);
                    setThumbnailFaveCommentCount(response, form.photo_id.value);
                    $('#photo-comment').val('');
                }
            });
    }
});