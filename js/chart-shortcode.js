
var petrolctx = document.getElementById('fsa-petrol-chart');
var petrolchart;
var dieselctx = document.getElementById('fsa-diesel-chart');
var dieselchart;

var borderColor = [
    'rgba(255,99,132,1)',
    'rgba(54, 162, 235, 1)',
    'rgba(255, 206, 86, 1)',
    'rgba(75, 192, 192, 1)',
    'rgba(153, 102, 255, 1)',
    'rgba(255, 159, 64, 1)'
]
  
function unique(values) {
  var arr = [];
  for (var i = 0; i < values.length; i++) {
    if (!arr.includes(values[i])) {
      arr.push(values[i]);
    }
  }
  return arr;
}

var petrol = fsafuelchartdata.petrol.map(function (item) {
    var date = new Date(item.date);
      return {
        date: date.toLocaleDateString(undefined, {day:'2-digit'}) + '-' + date.toLocaleDateString(undefined, {month:'short'}) + '-' + date.toLocaleDateString(undefined, {year:'numeric'}),
        label: item.location + ' - ' + item.type + ' - ' + item.octane,
        value: item.value / 100
      }
    })

    if (petrolchart) {
      petrolchart.destroy()
    }
    petrolchart = new Chart(petrolctx, {
      type: 'line',
      data: {
        datasets: [
          {
            label: 'Reef Unleaded 93',
            fill: false,
            borderColor: borderColor[0],
            backgroundColor: borderColor[0],
            data: petrol
              .filter(function(item) {
                return item.label === 'Reef - Unleaded - 93'
              })
              .map(function(item) {
                return item.value
              })
          },
          {
            label: 'Reef Unleaded 95',
            fill: false,
            borderColor: borderColor[1],
            backgroundColor: borderColor[1],
            data: petrol
              .filter(function(item) {
                return item.label === 'Reef - Unleaded - 95'
              })
              .map(function(item) {
                return item.value
              })
          },
          {
            label: 'Reef LRP 93',
            fill: false,
            borderColor: borderColor[2],
            backgroundColor: borderColor[2],
            data: petrol
              .filter(function(item) {
                return item.label === 'Reef - LRP - 95'
              })
              .map(function(item) {
                return item.value
              })
          },
          {
            label: 'Coast Unleaded 93',
            fill: false,
            borderColor: borderColor[3],
            backgroundColor: borderColor[3],
            data: petrol
              .filter(function(item) {
                return item.label === 'Coast - Unleaded - 93'
              })
              .map(function(item) {
                return item.value
              })
          },
          {
            label: 'Coast Unleaded 95',
            fill: false,
            borderColor: borderColor[4],
            backgroundColor: borderColor[4],
            data: petrol
              .filter(function(item) {
                return item.label === 'Coast - Unleaded - 95'
              })
              .map(function(item) {
                return item.value
              })
          },
          {
            label: 'Coast LRP 95',
            fill: false,
            borderColor: borderColor[5],
            backgroundColor: borderColor[5],
            data: petrol
              .filter(function(item) {
                return item.label === 'Coast - Unleaded - 95'
              })
              .map(function(item) {
                return item.value
              })
          }
        ],
        labels: unique(
          petrol.map(function(item) {
            return item.date
          })
        )
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        scales: {
          yAxes: [
            {
              ticks: {
                // Include a dollar sign in the ticks
                callback: function(value, index, values) {
                  return 'R' + value.toFixed(2)
                }
              }
            }
          ]
        }
      }
    })

var diesel = fsafuelchartdata.diesel.map(function (item) {
        var date = new Date(item.date);
      return {
        date: date.toLocaleDateString(undefined, {day:'2-digit'}) + '-' + date.toLocaleDateString(undefined, {month:'short'}) + '-' + date.toLocaleDateString(undefined, {year:'numeric'}),
        label: item.location + ' ' + item.percentage + '%',
        value: item.value / 100
      }
    })
    if (dieselchart) {
      dieselchart.destroy()
    }
    dieselchart = new Chart(dieselctx, {
      type: 'line',
      data: {
        datasets: [
          {
            label: 'Reef 0.05%',
            fill: false,
            borderColor: borderColor[0],
            backgroundColor: borderColor[0],
            data: diesel
              .filter(function(item) {
                return item.label === 'Reef 0.05%'
              })
              .map(function(item) {
                return item.value
              })
          },
          {
            label: 'Reef 0.01%',
            fill: false,
            borderColor: borderColor[1],
            backgroundColor: borderColor[1],
            data: diesel
              .filter(function(item) {
                return item.label === 'Reef 0.01%'
              })
              .map(function(item) {
                return item.value
              })
          },
          {
            label: 'Coast 0.05%',
            fill: false,
            borderColor: borderColor[2],
            backgroundColor: borderColor[2],
            data: diesel
              .filter(function(item) {
                return item.label === 'Coast 0.05%'
              })
              .map(function(item) {
                return item.value
              })
          },
          {
            label: 'Coast 0.01%',
            fill: false,
            borderColor: borderColor[3],
            backgroundColor: borderColor[3],
            data: diesel
              .filter(function(item) {
                return item.label === 'Coast 0.01%'
              })
              .map(function(item) {
                return item.value
              })
          }
        ],
        labels: unique(
          diesel.map(function(item) {
            return item.date
          })
        )
      },
      options: {
        responsive: true,
        maintainAspectRatio: true,
        scales: {
          yAxes: [
            {
              ticks: {
                // Include a dollar sign in the ticks
                callback: function(value, index, values) {
                  return 'R' + value.toFixed(2)
                }
              }
            }
          ]
        }
      }
    })