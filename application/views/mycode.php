
<div class="container" style="padding-top: 60px; padding-bottom: 40px;">
    <div class="row">
        <div class="span8 offset2 well">
            <legend>【<a href="<?= base_url('vote/join/' . $votetitle['start_voteid']) ?>"><?= $votetitle['title'] ?></a>】的邀请码</legend> 
            <div class="row span7">

                <table class="table table-striped table-condensed"> 
                    <thead> 
                        <tr> 
                            <th>邀请码</th> 
                            <th>状态</th>
                            <th>投票记录</th>
                            <th>二维码</th>
                        </tr> 
                    </thead>    
                    <tbody> 
                        <?php foreach ($codelog as $row): ?>
                            <tr> 
                                <td class="code"><?= $row['code'] ?></td>
                                <td><?php if ($row['is_voted'] == 0 > time()): ?>
                                        <span class="label label-success">未使用</span> 
                                    <?php else: ?>
                                        <span class="label">已使用</span>
                                    <?php endif; ?>
                                </td>
                                <td><a class="get-log"><i class="icon-time"></i> 查看</a></td>
                                <td>
                                    <?php $code_to = base_url('vote/join/' . $votetitle['start_voteid'] . '?code=' . $row['code']); ?>
                                    <a class="get-qrcode"><i class="icon-qrcode"></i> 生成</a>
                                </td>
                            </tr> 
                        <?php endforeach; ?>                                  
                    </tbody>
                </table> 
                <form action="<?= base_url('vote/addCode/' . $votetitle['start_voteid']) ?>" method="post">
                    <div class="control-group">
                        <label class="control-label" for="s">生成邀请码</label>
                        <div class="controls">
                            <input type="text" placeholder="数量" class="input-xlarge" name="count" id="title" >
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit">生成</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="modal-qrcode" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="confirm-modal-title">
            二维码
        </h3>
    </div>
    <div class="modal-body">
        <img src="#" id="qrcode-img" class="img-rounded" />
        <p id='qrcode-content'></p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
    </div>
</div>

<div id="modal-log" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="confirm-modal-title">
            邀请码投票记录
        </h3>
    </div>
    <div class="modal-body">
        <p id="code-log"></p>
        <p id="code-select"></p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button>
    </div>
</div>

<div id="qrcode-url" style="display: none"><?= base_url('qrcode.php') ?></div>
<div id="vote-url" style="display: none"><?= base_url('vote/join/' . $votetitle['start_voteid']) ?></div>
<div id="vote-id" style="display: none"><?= $votetitle['start_voteid'] ?></div>