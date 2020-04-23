<?php
$title = 'Music';
?>
<div id="player-container">
    <div class="row">
        <div  class="col-md-6 col-md offset-3" style="border: 1px solid red;">
            <div id="player">
                <audio controls>
                    <source src="" type="">
                </audio>
            </div>
            <div class="col-md-2" style="border: 1px solid green;">
                <button id="clear-playlist" class="btn btn-light">Clear Playlist</button>
            </div>
            <div class="col-md-1 down-arrow"></div>
        </div>
        <div class="col-md-3"></div>
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
