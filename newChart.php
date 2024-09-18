<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/drilldown.js"></script>
<style>
#container {
    min-width: 310px;
    height: 400px;
    margin: 0 auto;
}

</style>
<div id="container"></div>


<script>
// Create the chart
Highcharts.chart('container', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Basic drilldown'
    },
    xAxis: {
        type: 'category'
    },

    legend: {
        enabled: false
    },

    plotOptions: {
        series: {
            borderWidth: 0,
            dataLabels: {
                enabled: true
            }
        }
    },

    series: [{
        name: 'Units',
        colorByPoint: true,
        data: [{
            name: 'Unit-01',
            y: 5,
            drilldown: 'u1'
        }, {
            name: 'Unit-02',
            y: 2,
            drilldown: 'u2'
        }, {
            name: 'Unit-03',
            y: 4,
            drilldown: 'u3'
        }]
    }],
    drilldown: {
        series: [{
            id: 'u1',
            data: [
                ['Jan', 40],
                ['Feb', 80],
                ['Mar', 50],
                ['Apr', 20],
                ['May', 100],
				['Jun', 70],
				['Jul', 80],
				['Aug', 100],
				['Sep', 40],
				['Oct', 70],
				['Nov', 40],
				['Dec', 60]
            ]
        }, {
            id: 'u2',
            data: [
               ['Jan', 40],
                ['Feb', 80],
                ['Mar', 50],
                ['Apr', 20],
                ['May', 100],
				['Jun', 70],
				['Jul', 80],
				['Aug', 100],
				['Sep', 40],
				['Oct', 70],
				['Nov', 40],
				['Dec', 60]
            ]
        }, {
            id: 'u3',
            data: [
                ['Jan', 40],
                ['Feb', 80],
                ['Mar', 50],
                ['Apr', 20],
                ['May', 100],
				['Jun', 70],
				['Jul', 80],
				['Aug', 100],
				['Sep', 40],
				['Oct', 70],
				['Nov', 40],
				['Dec', 60]
            ]
        }]
    }
});

</script>