$('#games-select').on('change', function() {
    const game = this.value;
    fetchGameHtml(game).then( response => {
        document.querySelector('#games-display-area').innerHTML = response;
        $("head")
            .append(`<link href="/games/${game}/style.css" rel="stylesheet" />`)
            .append(`<script type="text/javascript" src="/games/${game}/app.js"></script>`);
    });
});

async function fetchGameHtml(game) {
    const result = await fetch(`/games/${game}/index.html`);
    return await result.text();
}