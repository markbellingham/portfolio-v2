import { playlist, objParams } from './application-data.js';
import * as fn from './functions.js';

/**
 * DataTables setup - list of albums
 * @type {jQuery}
 * @var data.album_id
 */
export const table = $('#musicList').DataTable({
    ajax: "/api/v1/albums.datatables",
    columns: [
        {
            defaultContent: "<i class='fa fa-plus-circle text-success gi-1-3x'></i>",
            className: "details-control align-middle"
        },
        {
            data: "image",
            render: value => `<img alt="cover" src="../Resources/${value}_sm.jpg" loading="lazy"/>`
        },
        {
            data: "album_artist",
            className: "align-middle"
        },
        {
            data: "title",
            className: "align-middle"
        },
        {
            data: "year",
            className: "align-middle"
        },
        {
            data: "genre",
            className: "align-middle"
        },
        {
            data: "album_id",
            className: "align-middle",
            render: value => `<button class="btn btn-primary btn-sm add-album" data-albumId="${value}">Add</button>`
        }
    ],
    columnDefs: [
        // remove click to reorder on column header
        { orderable: false, targets: [0,1,6] },
        // initial order of rows
        { orderData: [2, 4, 0], targets: 0 },
        // decide priority of which columns are shown when screen size is reduced
        { responsivePriority: 1, targets: 0 },
        { responsivePriority: 2, targets: 2 },
        { responsivePriority: 3, targets: 3 },
    ],
    // Don't show hidden items as child items on small screen sizes (because child rows are used for the tracks)
    responsive: { details: false },
    // Items outside of the main table (lengthMenu, info, pagination)
    dom : "<'row'<'col-md-3'l><'col-md-2'i><'col-md-7 searchStyle'p>>",
    orderCellsTop: true,
    fixedHeader: true,
    createdRow: function(row, data) {
        if(data.top50 > 0) {
            $(row).addClass('row-highlight');
        }
    },
    // function that shows the filtering options at the top of each column
    initComplete: function() {
        table.columns().every(function () {
            const column = this;
            const columnIndex = this.index();

            // text search
            if([2,3].indexOf(columnIndex) > -1) {
                $('<input type="text" class="form-control form-control-sm" />')
                    .appendTo($("thead tr:eq(1) td").eq(columnIndex))
                    .on("keyup", function () {
                        column.search($(this).val()).draw();
                    });
            }

            // select dropdown
            if([4,5].indexOf(columnIndex) > -1) {
                const select = $('<select class="form-control form-control-sm"><option value=""></option></select>')
                    .appendTo($("thead tr:eq(1) td").eq(columnIndex))
                    .on('change', function () {
                        const val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
                        column
                            .search(val ? '^' + val + '$' : '', true, false)
                            .draw();
                    });

                column.data().unique().sort().each(function (d, j) {
                    select.append(`<option value="${d}">${d}</option>`)
                });
            }
        });
    }
});

/**
 * rebuild the albums table when the page is resized
 */
table.on('responsive-resize.dt', function(e, datatable, columns) {
    columns.forEach(function(is_visible, index) {
        $.each($('tr', datatable.table().header()), function() {
            const col = $($(this).children()[index]);
            is_visible === true ? col.show() : col.hide();
            table.responsive.rebuild();
            table.responsive.recalc();
        });
    });
});

/**
 * Add event listener for opening and closing album details
 */
table.on('click', 'td.details-control', function () {
    const tr = $(this).closest('tr');
    const row = table.row( tr );

    if ( row.child.isShown() ) {
        // This row is already open - close it
        $('div.slider', row.child()).slideUp( function () {
            row.child.hide();
            tr.removeClass('shown');
        } );
        $(this).html('<i class="fas fa-plus-circle text-success gi-1-3x"></i>');
    }
    else {
        // Open this row
        $(this).html('<i class="fas fa-minus-circle text-danger gi-1-3x"></i>');
        tr.addClass('shown');
        format(row.data(), row.child);
    }
});


/**
 * formats the child row of the music table, showing track information
 * @param data
 * @param callback
 */
function format(data, callback) {
    getTracks(data.album_id).then( tracks => {
        const template = fn.printTrackList(tracks.data, data.image);
        callback(template, 'no-padding').show();
        $('#tracks').removeClass('table table-hover dt-responsive table-sm');
        $('div.slider', callback()).slideDown();
    });
}

/**
 * Add an album to the playlist
 */
table.on('click', 'button.add-album', function() {
    const albumId = this.getAttribute('data-albumId');
    addAlbumToPlaylist(albumId);
});

/**
 * Add a single track to the playlist
 */
table.on('click', 'button.add-track', function() {
    const trackId = this.getAttribute('data-id');
    getOneTrack(trackId).then( response => {
        if(playlist.length === 0) {
            fn.setPlayingTrack(response.data);
        }
        playlist.push(response.data);
        fn.printPlayList();
    });
});

/**
 * Get information about a single track from the database
 * @param trackId
 * @returns {Promise<any>}
 */
async function getOneTrack(trackId) {
    const result = await fetch(`/api/v1/track/${trackId}`);
    return await result.json();
}

/**
 * Get tracks for an album and add them to the playlist
 * @param albumId
 */
function addAlbumToPlaylist(albumId) {
    getTracks(albumId)
        .then( response => {
            for(let r of response.data) {
                if( playlist.length === 0 ) {
                    fn.setPlayingTrack(r);
                }
                if(playlist.find( t => t.trackId === r.trackId) === undefined) {
                    playlist.push(r);
                }
            }
            fn.printPlayList();
        });
}

/**
 * Get all tracks for one album from the database
 * @param albumId
 * @returns {Promise<any>}
 */
async function getTracks(albumId) {
    const result = await fetch(`/api/v1/tracks/${albumId}`);
    return await result.json();
}