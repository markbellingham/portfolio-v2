// DataTables setup - list of albums
let table = $('#musicList').DataTable({
    "ajax": "../src/controllers/db-queries.php?albums=true",
    "columns": [
        {
            "defaultContent": "<i class='icon-plus-circled gi-1-3x'></i>",
            "className": "details-control"
        },
        {
            "data": "image",
            "aTargets": [1],
            "mRender": function (data) {
                return `<img alt="cover" src="../Resources/${data}_sm.jpg"/>`;
            }
        },
        {"data": "artist"},
        {"data": "title"},
        {"data": "year"},
        {"data": "genre"},
        {"defaultContent": ""}
    ],
    "createdRow": function(row, data, index) {
        $('td', row).eq(6).html(`<button class="btn btn-primary btn-round add-album" data-albumId="${data.album_id}">Add</button>`);
    },
    "columnDefs": [
        // remove click to reorder on column header
        { "orderable": false, "targets": [0,1,6] },
        // initial order of rows
        { "orderData": [2, 4, 0], "targets": 0 },
        // decide priority of which columns are shown when screen size is reduced
        { "responsivePriority": 1, "targets": 0 },
        { "responsivePriority": 2, "targets": 2 },
        { "responsivePriority": 3, "targets": 3 },
    ],
    // Don't show hidden items as child items on small screen sizes (because child rows are used for the tracks)
    "responsive": { details: false },
    // Items outside of the main table (lengthMenu, info, pagination)
    "dom" : "<'row'<'col-3'l><'col-2'i><'col-7 searchStyle'p>>",
    orderCellsTop: true,
    fixedHeader: true,
    // function that shows the filtering options at the top of each column
    "initComplete": function() {
        table.columns().every(function () {
            let column = this;
            let columnIndex = this.index();

            // text search
            if([2,3].indexOf(columnIndex) > -1) {
                $('<input type="text" class="form-control input-sm" />')
                    .appendTo($("thead tr:eq(1) td").eq(columnIndex))
                    .on("keyup", function () {
                        column.search($(this).val()).draw();
                    });
            }

            // select dropdown
            if([4,5].indexOf(columnIndex) > -1) {
                let select = $('<select class="form-control input-sm"><option value=""></option></select>')
                    .appendTo($("thead tr:eq(1) td").eq(columnIndex))
                    .on('change', function () {
                        let val = $.fn.dataTable.util.escapeRegex(
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

// rebuild the table when the page is resized
table.on('responsive-resize.dt', function(e, datatable, columns) {
    columns.forEach(function(is_visible, index) {
        $.each($('tr', datatable.table().header()), function() {
            let col = $($(this).children()[index]);
            is_visible === true ? col.show() : col.hide();
            table.responsive.rebuild();
            table.responsive.recalc();
        });
    });
});

// Add event listener for opening and closing details
table.on('click', 'td.details-control', function () {
    let tr = $(this).closest('tr');
    let row = table.row( tr );

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


// formats the child row of the music table, showing track information
function format(data, callback) {
    $.ajax({
        url: '../src/controllers/db-queries.php?get-tracks=' + data['album_id'],
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log("response: %0", response);
            let tracks = '<table class="table-responsive">';
            $.each(response, function(i, d) {
                let trackNo = Number(i+1).toString().padStart(2,'0');
                tracks += `<tr><td class="tracks">${trackNo}</td><td class="tracks">${d.track_name}</td><td class="tracks">${d.duration}</td><td><button class="btn btn-default add-track">Add</button></td></tr>`;
            });
            let template = `<div class="slider">
                <div class="col-3">
                    <img alt="cover" src="../Resources/${data.image}.jpg" width="100%"/>
                </div>
                <div class="col-9">${tracks}</div>
                </div>`;
            callback(template, 'no-padding').show();
            $('div.slider', callback()).slideDown();
        }
    });
}