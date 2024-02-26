<?php
$get_type = $_GET['type'] ?? '';
$save_center = $_GET['save_center'] ?? '';
if ( $get_type == 'get_products_container' ) {
	the_home_catalog();
	die();
}
get_header();
$var                  = variables();
$set                  = $var['setting_home'];
$assets               = $var['assets'];
$url                  = $var['url'];
$url_home             = $var['url_home'];
$id                   = get_the_ID();
$isLighthouse         = isLighthouse();
$size                 = isLighthouse() ? 'thumbnail' : 'full';
$screens              = carbon_get_post_meta( $set ?: $id, 'screens' );
$categories           = carbon_get_post_meta( $set ?: $id, 'home_categories' );
$title                = carbon_get_post_meta( $set ?: $id, 'home_title' );
$queried_object       = get_queried_object();
$term_id              = $queried_object->term_id;
$taxonomy             = $queried_object->taxonomy;
if ( $get_type == 'map' ):
	$user_location = get_user_location();
	$user_coordinates = get_user_location_coordinates();
	$user_lat         = $user_coordinates['lat'] ?? ( $user_location['lat'] ?? '' );
	$user_lon         = $user_coordinates['lon'] ?? ( $user_location['lon'] ?? '' );
	$cat_str          = $taxonomy . '=' . $term_id;
	$params           = get_query_string();
	$cat_str          = $params ? $cat_str . '' . $params : $cat_str;
	$map_api_url      = carbon_get_theme_option( 'map_api_url' );
	?>

    <section class="section-catalog">
        <div class="container">
            <div class="catalog-top">
				<?php the_home_categories( $categories ); ?>
                <a class="tog-filter" href="#">
                    <span>Смарт фільтри</span>
                    <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" style="enable-background:new 0 0 15 15"
                         viewBox="0 0 15 15">
                            <path d="M15 12.7c0 .1 0 .2-.1.3 0 .1-.1.2-.2.2-.1.1-.2.1-.2.2-.1 0-.2.1-.3.1H7.4c-.2.4-.4.8-.8 1.1-.4.3-.9.4-1.3.4-.5 0-.9-.1-1.3-.4-.4-.3-.7-.6-.8-1.1H.8c-.2 0-.4-.1-.5-.2-.2-.2-.3-.4-.3-.6 0-.2.1-.4.2-.5.2-.1.4-.2.6-.2h2.4c.2-.4.4-.8.8-1.1.4-.3.8-.4 1.3-.4s.9.1 1.3.4c.4.3.7.6.8 1.1h6.9c.1 0 .2 0 .3.1.1 0 .2.1.2.2.1.1.1.2.2.2v.2zm-.8-6h-1.6c-.2-.4-.4-.8-.8-1.1-.4-.3-.8-.4-1.3-.4s-.9.2-1.3.5c-.4.2-.7.6-.8 1H.8c-.2 0-.4.1-.6.3-.1.1-.2.3-.2.5s.1.4.2.5c.1.1.3.2.5.2h7.6c.2.4.4.8.8 1.1.4.3.8.4 1.3.4s.9-.1 1.3-.4c.4-.3.7-.6.8-1.1h1.6c.2 0 .4-.1.5-.2.3-.1.4-.3.4-.5s-.1-.4-.2-.5c-.2-.2-.4-.3-.6-.3zM.8 3h3.9c.2.4.4.8.8 1.1.4.3.8.4 1.3.4s.9-.1 1.3-.4c.3-.3.6-.7.8-1.1h5.4c.2 0 .4-.1.5-.2.1-.2.2-.4.2-.6 0-.2-.1-.4-.2-.5-.1-.1-.3-.2-.5-.2H8.9C8.7 1.1 8.4.7 8 .4 7.7.1 7.2 0 6.8 0s-1 .1-1.3.4c-.4.3-.7.7-.9 1.1H.8c-.2 0-.4.1-.5.2-.2.2-.3.4-.3.5 0 .2.1.4.2.5.2.2.4.3.6.3z"
                                  style="fill:#262c40"/>
                        </svg>
                </a>
            </div>
        </div>
    </section>

    <section class="section-map">
        <div class="map-list" id="map-list"
             data-save-center="<?php echo $save_center; ?>"
             data-lat="<?php echo $user_lat; ?>"
             data-long="<?php echo $user_lon; ?>"
             data-cluster="<?php echo $assets; ?>img/cluster.webp"
             data-json="<?php echo $var['admin_ajax'] . '?action=get_locations&' . $cat_str; ?>"
             data-map="4504f8b37365c3d0"></div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/js-marker-clusterer/1.0.0/markerclusterer_compiled.js"></script>
	    <?php if ( $map_api_url ): ?>
            <script src="<?php echo $map_api_url; ?>" defer></script>
	    <?php endif; ?>
    </section>
    <div class="map-btn">
        <a class="btn_st btn_yellow" href="<?php echo get_term_link( $term_id ); ?>">
                    <span>Показати список<svg xmlns="http://www.w3.org/2000/svg"
                                              xml:space="preserve"
                                              style="enable-background:new 0 0 15 15"
                                              viewBox="0 0 15 15">
                                <path d="M10.6 0H4.4C1.6 0 0 1.6 0 4.4v6.3C0 13.4 1.6 15 4.4 15h6.3c2.7 0 4.4-1.6 4.4-4.4V4.4C15 1.6 13.4 0 10.6 0zM6 9.7l-1.7 1.7c-.1.1-.3.2-.4.2s-.3-.1-.4-.2l-.6-.6c-.2-.2-.2-.6 0-.8.2-.2.6-.2.8 0l.2.2 1.3-1.3c.2-.2.6-.2.8 0 .2.2.2.6 0 .8zm0-5.3L4.3 6.1c-.1.1-.3.2-.4.2s-.3-.1-.4-.2l-.6-.5c-.2-.3-.2-.6 0-.8.2-.2.6-.2.8 0l.2.2 1.3-1.3c.2-.2.6-.2.8 0 .2.1.2.5 0 .7zm5.7 6.6h-4c-.3 0-.6-.3-.6-.6s.3-.6.6-.6h3.9c.3 0 .6.3.6.6s-.2.6-.5.6zm0-5.3h-4c-.3 0-.6-.3-.6-.6s.3-.6.6-.6h3.9c.3 0 .6.3.6.6 0 .4-.2.6-.5.6z"
                                      style="fill:#1a1b1f"/>
                            </svg></span>
        </a>
    </div>

