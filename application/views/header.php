<!DOCTYPE html>
<html>
    <head>
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

        <link href='http://fonts.useso.com/css?family=Source+Sans+Pro:600'
              rel='stylesheet' type='text/css' />
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
                                    <li<?php echo isset($act) && $act == 'download' ? ' class="active"' : ''; ?>><a href="#">下载</a></li>
                                    <li<?php echo isset($act) && $act == 'hall' ? ' class="active"' : ''; ?>><a href="<?php echo base_url('home/hall') ?>">投票大厅</a></li>
                                    <li<?php echo isset($act) && $act == 'about' ? ' class="active"' : ''; ?>><a href="#">关于</a></li>

                                    <?php if (!empty($userinfo['uid'])) : ?>
                                        <li class="dropdown"><a href="#" class="dropdown-toggle"
                                                                data-toggle="dropdown"><?php echo $userinfo['nickname'] ?> <b class="caret"></b></a>
                                            <ul class="dropdown-menu">
                                                <li><a href="#">Action</a></li>
                                                <li><a href="#">Another action</a></li>
                                                <li><a href="#">Something else here</a></li>
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