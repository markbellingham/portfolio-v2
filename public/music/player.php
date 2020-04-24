<?php
$title = 'Music';
?>
<div>
    <div class="row">
        <div id="player-container" class="col-md-8 offset-2">
            <div id="player" class="col-md-7 offset-1">
                <audio controls>
                    <source src="" type="">
                </audio>
            </div>
            <div class="col-md-4 text-right">
                <i id="toggle-tracklist" class="fas fa-chevron-circle-down text-primary" style="font-size: 2em; z-index: 1000;"></i>
            </div>
        </div>
        <div class="col-md-2">

        </div>
    </div>
    <div class="row">
        <div id="tracklist-container" class="col-md-8 offset-2">
            <div class="col-md-6">
                <h5>
                    <span class="text-primary">Tracklist</span>
                    <button id="clear-playlist" class="btn btn-light float-right">Clear Playlist</button>
                </h5>
                <div id="track-list" style="width: 100%;">

                </div>
            </div>
            <div class="col-md-6">
                <h5 class="text-primary">Lyrics</h5>
                <div id="lyrics">

                </div>
            </div>
        </div>
    </div>
</div>

<script type="module" src="music/js/player.js"></script>
