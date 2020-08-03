import { playlist, nowPlaying } from "./application-data.js";

/**
 * Set playing track in the player, now playing area,
 * @param track
 */
export function setPlayingTrack(track) {
    const player = document.getElementById('player');
    player.src = '/Resources/Music/' + track.filename;
    player.load();
    player.play();
    const nowPlayingInfo = `
    <div style="float: left;">
        <img src="Resources/${track.image}_sm.jpg" alt="album cover" class="pr-1"/>
    </div>
    <div style="float: left;">
        ${track.artist} <br> ${track.track_name} (${track.title})
    </div>
    `;
    document.getElementById('now-playing').innerHTML = nowPlayingInfo;
    nowPlaying.trackId = track.trackId;
}

/**
 * Refresh the playlist in the music player
 */
export function printPlayList() {
    let markup = `<table style="width: 100%;">`;
    if(playlist.length > 0) {
        for(let [i, track] of playlist.entries()) {
            const colour = nowPlaying.trackId === track.trackId ? 'text-danger' : '';
            markup += `
            <tr class="${colour}" id="t-${track.trackId}">
                <td>${(i + 1).toString().padStart(2,'0')}</td>
                <td>${track.track_name}</td>
                <td>${track.duration}</td>
                <td><button class="btn btn-xs btn-danger remove" data-id="${track.trackId}" title="Remove">x</button></td>
            </tr>`;
        }
    } else {
        markup += `
        <tr><td class="text-center"><h5>Playlist Is Empty</h5></td></tr>
        `;
    }
    markup += `</table>`;
    $('#track-list').html(markup);
}

/**
 * Format the track list for displaying in the album browser
 * @param tracks
 * @param image
 * @param extract
 * @param pageLink
 * @returns {string}
 */
export function printTrackList(tracks, image, extract, pageLink) {
    let template = `
    <div class="slider">
        <div class="row ml-2">`;
                if(tracks[0].artist_top50 > 0) {
                    template += `<div class="col-md-6">
                        <h5 class="text-primary">Top 50 artist ranked ${tracks[0].artist_top50} with ${tracks[0].artist_playcount} plays</h5>
                    </div>`;
                }
                if(tracks[0].album_top50 > 0) {
                    template += `<div class="col-md-6">
                        <h5 class="text-primary">Top 50 album ranked ${tracks[0].album_top50} with ${tracks[0].album_playcount} plays</h5>
                    </div>`;
                }
        template += `</div>
        <div class="row ml-2">
            <div class="col-md-3">
                <img alt="cover" src="../Resources/${image}.jpg" width="100%"/>
            </div>
            <div class="col-md-auto">
                <table id="tracks" class="table-condensed">`;
                    for(let [i, track] of tracks.entries()) {
                        const trackNo = (i + 1).toString().padStart(2,'0');
                        const trackName = track.track_top50 > 0 ? track.track_name + ' <i class="fas fa-star text-warning"></i>' : track.track_name;
                        template += `
                        <tr>
                            <td class="tracks align-text-top">${trackNo}</td>
                            <td class="tracks align-middle">${trackName}</td>
                            <td class="tracks align-text-top">${track.duration}</td>
                            <td class="align-middle">
                                <button class="btn btn-outline-secondary btn-sm add-track" data-id="${track.trackId}">Add</button>
                            </td>
                        </tr>`;
                    }
                template += `
                </table>
            </div>
            <div id="album-info" class="col-md-4">
                <p>${extract}</p>
                <p>Extract taken from <a href="http://en.wikipedia.org/?curid=${pageLink}">Wikipedia</a></p>
            </div>
        </div>
    </div>`;
    return template;
}