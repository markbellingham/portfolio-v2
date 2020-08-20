import { photos } from './application-data.js';
import * as c from '../../common/functions/cookies.js';
import { Comments } from "../../common/comments/main.js";
import { Favourites } from "../../common/faves/main.js";
import { Tags } from '../../common/tags/main.js';
import * as fn from '../../common/functions/general.js';

let timeout = null;
let tagObjects = [];
let selectedPhotoId = null;

const cookie = c.getCookie('settings');
const userSettings = cookie ? JSON.parse(cookie) : {};

$('#photo-search-input').on('keyup', function() {
    clearTimeout(timeout);
    let input = this;
    timeout = setTimeout(function() {
        getPhotos(input.value).then( response => {
            response.data = fn.reorder(response.data, 4);
            const photoMarkup = formatPhotoGrid(response.data);
            $('#photos').html(photoMarkup);
        });
    }, 500);
});

getPhotos().then( response => {
        const photoMarkup = formatPhotoGrid(response.data);
        $('#photos').html(photoMarkup);
    });

getPhotoTags().then( response => {
    tagObjects = response.data;
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
 * @returns {Promise<any>}
 */
async function getPhotoDetails() {
    const result = await fetch(`/api/v1/photo/${selectedPhotoId}`);
    return await result.json();
}

async function getPhotoTags() {
    const result = await fetch(`/api/v1/photo-tags`);
    return await result.json();
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
 * Event handler to show large modal when clicking on a photo thumbnail
 */
$('#photos').on('click', 'img', function() {
    selectedPhotoId = Number(this.getAttribute('data-id'));
    const photo = photos.find(p => p.id === selectedPhotoId );

    const appOptions = { section: 'photo', user: userSettings, itemId: selectedPhotoId };
    const commentsApp = new Comments( appOptions );
    const favesApp = new Favourites( appOptions );
    const tagsApp = new Tags( appOptions );

    getPhotoDetails().then( response => {
        commentsApp.formatUserComments(response.comments);
        favesApp.showFavourite(response.fave_count);
        fn.setThumbnailFaveCommentCount(response);
        tagsApp.setOptions({ itemTags: response.tags, allTags: tagObjects });
    });
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
    $('#modalIMG').modal();
});

/**
 * Event handler for full size photo button on image modal
 */
$('#full-size-photo').on('click', function() {
    const photo = photos.find(p => p.id === selectedPhotoId );
    window.open(`/Resources/Pictures/${photo.directory}/${photo.filename}`);
});

// $('#available-photo-tags').on('click', '.tag-btn', function() {
//     const inputTags = getInputTags();
//     inputTags.pop();
//     const selectedButton = this.innerText;
//     inputTags.push(selectedButton);
//     const inputText = inputTags.join(', ') + ', ';
//     $('#add-photo-tags').val(inputText);
// });