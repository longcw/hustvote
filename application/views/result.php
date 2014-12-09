
<div class="container" style="padding-top: 60px; padding-bottom: 40px;">
    <div class="row">
        <div class="span8 offset2 well">
            <legend><a href="<?= base_url('vote/join/' . $vote['content']['start_voteid']) ?>"><?= $vote['content']['title'] ?></a> 投票结果</legend> 
            <div class="row span6">

                <script>
                    var data = {
                        labels: [<?php
                                foreach ($vote['choices'] as $v) {
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
                                foreach ($result as $r) {
                                    echo "\"$r\",";
                                }
                                ?>]
                            }
                        ]
                    };
                </script>
                <canvas id="result-pic"></canvas>
            </div>
        </div>
    </div>
</div>
