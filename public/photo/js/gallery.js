import { photos } from './application-data.js';

getPhotos().then( data => {
        const photoMarkup = formatOutput(data);
        $('#photos').html(photoMarkup);
    });

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
                <p class="mr-2 text-right" style="float: right;">
                    <i class="fas fa-heart"></i>
                    <i class="fas fa-comment-alt"></i>
                </p>
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
    const filename = '/Resources/Pictures/Favourites/' + photo.filename;
    $('#modal-image').attr({'src': filename, 'alt': photo.title});
    $('#modalIMG').modal();
});