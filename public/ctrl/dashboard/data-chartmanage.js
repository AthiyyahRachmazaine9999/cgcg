var url = "chart";
$(document).ready(function() {
    $.get(url, function(response) {
        Highcharts.chart('manageChart', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Info Sales'
            },
            xAxis: {
                categories: response.sale,
                crosshair: true
            },
            credits: {
                enabled: false
            },
            yAxis: {
                min: 0,
                labels: {
                    formatter: function() {
                        return IDRFormatter(this.value, 'Rp. ');
                    }
                },
                title: {
                    text: 'Total (Rp)'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:#263870;padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>Rp. {point.y:.1f}</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: "Total SO Price",
                data: response.quos
            }, {
                name: 'Total Cancel SO Price ',
                data: response.bquos,
            }]
        });
    });
});

var url = "chart";
$(document).ready(function() {
    $.get(url, function(response) {
        Highcharts.chart('so_so', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Info Sales In ' + response.year
            },
            xAxis: {
                categories: response.sales,
                crosshair: true
            },
            credits: {
                enabled: false
            },
            yAxis: {
                min: 0,
                labels: {
                    formatter: function() {
                        return IDRFormatter(this.value, 'Rp. ');
                    }
                },
                title: {
                    text: 'Total (Rp)'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:#263870;padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>Rp. {point.y:.1f}</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: "Total Price Sales",
                color: '#94a7e3',
                data: response.price
            }, {
                name: 'Total Cancel SO Price ',
                color: '#f7abc3',
                data: response.batal,
            }]
        });
    });
});


var url = "chart";
$(document).ready(function() {
    $.get(url, function(response) {
        Highcharts.chart('sos', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Info Sales Per - Month'
            },
            credits: {
                enabled: false
            },
            xAxis: {
                categories: response.month,
                crosshair: true
            },
            yAxis: {
                min: 0,
                labels: {
                    formatter: function() {
                        return IDRFormatter(this.value, 'Rp. ');
                    }
                },
                title: {
                    text: 'Total (Rp)'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:#263870;padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>Rp. {point.y:.1f}</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: "All Sales",
                color: '#715b73',
                data: response.post_count_data
            }]
        });
    });
});


function IDRFormatter(angka, prefix) {
    var number_string = angka.toString().replace(/[^,\d]/g, ''),
        split = number_string.split(','),
        sisa = split[0].length % 3,
        rupiah = split[0].substr(0, sisa),
        ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        var separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
}