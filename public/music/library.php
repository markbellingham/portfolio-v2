<div class="col-md-12 btn-group-toggle" data-toggle="buttons">
    <label for="show-top50-artists-btn" class="btn btn-light music-library-filter" title="Show Top 50 Artists" data-filter="top50artists">
        <input type="radio" name="top50_artists" id="show-top50-artists-btn"/>
        Show Top 50 Artists
    </label>
    <label for="show-top50-albums-btn" class="btn btn-light active music-library-filter" title="Show Top 50 Albums" data-filter="top50albums">
        <input type="radio" name="top50_albums" id="show-top50-albums-btn" checked/>
        Show Top 50 Albums
    </label>
    <label for="show-top50-tracks-btn" class="btn btn-light music-library-filter" title="Show Top 50 Tracks" data-filter="top50tracks">
        <input type="radio" name="top50_tracks" id="show-top50-tracks-btn"/>
        Show Top 50 Tracks
    </label>
    <label for="show-all-albums-btn" class="btn btn-light music-library-filter" title="Show All Albums" data-filter="all">
        <input type="radio" name="all_albums" id="show-all-albums-btn"/>
        Show All Albums
    </label>
</div>

<div class="col-md-12" style="margin-top: -30px;">
    <table id="musicList" class="table table-hover dt-responsive table-sm" style="width: 100%;">
        <thead>
        <tr><th></th><th></th><th>Artist</th><th>Title</th><th>Year</th><th>Genre</th><th></th></tr>
        <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>
<script type="module" src="music/js/library.js"></script>
<script type="module" src="music/js/playlists.js"></script>
