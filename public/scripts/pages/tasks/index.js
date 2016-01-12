$(document).ready(function() {
    $("#btn-process-tasks").click(function(){
        $.ajax({
            url: "tasks/process", 
            type: 'POST',
            beforeSend: function() {

            },
            success: function(result) {
                console.log(result);
            }
        });
    }); 

    var grid = $('#table-tasks').bootstrapTable({
        cache: false,
        method: 'POST',
        contentType: 'application/x-www-form-urlencoded',
        url: 'tasks/load',
        queryParams: function(params) {
            return {
                "sort": params.sort,
                "order": params.order,
                "page": $('#table-tasks').bootstrapTable('getOptions').pageNumber
            };
        },
        sidePagination: 'server',
        toolbar: '#filter-bar',
        pagination: true,
        showfilter: true,
        sortName: 'date',
        pageSize: 10,
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
            field: 'created_at',
            title: 'Created at',
            class: 'col-md-2',
            visible: false,
            sortable: true
        },{
            field: 'updated_at',
            title: 'Updated at',
            class: 'col-md-2',
            visible: false,
            sortable: true
        },{
            field: 'task',
            title: 'Task',
            class: 'col-md-2',
            sortable: true
        },{
            field: 'date',
            title: 'Task date',
            class: 'col-md-2',
            sortable: true
        }, {
            field: 'time',
            title: 'Time spent',
            type: 'range',
            class: 'col-md-1',
            sortable: true
        }, {
            field: 'description',
            title: 'Description',
            class: 'col-md-5',
            sortable: true
        }, {
            field: 'link',
            title: 'Link',
            class: 'col-md-2',
            //visible: false,
            sortable: false,
            searchable: false
        }, {
            field: 'status',
            title: 'Status',
            class: 'col-md-1',
            sortable: true
        }, {
            field: 'sent_at',
            title: 'Sent at',
            class: 'col-md-2',
            visible: false,
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

    $('#table-tasks').on('post-body.bs.table', function () {
        $('[data-tooltip="true"]').tooltip({
            container: 'body'
        });
    });

    $('#filter-bar').bootstrapTableFilter({
        connectTo: '#table-tasks',
        onAll: function(name, args) {
            var d = new Date();
            $('#log').prepend(d.toLocaleString() + ': ' + name + "\n");
        },
        onSubmit: function(data) {
            var data = $('#filter-bar').bootstrapTableFilter('getData');
            var d = new Date();
            $('#log').prepend(d.toLocaleString() + ': ' + JSON.stringify(data) + "\n");
        }
    });
});

function actionFormatter(value, row, index) {
    return [

        '<a href="'+ row.edit +'" title="Edit" data-tooltip="true" class="table-link edit">',
            '<i class="fa fa-pencil"></i>',
            '</a>',


        '<a href="'+ row.delete +'" title="Remove" data-tooltip="true" data-title="Tasks" data-confirm="Are you sure you want to remove this task?" class="table-link danger delete">',
            '<i class="fa fa-trash-o"></i>',
            '</a>'
    ].join('');
}