$(document).ready(function() {
    if ($('#graph-bar').length) {
        plot2 = $.jqplot('graph-bar', [s2, s1, s4, s3], {
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
                    numberColumns: '4'
                },
                seriesToggle: true
            },
            series:[
                {label:'Tasks Month Hours&nbsp&nbsp'},
                {label:'Worked Hours&nbsp&nbsp'},
                {label:'Eletronic Point Hours&nbsp&nbsp'},
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

    //plot3 = $.jqplot('graph-bar-goal', [[monthWorkedHours]], {
    //    seriesDefaults: {
    //        renderer: $.jqplot.MeterGaugeRenderer,
    //        rendererOptions: {
    //            label: 'You need ' + (monthHours - monthWorkedHours) + '  hours to reach the goal',
    //            labelPosition: 'top',
    //            min: 0,
    //            max: monthHours,
    //            intervals: [0, 50, 100, monthHours],
    //            intervalColors: ['#cc6666', '#E7E658', '#93b75f', '#66cc66' ]
    //        }
    //    }
    //});

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
                {label:'Hours&nbsp&nbsp'},
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

        if (plot3) {
            $.each(plot3.series, function(index, series) {
                series.barWidth = undefined;
            });
            plot3.replot();
        }
    });
});