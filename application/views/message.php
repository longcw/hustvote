
<div class="container" style="padding-top: 60px; padding-bottom: 40px;">
    <div class="row">
        <div class="span8 offset2 well">
            <legend>我的消息</legend> 
            <div class="row span7">

                <table class="table table-striped table-condensed"> 
                    <thead> 
                        <tr>
                            <th>状态</th>
                            <th>发信人</th> 
                            <th>投票</th> 
                            <th>时间</th> 
                            <th>内容</th>                                           
                        </tr> 
                    </thead>    
                    <tbody> 
                        <?php foreach ($comment as $row): ?>
                            <tr> 
                                <td><?php if ($row['is_read'] == 0): ?>
                                        <span class="label label-success">未读</span> 
                                    <?php else: ?>
                                        <span class="label">已读</span>
                                    <?php endif; ?>
                                </td>
                                <td><?=$row['from_nickname'] ?></td> 
                                <td><a href="<?=  base_url('vote/join/'.$row['vid'])?>"><?= $row['title'] ?></a></td> 
                                <td><?= date('Y-m-d H:i:s', $row['create_time']) ?></td> 
                                <td><?=$row['content']?></td> 
                                                                      
                            </tr> 
                        <?php endforeach; ?>                                  
                    </tbody> 
                </table> 

            </div>
        </div>
    </div>
</div>