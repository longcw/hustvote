
<div class="container" style="padding-top: 60px; padding-bottom: 40px;">
    <div class="row">
        <div class="span8 offset2 well">
            <legend>我发起的投票</legend> 
            <div class="row span7">

                <table class="table table-striped table-condensed"> 
                    <thead> 
                        <tr> 
                            <th>投票名称</th> 
                            <th>创建时间</th>
                            <th>状态</th>
                            <th>编辑</th>
                            <th>邀请码</th>
                        </tr> 
                    </thead>    
                    <tbody> 
                        <?php foreach ($votelog as $row): ?>
                            <tr> 
                                <td><a href="<?=  base_url('vote/join/'.$row['start_voteid'])?>"><?= $row['title'] ?></a></td> 
                                <td><?= date('Y-m-d H:i', $row['create_time']) ?></td>
                                <td><?php if ($row['end_time'] == -1 || $row['end_time'] > time()): ?>
                                        <span class="label label-success">进行中</span> 
                                    <?php else: ?>
                                        <span class="label">结束</span>
                                    <?php endif; ?>
                                </td>
                                <td><a href="<?=  base_url('vote/start_step_2/'.$row['start_voteid'])?>"><i class="icon-pencil"></i>编辑</a></td>
                                <td><a href="<?=  base_url('vote/mycode/'.$row['start_voteid'])?>"><i class="icon-pencil"></i>生成</a></td>
                            </tr> 
                        <?php endforeach; ?>                                  
                    </tbody> 
                </table> 

            </div>
        </div>
    </div>
</div>