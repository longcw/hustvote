<div class="container" style="padding-top: 60px;padding-bottom: 30px;">
    <div class="row"> 
        <div class="span4 offset4 well"> 
            <legend>新用户注册</legend> 
            <?php if(!empty($error)) :?>
            <div class="alert alert-error"> 
                <a class="close" data-dismiss="alert" href="#">×</a><?php echo $error; ?>
            </div> 
            <?php endif;?>
            <form method="POST" action="<?php echo base_url('user/register')?>" accept-charset="UTF-8"> 
			<fieldset>
			<div class="well">
			<div class="input-prepend">
            	<span class="add-on">昵称</span>
            	<input type="text" id="nickname" class="span3" name="nickname" placeholder="Nick Name" value="<?php echo set_value('nickname'); ?>">
            </div>
            <div class="input-prepend">
            	<span class="add-on">邮箱</span>
            	<input type="text" id="email" class="span3" name="email" placeholder="Email Address" value="<?php echo set_value('email'); ?>">
            </div>
            <div class="input-prepend">
            	<span class="add-on">密码</span>
            	<input type="password" id="password" class="span3" name="password" placeholder="Password" >
            </div>
            <div class="input-prepend">
            	<span class="add-on">密码</span>
            	<input type="password" id="password2" class="span3" name="password2" placeholder="Password Again">
            </div>
            </div>
            </fieldset>
            <button type="submit" class="btn btn-primary">注册</button> 
            <a href="<?php echo base_url('user/login')?>" class="btn btn-default" role="button">登录</a>
            </form>     
        </div> 
    </div> 
</div> 