$(document).ready(function() {
    var grid = $('#table-hours').bootstrapTable({
        cache: false,
        method: 'POST',
        contentType: 'application/x-www-form-urlencoded',
        url: 'hours-control/load',
        queryParams: function(params) {
            return {
                "sort": params.sort,
                "order": params.order,
                "page": $('#table-hours').bootstrapTable('getOptions').pageNumber
            };
        },
        sidePagination: 'server',
        toolbar: '#filter-bar',
        pagination: true,
        showfilter: true,
        pageList: [],
        sortName: 'day',
        sortOrder: 'DESC',
        search: false,
        columns: [{
            field: 'id',
            type: 'range',
            title: 'ID',
            visible: false,
            class: 'col-md-1',
            sortable: true
        },{
            field: 'day',
            title: 'Date',
            class: 'col-md-2',
            sortable: true
        }, {
            field: 'time',
            title: 'Time',
            class: 'col-md-1',
            sortable: true
        }, {
            field: 'action',
            title: 'Actions',
            class: 'col-md-1',
            align: 'center',
            searchable: false,
            formatter: 'actionFormatter'
        }]
    });

    $('#table-hours').on('post-body.bs.table', function () {
        $('[data-tooltip="true"]').tooltip({
            container: 'body'
        });
    });

    $('#filter-bar').bootstrapTableFilter({
        connectTo: '#table-hours'
    });
});

function actionFormatter(value, row, index) {
    return [

        '<a href="'+ row.edit +'" title="Edit" data-tooltip="true" class="table-link edit">',
            '<i class="fa fa-pencil"></i>',
            '</a>',


        '<a href="'+ row.delete +'" title="Remove" data-tooltip="true" data-title="Hours Control" data-confirm="Are you sure you want to remove this register?" class="table-link danger delete">',
            '<i class="fa fa-trash-o"></i>',
            '</a>'
    ].join('');
}

