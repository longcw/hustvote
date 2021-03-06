<!DOCTYPE html>
<html>
    <head><?php //var_dump($userinfo)?>
        <title><?php echo $title ?></title>
        <!-- Bootstrap -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link href="<?= base_url('css/bootstrap.min.css') ?>" rel="stylesheet" media="screen" />
        <link href="<?= base_url('css/style.css') ?>" rel="stylesheet" media="screen" />
        <link rel="stylesheet" href="<?= base_url('css/font-awesome.css') ?>" />

        <?php if (isset($css)): ?>
            <?php foreach ($css as $value): ?>
                <link rel="stylesheet" href="<?= base_url('css/' . $value . '.css') ?>" />
            <?php endforeach; ?>
        <?php endif; ?>
        
        <!--
        <link href='http://fonts.useso.com/css?family=Source+Sans+Pro:600'
              rel='stylesheet' type='text/css' />
        -->
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body>
        <div id="header" class="no-middle-bar">
            <div class="container">
                <div id="logo-nav-container" class="row">
                    <div class="span12">
                        <div id="top-left-nav" class="navbar">
                            <h1 id="logo" class="brand">
                                <a href="<?php echo base_url() ?>">HustVote <small>在线投票</small></a>
                            </h1>
                            <!-- .btn-navbar is used as the toggle for collapsed navbar content -->
                            <a class="btn btn-navbar" data-toggle="collapse"
                               data-target=".nav-collapse"> <span class="icon-reorder"></span>
                            </a>
                            <div class="nav-collapse collapse">
                                <ul id="bricks-nav" class="nav nav-pills pull-right">
                                    <li<?php echo isset($act) && $act == 'home' ? ' class="active"' : ''; ?>><a href="<?php echo base_url('home') ?>">首页</a></li>
                                    <li<?php echo isset($act) && $act == 'download' ? ' class="active"' : ''; ?>><a href="<?=  base_url('about')?>">下载</a></li>
                                    <li<?php echo isset($act) && $act == 'hall' ? ' class="active"' : ''; ?>><a href="<?php echo base_url('home/hall') ?>">投票大厅</a></li>
                                    <li<?php echo isset($act) && $act == 'about' ? ' class="active"' : ''; ?>><a href="<?=  base_url('about')?>">关于</a></li>
                                    
                                    <div id="is-login" style="display: none"><?=empty($userinfo['uid']) ? 0 : $userinfo['uid']?></div>
                                    <?php if (!empty($userinfo['uid'])) : ?>
                                        <li class="dropdown"><a href="#" class="dropdown-toggle"
                                                                data-toggle="dropdown"><?php echo $userinfo['nickname'] ?> 
                                                <span class="badge badge-info"><?=$userinfo['msg_count']?></span> <b class="caret"></b></a>
                                            <ul class="dropdown-menu">
                                                <li><a href="<?=  base_url('user/message')?>">我的消息<span class="badge badge-info"><?=$userinfo['msg_count']?></span></a></li>
                                                <li><a href="<?=  base_url('vote/started')?>">我发起的投票</a></li>
                                                <li><a href="<?=  base_url('vote/joined')?>">我参与的投票</a></li>
                                                <li><a href="<?=  base_url('user/change')?>">修改密码</a></li>
                                                <li><a href="<?=  base_url('user/verify')?>">验证邮箱</a></li>
                                                <li class="divider"></li>
                                                <li><a href="<?php echo base_url('user/logout'); ?>">退出</a></li>
                                            </ul>
                                        </li>
                                    <?php else : ?>

                                        <li><a href="<?php echo base_url('user/login') ?>"><i class="icon-user"></i>登录</a></li>
                                        <li><a href="<?php echo base_url('user/register') ?>"><i class="icon-pencil"></i>注册</a></li>
                                        <?php endif; ?>
                                </ul>

                            </div>
                        </div>
                    </div>
                </div>

                <?php if (!isset($lastdiv) || $lastdiv == true) : ?>
                </div>
            </div>
        <?php endif; ?>