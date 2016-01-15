$(document).ready(function() {
    if ($('#graph-bar').length) {

        plot2 = $.jqplot('graph-bar', [s1, s2], {
            animate: true,
            animateReplot: true,
            legend: {
                renderer: jQuery.jqplot.EnhancedLegendRenderer,
                show: true,
                location: 's',
                placement: 'outsideGrid',
                border: 'none',
                rowSpacing: '1em',
                marginRight: "100",
                rendererOptions: {
                    numberRows: '1',
                    numberColumns: '2'
                },
                seriesToggle: true
            },
            series:[
                {label:'Hours'},
                {label:'Tasks'}
            ],
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

    $(window).resize(function() {
        if (plot2) {
            $.each(plot2.series, function(index, series) {
                series.barWidth = undefined;
            });
            plot2.replot();
        }
    });
});