/**
 * Function to get song lyrics from Lyric Wiki, and remove all HTML formatting
 * Inspired by https://github.com/scf4/lyricist/blob/master/lib/index.js
 */
export class lyrics {

    async get(artist, song, callback) {
        fetch('http://lyrics.wikia.com/wiki/' + artist + ':' + song)
            .then( response => response.text() )
            .then ( html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                console.log(doc);
            }).error( function() {
                callback();
            });
    }

}