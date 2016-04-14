$(document).ready(function() {
    var grid = $('#table-profiles').bootstrapTable({
        cache: false,
        method: 'POST',
        contentType: 'application/x-www-form-urlencoded',
        url: 'profiles/load',
        queryParams: function(params) {
            return {
                "sort": params.sort,
                "order": params.order,
                "page": $('#table-profiles').bootstrapTable('getOptions').pageNumber
            };
        },
        sidePagination: 'server',
        toolbar: '#filter-bar',
        pagination: true,
        showfilter: true,
        pageList: [],
        sortName: 'name',
        sortOrder: 'ASC',
        search: false,
        columns: [{
            field: 'id',
            type: 'range',
            title: 'ID',
            visible: false,
            class: 'col-md-1',
            sortable: true
        },{
            field: 'name',
            title: 'Nome',
            class: 'col-md-6',
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

    $('#table-profiles').on('post-body.bs.table', function () {
        $('[data-tooltip="true"]').tooltip({
            container: 'body'
        });
    });

    $('#filter-bar').bootstrapTableFilter({
        connectTo: '#table-profiles'
    });
});

function actionFormatter(value, row, index) {
    return [

        '<a href="'+ row.edit +'" title="Edit" data-tooltip="true" class="table-link edit">',
            '<i class="fa fa-pencil"></i>',
            '</a>',


        '<a href="'+ row.delete +'" title="Remove" data-tooltip="true" data-title="Perfil" data-confirm="Tem certeza que deseja remover este perfil?" class="table-link danger delete">',
            '<i class="fa fa-trash-o"></i>',
            '</a>'
    ].join('');
}