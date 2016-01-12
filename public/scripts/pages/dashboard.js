$(document).ready(function() {

    // Widget de ramais
    $('.widget-users').slimScroll({
        height: '560px',
        alwaysVisible: false,
        railVisible: true,
        wheelStep: 5,
        allowPageScroll: false
    });

    // Widget minhas tarefas
    $('.widget-todo').slimScroll({
        height: '290px',
        alwaysVisible: false,
        railVisible: true,
        wheelStep: 5,
        allowPageScroll: false
    });

    $('.form-control').hideseek({
        nodata: 'Nenhum resultado encontrado.'
    });

    if ($('#graph-bar').length) {

        plot2 = $.jqplot('graph-bar', [s1, s2], {
            animate: true,
            animateReplot: true,
            legend: {
                show: true,
                location: 'e',
                placement: 'inside'
            },
            seriesDefaults: {
                renderer: $.jqplot.BarRenderer,
                pointLabels: {show: true}
            },
            axes: {
                xaxis: {
                    renderer: $.jqplot.CategoryAxisRenderer,
                    ticks: ticks
                }
            }
        });
    }
});