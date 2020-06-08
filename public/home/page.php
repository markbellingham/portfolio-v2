<?php
$cookie = $_COOKIE['settings'] ?? '{"permission": false, "uuid": "", "username": "Anonymous"}';
$cookieSettings = json_decode($cookie);
$cookiePermission = $cookieSettings->permission ?? false;
$showCookiePermission = $cookiePermission ? 'd-none' : '';
$showChangeCookiePermission = $cookiePermission ? '' : 'd-none';
?>
<div class="d-none" id="cookie-settings"><?= $cookie ?></div>

<div class="col-md-12">
    <div class="card <?= $showChangeCookiePermission ?>" id="change-cookie-permissions-div">
        <button class="btn btn-link" id="change-cookie-permissions-btn">Change Cookie Permissions</button>
    </div>
    <div class="card <?= $showCookiePermission ?>" id="cookie-permissions">
        <h3 class="card-header bg-light">
            <span class="text-dark mr-5">Can this website remember you?</span>
            <button class="btn btn-danger cookie-permissions-btn" data-permission="no">No</button>
            <button class="btn btn-success cookie-permissions-btn" data-permission="yes">Yes</button>
        </h3>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="card-title">Cookies Denied:</h5>
                    <ul>
                        <li>Cookie set to remember preference only</li>
                        <li>Comments sent via anti-spam protection</li>
                        <li>IP addresses saved for anti-spam protection, then deleted</li>
                        <li>Activity merged with other anonymous users</li>
                        <ul>
                            <li>Comments giver the username "Anonymous"</li>
                            <li>Used to track general activity across the site but cannot link to an individual user</li>
                        </ul>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h5 class="card-title">Cookies Allowed:</h5>
                    <ul>
                        <li>Cookie set to remember username</li>
                        <li>Comments and likes can be filtered by username</li>
                        <li>Comments and likes for each user are publicly viewable</li>
                        <li>Apart from comment anti-spam protection, no user information is shared with third parties</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <h3 class="card-header">Step One</h3>
        <div class="card-body">
            <h5 class="card-title">Choose some music</h5>
            <p class="card-text">In the music library area, add some songs to the playlist, they should start playing automatically.
                You can view the playlist by clicking on the chevron to the right of the player at the top of the page.</p>
        </div>
    </div>

    <div class="card mt-4">
        <h3 class="card-header">Step Two</h3>
        <div class="card-body">
            <h5 class="card-title">Browse the photos or blog</h5>
            <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="usernameModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white">Choose a username</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-left">
                <div id="username-warning" class="text-danger"></div>
                <label for="username">Username:</label>
                <input type="text" id="username" class="form-control" value=""/>
                <input type="hidden" id="secret" value="<?= $secret ?>"/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="save-username-btn">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="module" src="home/js/page.js"></script>