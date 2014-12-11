
<div class="container" style="padding-top: 60px; padding-bottom: 40px;">
    <div class="row">
        <div class="span8 offset2 well">
            <legend><a href="<?= base_url('vote/join/' . $vote['start_voteid']) ?>"><?= $vote['title'] ?></a> 投票结果</legend> 
            <div class="row span6">

                <div class="controls">
                    <label class="radio inline">
                        <input type="radio" value="HorizontalBar" name="chart_type" checked="checked" class="select" > 
                        条形图
                    </label>
                    <label class="radio inline">
                        <input type="radio" value="Radar" name="chart_type" class="select">
                        雷达图
                    </label>
                </div>
                <div class="span6 well">
                <canvas id="chart"></canvas>
                </div>
                
            </div>
        </div>
    </div>
</div>
<script>
    var data = {
        labels: [<?php
foreach ($result as $v) {
    echo "\"$v[choice_name]\",";
}
?>],
        datasets: [
            {
                label: "My Second dataset",
                fillColor: "rgba(151,187,205,0.5)",
                strokeColor: "rgba(151,187,205,0.8)",
                highlightFill: "rgba(151,187,205,0.75)",
                highlightStroke: "rgba(151,187,205,1)",
                data: [<?php
                        foreach ($result as $v) {
                            echo "\"$v[count]\",";
                        }
                        ?>],
            }
        ]
    };
</script>