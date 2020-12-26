let game = '';

$('#games-select').on('change', function() {
    const stylesheet = document.getElementById(`${game}-styles`);
    if(stylesheet) {
        stylesheet.parentNode.removeChild(stylesheet);
    }
    game = this.value;
    fetchGameHtml(game).then( response => {
        document.querySelector('#games-display-area').innerHTML = response;
        const cssParam = Math.random().toString(36).replace(/[^a-z]+/g, '');
        const jsParam = Math.random().toString(36).replace(/[^a-z]+/g, '');
        $("head")
            .append(`<link id="${game}-styles" href="games/${game}/${game}-style.css?t=${cssParam}" rel="stylesheet" />`)
            .append(`<script type="text/javascript" src="games/${game}/${game}-app.js?t=${jsParam}"></script>`);
    });
});

async function fetchGameHtml(game) {
    const param = Math.random().toString(36).replace(/[^a-z]+/g, '');
    const result = await fetch(`games/${game}/index.html?t=${param}`);
    return await result.text();
}