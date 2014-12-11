$(document).ready(function () {
    $('input').iCheck({
        checkboxClass: 'icheckbox_flat-blue',
        radioClass: 'iradio_flat-blue'
    });


    var option_data = {
        responsive: true,
        inGraphDataShow: true,
        scaleStartValue: 0
    };
    var option_nodata = {
        responsive: true,
        scaleStartValue: 0
    };
    DrawTheChart(data, option_data, 'chart', 'HorizontalBar');

    $('.select').on('ifChecked', function () {
        var type = $(this).val();
        if (type === 'Radar') {
            DrawTheChart(data, option_nodata, 'chart', type);
        } else {
            DrawTheChart(data, option_data, 'chart', type);
        }

    });

    //DrawTheChart(data, option, 'chart-pie', 'Pie');
    //DrawTheChart(data, option, 'chart-radar', 'Radar');
});

function DrawTheChart(ChartData, ChartOptions, ChartId, ChartType) {
    eval('new Chart(document.getElementById(ChartId).getContext("2d")).'
            + ChartType + '(ChartData,ChartOptions);document.getElementById(ChartId).getContext("2d").stroke();');
}

