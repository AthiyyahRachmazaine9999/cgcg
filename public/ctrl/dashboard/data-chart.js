var url = "chart";
var Prices = new Array();
$(document).ready(function() {
    $.get(url, function(response) {
        var ctx = document.getElementById("mataChart").getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: response.sales,

                datasets: [{
                    label: 'Total Sales Order',
                    backgroundColor: '#263870',
                    borderColor: '#263870',
                    data: response.price,
                }, {
                    label: 'Total Cancel',
                    backgroundColor: '#a1add4',
                    borderColor: '#a1add4',
                    data: response.batal,
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            // Shorthand the millions
                            callback: function(value, index, values) {
                                return "Rp. " + value / 1e6 + 'M';
                            }
                        }
                    }]
                }
            }
        });
    });
});

//////
var url = "chart";
var Prices = new Array();
$(document).ready(function() {
    $.get(url, function(response) {
        var ctx = document.getElementById("so").getContext('2d');
        var m = response.month;
        var months = []
        for (let i = 0; i < m.length; i++) {
            months.push(moment().year(2020).month(i + 1).date(0).startOf('month'))
        }
        var so = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: m,

                datasets: [{
                    label: 'Total Sales Order Per Month',
                    backgroundColor: ["#700d1e", "#cc4159"],
                    borderColor: '#020303',
                    data: response.post_count_data,
                }]
            },
            options: {
                scales: {
                    xAxes: [{
                        barPercentage: 0.3
                    }],
                    yAxes: [{
                        ticks: {
                            // Shorthand the millions
                            callback: function(value, index, values) {
                                return "Rp. " + value / 1e6 + 'M';
                            }
                        }
                    }]
                }
            },
        });
    });
});



var url = "chart";
var Prices = new Array();
$(document).ready(function() {
    $.get(url, function(response) {
        var ctx = document.getElementById("so_so").getContext('2d');
        var so = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: response.sales,
                datasets: [{
                    label: 'All',
                    backgroundColor: '#005759',
                    borderColor: '#005759',
                    data: response.count_all,
                }, {
                    label: 'Active',
                    backgroundColor: '#008A8B',
                    borderColor: '#008A8B',
                    data: response.aktif,
                }, {
                    label: 'Cancel',
                    backgroundColor: '#6ADCDC',
                    borderColor: '#080808',
                    data: response.btl_paket,
                }],
                options: {
                    animation: {
                        onProgress: function(animation) {
                            progress.value = animation.animationObject.currentStep / animation.animationObject
                                .numSteps;
                        }
                    }
                }
            }
        });
    });
});