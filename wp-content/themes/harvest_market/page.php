<?php
get_header();
$var          = variables();
$set          = $var['setting_home'];
$assets       = $var['assets'];
$url          = $var['url'];
$url_home     = $var['url_home'];
$id           = get_the_ID();
$isLighthouse = isLighthouse();
$size         = $isLighthouse ? 'thumbnail' : 'full';
$title        = get_the_title();
$img          = get_the_post_thumbnail_url();
$permalink    = get_the_permalink();
?>

    <section class="section-article pad_section_bot">
        <div class="container">
            <ul class="breadcrumbs">
                <li><a href="<?php echo $url; ?>"><?php echo get_the_title( $set ); ?></a></li>
                <li> <?php echo $title; ?> </li>
            </ul>
            <div class="text-wrap">
                <div class="title-sm">
					<?php echo $title; ?>
                </div>
                <!--<ul class="product-item__place">
                    <li><?php echo get_the_date( 'd.m.Y' ); ?></li>
                </ul> Закрили дату-->
            </div>
        </div>
		<?php if ( $img ): ?>
            <div class="article-big-media">
                <img src="<?php echo $img; ?>" alt="<?php echo $title; ?>"/>
            </div>
		<?php endif; ?>
        <div class="container">
            <div class="text-wrap">
                <div class="text-group">
					<?php the_post();
					the_content(); ?>
                </div>
                <div class="article-bot">
	                <div></div>
                    <ul class="share">
                        <li>
	                        <a target="_blank" rel="nofollow"
                               href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $permalink; ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                     style="enable-background:new 0 0 8 15" viewBox="0 0 8 15">
                                        <path d="M5.2 8.2h2.4L8 5.5H5.2V3.8c0-.8.2-1.3 1.4-1.3H8V.1C7.7.1 6.9 0 5.9 0 3.8 0 2.4 1.2 2.4 3.5v2H0v2.7h2.4V15h2.8V8.2z"
                                              style="fill:#285eb4"/>
                                    </svg>
                            </a></li>
                        <li>
	                        <a target="_blank" rel="nofollow"
                               href="https://twitter.com/share?text=<?php echo $title; ?>&url=<?php echo $permalink; ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                     style="enable-background:new 0 0 17 14" viewBox="0 0 17 14">
                                        <path d="M5.3 14c6.4 0 9.9-5.4 9.9-10.1v-.5c.7-.4 1.3-1 1.8-1.7-.6.3-1.3.5-2 .6.7-.4 1.3-1.1 1.5-2-.7.4-1.4.7-2.2.9C13.7.4 12.8 0 11.8 0c-2 0-3.5 1.6-3.5 3.5 0 .3 0 .5.1.8C5.5 4.2 2.9 2.8 1.2.6.9 1.2.7 1.8.7 2.4c0 1.2.6 2.3 1.6 2.9-.6 0-1.1-.2-1.6-.4C.7 6.6 1.9 8 3.5 8.4c-.3.1-.6.2-.9.2-.2 0-.4 0-.7-.1.4 1.4 1.7 2.4 3.3 2.5-1.2.9-2.7 1.5-4.3 1.5H.1c1.4.9 3.3 1.5 5.2 1.5z"
                                              style="fill:#4db6f1"/>
                                    </svg>
                            </a></li>
                        <li>
	                        <a class="copy_link" href="<?php echo $permalink; ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                     style="enable-background:new 0 0 15 15" viewBox="0 0 15 15">
                                        <path d="M9 1 6 4c.8-.1 1.5 0 2.2.2l2.1-2.1c.7-.7 1.8-.7 2.5 0s.7 1.8 0 2.5l-3 3c-.8.9-1.9.9-2.6.2-.3-.3-.9-.3-1.2 0l-.6.5c.2.3.3.5.6.8 1.3 1.3 3.5 1.4 5 0L14 6c1.4-1.4 1.4-3.6 0-5-1.4-1.3-3.6-1.3-5 0z"
                                              style="fill:#1a1b1f"/>
                                    <path d="m6.8 10.7-2.1 2.1c-.7.7-1.8.7-2.5 0s-.7-1.8 0-2.5l3.1-3.1c.7-.7 1.8-.7 2.5 0 .3.3.9.3 1.2 0l.5-.5c-.1-.3-.2-.5-.4-.8-1.3-1.3-3.5-1.4-5 0L1 9c-1.4 1.4-1.4 3.6 0 5s3.6 1.4 5 0l3.1-3.1c-.8.1-1.5 0-2.3-.2z"
                                          style="fill:#1a1b1f"/>
                                    </svg>
                            </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

<?php get_footer(); ?>