<?php else: ?>

    <section class="section-catalog">
        <div class="container">
            <div class="catalog-top">
				<?php the_home_categories( $categories ); ?>
                <a class="tog-filter" href="#">
                    <span>Смарт фільтри</span>
                    <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" style="enable-background:new 0 0 15 15"
                         viewBox="0 0 15 15">
                            <path d="M15 12.7c0 .1 0 .2-.1.3 0 .1-.1.2-.2.2-.1.1-.2.1-.2.2-.1 0-.2.1-.3.1H7.4c-.2.4-.4.8-.8 1.1-.4.3-.9.4-1.3.4-.5 0-.9-.1-1.3-.4-.4-.3-.7-.6-.8-1.1H.8c-.2 0-.4-.1-.5-.2-.2-.2-.3-.4-.3-.6 0-.2.1-.4.2-.5.2-.1.4-.2.6-.2h2.4c.2-.4.4-.8.8-1.1.4-.3.8-.4 1.3-.4s.9.1 1.3.4c.4.3.7.6.8 1.1h6.9c.1 0 .2 0 .3.1.1 0 .2.1.2.2.1.1.1.2.2.2v.2zm-.8-6h-1.6c-.2-.4-.4-.8-.8-1.1-.4-.3-.8-.4-1.3-.4s-.9.2-1.3.5c-.4.2-.7.6-.8 1H.8c-.2 0-.4.1-.6.3-.1.1-.2.3-.2.5s.1.4.2.5c.1.1.3.2.5.2h7.6c.2.4.4.8.8 1.1.4.3.8.4 1.3.4s.9-.1 1.3-.4c.4-.3.7-.6.8-1.1h1.6c.2 0 .4-.1.5-.2.3-.1.4-.3.4-.5s-.1-.4-.2-.5c-.2-.2-.4-.3-.6-.3zM.8 3h3.9c.2.4.4.8.8 1.1.4.3.8.4 1.3.4s.9-.1 1.3-.4c.3-.3.6-.7.8-1.1h5.4c.2 0 .4-.1.5-.2.1-.2.2-.4.2-.6 0-.2-.1-.4-.2-.5-.1-.1-.3-.2-.5-.2H8.9C8.7 1.1 8.4.7 8 .4 7.7.1 7.2 0 6.8 0s-1 .1-1.3.4c-.4.3-.7.7-.9 1.1H.8c-.2 0-.4.1-.5.2-.2.2-.3.4-.3.5 0 .2.1.4.2.5.2.2.4.3.6.3z"
                                  style="fill:#262c40"/>
                        </svg>
                </a>
            </div>
			<?php the_vip_catalog(); ?>
			<?php the_top_catalog(); ?>
            <div class="catalog-group">
                <div class="title-sm">
					<?php _t( $title ); ?>
                </div>
				<?php the_home_catalog(); ?>
            </div>
        </div>
    </section>
	<?php
	$get_map_link = get_map_link();
	?>
    <div class="map-btn">
        <a class="btn_st btn_yellow" href="<?php echo $get_map_link; ?>">
                    <span>Показати карту<svg
                                xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                style="enable-background:new 0 0 15 15" viewBox="0 0 15 15">
                                    <path d="M11.7 0C9.9 0 8.4 1.5 8.4 3.3c0 1.7 2.6 4.7 2.9 5 .1.1.2.2.3.2.1 0 .3-.1.3-.2.5-.4 3.1-3.3 3.1-5C15 1.5 13.5 0 11.7 0zm0 4.7c-.8 0-1.4-.6-1.4-1.4s.6-1.4 1.4-1.4c.8 0 1.4.6 1.4 1.4s-.6 1.4-1.4 1.4zM.3 5.5c-.2.1-.3.3-.3.4v8.6c0 .2.1.3.2.4.1.1.2.1.3.1h.2l4-1.6V3.8L.3 5.5z"
                                          style="fill:#1a1b1f"/>
                                    <path d="M12.8 8.9c-.3.3-.6.5-1 .5s-.8-.2-1-.5l-.4-.4V15l4.4-1.8c.2-.1.3-.2.3-.4V5.9c-.9 1.3-1.9 2.5-2.3 3zM7.8 4.6l-2.2-.8v9.6l3.8 1.5V7.3c-.6-.8-1.2-1.8-1.6-2.7z"
                                          style="fill:#1a1b1f"/>
                                </svg></span>
        </a>
    </div>
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
            elseif ( $screen['_type'] == 'screen_2' ):
				if ( ! $screen['screen_off'] ):
					?>

				<?php
				endif;
			endif;
		endforeach;
	endif; endif; ?>

<?php get_footer(); ?>

