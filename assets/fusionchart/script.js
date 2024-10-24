const data = {
  title: 'Solar Employment Growth by Sector, 2010-2016',
  x: 'Year',
  y: 'Number of Employeees',
  data: [
    ['Year', 'Installation', 'Manufacturing', 'Sales & Distribution', 'Project Development', 'Other'],
    [2010, 43934, 24916, 11744, null, 12908],
    [2011, 52503, 24064, 17722, null, 5948],
    [2012, 57177, 29742, 16005, 7988, 8105],
    [2013, 69658, 29851, 19771, 12169, 11248],
    [2014, 97031, 32490, 20185, 15112, 8989],
    [2015, 119931, 30282, 24377, 22452, 11816],
    [2016, 137133, 38121, 32147, 34400, 18274],
    [2017, 154175, 40434, 39387, 34227, 18111]
  ]
};

function getColumnData(data, name) {
   const index = data.data[0].indexOf(name); // 0
   return data.data.slice(1).map(row => row[index]);
}

function dataToChartData(data) {
  return {
    "chart": {
      "caption": data.title,
      "yaxisname": data.y,
      "xaxisname": data.x,
      "subcaption": `${Math.min(...getColumnData(data, 'Year'))}-${Math.max(...getColumnData(data, 'Year'))}`,
      "showhovereffect": "1",
      "numbersuffix": "%",
      "drawcrossline": "1",
      "plottooltext": "<b>$dataValue</b> of youth were on $seriesName",
      "theme": "fusion"
    },
    "categories": [
      {
        "category": getColumnData(data, 'Year').map(label => ({ label: label.toString() })),
      }
    ],
    "dataset": data.data[0].slice(1).map((seriesname, index) => ({
      seriesname,
      "data": getColumnData(data, seriesname).map(value => ({ value })),
    }))
  };
}

const dataSource = dataToChartData(data);

FusionCharts.ready(function() {
   var myChart = new FusionCharts({
      type: "msline",
      renderAt: "chart-container",
      width: "100%",
      height: "100%",
      dataFormat: "json",
      dataSource
   }).render();
});