Apex.grid = {
    padding: {
        right: 0,
        left: 0
    }
}, Apex.dataLabels = {
    enabled: !1
};
var randomizeArray = function(e) {
        for (var t, a, o = e.slice(), r = o.length; 0 !== r;) a = Math.floor(Math.random() * r), t = o[r -= 1], o[r] = o[a], o[a] = t;
        return o
    },
    sparklineData = [47, 45, 54, 38, 56, 24, 65, 31, 37, 39, 62, 51, 35, 41, 35, 27, 93, 53, 61, 27, 54, 43, 19, 46],
    colorPalette = ["#00D8B6", "#008FFB", "#FEB019", "#FF4560", "#775DD0"],
    spark1 = {
        chart: {
            type: "area",
            height: 160,
            sparkline: {
                enabled: !0
            }
        },
        stroke: {
            width: 2,
            curve: "straight"
        },
        fill: {
            opacity: .2
        },
        series: [{
            name: "Sales ",
            data: randomizeArray(sparklineData)
        }],
        yaxis: {
            min: 0
        },
        colors: ["#56c2d6"],
        title: {
            text: "$424,652",
            offsetX: 10,
            style: {
                fontSize: "22px"
            }
        },
        subtitle: {
            text: "Total Sales",
            offsetX: 10,
            offsetY: 35,
            style: {
                fontSize: "13px"
            }
        }
    };
(chart = new ApexCharts(document.querySelector("#apex-line-1"), options)).render();
options = {
    chart: {
        height: 380,
        type: "line",
        shadow: {
            enabled: !1,
            color: "#bbb",
            top: 3,
            left: 2,
            blur: 3,
            opacity: 1
        }
    },
    stroke: {
        width: 5,
        curve: "smooth"
    },
    series: [{
        name: "Likes",
        data: [4, 3, 10, 9, 29, 19, 22, 9, 12, 7, 19, 5, 13, 9, 17, 2, 7, 5]
    }],
    xaxis: {
        type: "datetime",
        categories: ["1/11/2000", "2/11/2000", "3/11/2000", "4/11/2000", "5/11/2000", "6/11/2000", "7/11/2000", "8/11/2000", "9/11/2000", "10/11/2000", "11/11/2000", "12/11/2000", "1/11/2001", "2/11/2001", "3/11/2001", "4/11/2001", "5/11/2001", "6/11/2001"]
    },
    title: {
        text: "Social Media",
        align: "left",
        style: {
            fontSize: "14px",
            color: "#666"
        }
    },
    fill: {
        type: "gradient",
        gradient: {
            shade: "dark",
            gradientToColors: ["#f0643b"],
            shadeIntensity: 1,
            type: "horizontal",
            opacityFrom: 1,
            opacityTo: 1,
            stops: [0, 100, 100, 100]
        }
    },
    markers: {
        size: 4,
        opacity: .9,
        colors: ["#56c2d6"],
        strokeColor: "#fff",
        strokeWidth: 2,
        style: "inverted",
        hover: {
            size: 7
        }
    },
    yaxis: {
        min: -10,
        max: 40,
        title: {
            text: "Engagement"
        }
    },
    grid: {
        row: {
            colors: ["transparent", "transparent"],
            opacity: .2
        },
        borderColor: "#185a9d"
    },
    responsive: [{
        breakpoint: 600,
        options: {
            chart: {
                toolbar: {
                    show: !1
                }
            },
            legend: {
                show: !1
            }
        }
    }]
};
(chart = new ApexCharts(document.querySelector("#apex-line-2"), options)).render();
options = {
    chart: {
        height: 380,
        type: "area",
        stacked: !0,
        events: {
            selection: function(e, t) {
                console.log(new Date(t.xaxis.min))
            }
        }
    },
    colors: ["#f0643b", "#56c2d6", "#CED4DC"],
    dataLabels: {
        enabled: !1
    },
    stroke: {
        width: [2],
        curve: "smooth"
    },
    series: [{
        name: "South",
        data: generateDayWiseTimeSeries(new Date("11 Feb 2017 GMT").getTime(), 20, {
            min: 10,
            max: 60
        })
    }, {
        name: "North",
        data: generateDayWiseTimeSeries(new Date("11 Feb 2017 GMT").getTime(), 20, {
            min: 10,
            max: 20
        })
    }, {
        name: "Central",
        data: generateDayWiseTimeSeries(new Date("11 Feb 2017 GMT").getTime(), 20, {
            min: 10,
            max: 15
        })
    }],
    fill: {
        type: "gradient",
        gradient: {
            opacityFrom: .6,
            opacityTo: .8
        }
    },
    legend: {
        position: "top",
        horizontalAlign: "left"
    },
    xaxis: {
        type: "datetime"
    }
};

