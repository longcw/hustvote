<div id="content" class="container">
    <div>
        <div id="blog" class="row-fluid">
            <div class="span7">
                <div class="page-header">
                    <p class="entry-meta">
                        <span class="entry-author"><i class="icon-user"></i> John Smith</span>
                        <span class="entry-date"><i class="icon-calendar"></i> December 24, 2012</span>
                        <span class="entry-comments"><i class="icon-comments"></i> 3 Comments</span>
                    </p>
                    <h2><?= $detail['content']['title'] ?></h2>
                </div>


                <div class="entry-content">
                    <div class="well">
                    <?= $detail['content']['intro'] ?>
                    </div>
                    
                    <?php foreach ($detail['choices'] as $row) : ?>
                        <div class="sengal-thumbnail thumbnail">
                            <div class="caption">
                                <input type="checkbox" name="choice[]"> 
                                <a href="#"><?= $row['choice_name'] ?></a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div> 


                    <div class="entry-comments">
                        <h3>3 Comments</h3>
                        <ul id="comments-list">
                            <li class="comment media">
                                <img src="http://placehold.it/75x75" class="img-polaroid pull-left" />
                                <div class="media-body">
                                    <h4 class="comment-author">John Smith <small> on December 25, 2012</small></h4>
                                    <div class="comment-content">
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer molestie sollicitudin tortor ut gravida. Etiam eleifend pretium diam, in ullamcorper enim faucibus ac.</p>
                                        <p>Integer eu aliquet massa. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vivamus porttitor facilisis volutpat. Nulla tortor eros, convallis nec commodo ac, egestas in lacus.</p>
                                    </div>
                                </div>
                            </li>
                            <li class="comment media">
                                <img src="http://placehold.it/75x75" class="img-polaroid pull-left" />
                                <div class="media-body">
                                    <h4 class="comment-author">John Smith <small> on December 25, 2012</small></h4>
                                    <div class="comment-content">
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer molestie sollicitudin tortor ut gravida. Etiam eleifend pretium diam, in ullamcorper enim faucibus ac.</p>
                                        <p>Integer eu aliquet massa. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vivamus porttitor facilisis volutpat. Nulla tortor eros, convallis nec commodo ac, egestas in lacus.</p>
                                    </div>
                                </div>
                            </li>
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
                                <form class="sengal-form" />
                                <div class="control-group">
                                    <label>Your Name</label>
                                    <input type="text" class="input-block-level" />
                                </div>

                                <div class="control-group">
                                    <label>Email</label>
                                    <input type="text" class="input-block-level" />
                                </div>
                                <div class="control-group">
                                    <label>Website</label>
                                    <input type="text" class="input-block-level" />
                                </div>
                                <div class="control-group">
                                    <label>Message</label>
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
                <div class="span4">

                    <div class="sidebar-widget">
                        <h3>Archives</h3>
                        <ul class="sidebar-list">
                            <li><a href="blog.html">October 2012</a></li>
                            <li><a href="blog.html">September 2012</a></li>
                            <li><a href="blog.html">April 2012</a></li>
                            <li><a href="blog.html">March 2012</a></li>
                        </ul>
                    </div>

                    <div class="sidebar-widget">
                        <h3>Categories</h3>
                        <ul class="sidebar-list">
                            <li><a href="blog.html">Events</a></li>
                            <li><a href="blog.html">Web Desigining</a></li>
                            <li><a href="blog.html">PHP &amp; MySql</a></li>
                        </ul>
                    </div>


                    <div class="sidebar-widget">
                        <h3>Recent Posts</h3>
                        <ul class="sidebar-list">
                            <li><a href="single.html">In quis luctus dui</a></li>
                            <li><a href="single.html">Morbi in lorem in libero volutpat sagittis</a></li>
                            <li><a href="single.html">Etiam eleifend pretium diam, in ullamcorper enim faucibus ac</a></li>
                            <li><a href="single.html">Pellentesque habitant morbi tristique senectus et netus et malesuada fames</a></li>
                        </ul>
                    </div>

                    <div class="sidebar-widget">
                        <h3>Subscribe to our Newsletter</h3>
                        <p style="padding-top: 10px">Be informed about our latest posting. Subscribe to our blog post.</p>
                        <p>
                        <form />
                        <div class="input-append">
                            <input type="text" placeholder="Enter your Email" />
                            <button class="btn btn-primary">Subscribe</button>
                        </div>
                        </form>
                        </p>
                    </div>

                    <div class="sidebar-widget">
                        <h3>Find us on Facebook</h3>
                        <div class="cleafix" style="margin-top: 10px;">
                            <iframe src="" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:100%; height:258px;" allowtransparency="true"></iframe>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>