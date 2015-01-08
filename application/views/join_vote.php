<div id="content" class="container">
    <div id="blog" class="row-fluid">
        <div class="span7">
            <div class="page-header">
                <p class="entry-meta">
                    <span class="entry-author"><i class="icon-user"></i> <?= $voteuser['nickname'] ?></span>
                    <span class="entry-date"><i class="icon-calendar"></i> <?= date('Y-m-d', $detail['content']['create_time']) ?></span>
                    <span class="entry-comments"><i class="icon-comments"></i> <span id="comment-count-header"><?= $comment_count ?> 条评论</span></span>
                </p>
                <h2><?= $detail['content']['title'] ?></h2>
            </div>
            <div id="vote-uid" style="display: none"><?= $detail['content']['uid'] ?></div>

            <div class="entry-content">
                <div class="well">
                    <?= $detail['content']['intro'] ?>
                </div>
                <form method="post" action="<?= base_url('vote/doJoinVote') ?>" id="joinForm">
                    <?php foreach ($detail['choices'] as $row) : ?>
                        <aa href="#c<?= $row['choiceid'] ?>"></aa>
                        <div class="sengal-thumbnail thumbnail">
                            <div class="caption">
                                <input type="checkbox" name="choice[]" class="choice" value="<?= $row['choiceid'] ?>" id="c<?= $row['choiceid'] ?>"> &nbsp;
                                <a href="#" class="title" cid="<?= $row['choiceid'] ?>"><?= $row['choice_name'] ?></a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <input type="text" value="<?= $detail['content']['start_voteid'] ?>" style="display:none" name="start_voteid" id="start_voteid"/>
                    <input type="text" value="<?= $code ?>" style="display:none" name="code" id="code"/>
                    <input type="text" value="0" style="display:none" id="fingerprint" name="fingerprint" />
                    <input id="captcha" value="" name="captcha" style="display: none" />
                </form>

                <div class="sidebar-widget" id="selected" style="display: none">
                    <h3>选中</h3>
                    <ul class="sidebar-list">
                        <span id="select-end" style="display: none"></span>
                        <li>&nbsp;</li>
                    </ul>
                    <button class="btn btn-primary" id="submit-btn">确认</button>
                </div>
            </div> 

            
            <div class="entry-comments">
                <h3><span id="comment-count"><?= $comment_count ?></span> 条评论</h3>
                <ul id="comments-list">
                    <div id="comment-head" style="display:none"></div>
                    <?php foreach ($comment as $row):?>
                    <li>
                        <div class="media-body">
                            <h4 class="comment-author"><?=$row['from_nickname']?> <small> on <?=date('Y-m-d H:i:s', $row['create_time'])?> <a href="#" class="comment-reply" from-uid="<?=$row['from_uid']?>" nickname ="<?=$row['from_nickname']?>">回复</a></small></h4>
                            <div class="comment-content">
                                <?=$row['content']?>
                            </div>
                        </div>
                    </li>
                    <?php endforeach;?>
                    <div id="comment-bottom" style="display:none"></div>
                </ul>
                <button class="btn btn-block" id="next-comment" type="button">加载下一页</button>
            </div>
            
            <div class="add-a-comment-form" id='comment-form'>
                <h3>添加评论</h3>

                <div class="row-fluid">
                    <div class="span8">
                        <form class="sengal-form" action="#">

                            <div class="control-group">
                                <textarea rows="5" cols="5" class="input-block-level" name="content" id="comment-content"></textarea>
                            </div>
                            <div class="control-group">
                                <button class="btn btn-primary btn-large" id="comment-button"><i class="icon-edit"></i> Add Comment</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

        </div>
        <div class="span1"></div>
        <div class="span4 pinned well">

            <div class="sidebar-widget">
                <h3>分享</h3>
                <img src="<?= base_url('qrcode.php?data=' . base_url('vote/join/' . $detail['content']['start_voteid'])) ?>" />

                <h3>投票说明</h3>
                <dl>
                    <dt>最多可选</dt>
                    <dd id="choice-max"><?= $detail['content']['choice_max'] ?>项</dd>
                    <dt>开始时间</dt>
                    <dd><?= date('Y-m-d H:i', $detail['content']['start_time']) ?></dd>
                    <dt>结束时间</dt>
                    <dd><?= $detail['content']['end_time'] > 0 ? date('Y-m-d H:i', $detail['content']['end_time']) : '长期有效' ?></dd>
                    <dt>限制条件</dt>
                    <?php
                    $limit = $detail['limit'];
                    if (!empty($limit['ip_address'])) {
                        echo "<dd>每个IP限投一次</dd>";
                    }
                    if (!empty($limit['cycle_time'])) {
                        echo "<dd>每$limit[cycle_time]小时可投一次</dd>";
                    }
                    if (!empty($limit['code_need'])) {
                        echo "<dd>需要邀请码</dd>";
                    }
                    if (!empty($limit['email_need'])) {
                        echo "<dd>需要邮箱验证</dd>";
                        if (!empty($limit['email_limit'])) {
                            echo "<dd>仅限邮箱域名为$limit[email_area]的用户</dd>";
                        }
                    }
                    if (!empty($limit['captcha_need'])) {
                        echo "<dd>须要输入验证码</dd>";
                    }
                    ?>
                </dl>
                <p>
                    <?php if ($error['type'] != 'none' && $error['type'] != 'captcha_need') : ?>
                        <a href="#modal-<?= $error['type'] ?>" class="btn btn-primary" role="button" data-toggle="modal">提示</a>
                    <?php endif; ?>
                    <a href="<?= base_url('vote/result/' . $detail['content']['start_voteid']) ?>" class="btn btn-success" role="button">投票结果</a>
                </p>
            </div>



        </div>
    </div>
