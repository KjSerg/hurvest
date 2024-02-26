<?php
/* Template Name: Шаблон сторінки Покупцям */
get_header();
$var          = variables();
$set          = $var['setting_home'];
$assets       = $var['assets'];
$url          = $var['url'];
$url_home     = $var['url_home'];
$admin_ajax   = $var['admin_ajax'];
$id           = get_the_ID();
$isLighthouse = isLighthouse();
$size         = $isLighthouse ? 'thumbnail' : 'full';
$screens      = carbon_get_post_meta( $id, 'buyers_screens' );
?>

    <div class="container">
        <ul class="breadcrumbs">
            <li><a href="<?php echo $url; ?>"><?php echo get_the_title( $set ); ?></a></li>
            <li><?php echo get_the_title(); ?></li>
        </ul>
    </div>

<?php
if ( ! empty( $screens ) ) :
	foreach ( $screens as $index => $screen ) :
		if ( $screen['_type'] == 'screen_1' ) :
			if ( ! $screen['screen_off'] ) :
				?>

                <section class="section-text-column pad_section_bot" id="<?php echo $screen['id']; ?>">
                    <div class="container">
                        <div class="text-column">
                            <div class="text-column__left">
                                <div class="title-sm"><?php echo $screen['title']; ?></div>
                            </div>
                            <div class="text-column__right">
                                <div class="text-group">
									<?php _t( $screen['text'] ); ?>
                                </div>
                            </div>
                        </div>
                    </div>
					<?php if ( $screen['image'] ): ?>
                        <div class="text-img">
                            <img src="<?php _u( $screen['image'] ); ?>" alt=""/>
                        </div>
					<?php endif; ?>
                </section>

			<?php
			endif;
        elseif ( $screen['_type'] == 'screen_2' ):
			if ( ! $screen['screen_off'] ):
				?>

                <section class="section-advantage pad_section dark_section"
                         id="<?php echo $screen['id']; ?>"
                         style="background-image: url(<?php _u( $screen['image'] ); ?>);">
                    <div class="container">
                        <div class="title-section-group">
                            <div class="title-sm">
								<?php _t( $screen['title'] ); ?>
                            </div>
                            <div class="title-section__text">
                                <div class="text-group"><?php _t( $screen['subtitle'] ); ?></div>
                            </div>
                        </div>
                        <div class="advantage sm_column">
							<?php if ( $list = $screen['list'] ): foreach ( $list as $item ): ?>
                                <div class="advantage-item">
                                    <div class="advantage-item__media">
										<?php the_image( $item['image'] ); ?>
                                    </div>
                                    <div class="advantage-item__content">
                                        <div class="advantage-item__title">
											<?php echo $item['title']; ?>
                                        </div>
                                        <div class="advantage-item__text">
											<?php echo $item['text']; ?>
                                        </div>
                                    </div>
                                </div>
							<?php endforeach; endif; ?>
                        </div>
                    </div>
                </section>

			<?php
			endif;
        elseif ( $screen['_type'] == 'screen_3' ):
			if ( ! $screen['screen_off'] ):
				?>

                <section class="section-help pad_section"
                         id="<?php echo $screen['id']; ?>">
                    <div class="container">
                        <div class="title-section-group">
                            <div class="title-sm">
								<?php _t( $screen['title'] ); ?>
                            </div>
                            <div class="title-section__text">
                                <div class="text-group"><?php _t( $screen['subtitle'] ); ?></div>
                            </div>
                        </div>
                        <div class="help">
							<?php if ( $list = $screen['list'] ): foreach ( $list as $item ): ?>
                                <div class="help-item">
                                    <div class="help-item__title"><?php echo $item['title']; ?></div>
                                    <div class="help-item__media">
										<?php the_image( $item['image'] ); ?>
                                    </div>
                                </div>
							<?php endforeach; endif; ?>
                        </div>
                        <div class="btn_center">
							<?php the_buttons( $screen['links'] ); ?>
                        </div>
                    </div>
                </section>

			<?php
			endif;
        elseif ( $screen['_type'] == 'screen_4' ):
			if ( ! $screen['screen_off'] ):
				?>

                <section class="section-form pad_section dark_section"
	                <?php if ( $screen['image'] ): ?>
                        style="background: url(<?php _u( $screen['image'] ); ?>) top center/cover no-repeat"
	                <?php endif; ?>
                         id="<?php echo $screen['id']; ?>">
                    <div class="container">
                        <div class="form-action">
                            <div class="form-action__left">
                                <div class="title-sm">
									<?php _t( $screen['title'] ); ?>
                                </div>
                            </div>
                            <div class="form-action__right">
								<?php echo do_shortcode( $screen['form'] ); ?>
                            </div>
                        </div>
                    </div>
                </section>

			<?php
			endif;
		endif;
	endforeach;
endif;
get_footer();