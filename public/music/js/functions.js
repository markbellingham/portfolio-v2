import { playlist } from "./application-data.js";
import * as fn from './functions.js';

/**
 * Get tracks for an album and add them to the playlist
 * @param albumId
 */
export function addAlbumToPlaylist(albumId) {
    getTracks(albumId)
        .then( response => {
            for(let r of response) {
                playlist.push(r);
            }
            fn.printPlayList();
        });
}

/**
 * Refresh the playlist in the music player
 */
export function printPlayList() {
    console.log(playlist);
    let markup = `<table>`;
    for(let [i, track] of playlist.entries()) {
        markup += `
        <tr>
            <td>${(i + 1).toString().padStart(2,'0')}</td>
            <td>${track.track_name}</td>
            <td>${track.duration}</td>
        </tr>`;
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
                        <td class="tracks align-middle">${trackNo}</td>
                        <td class="tracks align-middle">${track.track_name}</td>
                        <td class="tracks align-middle">${track.duration}</td>
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