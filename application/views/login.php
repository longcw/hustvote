<div class="container" style="padding-top: 60px;padding-bottom: 40px;">
    <div class="row"> 
        <div class="span4 offset4 well"> 
            <legend>用户登录</legend> 
            <?php if(!empty($error)) :?>
            <div class="alert alert-error"> 
                <a class="close" data-dismiss="alert" href="#">×</a><?php echo $error;?>
            </div> 
            <?php endif;?>
            <form method="POST" action="<?php echo base_url('user/login')?>" accept-charset="UTF-8"> 
            <input type="text" id="email" class="span4" name="email" placeholder="邮箱"  value="<?php echo set_value('email'); ?>"> 
            <input type="password" id="password" class="span4" name="password" placeholder="密码"> 
            <button type="submit" class="btn btn-primary">登录</button> 
            <a href="<?php echo base_url('user/register')?>" class="btn btn-default" role="button">注册</a>
            </form>     
        </div> 
    </div> 
</div> 