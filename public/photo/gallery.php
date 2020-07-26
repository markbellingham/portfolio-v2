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
                                <span  class="float-right">
                                    <i id="make-favourite" class="fas fa-heart"></i>
                                    <span id="fave-count"></span>
                                </span>
                            </h3>
                            <p id="modal-photo-location"></p>
                        </div>
                        <div id="comments" class="text-left mt-2"></div>
                        <div class="text-left mt-2">
                            <h5 class="text-primary">Add Comment</h5>
                        </div>
                        <form id="photo-comment-form">
                            <div class="text-left mt-2">
                                <label for="photo-comment-username">Name:</label>
                                <input type="text" name="name" id="photo-comment-username" class="form-control" value="<?= $cookieSettings->username ?? '' ?>" readonly/>
                                <label for="photo-comment" class="text-left mt-2">Comment:</label>
                                <textarea id="photo-comment" name="comment" class="form-control" required></textarea>
                                <label for="description" class="d-none">Description</label>
                                <input type="text" name="description" id="description" class="d-none" value=""/>
                                <input type="hidden" name="secret" id="server-secret" value="<?= $secret ?>"/>
                                <input type="hidden" name="uuid" value="<?= $cookieSettings->uuid; ?>"/>
                            </div>
                            <div id="photo-error-message" class="text-danger"></div>
                            <div id="gallery-icons" class="text-center mt-3 btn-group-toggle" data-toggle="buttons"></div>
                            <div class="text-right mt-2">
                                <button type="button" id="photo-comment-submit" class="btn btn-primary" form="add-comment-form">Submit</button>
                            </div>
                        </form>
                        <div class="text-left mt-2">
                            <form id="add-tags-form">
                                <?php
                                if($cookieSettings->username == 'Mark') {
                                    ?>
                                    <label for="add-photo-tags" class="text-primary">Add Tags:</label>
                                    <div class="row">
                                        <input type="text" id="add-photo-tags" class="form-control col-md-10" placeholder="Add tags separated by a comma"/>
                                        <button type="submit" class="btn btn-primary" id="add-tag-btn">Add</button>
                                    </div>
                                    <div class="row">
                                        <div id="available-photo-tags" class="col-md-12">

                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="module" src="photo/js/gallery.js"></script>