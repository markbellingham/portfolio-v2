const objParams = { icon_id: '' };

/**
 * @param {int} numberOfIcons
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
    const result = await fetch(`/api/v1/contact/icons/${num}`);
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
            <input type="radio" class="" name="icon" id="r-${i.icon_id}" value="${i.icon_id}"/>
            ${i.icon}
            </label>`
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