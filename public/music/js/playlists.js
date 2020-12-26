fetch(`/music/json/top-albums.json`)
    .then( res => res.json() )
    .then( data => {
        if(dataIsStale(data.date)) {
            fetch('/api/v1/lastfm').then();
        }
    });

function dataIsStale(date) {
    const q = new Date();
    const d = q.getDate();
    const m = q.getMonth();
    const y = q.getFullYear();

    const today = new Date(y, m, d);
    const savedDate = new Date(date);

    return today > savedDate;
}