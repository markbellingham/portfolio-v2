<?php
$title = 'Music';
?>
<div id="player-container">
    <div class="row">
        <div id="player">
            <audio controls>
                <source src="" type="">
            </audio>
        </div>
        <div class="col-md-2">
            <button id="clear-playlist" class="btn btn-light">Clear Playlist</button>
        </div>
        <div class="col-md-1 down-arrow"></div>
    </div>
    <div class="row">
        <div id="track-list" class="col-md-4">

        </div>
        <div class="col-md-2" id="lyrics">
            <h2>Lyrics</h2>
        </div>
    </div>
</div>

<script type="module" src="music/js/player.js"></script>
