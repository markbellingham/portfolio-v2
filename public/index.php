<?php
require_once '../src/appInit.php';

include_once "common/layout/header.php";
?>
<body>
<div class="col-md-10 col-md-offset-1">
    <?php
    include_once 'music/player.php';
    ?>
</div>
<div  class="col-md-12">
    <div class="col-md-2">
        <!-- Nav tabs -->
        <ul class="nav flex-column">
            <li class="active nav-item active">
                <a href="#home" class="nav-link active" data-toggle="tab">Home</a>
            </li>
            <li class="nav-item">
                <a href="#library" class="nav-link" data-toggle="tab">Library</a>
            </li>
            <li class="nav-item">
                <a href="#playlists" class="nav-link" data-toggle="tab">Playlists</a>
            </li>
            <li class="nav-item">
                <a href="#gallery" class="nav-link" data-toggle="tab">Gallery</a>
            </li>
            <li class="nav-item">
                <a href="#tutorials" class="nav-link" data-toggle="tab">Tutorials</a>
            </li>
            <li class="nav-item">
                <a href="#contact" class="nav-link" data-toggle="tab">Contact</a>
            </li>
        </ul>
    </div>

    <div class="col-md-10" style="height: 85vh; overflow: auto;">
        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane active" id="home">Home Tab.</div>
            <div class="tab-pane" id="library" style="width: 100%;">
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
                include_once 'photos/gallery.php';
                ?>
            </div>
            <div class="tab-pane" id="tutorials">Tutorials Tab.</div>
            <div class="tab-pane" id="contact">
                <?php
                include_once 'contact/me.php';
                ?>
            </div>
        </div>
    </div>
</div>
</body>
<?php
include_once "common/layout/footer.php";