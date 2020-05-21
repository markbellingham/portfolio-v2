<?php
require_once 'autoload.php';
include_once "common/layout/header.php";

$fn = new Functions();
$secret = $fn->RandomToken();
$_SESSION['server-secret'] = $secret;

?>
<body>
<div id="container">

    <div class="col-md-12">
        <?php
        include_once 'music/player.php';
        ?>
    </div>
    <div id="main-page" class="col-md-12">
        <div class="col-md-2">
            <!-- Nav tabs -->
            <ul class="nav flex-column">
                <li class="active nav-item active">
                    <a href="#home" class="nav-link active" data-toggle="tab">Home</a>
                </li>
                <li class="nav-item">
                    <a href="#music" class="nav-link" data-toggle="tab">Music</a>
                </li>
                <li class="nav-item">
                    <a href="#playlists" class="nav-link" data-toggle="tab">Playlists</a>
                </li>
                <li class="nav-item">
                    <a href="#gallery" class="nav-link" data-toggle="tab">Gallery</a>
                </li>
                <li class="nav-item">
                    <a href="#blog" class="nav-link" data-toggle="tab">Blog</a>
                </li>
                <li class="nav-item">
                    <a href="#contact" class="nav-link" data-toggle="tab">Contact</a>
                </li>
            </ul>
        </div>

        <div class="col-md-10" style="height: 85vh; overflow: auto;">
            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane active" id="home">
                    <?php
                    include_once 'home/page.php'
                    ?>
                </div>
                <div class="tab-pane" id="music" style="width: 100%;">
                    <?php
                    include_once 'music/library.php';
                    ?>
                </div>
                <div class="tab-pane" id="playlists">
                    <?php
                    include_once 'playlists/playlists.php';
                    ?>
                </div>
                <div class="tab-pane" id="gallery">
                    <?php
                    include_once 'photo/gallery.php';
                    ?>
                </div>
                <div class="tab-pane" id="blog">Tutorials Tab.</div>
                <div class="tab-pane" id="contact">
                    <?php
                    include_once 'contact/me.php';
                    ?>
                </div>
            </div>
        </div>
    </div>

</div>
</body>


</html>


<script type="module">

    // Initialise the music player
    import * as fn from './music/js/functions.js';
    fn.printPlayList();

    // Auto select the tab if the name is in the url
    const url = document.location.toString();
    if(url.match('#')) {
        $(`.nav-item a[href="#${url.split('#')[1]}"]`).tab('show');
    }

    // Update the url when a tab is selected
    $('.nav-link').on('click', function() {
        const tab = this.href;
        history.pushState({
            id: tab
        }, 'Title', tab);
    });
</script>