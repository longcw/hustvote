<div class="container" style="padding-top: 60px; padding-bottom: 40px;">
	<div class="row">
		<div class="span8 offset2 well">
			<legend>发起投票</legend> 
            <?php if(!empty($error)) :?>
            <div class="alert alert-error">
				<a class="close" data-dismiss="alert" href="#">×</a><?php echo $error;?>
            </div> 
            <?php endif;?>
            <div class="row span6">
  			<form class="form-horizontal " method="POST" action="<?php echo base_url('vote/start')?>" accept-charset="UTF-8">
				<fieldset>
					<div class="control-group">

						<!-- Text input-->
						<label class="control-label" for="title">标题</label>
						<div class="controls">
							<input type="text" placeholder="投票标题" class="input-xlarge" name="title" id="title" value="<?php echo set_value('title'); ?>">
						</div>
					</div>

					<div class="control-group">

						<!-- Prepended text-->
						<label class="control-label" for="maxvote">限制</label>
						<div class="controls">
							<div class="input-prepend">
								<span class="add-on">^_^</span> <input class="span2"
									placeholder="最多可选几项" id="maxvote" name="choice_count" type="text" value="<?php echo set_value('choice_count'); ?>">
							</div>
							<p class="help-block">最多可选择的数量</p>
						</div>

					</div>
					<div class="control-group">

						<!-- Textarea -->
						<label class="control-label" for="intro">投票描述</label>
						<div class="controls">
							<div class="textarea">
								<textarea type="text" class="span3" id="intor" name="intro" value="<?php echo set_value('intro'); ?>"> </textarea>
							</div>
						</div>
					</div>
					
					
					<div class="control-group">
					<!-- 选项-->
						<label class="control-label" for="choice1">选项</label>
						<div class="controls">
							<div class="input-prepend">
								<span class="add-on">1</span> <input class="span3"
									placeholder="选项描述" id="choice1" name="choice[]" type="text">
							</div>
						</div>
						
						<div class="controls">
							<div class="input-prepend">
								<span class="add-on">2</span> <input class="span3"
									placeholder="选项描述" id="choice1" name="choice[]" type="text">
							</div>
						</div>
					</div>
					
				</fieldset>
				<button type="submit" class="btn btn-primary">发布</button> 
			</form></div>
		</div>
	</div>
</div>
