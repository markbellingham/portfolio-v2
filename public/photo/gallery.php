<!-- Area for photo grid -->
<div class="">
    <div id="photos">
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
                            <button class="btn btn-outline-dark btn-rounded btn-md" type="button" id="full-size-photo" data-photoid="">Full Size</button>
                            <button class="btn btn-outline-primary btn-rounded btn-md" data-dismiss="modal" type="button">Close</button>
                        </div>
                        <div class="text-left mt-2">
                            <h3>
                                <span class="text-primary" id="modal-photo-title"></span>
                                <span  class="float-right">
                                    <i id="make-favourite" data-photoid="" class="fas fa-heart"></i>
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
                                <label for="photo-comment" class="text-left">Comment:</label>
                                <textarea id="photo-comment" name="comment" class="form-control" required></textarea>
                                <label for="description" class="d-none">Description</label>
                                <input type="text" name="description" id="description" class="d-none" value=""/>
                                <input type="hidden" name="secret" id="server-secret" value="<?= $secret ?>"/>
                                <input type="hidden" name="uuid" value="<?= $cookieSettings->uuid; ?>"/>
                                <input type="hidden" name="photo_id" id="comment-photoId" value=""/>
                            </div>
                            <div id="photo-error-message" class="text-danger"></div>
                            <div id="gallery-icons" class="text-center mt-3 btn-group-toggle" data-toggle="buttons"></div>
                            <div class="text-right mt-2">
                                <button type="button" id="photo-comment-submit" class="btn btn-primary" form="add-comment-form">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="module" src="photo/js/gallery.js"></script>