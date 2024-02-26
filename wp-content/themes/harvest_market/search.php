<?php

$var       = variables();
$set       = $var['setting_home'];
$assets    = $var['assets'];
$url       = $var['url'];
$url_home  = $var['url_home'];
$post_type = $_GET['post_type'] ?? '';
$s         = $_GET['s'] ?? '';
if ( ! $s ) {
	header( 'Location: ' . $url );
	die();
}
global $wp_query;
get_header();
$screens = carbon_get_post_meta( $set, 'screens' );
$str     = $post_type == 'organizations' ? 'Результати пошуку господарств' : 'Результати пошуку продукції';
?>
    <section class="section-catalog">
        <div class="container">
			<?php the_vip_catalog(); ?>
            <div class="catalog-group">
                <div class="title-sm">
					<?php echo "$str: $s"; ?>
                </div>
                <div class="button-wrapper">

                    <a class="btn_st <?php echo $post_type == 'products' ? 'not-active' : ''; ?> "
                       href="<?php echo $url . '?s=' . $s . '&post_type=products' ?>">
                        <span>Пошук продукції</span>
                    </a>
                    <a class="btn_st 	<?php echo $post_type == 'organizations' ? 'not-active' : ''; ?>"
                       href="<?php echo $url . '?s=' . $s . '&post_type=organizations' ?>">
                        <span>Пошук господарств</span>
                    </a>
                </div>
                <div class="catalog container-js">
					<?php
					if ( $post_type == 'products' ):
						if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
							<?php the_product(); ?>
						<?php endwhile; else : ?>
                            <div class="text-group" style="text-align: center; margin: 2rem; width: 100%;">
                                Не знайдено!
                            </div>
						<?php endif; ?>
					<?php else:
						$args = array(
							'meta_query' => array(
								'relation' => 'OR',
								array(
									'key'     => '_user_company_name',
									'value'   => $s,
									'compare' => 'LIKE',
								),
								array(
									'key'     => '_user_company_description',
									'value'   => $s,
									'compare' => 'LIKE',
								),
							),
						);
						$user_query = new WP_User_Query( $args );
						$users = $user_query->get_results();
                        if($users):
                        foreach ($users as $user){
                            the_organization($user->ID);
                        }
                        else: ?>
                            <div class="text-group" style="text-align: center; margin: 2rem; width: 100%;">
                                Не знайдено!
                            </div>
					<?php endif; ?>
					<?php endif; ?>
                </div>

                <div class="btn_center pagination-js">
					<?php echo _get_more_link( $wp_query->max_num_pages ); ?>
                </div>
            </div>

        </div>
    </section>
<?php
if ( ! empty( $screens ) ) :
	foreach ( $screens as $index => $screen ) :
		if ( $screen['_type'] == 'screen_seo' ) :
			if ( ! $screen['screen_off'] ) :
				?>

                <section id="<?php echo $screen['id']; ?>" class="section-seo-text dark_section"
                         style="background-image: url(<?php _u( $screen['image'] ) ?>);">
                    <div class="container">
                        <div class="seo-text">
                            <div class="seo-text__left">
                                <div class="seo-text__title">
									<?php _t( $screen['title'] ) ?>
                                </div>
                                <div class="seo-text__subtitle">
									<?php echo $screen['subtitle']; ?>
                                </div>
                            </div>
                            <div class="seo-text__main">
                                <div class="text-group">
									<?php _t( $screen['text'] ) ?>
                                </div>
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
