var options = {
    chart: {
        height: 279,
        type: "bar",
        toolbar: {
            show: !1
        }
    },
    plotOptions: {
        bar: {
            columnWidth: "30%",
            endingShape: "rounded"
        }
    },
    dataLabels: {
        enabled: !1
    },
    stroke: {
        width: 2
    },
    colors: ["#f0643b"],
    series: [{
        name: "Growth",
        data: [44, 55, 41, 67, 22, 43, 21, 33, 45, 31, 87, 65]
    }],
    grid: {
        borderColor: "#f1f3fa",
        padding: {
            right: 0,
            bottom: 0,
            left: 0
        }
    },
    xaxis: {
        categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        offsetX: 0
    },
    yaxis: {
        title: {
            text: "Growth"
        }
    },
    fill: {
        type: "gradient",
        gradient: {
            shade: "light",
            type: "horizontal",
            shadeIntensity: .25,
            gradientToColors: void 0,
            inverseColors: !0,
            opacityFrom: .85,
            opacityTo: .85,
            stops: [50, 0, 100]
        }
    }
};
(chart = new ApexCharts(document.querySelector("#rotate-labels-column"), options)).render();
var chart;

options = {
    chart: {
        height: 337,
        type: "radar",
        toolbar: {
            show: !1
        }
    },
    series: [{
        name: "Desktops",
        data: [80, 50, 30, 40, 100, 20]
    }, {
        name: "Tablets",
        data: [20, 30, 40, 80, 20, 80]
    }, {
        name: "Mobiles",
        data: [44, 76, 78, 13, 43, 10]
    }],
    stroke: {
        width: 0,
        curve: "smooth"
    },
    fill: {
        opacity: .4
    },
    markers: {
        size: 0
    },
    legend: {
        show: !0,
        offsetY: -10
    },
    colors: ["#5089de", "#56c2d6", "#f0643b"],
    labels: ["2011", "2012", "2013", "2014", "2015", "2016"]
};
(chart = new ApexCharts(document.querySelector("#radar-multiple-series"), options)).render(), $("#usa-map").vectorMap({
    map: "us_merc_en",
    backgroundColor: "transparent",
    regionStyle: {
        initial: {
            fill: "#f5f6f8"
        }
    },
    series: {
        regions: [{
            values: {
                "US-VA": "#a6d8d1",
                "US-PA": "#a6d8d1",
                "US-TN": "#a6d8d1",
                "US-WY": "#a6d8d1",
                "US-WA": "#a6d8d1",
                "US-TX": "#a6d8d1"
            },
            attribute: "fill"
        }]
    }
});