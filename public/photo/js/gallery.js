getPhotos()
    .then( data => {
        const photos = formatOutput(data);
        $('#photos').html(photos);
    });

async function getPhotos() {
    const result = await fetch(`/api/v1/pictures.json`);
    return await result.json();
}

function formatOutput(data) {
    let markup = '';
    for(let d of data) {
        markup += `
        <div class="grid-item mt-2" style="width: 100%;">
            <img loading="lazy" width="100%" src="/Resources/Pictures/Favourites/thumbs_md/${d.filename}" alt="${d.title}"/>
        </div>
        `;
    }
    return markup;
}