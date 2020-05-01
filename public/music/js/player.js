import {playlist, objParams, nowPlaying} from './application-data.js';
import * as fn from './functions.js';
// import { lyrics } from "./lyrics.js";

const tracklistContainer = $('#tracklist-container');

/**
 * Show and hide the tracklist
 */
$('#toggle-tracklist').click( function() {
    if(this.classList.contains('fa-chevron-circle-down')) {
        switchChevrons(this, 'down','up');
        tracklistContainer.css({'height': '100%'});
        $('#track-list').css({'overflow': 'auto', 'max-height': window.innerHeight - 220});
        $('#lyrics').css({'overflow': 'auto', 'max-height': window.innerHeight - 220});
        $('#player-container').css('box-shadow', '0 8px 8px 0 rgba(0, 0, 0, 0.2), 0 10px 20px 0 rgba(0, 0, 0, 0.19)');
        tracklistContainer.slideToggle();
    } else {
        switchChevrons(this, 'up','down');
        tracklistContainer.slideToggle( function() {
            $('#player-container').css({
                'box-shadow': 'none'
            });
        });
        tracklistContainer.css({'height': '0'});
    }
});

/**
 *
 * @param {$ElementType} t - JavaScript icon element
 * @param {string} remove - class to remove
 * @param {string} add - class to add
 */
function switchChevrons(t, remove, add) {
    t.classList.remove(`fa-chevron-circle-${remove}`);
    t.classList.add(`fa-chevron-circle-${add}`);
}

/**
 * Remove all tracks from the playlist
 */
$('#clear-playlist').click( function() {
    playlist.length = 0;
    fn.printPlayList();
});

/**
 * Change the playing track by double clicking it in the playlist
 */
$('#track-list').on('dblclick', 'tr', function() {
    const trackId = parseInt(this.id.substring(2));
    const track = playlist.find( t => t.trackId === trackId );
    if(track) {
        fn.setPlayingTrack(track);
        fn.printPlayList();
    }
});

/**
 * Remove a single track from the playlist
 */
$('#track-list').on('click','.remove', function(e) {
    e.stopPropagation();
    const trackId = parseInt(this.getAttribute('data-id'));
    playlist.splice(playlist.findIndex( t => t.trackId === trackId), 1);
    fn.printPlayList();
});

/**
 * When track stops start playing the next one in the playlist, if it exists
 */
document.getElementById('player').addEventListener('ended', function() {
    const playingTrackIndex = playlist.findIndex( t => t.trackId === nowPlaying.trackId );
    if(playlist.length > 0) {
        if(playingTrackIndex > -1 && playlist.length - 1 >= playingTrackIndex + 1) {
            fn.setPlayingTrack(playlist[playingTrackIndex + 1]);
        }
    }
    fn.printPlayList();
});

// lyrics.get('john_lennon','imagine');