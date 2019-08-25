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
                return `<img alt="" src="../Resources/${data}_sm.jpg"/>`;
            }
        },
        {"data": "artist"},
        {"data": "title"},
        {"data": "year"},
        {"data": "genre"},
        {"defaultContent": ""}
    ],
    "createdRow": function(row, data, index) {
        $('td', row).eq(6).html(`<button class="btn btn-primary btn-round add-album">Add</button>`);
    },
    "columnDefs": [
        { "orderable": false, "targets": [0,1,6] },
        { "orderData": [2, 4, 0], "targets": 0 },
        { "responsivePriority": 1, "targets": 0 },
        { "responsivePriority": 2, "targets": 2 },
        { "responsivePriority": 3, "targets": 3 },
    ],
    "lengthMenu": [10, 25, 50, 100],
    "responsive": { details: false },
    "dom" : "<'row'<'col-3'l><'col-2'i><'col-7 searchStyle'p>>",
    orderCellsTop: true,
    fixedHeader: true,
    "initComplete": function() {
        // Add filtering
        table.columns().every(function () {
            let column = this;

            if([2,3].indexOf(column.index()) > -1) {
                $('<input type="text" class="form-control input-sm" />')
                    .appendTo($("thead tr:eq(1) td").eq(this.index()))
                    .on("keyup", function () {
                        column.search($(this).val()).draw();
                    });
            }

            if([4,5].indexOf(column.index()) > -1) {
                let select = $('<select class="form-control input-sm"><option value=""></option></select>')
                    .appendTo($("thead tr:eq(1) td").eq(this.index()))
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


function format(data, callback) {
    console.log("data: %0", data);
    console.log("callback: %0", callback);
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