function generateDayWiseTimeSeries(e, t, a) {
    for (var o = 0, r = []; o < t;) {
        var i = e,
            s = Math.floor(Math.random() * (a.max - a.min + 1)) + a.min;
        r.push([i, s]), e += 864e5, o++
    }
    return r
}(chart = new ApexCharts(document.querySelector("#apex-area"), options)).render();
options = {
    chart: {
        height: 380,
        type: "bar",
        toolbar: {
            show: !1
        }
    },
    plotOptions: {
        bar: {
            horizontal: !1,
            endingShape: "rounded",
            columnWidth: "55%"
        }
    },
    dataLabels: {
        enabled: !1
    },
    stroke: {
        show: !0,
        width: 2,
        colors: ["transparent"]
    },
    colors: ["#f0643b", "#56c2d6", "#f8cc6b", "#23b397"],
    series: [{
        name: "OPENING VALUE",
        data: [44, 55, 57, 56, 61, 58, 63, 60]
    }, {
        name: "RECEIVED VALUE",
        data: [76, 85, 101, 98, 87, 105, 91, 114]
    }, {
        name: "ISSUED VALUE",
        data: [76, 85, 101, 98, 87, 105, 91, 114]
    }, {
        name: "CLOSING VALUE",
        data: [35, 41, 36, 26, 45, 48, 52, 53]
    }],
    xaxis: {
        categories: ["BCP-CCM", "BCP-FURNACE", "CONCAST-CCM", "CONCAST-FURNACE", "HRM", "HRM UNIT-2", "LAL MASJID", "SONARGAON"]
    },
    legend: {
        offsetY: -10
    },
    yaxis: {
        title: {
            text: "Tk. (Amount)"
        }
    },
    fill: {
        opacity: 1
    },
    grid: {
        row: {
            colors: ["transparent", "transparent"],
            opacity: .2
        },
        borderColor: "#f1f3fa"
    },
    tooltip: {
        y: {
            formatter: function(e) {
                return "$ " + e + " Taka"
            }
        }
    }
};
(chart = new ApexCharts(document.querySelector("#apex-column-1"), options)).render();
options = {
    chart: {
        height: 380,
        type: "bar",
        toolbar: {
            show: !1
        }
    },
    plotOptions: {
        bar: {
            dataLabels: {
                position: "top"
            }
        }
    },
    dataLabels: {
        enabled: !0,
        formatter: function(e) {
            return e + "%"
        },
        offsetY: -30,
        style: {
            fontSize: "12px",
            colors: ["#304758"]
        }
    },
    colors: ["#23b397"],
    series: [{
        name: "Inflation",
        data: [2.3, 3.1, 4, 10.1, 4, 3.6, 3.2, 2.3, 1.4, .8, .5, .2]
    }],
    xaxis: {
        categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        position: "top",
        labels: {
            offsetY: -18
        },
        axisBorder: {
            show: !1
        },
        axisTicks: {
            show: !1
        },
        crosshairs: {
            fill: {
                type: "gradient",
                gradient: {
                    colorFrom: "#D8E3F0",
                    colorTo: "#BED1E6",
                    stops: [0, 100],
                    opacityFrom: .4,
                    opacityTo: .5
                }
            }
        },
        tooltip: {
            enabled: !0,
            offsetY: -35
        }
    },
    fill: {
        gradient: {
            enabled: !1,
            shade: "light",
            type: "horizontal",
            shadeIntensity: .25,
            gradientToColors: void 0,
            inverseColors: !0,
            opacityFrom: 1,
            opacityTo: 1,
            stops: [50, 0, 100, 100]
        }
    },
    yaxis: {
        axisBorder: {
            show: !1
        },
        axisTicks: {
            show: !1
        },
        labels: {
            show: !1,
            formatter: function(e) {
                return e + "%"
            }
        }
    },
    title: {
        text: "Monthly Inflation in Argentina, 2002",
        floating: !0,
        offsetY: 350,
        align: "center",
        style: {
            color: "#444"
        }
    },
    grid: {
        row: {
            colors: ["#f1f3fa", "transparent"],
            opacity: .2
        },
        borderColor: "#f1f3fa"
    }
};