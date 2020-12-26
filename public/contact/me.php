<?php

?>
<div class="col-md-12" style="margin-top: -30px;">
    <form id="send-email-form">
        <div class="col-md-8">
            <h4 class="text-primary">Contact Me</h4>

            <input type="hidden" name="secret" class="server-secret" value="<?= $secret ?>"/>

            <label for="contact-name" class="mt-3">Name:<span class="text-danger">*</span></label>
            <input type="text" name="name" id="contact-name" class="form-control" required/>

            <label for="email-address" class="mt-3">Email Address:</label>
            <input type="email" name="email" id="email-address" class="form-control"/>

            <label for="subject" class="mt-3">Subject:<span class="text-danger">*</span></label>
            <input type="text" name="subject" id="subject" class="form-control" required/>

            <label for="contact-description" class="d-none">Description</label>
            <input type="text" name="description" class="d-none" id="contact-description" value=""/>

            <label for="message" class="mt-3">Message:<span class="text-danger">*</span></label>
            <textarea rows="10" name="message" id="message" class="form-control" required></textarea>

            <div id="contact-icons" class="text-center mt-3 btn-group-toggle" data-toggle="buttons"></div>

            <div class="text-right mt-3">
                <button id="send-email-btn" class="btn btn-primary">Send Message</button>
            </div>
        </div>
    </form>
    <div class="col-md-3 align-self-end">

        <h4 class="text-primary">Social Media</h4>

        <p class="mt-5">
            <i class="fab fa-stack-overflow gi-1-3x"></i>
            Stack Overflow
        </p>
        <a href="https://stackexchange.com/users/15331650/mark">
            <img src="https://stackexchange.com/users/flair/15331650.png" width="208" height="58" alt="profile for Mark on Stack Exchange, a network of free, community-driven Q&amp;A sites" title="profile for Mark on Stack Exchange, a network of free, community-driven Q&amp;A sites" />
        </a>

        <p class="mt-5">
            <i class="fab fa-github gi-1-3x"></i>
            <a href="https://github.com/markbellingham/portfolio-v2">Github</a>
        </p>

        <p class="mt-5">
            <i class="fab fa-linkedin text-primary gi-1-3x"></i>
            <a href="https://www.linkedin.com/in/markbellingham/">Linked In</a>
        </p>

        <p class="mt-5">
            <i class="fab fa-youtube text-danger gi-1-3x"></i>
            <a href="https://www.youtube.com/user/mbellingham/videos">YouTube</a>
        </p>

        <p class="mt-5">If you want to send me a secure message,<br><a href="contact/markbellingham-publickey.asc" download>download my PGP public key</a>,<br>then send an email to:<br>
            <span class="text-info">mark dot bellingham at pm dot me</span>
        </p>

        <p class="mt-5">
            If you are looking for my old university projects website it can be found at:<br>
            <a href="https://markbellingham.me">https://markbellingham.me</a>
        </p>

    </div>
</div>

<script type="module" src="contact/js/me.js"></script>