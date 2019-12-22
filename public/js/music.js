let tracklist = [];
/**
 * DataTables setup - list of albums
 * @type {jQuery}
 * @var data.album_id
 */
const table = $('#musicList').DataTable({
    ajax: "../src/controllers/db-queries.php?albums=true",
    columns: [
        {
            defaultContent: "<i class='icon-plus-circled gi-1-3x'></i>",
            className: "details-control align-middle"
        },
        {
            data: "image",
            render: value => `<img alt="cover" src="../Resources/${value}_sm.jpg"/>`
        },
        { data: "artist", className: "align-middle" },
        { data: "title", className: "align-middle" },
        { data: "year", className: "align-middle" },
        { data: "genre", className: "align-middle" },
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
 * rebuild the table when the page is resized
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
 * Add event listener for opening and closing details
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
        $(this).html('<i class="icon-plus-circled gi-1-3x"></i>');
    }
    else {
        // Open this row
        $(this).html('<i class="icon-minus-circled gi-1-3x"></i>');
        tr.addClass('shown');
        format(row.data(), row.child);
    }
});


/**
 * formats the child row of the music table, showing track information
 * @param data
 * @param data.track_name
 * @param data.duration
 * @param data.image
 * @param callback
 */
function format(data, callback) {
    $.ajax({
        url: `../src/controllers/db-queries.php?get-tracks=${data.album_id}`,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            let tracks = '<table id="tracks" class="">';
            $.each(response, function(i, d) {
                const trackNo = Number(i+1).toString().padStart(2,'0');
                tracks += `<tr>
                            <td class="tracks align-middle">${trackNo}</td>
                            <td class="tracks align-middle">${d.track_name}</td>
                            <td class="tracks align-middle">${d.duration}</td>
                            <td class="align-middle"><button class="btn btn-outline-secondary btn-sm add-track">Add</button></td>
                        </tr>`;
            });
            const template = `<div class="slider">
                <div class="col-md-3">
                    <img alt="cover" src="../Resources/${data.image}.jpg" width="100%"/>
                </div>
                <div class="col-md-5">${tracks}</div>
                </div>`;
            callback(template, 'no-padding').show();
            $('#tracks').removeClass('table table-hover dt-responsive table-sm');
            $('div.slider', callback()).slideDown();
        }
    });
}

table.on('click', 'button.add-album', function() {
    const albumId = this.getAttribute('data-albumId');
    const result = fetch(`../src/controllers/db-queries.php?get-tracks=${albumId}`);
    result
        .then( response => response.json() )
        .then( response => {
            for(let r of response) {
                tracklist.push(r);
            }
            printTrackList(tracklist);
        });
});

function printTrackList(trackList) {
    let markup = `
    <table>
    ${trackList.map(
        track => `<tr><td>${track.track_no}</td><td>${track.track_name}</td></tr>`
    ).join('')}
    </table>
    `;
    $('#track-list').html(markup);
}

$('#clear-tracklist').click( function() {
    tracklist = [];
    printTrackList(tracklist);
});