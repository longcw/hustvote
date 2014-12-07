<div id="content" class="container">
    <div>
        <div id="blog" class="row-fluid">
            <div class="span7">
                <div class="page-header">
                    <p class="entry-meta">
                        <span class="entry-author"><i class="icon-user"></i> <?=$voteuser['nickname']?></span>
                        <span class="entry-date"><i class="icon-calendar"></i> <?= date('Y-m-d', $detail['content']['create_time']) ?></span>
                        <span class="entry-comments"><i class="icon-comments"></i> 3 Comments</span>
                    </p>
                    <h2><?= $detail['content']['title'] ?></h2>
                </div>


                <div class="entry-content">
                    <div class="well">
                        <?= $detail['content']['intro'] ?>
                    </div>
                    <form method="post" action="<?=base_url('vote/doJoinVote')?>" id="joinForm">
                    <?php foreach ($detail['choices'] as $row) : ?>
                        <aa href="#c<?=$row['choiceid']?>"></aa>
                        <div class="sengal-thumbnail thumbnail">
                            <div class="caption">
                                <input type="checkbox" name="choice[]" class="choice" value="<?=$row['choiceid']?>" id="c<?=$row['choiceid']?>"> &nbsp;
                                <a href="#" class="title"><?= $row['choice_name'] ?></a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                        <input type="text" value="<?=$detail['content']['start_voteid']?>" style="display:none" name="start_voteid" />
                        <input type="text" value="-1" style="display:none" id="fingerprint" name="fingerprint" />
                    </form>
                </div> 


                <div class="entry-comments">
                    <h3>3 Comments</h3>
                    <ul id="comments-list">
                        <li class="last-comment comment media">
                            <img src="http://placehold.it/75x75" class="img-polaroid pull-left" />
                            <div class="media-body">
                                <h4 class="comment-author">John Smith <small> on December 25, 2012</small></h4>
                                <div class="comment-content">
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer molestie sollicitudin tortor ut gravida. Etiam eleifend pretium diam, in ullamcorper enim faucibus ac.</p>
                                    <p>Integer eu aliquet massa. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vivamus porttitor facilisis volutpat. Nulla tortor eros, convallis nec commodo ac, egestas in lacus.</p>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="add-a-comment-form">
                    <h3>Add Comment</h3>

                    <div class="row-fluid">
                        <div class="span8">
                            <form class="sengal-form">
                            
                            <div class="control-group">
                                <textarea rows="5" cols="5" class="input-block-level"></textarea>
                            </div>
                            <div class="control-group">
                                <button class="btn btn-primary btn-large"><i class="icon-edit"></i> Add Comment</button>
                            </div>
                            </form>
                        </div>
                    </div>

                </div>

            </div>
            <div class="span1"></div>
            <div class="span4 pinned well">

                <div class="sidebar-widget">
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
                        if(!empty($limit['ip_address'])) {
                            echo "<dd>每个IP限投一次</dd>";
                        }
                        if(!empty($limit['cycle_time'])) {
                            echo "<dd>每$limit[cycle_time]小时可投一次</dd>";
                        }
                        if(!empty($limit['code_need'])) {
                            echo "<dd>需要邀请码</dd>";
                        }
                        if(!empty($limit['email_need'])) {
                            echo "<dd>需要邮箱验证</dd>";
                            if(!empty($limit['email_limit'])) {
                                echo "<dd>仅限邮箱域名为$limit[email_area]的用户</dd>";
                            }
                        }
                        
                        
                        ?>
                    </dl>
                </div>

                <div class="sidebar-widget" id="selected" style="display: none">
                    <h3>选中</h3>
                    <ul class="sidebar-list">
                        <span id="select-end" style="display: none"></span>
                        <li>&nbsp;</li>
                    </ul>
                    <button class="btn btn-primary" id="submit-btn">确认</button>
                </div>

            </div>
        </div>
    </div>
</div>
</div>