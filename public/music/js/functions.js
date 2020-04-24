import { playlist, nowPlaying } from "./application-data.js";

/**
 * Get tracks for an album and add them to the playlist
 * @param albumId
 */
export function addAlbumToPlaylist(albumId) {
    getTracks(albumId)
        .then( response => {
            for(let r of response) {
                if( playlist.length === 0 ) {
                    playlist.push(r);
                    const player = document.getElementById('player');
                    player.src = '/Resources/Music/' + r.filename;
                    player.load();
                    document.getElementById('now-playing').innerHTML = `<img src="Resources/${r.image}_sm.jpg" alt="album cover" class="pr-1"/> ${r.artist} - ${r.track_name}`;
                    nowPlaying.trackId = r.trackId;
                } else {
                    playlist.push(r);
                }
            }
            printPlayList();
        });
}

/**
 * Refresh the playlist in the music player
 */
export function printPlayList() {
    console.log(playlist);
    let markup = `<table style="width: 100%;">`;
    if(playlist.length > 0) {
        for(let [i, track] of playlist.entries()) {
            const colour = nowPlaying.trackId === track.trackId ? 'text-danger' : '';
            markup += `
            <tr class="${colour}">
                <td>${(i + 1).toString().padStart(2,'0')}</td>
                <td>${track.track_name}</td>
                <td>${track.duration}</td>
                <td><button class="btn btn-xs btn-danger remove" data-id="${track.trackId}" title="Remove">x</button></td>
            </tr>`;
        }
    } else {
        markup += `
        <tr><td class="text-center"><h5>Playlist Empty</h5></td></tr>
        `;
    }
    markup += `</table>`;
    $('#track-list').html(markup);
}

/**
 * Get tracks for one album
 * @param albumId
 * @returns {Promise<any>}
 */
export async function getTracks(albumId) {
    const result = await fetch(`/api/v1/get/tracks/${albumId}`);
    return await result.json();
}

/**
 * Format the track list for displaying in the album browser
 * @param tracks
 * @param image
 * @returns {string}
 */
export function printTrackList(tracks, image) {
    let template = `
    <div class="slider">
        <div class="col-md-3">
            <img alt="cover" src="../Resources/${image}.jpg" width="100%"/>
        </div>
        <div class="col-md-5">
            <table id="tracks" class="table-condensed">`;
                for(let [i, track] of tracks.entries()) {
                    const trackNo = (i + 1).toString().padStart(2,'0');
                    template += `
                    <tr>
                        <td class="tracks align-text-top">${trackNo}</td>
                        <td class="tracks align-middle">${track.track_name}</td>
                        <td class="tracks align-text-top">${track.duration}</td>
                        <td class="align-middle">
                            <button class="btn btn-outline-secondary btn-sm add-track" data-id="${track.trackId}">Add</button>
                        </td>
                    </tr>`;
                }
            template += `
            </table>
        </div>
    </div>`;
    return template;
}

export async function getOneTrack(trackId) {
    const result = await fetch(`/api/v1/get/track/${trackId}`);
    return await result.json();
}