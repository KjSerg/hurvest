<?php
/* Template Name: Шаблон сторінки про нас */
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
$screens      = carbon_get_post_meta( $id, 'about_screens' );
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

                <section class="section-text-column <?php echo $index == 0 ? 'pad_section_bot' : 'pad_section'; ?> "
                         id="<?php echo $screen['id']; ?>">
                    <div class="container">
                        <div class="text-column">
                            <div class="text-column__left">
                                <div class="<?php echo $index == 0 ? 'title-sm' : 'product-description__title'; ?>">
									<?php _t( $screen['title'] ) ?>
                                </div>
                            </div>
                            <div class="text-column__right">
                                <div class="text-group">
									<?php _t( $screen['text'] ) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

			<?php
			endif;
        elseif ( $screen['_type'] == 'screen_2' ):
			if ( ! $screen['screen_off'] ):
				?>

                <section class="section-big-media">
                    <img src="<?php _u( $screen['image'] ); ?>" alt=""/>
                </section>

			<?php
			endif;
        elseif ( $screen['_type'] == 'screen_3' ):
			if ( ! $screen['screen_off'] ):
				?>

                <section class="section-advantage pad_section dark_section"
                         id="<?php echo $screen['id']; ?>">
                    <div class="container">
                        <div class="title-section-group">
                            <div class="title-sm">
								<?php _t( $screen['title'] ); ?>
                            </div>
                            <div class="title-section__text">
                                <div class="text-group">
									<?php _t( $screen['subtitle'] ); ?>
                                </div>
                            </div>
                        </div>
						<?php if ( $list = $screen['list'] ): ?>
                            <div class="advantage">
								<?php foreach ( $list as $item ): ?>
                                    <div class="advantage-item">
                                        <div class="advantage-item__content">
                                            <div class="advantage-item__title">
												<?php echo $item['title']; ?>
                                            </div>
                                            <div class="advantage-item__text">
												<?php echo $item['text']; ?>
                                            </div>
                                        </div>
                                        <div class="advantage-item__media">
											<?php the_image( $item['image'] ); ?>
                                        </div>
                                    </div>
								<?php endforeach; ?>
                            </div>
						<?php endif; ?>
                    </div>
                </section>

			<?php
			endif;
        elseif ( $screen['_type'] == 'screen_4' ):
			if ( ! $screen['screen_off'] ):
				?>

                <section class="section-team pad_section"
                         id="<?php echo $screen['id']; ?>">
                    <div class="container">
                        <div class="title-section-group">
                            <div class="title-sm">
								<?php _t( $screen['title'] ); ?>
                            </div>
                            <div class="title-section__text">
                                <div class="text-group">
									<?php _t( $screen['subtitle'] ); ?>
                                </div>
                            </div>
                        </div>
                        <div class="team">
							<?php if ( $team = $screen['team'] ): foreach ( $team as $item ):
								$_id = $item['id'];
								if ( get_post( $_id ) ):
									$_img = get_the_post_thumbnail_url( $_id );
									$_title = get_the_title( $_id );
									?>
                                    <div class="team-item">
                                        <div class="team-item__media">
                                            <img src="<?php echo $_img; ?>" alt="<?php echo $_title; ?>">
                                        </div>
                                        <div class="team-item__name"> <?php echo $_title; ?></div>
                                        <div class="team-item__position">
											<?php echo carbon_get_post_meta( $_id, 'position' ); ?>
                                        </div>
                                    </div>
								<?php endif; endforeach; endif; ?>
                        </div>
                    </div>
                </section>

			<?php
			endif;
        elseif ( $screen['_type'] == 'screen_5' ):
			if ( ! $screen['screen_off'] ):
				?>

                <section class="section-faq pad_section line_top"
                         id="<?php echo $screen['id']; ?>">
                    <div class="container">
                        <div class="title-sm text-center">
							<?php echo $screen['title']; ?>
                        </div>
                        <div class="faq-wrap">
                            <div class="faq js-collapse">
								<?php if ( $list = $screen['list'] ): foreach ( $list as $item ): ?>
                                    <div class="faq-item js-collapse-item">
                                        <div class="faq-item__title js-collapse-title">
											<?php echo $item['title']; ?><span></span>
                                        </div>
                                        <div class="faq-item__content js-collapse-content">
                                            <div class="text-group">
												<?php _t( $item['text'] ); ?>
                                            </div>
                                        </div>
                                    </div>
								<?php endforeach; endif; ?>
                            </div>
                        </div>
                    </div>
                </section>

			<?php
			endif;
        elseif ( $screen['_type'] == 'screen_6' ):
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
        elseif ( $screen['_type'] == 'screen_4_1' ):
			if ( ! $screen['screen_off'] ):
				?>

                <section class="section-team-info pad_section" id="<?php echo $screen['id']; ?>">
                    <div class="container">
						<?php if ( $team = $screen['team'] ): foreach ( $team as $item ):
							$_id = $item['id'];
							if ( get_post( $_id ) ):
								$_img = get_the_post_thumbnail_url( $_id ) ?: $assets . 'img/team1.webp';
								$_title = get_the_title( $_id );
								?>
                                <div class="team-info">
                                    <div class="team-info__left">
                                        <div class="team-item">
                                            <div class="team-item__front">
                                                <div class="team-item__media">
                                                    <img src="<?php echo $_img; ?>" alt="<?php echo $_title; ?>"/>
                                                </div>
                                                <div class="team-item__name">
													<?php echo $_title; ?>
                                                </div>
                                                <div class="team-item__position">
													<?php echo carbon_get_post_meta( $_id, 'position' ); ?>
                                                </div>
                                            </div>
                                            <div class="team-item__back">
                                                <div class="team-item__text">
													<?php echo carbon_get_post_meta( $_id, 'employee_description' ); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="team-info__right">
                                        <div class="text-group">
											<?php echo get_content_by_id( $_id ); ?>
                                        </div>
                                    </div>
                                </div>
							<?php endif; endforeach; endif; ?>
                    </div>
                </section>

			<?php
			endif;
		endif;
	endforeach;
endif;
get_footer();
