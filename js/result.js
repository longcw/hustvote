$(document).ready(function () {
    

    
    var option = {responsive: true,inGraphDataShow:true };
    //var result_ctx = $("#result-pic").get(0).getContext("2d");
    //var result_pic = new Chart(result_ctx).Bar(data, {responsive: true, });
    DrawTheChart(data, option, 'result-pic', 'HorizontalBar');
});

function DrawTheChart(ChartData, ChartOptions, ChartId, ChartType) {
    eval('var myLine = new Chart(document.getElementById(ChartId).getContext("2d")).'
            + ChartType + '(ChartData,ChartOptions);document.getElementById(ChartId).getContext("2d").stroke();');
}

