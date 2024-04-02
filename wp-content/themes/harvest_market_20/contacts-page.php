<?php
/* Template Name: Шаблон сторінки контактів */
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
$screens      = carbon_get_post_meta( $id, 'contacts_screens' );
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
                                <div class="title-sm">
									<?php _t( $screen['title'] ); ?>
                                </div>

	                            <?php if ( $contacts_list = $screen['list'] ): ?>
                                    <div class="contact-list">
			                            <?php foreach ( $contacts_list as $item ): if ( $item['_type'] == 'tel_list' ): ?>
                                            <div class="contact-list__item">
                                                <div class="contact-list__item-content">
						                            <?php if ( $list = $item['list'] ): foreach ( $list as $value ): ?>
                                                        <a href="<?php the_phone_link( $value['tel'] ); ?>">
								                            <?php echo $value['tel']; ?>
                                                        </a>
						                            <?php endforeach; endif; ?>
                                                </div>
                                            </div>
			                            <?php elseif ( $item['_type'] == 'email_list' ): ?>
                                            <div class="contact-list__item">
                                                <div class="contact-list__item-content">
						                            <?php if ( $list = $item['list'] ): foreach ( $list as $value ): ?>
                                                        <a href="mailto:<?php echo $value['email']; ?>">
								                            <?php echo $value['email']; ?>
                                                        </a>
						                            <?php endforeach; endif; ?>
                                                </div>
                                            </div>
			                            <?php endif; endforeach; ?>
                                    </div>
	                            <?php endif; ?>
                            </div>
                            <div class="text-column__right">
                                <div class="text-group" style="margin-bottom: 2rem;">
									<?php _t( $screen['text'] ); ?>
                                </div>
	                            <?php if ( $screen['image'] ): ?>
                                    <img src="<?php _u( $screen['image'] ); ?>" alt=""/>
	                            <?php endif; ?>
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
		endif;
	endforeach;
endif;
?>

<?php get_footer(); ?>