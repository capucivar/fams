require.config({
    paths: {
        echarts: '/static/cdn/echarts/'
    }
});

$(function () {
    var chartHeight = $(window).height() * 0.8;
    var chartWidth = $(window).width();
    var chartStyle = "height:" + chartHeight + "px;width:" + chartWidth + "px;";

    setTimeout(function () {
        initChart1(chartStyle);
        initChart2(chartStyle);
        initChart3();
    }, 2000);
});

function initChart1(chartStyle) {
    var chartId = "chart1";
    $("#chartContainer1").html('<div id="' + chartId + '" style="' + chartStyle + '"></div>');

    $.post(SERVER_IP + "/WxReport/getTodayData", function (response) {
        console.info(response);
        if (response.code != 1) return;
        var tableData = response.result;
        var dataAry = [];
        var nameAry = [];
        $.each(tableData, function (index, tableItem) {
            dataAry.push(tableItem["all"]);
            nameAry.push(tableItem["tname"]);
        });

        require(
            [
                'echarts',
                'echarts/chart/bar'
            ],
            function (ec) {
                var option = {
                    grid: {
                        x: 40,
                        y: 0,
                        x2: 40,
                        y2: 20
                    },
                    tooltip: {
                        trigger: 'axis'
                    },
                    xAxis: [
                        {
                            type: 'value',
                            boundaryGap: [0, 0.01]
                        }
                    ],
                    yAxis: [
                        {
                            type: 'category',
                            data: nameAry
                        }
                    ],
                    series: [
                        {
                            name: '今日营业额',
                            type: 'bar',
                            data: dataAry
                        }
                    ]
                };

                var myChart = ec.init(document.getElementById(chartId));
                myChart.setOption(option);
                hideLoading();
            }
        );
    });
}

function initChart2(chartStyle) {
    var chartId = "chart2";
    $("#chartContainer2").html('<div id="' + chartId + '" style="' + chartStyle + '"></div>');

    $.post(SERVER_IP + "/WxReport/getTodayData", function (response) {
        console.info(response);
        if (response.code != 1) return;
        var tableData = response.result;
        var dataAry = [];
        var nameAry = [];
        $.each(tableData, function (index, tableItem) {
            dataAry.push(tableItem["all"]);
            nameAry.push(tableItem["tname"]);
        });

        require(
            [
                'echarts',
                'echarts/chart/line'
            ],
            function (ec) {
                var option = {
                    grid: {
                        x: 40,
                        y: 10,
                        x2: 40,
                        y2: 80
                    },
                    tooltip: {
                        trigger: 'axis'
                    },
                    dataZoom: {
                        show: true,
                        realtime: true,
                        start: 20,
                        end: 80
                    },
                    xAxis: [
                        {
                            type: 'category',
                            boundaryGap: false,
                            data: function () {
                                var list = [];
                                for (var i = 1; i <= 24; i++) {
                                    list.push(i + "时");
                                }
                                return list;
                            }()
                        }
                    ],
                    yAxis: [
                        {
                            type: 'value'
                        }
                    ],
                    series: [
                        {
                            name: '最高',
                            type: 'line',
                            data: function () {
                                var list = [];
                                for (var i = 1; i <= 30; i++) {
                                    list.push(Math.round(Math.random() * 30));
                                }
                                return list;
                            }()
                        }
                    ]
                };

                var myChart = ec.init(document.getElementById(chartId));
                myChart.setOption(option);
                hideLoading();
            }
        );
    });
}

function initChart3() {
    var chartId = "chart3";
    var chartHeight = $(window).width();
    var chartWidth = $(window).width();
    var chartStyle = "height:" + chartHeight + "px;width:" + chartWidth + "px;";
    $("#chartContainer3").html('<div id="' + chartId + '" style="' + chartStyle + '"></div>');

    $.post(SERVER_IP + "/WxReport/getTodayData", function (response) {
        console.info(response);
        if (response.code != 1) return;
        var tableData = response.result;
        var dataAry = [];
        var nameAry = [];
        $.each(tableData, function (index, tableItem) {
            dataAry.push(tableItem["all"]);
            nameAry.push(tableItem["tname"]);
        });

        require(
            [
                'echarts',
                'echarts/chart/pie'
            ],
            function (ec) {
                var option = option = {
                    tooltip: {
                        trigger: 'item',
                        formatter: "{a} <br/>{b} : {c} ({d}%)"
                    },
                    legend: {
                        orient: 'vertical',
                        x: 'left',
                        data: ['铁观音', '拿铁', '酸菜鱼', '麻辣小龙虾', '卡布奇诺']
                    },
                    series: [
                        {
                            type: 'pie',
                            radius: '55%',
                            center: ['50%', '60%'],
                            data: [
                                {value: 1548, name: '铁观音'},
                                {value: 335, name: '拿铁'},
                                {value: 310, name: '酸菜鱼'},
                                {value: 234, name: '麻辣小龙虾'},
                                {value: 135, name: '卡布奇诺'}
                            ]
                        }
                    ]
                };

                var myChart = ec.init(document.getElementById(chartId));
                myChart.setOption(option);
                hideLoading();
            }
        );
    });
}

var loadedCount = 0;
function hideLoading() {
    loadedCount++;
    if (loadedCount < 3) return;

    $('#loading').hide();
    $('.wrapper').removeClass("hidden");
}