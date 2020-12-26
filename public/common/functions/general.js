const objParams = { icon_id: '' };

/**
 * @param {number} numberOfIcons
 * @param callback
 */
export function buildCaptchaIcons(numberOfIcons = 6, callback) {
    getIcons(numberOfIcons).then( response => {
        const icons = buildIconChooser(response.data);
        callback(icons);
    });
}

/**
 * Get icons for the captcha from the database
 * @param {int} num - number of icons you want to show
 * @returns {Promise<any>}
 */
async function getIcons(num) {
    const result = await fetch(`/api/v1/icons/${num}`);
    return await result.json();
}

/**
 * Generate the HTML for the icon chooser
 * @param icons
 * @returns {{iconsHtml: string, chosenIcon: object, chosenIconHtml: string}}
 */
function buildIconChooser(icons) {
    const chosenIcon = icons[Math.floor(Math.random() * icons.length)];
    const chosenIconHtml = `<label for="icons" class="mr-1">Choose the ${chosenIcon.name} icon:</label>`;
    let iconsHtml = '';
    for(let i of icons) {
        iconsHtml += `<label for="r-${i.icon_id}" class="btn btn-warning ml-1" title="${i.name}">
            <input type="radio" name="icon" id="r-${i.icon_id}" value="${i.icon_id}" required/>
            ${i.icon}
            </label>`;
    }
    return { chosenIcon: chosenIcon, chosenIconHtml: chosenIconHtml, iconsHtml: iconsHtml };
}

/**
 * Generate a UUID (random string)
 * @returns {string}
 */
export function uuidv4() {
    return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
        (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
    );
}

/**
 * Returns an object literal of form names and values
 * @param {HTMLElement} form
 * @returns {object}
 */
export const formToJSON = form => {
    return Array.from(new FormData(form).entries())
        .reduce((m, [key, value]) => Object.assign(m, {[key]: value}), {})
}

/**
 * Pure JavaScript element slide up
 * @param {string} selector
 * @param {int} duration
 */
export function slideUp(selector, duration) {
    let target = document.querySelector(selector);
    /* Slide Up Logic 8 */
    target.style.transitionProperty = 'height, margin, padding';
    target.style.transitionDuration = duration + 'ms';
    target.style.boxSizing = 'border-box';
    target.style.height = target.offsetHeight + 'px';

    target.style.height = 0;
    target.style.paddingTop = 0;
    target.style.paddingBottom = 0;
    target.style.marginTop = 0;
    target.style.marginBottom = 0;
    target.style.overflow = 'hidden';

    window.setTimeout( () => {
        target.style.display = 'none';
        target.style.removeProperty('height');
        target.style.removeProperty('padding-top');
        target.style.removeProperty('padding-bottom');
        target.style.removeProperty('margin-top');
        target.style.removeProperty('margin-bottom');
        target.style.removeProperty('overflow');
        target.style.removeProperty('transition-duration');
        target.style.removeProperty('transition-property');
    }, duration);

}

/**
 * Pure JavaScript element slide down
 * @param selector
 * @param duration
 */
export function slideDown(selector, duration) {
    let target = document.querySelector(selector);
    /* Slide Down Logic */
    target.style.removeProperty('display');
    let display = window.getComputedStyle(target).display;
    console.log(display);
    if(display === 'none') {
        display = 'block';
    }
    target.style.display = display;

    let height = target.offsetHeight;
    target.style.height = 0;
    target.style.paddingTop = 0;
    target.style.paddingBottom = 0;
    target.style.marginTop = 0;
    target.style.marginBottom = 0;
    target.style.overflow = 'hidden';
    target.offsetHeight;

    target.style.boxSizing = 'border-box';
    target.style.transitionProperty = "height, margin, padding";
    target.style.transitionDuration = duration + 'ms';
    target.style.height = height + 'px';
    target.style.removeProperty('padding-top');
    target.style.removeProperty('padding-bottom');
    target.style.removeProperty('margin-top');
    target.style.removeProperty('margin-bottom');

    window.setTimeout( () => {
        target.style.removeProperty('overflow');
        target.style.removeProperty('transition-duration');
        target.style.removeProperty('transition-property');
    }, duration);
}

/**
 * Pure JavaScript element slide toggle
 * @param {string} selector
 * @param {int} duration
 */
export function slideToggle(selector, duration) {
    let target = document.querySelector(selector);
    /* Slide Toggle Logic */
    if(window.getComputedStyle(target).display === 'none') {
        return slideDown(selector, duration);
    } else {
        return slideUp(selector, duration);
    }
}

/**
 * Adds icon and number to the photo thumbnail if it has comments and/or favourites
 * @param photo
 */
export function setThumbnailFaveCommentCount(photo) {
    let markup = '';
    if(photo.fave_count > 0) {
        markup += `<i class="fas fa-heart"></i> ${photo.fave_count} `;
    }
    if(photo.comment_count > 0) {
        markup += `<i class="fas fa-comment-alt"></i> ${photo.comment_count}`;
    }
    $(`#pcounts-${photo.id}`).html(markup);
}

/**
 * Sorts an array to work with CSS columns so that the items flow horizontally instead of vertically
 * @param {array} arr
 * @param {int} cols
 * @returns {[]}
 */
export function reorder(arr, cols) {
    const out = [];
    let col = 0;

    while(col < cols) {
        for (let i = 0; i < arr.length; i += cols) {
            const val = arr[i + col];
            if (val !== undefined)
                out.push(val);
        }
        col++;
    }
    return out;
}