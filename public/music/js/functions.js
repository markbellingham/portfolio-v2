import { playlist } from "./application-data.js";

/**
 * Get tracks for an album and add them to the playlist
 * @param albumId
 */
export function addAlbumToPlaylist(albumId) {
    getTracks(albumId).then( response => response.json() )
        .then( response => {
            for(let r of response) {
                playlist.push(r);
            }
        });
}

/**
 * Refresh the playlist in the music player
 * @param trackList
 */
export function printPlayList(trackList) {
    let markup = `
    <table>
    ${trackList.map(
        track => `<tr><td>${track.track_no}</td><td>${track.track_name}</td></tr>`
    ).join('')}
    </table>
    `;
    $('#track-list').html(markup);
}

/**
 * Get tracks for one album
 * @param albumId
 * @returns {Promise<any>}
 */
export async function getTracks(albumId) {
    const result = await fetch(`../../src/controllers/music-controller.php?get-tracks=${albumId}`);
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
            <img alt="cover" src="../../Resources/${image}.jpg" width="100%"/>
        </div>
        <div class="col-md-5">
            <table id="tracks" class="table-condensed">`;
                for(let [i, track] of tracks.entries()) {
                    const trackNo = Number(i + 1).toString().padStart(2,'0');
                    template += `
                    <tr>
                        <td class="tracks align-middle">${trackNo}</td>
                        <td class="tracks align-middle">${track.track_name}</td>
                        <td class="tracks align-middle">${track.duration}</td>
                        <td class="align-middle">
                            <button class="btn btn-outline-secondary btn-sm add-track">Add</button>
                        </td>
                    </tr>`;
                }
            template += `
            </table>
        </div>
    </div>`;
    return template;
}