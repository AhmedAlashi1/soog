jQuery(document).ready(function() {
    // ECHARTS
    require.config({
        paths: {
            echarts: '../assets/global/plugins/echarts/'
        }
    });

    // DEMOS
    require(
        [
            'echarts',
            'echarts/chart/bar',
            'echarts/chart/chord',
            'echarts/chart/eventRiver',
            'echarts/chart/force',
            'echarts/chart/funnel',
            'echarts/chart/gauge',
            'echarts/chart/heatmap',
            'echarts/chart/k',
            'echarts/chart/line',
            'echarts/chart/map',
            'echarts/chart/pie',
            'echarts/chart/radar',
            'echarts/chart/scatter',
            'echarts/chart/tree',
            'echarts/chart/treemap',
            'echarts/chart/venn',
            'echarts/chart/wordCloud'
        ],
        function(ec) {
            //--- BAR ---
            var myChart = ec.init(document.getElementById('echarts_bar'));
            myChart.setOption({
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    data: ['اجمالى الفواتير']
                },
                toolbox: {
                    show: true,
                    feature: {
                        mark: {
                            show: false
                        },
                        dataView: {
                            show: false,
                            readOnly: false
                        },
                        magicType: {
                            show: true,
                            type: ['line', 'bar']
                        },
                        restore: {
                            show: false
                        },
                        saveAsImage: {
                            show: true
                        }
                    }
                },
                calculable: true,
                xAxis: [{
                    type: 'category',
                    data: dataPerCountry.month
                }],
                yAxis: [{
                    type: 'value',
                    splitArea: {
                        show: true
                    }
                }],
                series: [{
                    name: 'اجمالى الفواتير',
                    type: 'bar',
                    data: dataPerCountry.country
                }]
            });

        }
    );
});
$('#defaultrange').daterangepicker({
        opens: (App.isRTL() ? 'left' : 'right'),
        format: 'YYYY-MM-DD',
        separator: ' to ',
        startDate: moment().subtract('days', 29),
        endDate: moment(),
        ranges: {
            'اليوم': [moment(), moment()],
            'أمس': [moment().subtract('days', 1), moment().subtract('days', 1)],
            'أخر 7 أيام': [moment().subtract('days', 6), moment()],
            'أخر 30 يوم': [moment().subtract('days', 29), moment()],
            'هذا الشهر': [moment().startOf('month'), moment().endOf('month')],
            'الشهر الماضى': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
        },
        minDate: '01/01/2012',
        maxDate: '12/31/2018',
    },
    function (start, end) {
        $('#defaultrange input').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
    }
);

$('.delete_date').click(function () {
    $('#daterange').val('');
    return false;
});


//charts
function showChartTooltip(x, y, xValue, yValue) {
    $('<div id="tooltip" class="chart-tooltip">' + yValue + '<\/div>').css({
        position: 'absolute',
        display: 'none',
        top: y - 40,
        left: x - 40,
        border: '0px solid #ccc',
        padding: '2px 6px',
        'background-color': '#fff'
    }).appendTo("body").fadeIn(200);
}
if ($('#site_activities').size() != 0) {
    //site activities
    var previousPoint2 = null;
    $('#site_activities_loading').hide();
    $('#site_activities_content').show();

    var plot_statistics = $.plot($("#site_activities"),
        [{
            data: data1,
            lines: {
                fill: 0.2,
                lineWidth: 0,
            },
            color: ['#BAD9F5']
        }, {
            data: data1,
            points: {
                show: true,
                fill: true,
                radius: 4,
                fillColor: "#9ACAE6",
                lineWidth: 2
            },
            color: '#9ACAE6',
            shadowSize: 1
        }, {
            data: data1,
            lines: {
                show: true,
                fill: false,
                lineWidth: 3
            },
            color: '#9ACAE6',
            shadowSize: 0
        }],
        {
            xaxis: {
                tickLength: 0,
                tickDecimals: 0,
                mode: "categories",
                min: 0,
                font: {
                    lineHeight: 18,
                    style: "normal",
                    variant: "small-caps",
                    color: "#6F7B8A"
                }
            },
            yaxis: {
                ticks: 5,
                tickDecimals: 0,
                tickColor: "#eee",
                font: {
                    lineHeight: 14,
                    style: "normal",
                    variant: "small-caps",
                    color: "#6F7B8A"
                }
            },
            grid: {
                hoverable: true,
                clickable: true,
                tickColor: "#eee",
                borderColor: "#eee",
                borderWidth: 1
            }
        });

    $("#site_activities").bind("plothover", function (event, pos, item) {
        $("#x").text(pos.x.toFixed(2));
        $("#y").text(pos.y.toFixed(2));
        if (item) {
            if (previousPoint2 != item.dataIndex) {
                previousPoint2 = item.dataIndex;
                $("#tooltip").remove();
                var x = item.datapoint[0].toFixed(2),
                    y = item.datapoint[1].toFixed(2);
                showChartTooltip(item.pageX, item.pageY, item.datapoint[0], item.datapoint[1] + ' طلب');
            }
        }
    });

    $('#site_activities').bind("mouseleave", function () {
        $("#tooltip").remove();
    });
}