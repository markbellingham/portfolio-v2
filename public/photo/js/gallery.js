import { photos } from './application-data.js';
import * as c from '../../common/functions/cookies.js';

const cookie = c.getCookie();

getPhotos().then( data => {
        const photoMarkup = formatOutput(data);
        $('#photos').html(photoMarkup);
    });

/**
 * Get a list of photos from the database
 * @returns {Promise<any>}
 */
async function getPhotos() {
    const result = await fetch(`/api/v1/pictures.json`);
    return await result.json();
}

/**
 * Format the photo grid on Gallery tab
 * @param {array} data
 * @returns {string}
 */
function formatOutput(data) {
    let markup = '';
    photos.length = 0;
    for(let [i, p] of data.entries()) {
        photos.push(p);
        const itemClass = i === 0 ? 'grid-item' : 'grid-item mt-2';
        markup += `
        <div class="${itemClass}">
            <img loading="lazy" width="${p.width}" height="${p.height}" src="/Resources/Pictures/Favourites/thumbs_md/${p.filename}" alt="${p.title}" data-id="${p.id}"/>
            <div class="img-overlay">
                <h5 class="ml-2">${p.title}</h5>
                <p class="ml-2" style="float: left;">${p.town}, ${p.country}</p>
                <p class="mr-2" style="float: right;">`;
                    if(p.fave_count > 0) {
                        markup += `<i class="fas fa-heart"></i> ${p.fave_count}`;
                    }
                    if(p.cmt_count > 0) {
                        markup += `<i class="fas fa-comment-alt"></i> ${p.cmt_count}`;
                    }
                markup += `</p>
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
    const photoId = Number(this.getAttribute('data-id'));
    const photo = photos.find(p => p.id === photoId );
    $('#modal-image').attr({'src': '/Resources/Pictures/Favourites/' + photo.filename, 'alt': photo.title});
    $('#modal-photo-title').text(photo.title);
    $('#modal-photo-location').text(photo.town + ', ' + photo.country);
    $('#make-favourite').attr('data-photoId', photoId.toString());
    $('#modalIMG').modal();
});

/**
 * Event handler when clicking on a heart on an image modal
 */
$('#make-favourite').on('click', function() {
    $(this).addClass('text-danger');
    const photoId = Number(this.getAttribute('data-photoid'));
    const photo = photos.find(p => p.id === photoId );
    console.log(photo);
});