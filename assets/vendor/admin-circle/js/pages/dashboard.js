$(document).ready(function () {
/**     var options_data_petugas = {
        series: [{
            name: "PNS",
            data: jmlPNS
      }, {
            name: "PJLP",
            data: jmlPJLP
      }],
        chart: {
        type: 'bar',
        height: 430
      },
        colors: ['#E91E63', '#546E7A'],
      
      plotOptions: {
        bar: {
          horizontal: true,
          dataLabels: {
            position: 'top',
          },
        }
      },
      dataLabels: {
        enabled: true,
        offsetX: -6,
        style: {
          fontSize: '12px',
          colors: ['#fff']
        }
      },
        stroke: {
        show: true,
        width: 1,
        colors: ['#fff']
      },
        tooltip: {
        shared: true,
        intersect: false
      },
        xaxis: {
            categories: list_sektor,
        },
      };

    var chart_data_petugas = new ApexCharts(
        document.querySelector("#chart-petugas"),
        options_data_petugas
    );

    chart_data_petugas.render();

    var options_data_apd = {
        series: [{
            name: "Persentase Input APD",
            data: persenSdhInput
        }, {
            name: "Persentase APD Terverifikasi",
            data: persenVerified
      }],
        chart: {
        type: 'bar',
        height: 430
      },
      plotOptions: {
        bar: {
          horizontal: true,
          dataLabels: {
            position: 'top',
          },
        }
      },
      dataLabels: {
        enabled: true,
        offsetX: -6,
        style: {
          fontSize: '12px',
          colors: ['#fff']
        }
      },
        stroke: {
        show: true,
        width: 1,
        colors: ['#fff']
      },
        tooltip: {
        shared: true,
        intersect: false
      },
        xaxis: {
            categories: list_sektor,
        },
      };

    var chart_data_apd = new ApexCharts(
        document.querySelector("#chart-apd"),
        options_data_apd
    );

    chart_data_apd.render();


    var options2 = {
        series: [{
            name: 'Series 1',
            data: [20, 100, 40, 30, 50, 80, 33]
        }],
        chart: {
            height: 337,
            type: 'radar',
            toolbar: {
                show: false,
            }
        },
        dataLabels: {
            enabled: true
        },
        plotOptions: {
            radar: {
                size: 140,
                polygons: {
                    strokeColors: '#e9e9e9',
                    fill: {
                        colors: ['#f8f8f8', '#fff']
                    }
                }
            }
        },
        colors: ['#EE6E83'],
        markers: {
            size: 4,
            colors: ['#fff'],
            strokeColor: '#FF4560',
            strokeWidth: 2,
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val
                }
            }
        },
        xaxis: {
            categories: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
        },
        yaxis: {
            tickAmount: 7,
            labels: {
                formatter: function (val, i) {
                    if (i % 2 === 0) {
                        return val
                    } else {
                        return ''
                    }
                }
            }
        }
    };
    var chart2 = new ApexCharts(document.querySelector("#apex22"), options2);
    chart2.render();
*/

  function roundNumber(num, scale) {
    if(!("" + num).includes("e")) {
      return +(Math.round(num + "e+" + scale)  + "e-" + scale);
    } else {
      var arr = ("" + num).split("e");
      var sig = ""
      if(+arr[1] + scale > 0) {
        sig = "+";
      }
      return +(Math.round(+arr[0] + "e" + sig + (+arr[1] + scale)) + "e-" + scale);
    }
  }


  const input_series = new Array();
  const verif_series = new Array();
  
  list_sudin.forEach((element, index) => {
    const name = element.sudin
    const jml_asn = parseInt(element.jml_pns) + parseInt(element.jml_pjlp)
    //input chart
    const chart_input = JSON.parse(element.chart_input_APD);
    const data_input = new Array();
    for (const i in chart_input) {
      const persen_input = parseInt(chart_input[i].total)/jml_asn*100;
      data_input.push( roundNumber(persen_input, 2) );
    }
    input_series[index] = {
      name: name,
      data: data_input
    };

    const chart_verif = JSON.parse(element.chart_verif_APD);
    //console.log(name)
    const data_verif = new Array();
    for (const j in chart_verif) {
      const persen_verif = parseInt(chart_verif[j].total)/jml_asn*100;
      data_verif.push( roundNumber(persen_verif, 2) );
    }
    verif_series[index] = {
      name: name,
      data: data_verif
    };
  });
  //console.log(input_series)

    var options3 = {
      series: input_series,
      chart: {
      height: 600,
      type: 'radar',
      dropShadow: {
        enabled: true,
        blur: 1,
        left: 1,
        top: 1
        }
      },
    title: {
      text: title_input
      },
    stroke: {
      width: 2
      },
    fill: {
      opacity: 0.1
      },
    markers: {
      size: 0
      },
    xaxis: {
      categories: listNamaJenisAPD
      },
    yaxis: {
      tickAmount: 7,
      labels: {
          formatter: function (val, i) {
            return val.toFixed(2);
          }
      }
    }
    };
    var chart_input = new ApexCharts(document.querySelector("#chart_input"), options3);
    chart_input.render();


    var options4 = {
      series: verif_series,
      chart: {
      height: 600,
      type: 'radar',
      dropShadow: {
        enabled: true,
        blur: 1,
        left: 1,
        top: 1
        }
      },
    title: {
      text: title_verif
      },
    stroke: {
      width: 2
      },
    fill: {
      opacity: 0.1
      },
    markers: {
      size: 0
      },
    xaxis: {
      categories: listNamaJenisAPD
      },
    yaxis: {
      tickAmount: 7,
      labels: {
          formatter: function (val, i) {
            return val.toFixed(2);
          }
        }
      }
    };
    var chart_verif = new ApexCharts(document.querySelector("#chart_verif"), options4);
    chart_verif.render();
});