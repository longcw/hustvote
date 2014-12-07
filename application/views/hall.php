<div id="home-page-marketing" class="row">
    <div id="bricks-hero-unit" class="span6">
        <h1>投票大厅</h1>
        <img class="img-polaroid visible-phone" src="http://placehold.it/600x265" />
        <p class="explanation">We've worked with everybody from start-ups to established online businesses.Nunc consectetur, nunc vel mollis consectetur, ligula quam dignissim nisl, at lacinia eros eros in nunc. Vivamus rutrum malesuada libero, a dictum lacus porta ultricies. </p>
        <p class="action-buttons-container"><a class="btn btn-primary btn-large" href="<?php echo base_url('vote/start') ?>">发起投票</a></p>
    </div>
    <div class="span6 hidden-phone">
        <img class="img-polaroid" src="http://placehold.it/600x265" />
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
                                <?php if (!empty($vote['image'])): ?>
                                    <a href="<?= base_url('vote/join/' . $vote['start_voteid']) ?>">
                                        <img src="<?= $vote['image'] ?>" alt="<?= $vote['title'] ?>" />
                                    </a>
                                <?php endif; ?>
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