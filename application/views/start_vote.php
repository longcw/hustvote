<script type="text/javascript">
    window.UMEDITOR_HOME_URL = "<?php echo base_url('umeditor'); ?>/"
</script>

<div class="container" style="padding-top: 60px; padding-bottom: 40px;">
    <div class="row">
        <div class="span8 offset2 well">
            <legend>发起投票</legend> 
            <?php if (!empty($error)) : ?>
                <div class="alert alert-error">
                    <a class="close" data-dismiss="alert" href="#">×</a><?php echo $error; ?>
                </div> 
            <?php endif; ?>
            <div class="row span6">
                <form class="form-horizontal" id="startForm" method="POST" action="<?php echo base_url('vote/start') ?>" accept-charset="UTF-8">
                    <fieldset>
                        <div class="control-group">

                            <!-- Text input-->
                            <label class="control-label" for="title">标题</label>
                            <div class="controls">
                                <input type="text" placeholder="投票标题" class="input-xlarge" name="title" id="title" value="<?php echo set_value('title'); ?>">
                                <?php echo form_error('title', '<div class="alert alert-error">', '</div>'); ?>
                            </div>
                        </div>


                        <div class="control-group">
                            <!-- 开始日期-->
                            <label class="control-label" for="start_time">开始时间<i class="icon-calendar"></i></label>
                            <div class="controls">
                                <label class="radio inline">
                                    <input type="radio" name="start_select" value="0" <?=!set_value('start_select')? 'checked="checked"':'';?> class="start-select">
                                    立即开始
                                </label>
                                <label class="radio inline">
                                    <input type="radio" name="start_select" value="1" <?=set_value('start_select')? 'checked="checked"':'';?> class="start-select">
                                    指定时间
                                </label>
                                <p class="help-block">
                                    <input style="display:<?=set_value('start_select')? 'block':'none';?>" id="start-time" class="date" type="text" name="start_time" placeholder="点击选择日期" value="<?php echo set_value('start_time'); ?>" readonly="readonly"/>
                                </p>
                            </div>
                        </div>

                        <div class="control-group">
                            <!-- 开始日期-->
                            <label class="control-label" for="end_time">结束时间<i class="icon-calendar"></i></label>
                            <div class="controls">
                                <label class="radio inline">
                                    <input type="radio" name="end_select" value="0" <?=!set_value('end_select')? 'checked="checked"':'';?> class="end-select">
                                    长期有效
                                </label>
                                <label class="radio inline">
                                    <input type="radio" name="end_select" value="1" <?=set_value('end_select')? 'checked="checked"':'';?> class="end-select">
                                    指定时间
                                </label>
                                <p class="help-block">
                                    <input style="display:<?=set_value('end_select')? 'block':'none';?>" id="end-time" class="date" type="text" name="end_time" placeholder="点击选择日期" value="<?php echo set_value('end_time'); ?>" readonly="readonly"/>
                                </p>
                            </div>

                        </div>

                        <div class="control-group">
                            <!-- Textarea -->
                            <label class="control-label" for="umeditor">投票描述</label>
                            <div class="controls">
                                <div id="umeditor_intro" style="height:150px;"><?php echo htmlspecialchars_decode(set_value('intro')); ?></div>
                                <textarea style="display: none" name="intro" id="intro_content"></textarea>
                                <?php echo form_error('intro', '<div class="alert alert-error">', '</div>'); ?>
                            </div>
                        </div>

                        <div class="control-label">选项</div>
                        <?php for ($i = 0; $i < $count; $i++) : ?>
                            <?php
                            $detail = htmlspecialchars_decode(set_value('detail[]'));
                            $tag = empty($detail);
                            ?>
                            <div class="control-group">
                                <!-- 选项-->
                                <div class="controls">
                                    <div class="input-prepend input-append">
                                        <span class="add-on choice-index"><?= $i + 1 ?></span>
                                        <input class="span3 choice" placeholder="选项描述" name="choice[]" type="text" value="<?php echo ($chv = set_value('choice[]')); ?>">
                                        <span class="add-on">更多细节 <input type="checkbox" class="has-detail" <?= $tag ? '' : 'checked=""'; ?>></span>
                                        <?php if ($i >= 2) : ?>
                                            <span class="add-on del-choice"><a><i class="icon-trash"></i>删除</a></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="textarea" style="display: <?= $tag ? 'none' : 'block'; ?>">
                                        <div id="ume<?= $i ?>" class="umeditor-detail" style="width: 500px; height:150px;"><?php echo $detail; ?></div>
                                        <textarea style="display: none" name="detail[]" id="detail<?= $i ?>" class="detail-content"></textarea>
                                    </div>
                                    <?php echo empty($chv) ? form_error('choice[]', '<div class="alert alert-error">', '</div>') : ''; ?>
                                </div>
                            </div>
                        <?php endfor; ?>

                        <div class="choice-end"></div>
                        <div class="control-label"><button class="btn btn-small btn-success choice-add" type="button">添加选项</button></div>

                        <!-- 投票限制-->
                        <div class="control-group">

                            <div class="controls">
                                <div class="input-prepend">
                                    <span class="add-on">限制</span>
                                    <input class="span2"  placeholder="最多可选几项" id="maxvote" name="choice_max" type="text" value="<?php echo set_value('choice_max'); ?>">

                                </div>
                                <p class="help-block">最多可选择的数量</p>
                                <?php echo form_error('choice_max', '<div class="alert alert-error">', '</div>'); ?>
                            </div>

                        </div>
                    </fieldset>
                    <button type="submit" class="btn btn-primary">发布</button> 
                </form></div>
        </div>
    </div>
</div>
