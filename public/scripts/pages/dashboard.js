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
                    numberColumns: '3'
                },
                seriesToggle: true
            },
            series:[
                {label:'Worked Hours'},
                {label:'Month Hours'},
                {label:'Percentage'}
            ],
            seriesDefaults: {
                renderer: $.jqplot.BarRenderer,
                pointLabels: {show: true}
            },
            axes: {
                xaxis: {
                    renderer: $.jqplot.CategoryAxisRenderer,
                    ticks: ticks
                },
                yaxis: {
                    tickOptions:{
                        formatString: "%#.2f"
                    }
                }
            }
        });
    }

    if ($('#graph-bar-pending').length) {
        plot = $.jqplot('graph-bar-pending', [sp1, sp2], {
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
            seriesColors:['#00749F', '#C7754C'],
            series:[
                {label:'Hours'},
                {label:'Pending Hours'}
            ],
            seriesDefaults: {
                renderer: $.jqplot.BarRenderer,
                pointLabels: {show: true}
            },
            axes: {
                xaxis: {
                    renderer: $.jqplot.CategoryAxisRenderer,
                    ticks: ticksP
                },
                yaxis: {
                    tickOptions:{
                        formatString: "%#.2f"
                    }
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

        if (plot) {
            $.each(plot.series, function(index, series) {
                series.barWidth = undefined;
            });
            plot.replot();
        }
    });
});