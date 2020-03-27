import { playlist, objParams } from './application-data.js';
import * as fn from './functions.js';

/**
 * Remove all tracks from the playlist
 */
$('#clear-playlist').click( function() {
    playlist.length = 0;
    fn.printPlayList();
});