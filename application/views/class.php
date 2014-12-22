<a href="<?=  base_url('classroom/update')?>" class="btn btn-large btn-block btn-primary" >更新数据(<?=  date('Y-m-d h:i:s', $classlog['update_time'])?>)</a>
<table class="table table-bordered"> 
    
    <thead> 
        <tr> 
            <th>教室号</th> 
            <th>1</th> 
            <th>2</th> 
            <th>3</th>
            <th>4</th>
            <th>5</th>
        </tr> 
    </thead>    
    <tbody> 
        <?php foreach ($classdata as $item) :?>
        <tr> 
            <td><?=$item['classid']?></td> 
            <?php
            for($i = 0; $i < 5; $i++) {
                $is = $item['classdata'] % 2;
                $item['classdata'] = intval($item['classdata'] / 2);
                $color = $is ? '#66CCCC' : '';
                echo "<td bgcolor=\"$color\">$is</td> ";
            }
            ?>                                 
        </tr>
        <?php endforeach;?>
    </tbody> 
</table> 
