<?php
$title = 'Music';

include_once("../common/layout/header.php");

?>
<body>
<div class="row">
    <div class="col-md-3" id="player">
        <div id="player">
            <audio controls>
                <source src="" type="">
            </audio>
        </div>
        <div id="track-list-container">
            <button id="clear-tracklist" class="btn btn-light">Clear</button>
            <div id="track-list">

            </div>
        </div>
    </div>
    <div class="col-md-2" id="lyrics">
        <h1>Lyrics</h1>
    </div>
    <div class="col-md-7" id="music-list">
        <table id="musicList" class="table table-hover dt-responsive table-sm" style="width: 100%;">
            <thead>
            <tr><th></th><th></th><th>Artist</th><th>Title</th><th>Year</th><th>Genre</th><th></th></tr>
            <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>
<?php
include_once("../common/layout/footer.php");
?>
<script src="js/player.js"></script>
