import { photos } from "../../photo/js/application-data.js";
import { blogs } from "../../blog/js/application-data.js";

export class Tags {

    constructor(options) {
        this.opt = { section: '', user: {}, itemId: 0, itemTags: [], allTags: [] }
        this.setOptions(options);
        this.setCollection();
        this.tagsArea = document.querySelector(`.tags-wrapper[data-section="${this.opt.section}"]`);
        this.tagsArea.innerHTML = this.tagsFormHtml();
        this.tagsInputField = document.querySelector(`#add-${this.opt.section}-tags`);
        this.setEvents();
        this.allTags = [];
        this.itemTags = [];
    }

    setOptions(options) {
        for(let [key, value] of Object.entries(this.opt)) {
            if(options.hasOwnProperty(key)) {
                this.opt[key] = options[key];
            }
        }
    }

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

    showTags(tags) {
        let buttons = '';
        tags.forEach( tag => {
            buttons += `<button class="btn btn-info mr-1 tag-btn" data-id="${tag.obj.id}">${tag.obj.tag}</button>`;
        });
        document.querySelector(`#available-${this.opt.section}-tags`).innerHTML = buttons;
    }

    setEvents() {
        const addTagsInput = document.querySelector(`#add-${this.opt.section}-tags`);
        addTagsInput.addEventListener('keyup', this.suggestInputTags.bind(this));

        const addTagBtn = document.querySelector(`#add-${this.opt.section}-tag-btn`);
        addTagBtn.addEventListener('click', this.addTag.bind(this) );
    }

    suggestInputTags() {
        const filteredTags = [];
        const inputTags = this.getInputTags();
        for(let tag of inputTags) {
            const alreadyHave = this.itemTags.find( t => t.tag === tag );
            if(!alreadyHave) {
                const result = fuzzysort.go(tag, this.allTags, {
                    limit: 10,
                    threshold: -10000,
                    key: "tag",
                });
                result.forEach( r => {
                    filteredTags.push(r);
                });
            }
        }
        this.showTags(filteredTags);
    }

    getInputTags() {
        const inputTags = this.tagsInputField.value.split(',');
        for(let tag of inputTags) {
            tag.trim();
        }
        return inputTags;
    }

    addTag(e) {
        e.preventDefault();
        const inputTags = this.getInputTags()
        const tagsToSave = [];
        for(let tag of inputTags) {
            let tagObject = this.allTags.find( t => t.tag === tag );
            if(!tagObject) {
                tagObject = { id: 'new', tag: tag };
            }
            let alreadySet = this.itemTags.find( t => t.tag === tag );
            if(!alreadySet) {
                tagsToSave.push(tagObject);
            }
        }
        this.saveTags(tagsToSave);
    }

    saveTags(tags) {
        const secret = document.querySelector('#server-secret').value;
        fetch(`/api/v1/${this.opt.section}/${this.opt.itemId}`, {
            method: 'POST',
            body: JSON.stringify({ 'task': 'addTags', 'tags': tags, 'secret': secret }),
            credentials: 'include'
        })
            .then( res => res.json() )
            .then( response => {
                this.allTags = response.tags;
                this.itemTags = response.photo_tags;
            });
    }

    tagsFormHtml() {
        return `
        <form id="add-tags-form">
            <label for="add-${this.opt.section}-tags" class="text-primary">Add Tags:</label>
            <div class="row">
                <div class="col-md-12">
                    <input type="text" id="add-${this.opt.section}-tags" class="tagging form-control col-md-10" placeholder="Add tags separated by a comma"/>
                    <button type="submit" class="btn btn-primary" id="add-${this.opt.section}-tag-btn">Add</button>
                </div>
            </div>
            <div class="row">
                <div id="available-${this.opt.section}-tags" class="col-md-12">
    
                </div>
            </div>
        </form>
        `;
    }

}