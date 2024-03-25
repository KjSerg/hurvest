<?php
add_action( 'after_setup_theme',
	function () {
		register_nav_menus(
			array(
				'header_menu'        => 'Меню в шапці',
				'footer_menu'        => 'Меню в підвалі',
				'footer_seller_menu' => 'Меню в підвалі сторінки продавця',
			)
		);
	}
);