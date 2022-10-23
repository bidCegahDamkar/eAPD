//var dataset = JSON.parse(dataChart)
dataChart.akronim.forEach(myFunction);
var capt_text 
function myFunction(item, index) {
    if (index == 0) {
        capt_text = item + ": " + dataChart.jenis_apd[index] + "<br>";
    } else {
        capt_text += item + ": " + dataChart.jenis_apd[index] + "<br>";
    }
  }

Highcharts.chart('report', {
    chart: {
        type: 'bar',
        marginBottom: dataChart.margin
    },
    title: {
        text: dataChart.title
    },
    subtitle: {
        text: dataChart.group
    },
    xAxis: {
        categories: dataChart.akronim,
        title: {
            text: null
        }
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Petugas',
            align: 'high'
        },
        labels: {
            overflow: 'justify'
        }
    },
    tooltip: {
        valueSuffix: ' petugas'
    },
    plotOptions: {
        bar: {
            dataLabels: {
                enabled: true
            }
        },
        series: {
            pointPadding: 0,
            groupPadding: 0.05
        }
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'bottom',
        y: 0,
        floating: true,
        borderWidth: 1,
        backgroundColor:
            Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
        shadow: true
    },
    credits: {
        enabled: false
    },
    series: [{
        name: 'Belum Terima',
        data: dataChart.belum,
        color: '#000000',
    }, {
        name: 'Hilang',
        data: dataChart.hilang,
        color: '#FF0000'
    }, {
        name: 'Baik',
        data: dataChart.baik,
        color: '#008000'
    }, {
        name: 'Rusak Ringan',
        data: dataChart.rr,
        color: '#00FF00'
    }, {
        name: 'Rusak Sedang',
        data: dataChart.rs,
        color: '#000080'
    }, {
        name: 'Rusak Berat',
        data: dataChart.rb,
        color: '#F4D03F'
    }],
    caption: {
        text: '<b>Keterangan :</b><br><em>'+capt_text+'</em>'
    }
});