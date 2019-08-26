<?php
$title = 'Music';

include_once("layout/header.php");

?>
<body>
<div class="row">
    <div class="col-3" id="player">
        <audio src="" controls>

        </audio>
    </div>
    <div class="col-2" id="lyrics">
        <h1>Lyrics</h1>
    </div>
    <div class="col-7" id="music-list">
        <table id="musicList" class="table table-hover table-responsive responsive">
            <thead>
            <tr><th></th><th>Image</th><th>Artist</th><th>Title</th><th>Year</th><th>Genre</th><th></th></tr>
            <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>
<?php
include_once ("layout/footer.php");
?>
<script src="js/music.js"></script>
