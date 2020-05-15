import { photos, userFaves, userId } from './application-data.js';
import * as c from '../../common/functions/cookies.js';
import { buildCaptchaIcons } from '../../common/functions/general.js';

let chosenIcon = {};

buildCaptchaIcons(4, icons => {
    $('#gallery-icons').html(icons.chosenIconHtml + icons.iconsHtml);
    chosenIcon = icons.chosenIcon;
});

const cookie = c.getCookie();

getPhotos().then( response => {
        const photoMarkup = formatPhotoGrid(response.data);
        $('#photos').html(photoMarkup);
    });

/**
 * Get a list of photos from the database
 * @returns {Promise<any>}
 */
async function getPhotos() {
    const result = await fetch(`/api/v1/photos.json`);
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
                <p class="mr-2" style="float: right;">`;
                    if(p.fave_count > 0) {
                        markup += `<i class="fas fa-heart"></i> ${p.fave_count}`;
                    }
                    if(p.comment_count > 0) {
                        markup += `<i class="fas fa-comment-alt"></i> ${p.comment_count}`;
                    }
                markup += `</p>
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
            <div class="col-md-12">
                <small class="text-left"><span class="text-primary">${c.name}</span> - ${c.created}</small>
                <p class="text-left">${c.comment}</p>
            </div>
        `;
        }
    } else {
        markup = '<p class="text-center">No Comments</p>';
    }
    return markup;
}

/**
 * Event handler to show large modal when clicking on a photo thumbnail
 */
$('#photos').on('click', 'img', function() {
    const photoId = Number(this.getAttribute('data-id'));
    getPhotoDetails(photoId).then( response => {
        const commentMarkup = formatComments(response.data.comments);
        $('#comments').html(commentMarkup);
    });
    const photo = photos.find(p => p.id === photoId );
    $('#modal-image').attr({'src': `/Resources/Pictures/${photo.directory}/${photo.filename}`, 'alt': photo.title});
    $('#modal-photo-title').text(photo.title);
    $('#modal-photo-location').text(photo.town + ', ' + photo.country);
    if(userFaves.indexOf(photoId) > -1) {
        $('#make-favourite').addClass('text-danger').attr('data-photoId', photoId.toString());
    } else {
        $('#make-favourite').removeClass('text-danger').attr('data-photoId', photoId.toString());
    }
    $('#full-size-photo').attr('data-photoid', photoId.toString());
    $('#fave-count').text(photo.fave_count);
    $('#modalIMG').modal();
});

/**
 * Event handler when clicking on a heart on an image modal
 */
$('#make-favourite').on('click', function() {
    $(this).addClass('text-danger');
    const photoId = Number(this.getAttribute('data-photoid'));
    const photo = photos.find(p => p.id === photoId );
    if(userFaves.indexOf(photoId) < 0) {
        userFaves.push(photoId);
        photo.fave_count++;
        $('#fave-count').text(photo.fave_count);
    }
    console.log(userFaves);
});

/**
 * Event handler for full size photo button on image modal
 */
$('#full-size-photo').on('click', function() {
    const photoId = Number(this.getAttribute('data-photoid'));
    const photo = photos.find(p => p.id === photoId );
    window.open('/Resources/Pictures/Favourites/' + photo.filename);
});