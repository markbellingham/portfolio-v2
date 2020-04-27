const rootUrl = 'http://ws.audioscrobbler.com/2.0/?method=user.';
const username = '&user=markbellingham';
const APIKey = '&api_key=44f408b49165de4b9008116f204d19c4';
const dataFormat = '&format=json';

getTopAlbums(rootUrl, username, APIKey, dataFormat)
    .then( data => {
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
});

getTopArtists(rootUrl, username, APIKey, dataFormat)
    .then( data => {
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
});

getTopTracks(rootUrl, username, APIKey, dataFormat)
    .then( data => {
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
});

async function getTopAlbums(rootUrl, username, APIKey, format) {
    const result = await fetch(`${rootUrl}gettopalbums${username}${APIKey}${format}`);
    return await result.json();
}

async function getTopArtists(rootUrl, username, APIKey, format) {
    const result = await fetch(`${rootUrl}gettopartists${username}${APIKey}${format}`);
    return await result.json();
}

async function getTopTracks(rootUrl, username, APIKey, format) {
    const result = await fetch(`${rootUrl}gettoptracks${username}${APIKey}${format}`);
    return await result.json();
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