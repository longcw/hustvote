<div id="home-page-marketing" class="row">
    <div id="bricks-hero-unit" class="span6">
        <h1>投票大厅</h1>
        <img class="img-polaroid visible-phone" src="<?=  base_url('img/hall.jpg')?>" />
        <p class="explanation">发起投票：注册用户登录安卓客户端可以进行投票发布，发布信息包括投票标题、介绍、具体选项以及投票的开始日期和结束日期。发布后自动生成投票地址，可以直接分享到社交网络以供他人进行投票
        </p><p class="explanation">
            参与投票：用户可以在应用内的投票大厅中进行投票，或者在微信朋友圈、微信公众号、新浪微博、QQ空间以及浏览器上打开web版投票界面参与投票</p>
        <p class="action-buttons-container"><a class="btn btn-primary btn-large" href="<?php echo base_url('vote/start') ?>">发起投票</a></p>
    </div>
    <div class="span6 hidden-phone">
        <img class="img-polaroid" src="<?=  base_url('img/hall.jpg')?>" />
    </div>
</div>
</div>
</div>

<div id="content">
    <div class="container">
        <div class="marketing" class="row">
            <ul id="portfolio-list" class="thumbnails">
                <?php foreach ($votes as $vote): ?>
                    <li class="span3">
                        <div>
                            <div class="sengal-thumbnail thumbnail">
                                <?php
                                if (empty($vote['image'])) {
                                    $vote['image'] = base_url('img/vote_default.jpg');
                                }
                                ?>
                                <a href="<?= base_url('vote/join/' . $vote['start_voteid']) ?>">
                                    <img src="<?= $vote['image'] ?>" alt="<?= $vote['title'] ?>" />
                                </a>
                                <div class="caption">
                                    <a href="<?= base_url('vote/join/' . $vote['start_voteid']) ?>">
                                        <h4><?= $vote['title'] ?></h4>
                                    </a>
                                    <p><?= $vote['summary'] ?></p>
                                </div></a>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>

            </ul>
            <div class="pagination">
                <ul>
                    <?php if ($cpage > 1) : ?>
                        <li><a href="<?= base_url('home/hall/' . ($cpage - 1)) ?>">Prev</a></li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $total_page; $i++): ?>
                        <li <?= ($i == $cpage) ? 'class="active"' : '' ?>><a href="<?= base_url('home/hall/' . $i) ?>"><?= $i ?></a></li>
                    <?php endfor; ?>
                    <?php if ($cpage < $total_page) : ?>
                        <li><a href="<?= base_url('home/hall/' . ($cpage + 1)) ?>">Next</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>