getSavedTopAlbums().then( data => {
    if(dataIsStale(data.date)) {
        fetch('/api/v1/lastfm').then();
    }
    formatTopAlbums(data.data);
});

getSavedTopTracks().then( data => {
    formatTopTracks(data.data);
});

getSavedTopArtists().then( data => {
    formatTopArtists(data.data);
});

async function getSavedTopAlbums() {
    const result = await fetch(`/playlists/json/top-albums.json`);
    return await result.json();
}

async function getSavedTopArtists() {
    const result = await fetch(`/playlists/json/top-artists.json`);
    return await result.json();
}

async function getSavedTopTracks() {
    const result = await fetch(`/playlists/json/top-tracks.json`);
    return await result.json();
}

function formatTopAlbums(data) {
    const albums = data.topalbums.album;
    let output = '<table class="table table-condensed table-hover" style="width: 100%;">';
    output += '<tr><th></th><th>Album</th><th class="text-right">Play Count</th></tr>';
    for(let [i, album] of albums.entries()) {
        const hidden = i < 5 ? '' : 'collapse';
        output += `<tr class="${hidden}"><td>${(i + 1).toString().padStart(2, '0')}</td>`;
        output += `<td>${album.name}<br>`;
        output += `${album.artist.name}</td>`;
        output += `<td class="text-right">${album.playcount}</td></tr>`;
    }
    output += '</table>';
    $('#top-albums').html(output);
}

function formatTopArtists(data) {
    const artists = data.topartists.artist;
    let output = '<table class="table table-condensed table-hover" style="width: 100%;">';
    output += '<tr><th></th><th>Artist</th><th class="text-right">Play Count</th></tr>';
    for(let [i, artist] of artists.entries()) {
        const hidden = i < 5 ? '' : 'collapse';
        output += `<tr class="${hidden}"><td>${(i + 1).toString().padStart(2, '0')}</td>`;
        output += `<td>${artist.name}</td>`;
        output += `<td class="text-right">${artist.playcount}</td></tr>`;
    }
    output += '</table>';
    $('#top-artists').html(output);
}

function formatTopTracks(data) {
    const tracks = data.toptracks.track;
    let output = '<table class="table table-condensed table-hover" style="width: 100%;">';
    output += '<tr><th></th><th>Track</th><th class="text-right">Play Count</th></tr>';
    for(let [i, track] of tracks.entries()) {
        const hidden = i < 5 ? '' : 'collapse';
        output += `<tr class="${hidden}"><td>${(i + 1).toString().padStart(2, '0')}</td>`;
        output += `<td>${track.name}<br>`;
        output += `${track.artist.name}</td>`;
        output += `<td class="text-right">${track.playcount}</td></tr>`;
    }
    output += '</table>';
    $('#top-tracks').html(output);
}

function dataIsStale(date) {
    const q = new Date();
    const d = q.getDate();
    const m = q.getMonth();
    const y = q.getFullYear();

    const today = new Date(y, m, d);
    const savedDate = new Date(date);

    return today > savedDate;
}

$('#top-albums-toggle').click( function() {
    $('#top-albums').find('.collapse').slideToggle();
    switchChevrons($(this));
});
$('#top-artists-toggle').click( function() {
    switchChevrons($(this));
    $('#top-artists').find('.collapse').slideToggle();
});
$('#top-tracks-toggle').click( function() {
    switchChevrons($(this));
    $('#top-tracks').find('.collapse').slideToggle();
});
function switchChevrons(t) {
    if(t.hasClass('fa-chevron-down')) {
        t.removeClass('fa-chevron-down').addClass('fa-chevron-up');
    } else {
        t.removeClass('fa-chevron-up').addClass('fa-chevron-down');
    }
}