/**
 * Creates the main comments display area
 * @param {string} section
 * @param {object} user
 * @param {string} secret
 * @returns {string}
 */
export function commentsHtml(section = '', user = {}, secret) {
    return `
    <div id="${section}-comments" class="text-left mt-2">
    
    </div>
    <div class="text-left mt-2">
        <h5 class="text-primary">Add Comment</h5>
    </div>
    <form id="${section}-comment-form">
        <div class="text-left mt-2">
            <label for="${section}-comment-username">Name:</label>
            <input type="text" name="name" id="${section}-comment-username" class="form-control" value="${user.username}" readonly/>
            <label for="${section}-comment" class="text-left mt-2">Comment:</label>
            <textarea id="${section}-comment" name="comment" class="form-control" required></textarea>
            <label for="${section}-description" class="d-none">Description</label>
            <input type="text" name="description" id="${section}-description" class="d-none" value=""/>
            <input type="hidden" name="secret" id="server-secret" value="${secret}"/>
            <input type="hidden" name="uuid" value="${user.uuid}"/>
        </div>
        <div id="${section}-error-message" class="text-danger"></div>
        <div id="${section}-icons" class="text-center mt-3 btn-group-toggle" data-toggle="buttons"></div>
        <div class="text-right mt-2">
            <button type="button" id="${section}-comment-submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
    `;
}

/**
 * Format the comments
 * @param {array} comments
 */
export function formatComments(comments) {
    let markup = ``;
    for(let c of comments) {
        markup += `
        <div class="text-left mt-2">
            <h5>
                <span class="text-primary">Comments:</span>
            </h5>
        </div>
        <div class="col-md-12">
            <small class="text-left"><span class="text-primary">${c.name}</span> - ${c.created}</small>
            <p class="text-left">${c.comment}</p>
        </div>
    `;
    }
    return markup;
}