
<div class="container" style="padding-top: 60px; padding-bottom: 40px;">
    <div class="row">
        <div class="span8 offset2 well">
            <legend>设置投票规则</legend> 
            <?php if (!empty(validation_errors())) : ?>
                <div class="alert alert-error">
                    <a class="close" data-dismiss="alert" href="#">×</a><?php echo validation_errors(); ?>
                </div> 
            <?php endif; ?>
            <div class="row span6">
                <form class="form-horizontal" id="startForm" method="POST" action="<?=  base_url("vote/doSetLimit/$start_voteid")?>" accept-charset="UTF-8">
                    <fieldset>

                        <div class="control-group">
                            <label class="control-label">邀请码</label>
                            <div class="controls">
                                <label class="radio inline">
                                    <input type="radio" name="code_need" value="1" id="code-need">
                                    是
                                </label>
                                <label class="radio inline">
                                    <input type="radio" name="code_need" value="0" checked="checked">
                                    否
                                </label>
                                <p class="help-block">是否需要邀请码（邀请码可在管理页面生成）</p>
                            </div>
                        </div>

                        <div style="display:block" id="limit">
                            <!-- Multiple Radios (inline) -->
                            <div class="control-group">
                                <label class="control-label">限制IP</label>
                                <div class="controls">
                                    <label class="radio inline">
                                        <input type="radio" name="ip_address" value="1" checked="checked">
                                        是
                                    </label>
                                    <label class="radio inline">
                                        <input type="radio" name="ip_address" value="0">
                                        否
                                    </label>
                                    <p class="help-block">相同IP不能重复投票</p>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">验证码</label>
                                <div class="controls">
                                    <label class="radio inline">
                                        <input type="radio" name="captcha_need" value="1" checked="checked">
                                        是
                                    </label>
                                    <label class="radio inline">
                                        <input type="radio" name="captcha_need" value="0">
                                        否
                                    </label>
                                    <p class="help-block">是否须要输入随机验证码</p>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">邮箱验证</label>
                                <div class="controls">
                                    <label class="radio inline">
                                        <input type="radio" name="email_need" value="1" id="email-need">
                                        是
                                    </label>
                                    <label class="radio inline">
                                        <input type="radio" name="email_need" value="0" checked="checked">
                                        否
                                    </label>
                                    <p class="help-block">是否须要验证用户邮箱</p>
                                </div>
                            </div>

                            <div style="display: none" id="email-limit-block">
                                <div class="control-group">
                                    <label class="control-label">限制邮箱</label>
                                    <div class="controls">
                                        <label class="radio inline">
                                            <input type="radio" name="email_limit" value="1" id="email-limit">
                                            是
                                        </label>
                                        <label class="radio inline">
                                            <input type="radio" name="email_limit" value="0" checked="checked">
                                            否
                                        </label>
                                        <p class="help-block">是否限制邮箱域名（确保本公司或学校的用户才能参与投票）</p>
                                    </div>
                                </div>

                                <!-- 邮箱域名 text-->
                                <div class="control-group" style="display: none" id='email-area-block'>

                                    <div class="controls">
                                        <div class="input-prepend">
                                            <span class="add-on">@</span>
                                            <input id="prependedtext" name="email_area" class="span2" placeholder="hust.edu.cn;" type="text">
                                        </div>
                                        <p class="help-block">多个域名请用分号 ; 隔开</p>
                                    </div>
                                </div>
                            </div>


                            <div class="control-group">
                                <label class="control-label">投票间隔</label>
                                <div class="controls">
                                    <select id="selectbasic" name="cycle_time" class="input-xlarge">
                                        <option value="0">禁止重复投票</option>
                                        <option value="6">6小时</option>
                                        <option value="12">12小时</option>
                                        <option value="24">24小时</option>
                                    </select>
                                    <p class="help-block">在以上时间内不能重复投票</p>
                                </div>
                            </div></div>

                        <div class="control-group">

                            <div class="controls">
                                <button type="submit" class="btn btn-primary">完成</button> 
                            </div>

                        </div>
                    </fieldset>

                </form></div>
        </div>
    </div>
</div>
