<?php
$pictures = new Pictures();
$tags = $pictures->getTags();
?>

<!-- Area for photo grid -->
<div class="row">
    <div class="col-md-4 col-xs-12" style="margin-top: -15px;">
        <input type="text" class="form-control" id="photo-search-input" placeholder="Search Photos"/>
    </div>
</div>

<div class="row">
    <div id="photos" class="col-md-12" style="margin-top: -15px;">
    </div>
</div>

<!-- Modal for displaying the large image -->
<div aria-hidden="true" aria-labelledby="myModalLabel" class="modal fade" id="modalIMG" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div id="modal-image-container" class="col-md-9 col-sm-12">
                        <img id="modal-image" src="" alt="">
                    </div>
                    <div id="modal-text-container" class="col-md-3 col-sm-12">
                        <div class="text-right">
                            <button class="btn btn-outline-dark btn-rounded btn-md" type="button" id="full-size-photo">Full Size</button>
                            <button class="btn btn-outline-primary btn-rounded btn-md" data-dismiss="modal" type="button">Close</button>
                        </div>
                        <div class="text-left mt-2">
                            <h3>
                                <span class="text-primary" id="modal-photo-title"></span>
                                <span class="faves-wrapper float-right" data-section="photo">

                                </span>
                            </h3>
                            <p id="modal-photo-location"></p>
                        </div>
                        <div class="comments-wrapper" data-section="photo">

                        </div>
                        <div class="tags-wrapper text-left mt-2" data-section="photo">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="module" src="photo/js/gallery.js"></script>