</div>

<div id="vote-error" value="modal-<?= $error['type'] == 'captcha_need' ? 'none' : $error['type'] ?>"></div>

<?php if ($error['type'] == 'code_need') : ?>
    <div id="modal-<?= $error['type'] ?>" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3>
                邀请码
            </h3>
        </div>
        <div class="modal-body">
            <p>
                参与本次投票需要邀请码
            </p>
            <form method="get" action="">
                <div class="input-append">
                    <input class="span3" name="code" id="appendedInputButton" type="text">
                    <button class="btn" type="submit" >Go!</button>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button> <a href="<?= base_url('vote/result/' . $detail['content']['start_voteid']) ?>" class="btn btn-success" role="button">投票结果</a>
        </div>
    </div>
<?php endif; ?>

<?php if ($error['type'] == 'cycle_time') : ?>
    <div id="modal-<?= $error['type'] ?>" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3>
                提示
            </h3>
        </div>
        <div class="modal-body">
            <p>
                系统检测到您已经在 <?= date('Y年m月d日 H时i分', $error['votetime']) ?> 参与投票，现在您可以查看投票结果
            </p>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button> <a href="<?= base_url('vote/result/' . $detail['content']['start_voteid']) ?>" class="btn btn-success" role="button">投票结果</a>
        </div>
    </div>
<?php endif; ?>

<?php if ($error['type'] == 'email_need') : ?>
    <div id="modal-<?= $error['type'] ?>" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3>
                提示
            </h3>
        </div>
        <div class="modal-body">
            <p>
                参与本投票需要验证邮箱，请先注册邮箱并验证再参与投票
            </p>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button> <a href="<?= base_url('user/verify') ?>"  class="btn btn-primary" role="button">去验证</a> <a href="<?= base_url('vote/result/' . $detail['content']['start_voteid']) ?>" class="btn btn-success" role="button">投票结果</a>
        </div>
    </div>
<?php endif; ?>

<?php if ($error['type'] == 'errorvote') : ?>
    <div id="modal-<?= $error['type'] ?>" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3>
                提示
            </h3>
        </div>
        <div class="modal-body">
            <p>
                投票<?= $error['vtime']['start_time'] > time() ? '还未开始' : '已经结束' ?>
            </p>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button> <a href="<?= base_url('vote/result/' . $detail['content']['start_voteid']) ?>" class="btn btn-success" role="button">投票结果</a>
        </div>
    </div>
<?php endif; ?>

<?php if ($error['type'] == 'email_limit') : ?>
    <div id="modal-<?= $error['type'] ?>" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3>
                提示
            </h3>
        </div>
        <div class="modal-body">
            <p>
                本投票仅限邮箱域名为 <?= $error['email_area'] ?> 的用户参与，如果您拥有该域名邮箱请先注册验证后再参与投票
            </p>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button> <a href="<?= base_url('user/verify') ?>"  class="btn btn-primary" role="button">去验证</a> <a href="<?= base_url('vote/result/' . $detail['content']['start_voteid']) ?>" class="btn btn-success" role="button">投票结果</a>
        </div>
    </div>
<?php endif; ?>

<div id="modal-choice" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="choice-modal-title">
            选项详情
        </h3>
    </div>
    <div class="modal-body" id="choice-modal-body">

    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button> <button class="btn btn-primary" id="select-button" style="display: none">选中</button>
    </div>
</div>

<div id="modal-confirm" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="confirm-modal-title">
            提示
        </h3>
    </div>
    <div class="modal-body">
        <?php if ($limit['captcha_need']): ?>
            <div class="control-group">
                <label class="control-label">请输入验证码：</label>
                <div class="controls">
                    <div>
                        <img id="captcha-img" src="<?= base_url('home/captcha?rand=' . rand()) ?>" width="140" height="50" onclick="changeCaptcha()"/>
                    </div>
                    <input id="captcha-input" type="text" placeholder="请输入验证码" class="input-xlarge">

                    <p class="help-block"><a href="javascript: changeCaptcha();">看不清？</a></p>
                    <p class="text-error" id="cphint" style="display:none">验证中...</p>
                </div>
            </div>
        <?php else: ?>
            <p>确认要提交您的选择吗？</p>
        <?php endif; ?>

    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">取消</button> <button class="btn btn-primary" id="confirm-button">提交</button>
    </div>
</div>