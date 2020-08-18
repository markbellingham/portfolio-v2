import * as he from './html-elements.js';
import * as fn from '../functions/general.js';

/**
 *
 */
export class Comments {

    constructor(options = {}) {
        // Default values for the options
        this.opt = {
            section: '',
            user: {},
            captchaIcons: 4,
            itemId: 0
        }
        // Values of options that are passed in override the defaults
        this.setOptions(options);
        // Main area that holds the entire comments app
        this.commentArea = document.querySelector(`.comments-wrapper[data-section="${this.opt.section}"]`);
        const secret = this.commentArea.getAttribute('data-secret');
        this.commentArea.innerHTML = he.commentsHtml(this.opt.section, this.opt.user, secret);
        fn.buildCaptchaIcons(this.opt.captchaIcons, icons => {
            document.querySelector(`#${this.opt.section}-icons`).innerHTML = icons.chosenIconHtml + icons.iconsHtml;
            this.chosenIcon = icons.chosenIcon;
            this.setEvents(this.chosenIcon);
        });
    }

    setOptions (options) {
        for(let [i, val] of Object.entries(this.opt)) {
            if(options.hasOwnProperty(i)) {
                this.opt[i] = options[i];
            }
        }
    }

    formatUserComments (comments) {
        this.commentArea.querySelector(`#${this.opt.section}-comments`).innerHTML = he.formatComments(comments);
    }

    setEvents(chosenIcon) {
        const submitBtn = document.querySelector(`#${this.opt.section}-comment-submit`);
        submitBtn.addEventListener('click', e => {
            e.preventDefault();
            const form = document.getElementById(`${this.opt.section}-comment-form`);
            if(form.reportValidity()) {
                const formData = fn.formToJSON(form);
                formData.chosenIcon = chosenIcon;
                formData.task = 'addComment';
                fetch(`/api/v1/${this.opt.section}/${this.opt.itemId}`, {
                    method: 'POST',
                    body: JSON.stringify(formData),
                    credentials: 'include'
                })
                    .then( res => res.json() )
                    .then( response => {
                        if(response.success) {
                            this.formatUserComments(response.comments);
                            form.comment.value = '';
                            if(this.opt.section === 'photo') {
                                fn.setThumbnailFaveCommentCount(response);
                            }
                        }
                    });
            }
        });
    }
}