<div class="container">
    这里是<?=$classlog['building']?>教室
    <a href="<?= base_url('classroom/jwc/d9') ?>" class="btn btn-info " >东九</a><a href="<?= base_url('classroom/jwc/d12') ?>" class="btn btn-info" >东十二</a>
    
    <a href="<?= base_url('classroom/update_jwc/' . $classlog['building']) ?>" class="btn btn-block btn-primary" >更新数据(<?= date('Y-m-d h:i:s', $classlog['update_time']) ?>)</a>

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
            <?php foreach ($classdata as $item) : ?>
                <tr> 
                    <?php
                    echo $item['classdata']
                    ?>                                 
                </tr>
            <?php endforeach; ?>
        </tbody> 
    </table> 
</div>
