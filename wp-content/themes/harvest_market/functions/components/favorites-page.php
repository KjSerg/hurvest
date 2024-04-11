<?php
get_header();
$var       = variables();
$set       = $var['setting_home'];
$assets    = $var['assets'];
$url       = $var['url'];
$url_home  = $var['url_home'];
$user_id   = get_current_user_id();
$favorites = carbon_get_user_meta( $user_id, 'user_favorites' ) ?: ( $_COOKIE['favorites'] ?? '' );
$favorites = $favorites ? explode( ",", $favorites ) : array();
$route = $_GET['route'] ?? '';
?>

<section class="section-cart pad_section_sm_top pad_section_bot">
    <div class="container">
        <div class="title-section-group db_xs">
            <div class="title-sm">Обрані оголошення</div>
			<?php if ( $favorites ): ?>
                <a class="remove-cart clear-favorites" href="#">
                    Очистити обрані
                    <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                         style="enable-background:new 0 0 17.3 20"
                         viewBox="0 0 17.3 20">
                            <path d="M5.8 0c-.5 0-.9.4-.9.9v1.8h-4c-.5 0-.9.4-.9.9s.4.9.9.9h15.6c.5 0 .9-.4.9-.9s-.4-.9-.9-.9h-4V.9c0-.5-.4-.9-.9-.9H5.8zm.9 2.6v-.8h4.1v.8H6.7zM15.5 6.4H1.7v10.2c0 2 1.4 3.5 3.3 3.5h7.2c1.9 0 3.3-1.5 3.3-3.5V6.4zm-8 3.2c0-.5-.4-.9-.9-.9s-.9.4-.9.9v6.1c0 .5.4.9.9.9s.9-.4.9-.9V9.6zm4.1 0c0-.5-.4-.9-.9-.9s-.9.4-.9.9v6.1c0 .5.4.9.9.9s.9-.4.9-.9V9.6z"
                                  style="fill-rule:evenodd;clip-rule:evenodd;fill:#fff"/>
                        </svg>
                </a>
			<?php endif; ?>
        </div>
        <div class="catalog catalog_main">
			<?php if ( $favorites ):
				foreach ( $favorites as $favorite ):
					if ( $favorite && get_post( $favorite ) && get_post_status( $favorite ) == 'publish' ):
						the_product( $favorite );
					endif;
				endforeach;
			else: ?>
                <div class="text-group" style="text-align: center; margin: 2rem; width: 100%;">
                    Додайте оголошення із <a class="<?php echo !$route == 'favorites' ? 'link-js' : ''; ?>" href="<?php echo $url ?>">каталогу</a>!
                </div>
			<?php endif; ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
