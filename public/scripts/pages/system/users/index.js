$(document).ready(function() {
    var grid = $('#table-users').bootstrapTable({
        cache: false,
        method: 'POST',
        contentType: 'application/x-www-form-urlencoded',
        url: 'users/load',
        queryParams: function(params) {
            return {
                "sort": params.sort,
                "order": params.order,
                "page": $('#table-users').bootstrapTable('getOptions').pageNumber
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
            field: 'created_at',
            title: 'Data de Criação',
            class: 'col-md-2',
            visible: false,
            sortable: true
        },{
            field: 'updated_at',
            title: 'Data de Atualização',
            class: 'col-md-2',
            visible: false,
            sortable: true
        },{
            field: 'name',
            title: 'Nome',
            class: 'col-md-2',
            sortable: true
        },{
            field: 'system_profile.name',
            title: 'Perfil',
            class: 'col-md-2',
            sortable: true
        },{
            field: 'email',
            title: 'E-mail',
            class: 'col-md-4',
            sortable: true
        },{
            field: 'status',
            title: 'Status',
            class: 'col-md-1',
            sortable: true
        },{
            field: 'last_access',
            title: 'Último Acesso',
            class: 'col-md-2',
            visible: false,
            sortable: true
        },{
            field: 'action',
            title: 'Actions',
            class: 'col-md-1',
            align: 'center',
            searchable: false,
            formatter: 'actionFormatter'
        }]
    });

    $('#table-users').on('post-body.bs.table', function () {
        $('[data-tooltip="true"]').tooltip({
            container: 'body'
        });
    });

    $('#filter-bar').bootstrapTableFilter({
        connectTo: '#table-users'
    });
});

function actionFormatter(value, row, index) {
    return [

        '<a href="'+ row.edit +'" title="Edit" data-tooltip="true" class="table-link edit">',
            '<i class="fa fa-pencil"></i>',
            '</a>',


        '<a href="'+ row.delete +'" title="Remove" data-tooltip="true" data-title="Usuários" data-confirm="Tem certeza que deseja remover este usuário?" class="table-link danger delete">',
            '<i class="fa fa-trash-o"></i>',
            '</a>'
    ].join('');
}