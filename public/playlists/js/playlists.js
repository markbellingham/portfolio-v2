const rootUrl = 'http://ws.audioscrobbler.com/2.0/?method=user.';
const username = '&user=markbellingham';
const APIKey = '&api_key=44f408b49165de4b9008116f204d19c4';
const dataFormat = '&format=json';

getTopAlbums(rootUrl, username, APIKey, dataFormat)
    .then( data => {
        const albums = data.topalbums.album;
        let output = '<table class="table table-condensed table-hover" style="width: 100%;">';
        output += '<tr><th>Rank</th><th>Artist</th><th>Album</th><th>Play Count</th></tr>';
        for(let [i, album] of albums.entries()) {
            output += `<tr><td>${(i + 1).toString().padStart(2, '0')}</td>`;
            output += `<td>${album.artist.name}</td>`;
            output += `<td>${album.name}</td>`;
            output += `<td class="text-right">${album.playcount}</td></tr>`;
        }
        output += '</table>';
        $('#top-albums').html(output);
});

getTopArtists(rootUrl, username, APIKey, dataFormat)
    .then( data => {
        const artists = data.topartists.artist;
        let output = '<table class="table table-condensed table-hover" style="width: 100%;">';
        output += '<tr><th>Rank</th><th>Artist</th><th>Play Count</th></tr>';
        for(let [i, artist] of artists.entries()) {
            output += `<tr><td>${(i + 1).toString().padStart(2, '0')}</td>`;
            output += `<td>${artist.name}</td>`;
            output += `<td class="text-right">${artist.playcount}</td></tr>`;
        }
        output += '</table>';
        $('#top-artists').html(output);
});

getTopTracks(rootUrl, username, APIKey, dataFormat)
    .then( data => {
        const tracks = '';
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