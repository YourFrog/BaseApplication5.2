
import "echarts/dist/echarts"

function getRandomInt(min, max) {
    min = Math.ceil(min);
    max = Math.floor(max);
    return Math.floor(Math.random() * (max - min + 1)) + min;
}

$(function() {
    let labels = [];
    for(let i = 0; i <= 23; i++) {
        let name = "";

        if( i < 10 ) {
            name = "0" + i;
        } else {
            name = i;
        }

        labels.push(name);
    }

    let percent = 100;
    let data = [];
    for(let i = 0; i < 23; i++) {
        let value = getRandomInt(0, percent / 3);

        percent -= value
        data.push(value)
    }

    let ctx = $('.js-myChart').each(function() {

        var myChart = Echar.init(this);

        var option = {
            title: {
                text: 'ECharts entry example'
            },
            tooltip: {},
            legend: {
                data:['Sales']
            },
            xAxis: {
                data: ["shirt","cardign","chiffon shirt","pants","heels","socks"]
            },
            yAxis: {},
            series: [{
                name: 'Sales',
                type: 'bar',
                data: [5, 20, 36, 10, 10, 20]
            }]
        };

        // use configuration item and data specified to show chart
        myChart.setOption(option);
    });
});