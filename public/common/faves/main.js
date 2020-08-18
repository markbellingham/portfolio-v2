import { photos } from '../../photo/js/application-data.js';
import { blogs } from '../../blog/js/application-data.js';
import { setThumbnailFaveCommentCount } from "../functions/general.js";

/**
 * Handles favouriting a photo or blog post
 */
export class Favourites {
    constructor(options) {
        this.opt = { section: '', user: {}, itemId: 0 };
        this.setOptions(options);
        this.favesArea = document.querySelector(`.faves-wrapper[data-section="${this.opt.section}"]`);
        this.userFaves = JSON.parse(localStorage.getItem(`${this.opt.section}-faves`)) || [];
        this.setCollection();
    }

    /**
     * Override default option values with user-defined ones, if set
     * @param options
     */
    setOptions(options) {
        for(let [i, val] of Object.entries(this.opt)) {
            if(options.hasOwnProperty(i)) {
                this.opt[i] = options[i];
            }
        }
    }

    /**
     * Get the list of items that this one belongs to
     */
    setCollection() {
        switch(this.opt.section) {
            case 'photo':
                this.collection = photos;
                break;
            case 'blog':
                this.collection = blogs;
                break;
        }
    }

    /**
     * Show the Favourite icon, highlight if user has favourited this item, show total number of faves
     * @param faveCount
     */
    showFavourite(faveCount) {
        const colour = this.userFaves.indexOf(this.opt.itemId) > -1 ? 'text-danger' : '';
        this.favesArea.innerHTML = `
        <i id="${this.opt.section}-favourite" class="fas fa-heart ${colour}"></i>
        <span id="${this.opt.section}-fave-count">${faveCount}</span>
        `;
        this.setEvents();
    }

    /**
     * Event listener for when a user clicks to favourite an item
     */
    setEvents() {
        const faveBtn = document.querySelector(`#${this.opt.section}-favourite`);
        const faveCountDisplay = document.querySelector(`#${this.opt.section}-fave-count`);
        faveBtn.addEventListener('click', () => {
            const item = this.collection.find(item => item.id === this.opt.itemId );
            const secret = document.querySelector('#server-secret').value;
            if(this.userFaves.indexOf(this.opt.itemId) < 0) {
                fetch(`/api/v1/${this.opt.section}/${this.opt.itemId}`, {
                    method: 'POST',
                    body: JSON.stringify({ task: 'addFave', secret: secret }),
                    credentials: 'include'
                })
                    .then( res => res.json() )
                    .then( response => {
                        if(response.success) {
                            faveBtn.classList.add('text-danger');
                            item.fave_count = response.fave_count;
                            faveCountDisplay.innerHTML = item.fave_count;
                            this.userFaves.push(this.opt.itemId);
                            localStorage[`${this.opt.section}-faves`] = JSON.stringify(this.userFaves);
                            if(this.opt.section === 'photo') {
                                setThumbnailFaveCommentCount(response);
                            }
                        }
                    });
            }
        });
    }
}