<?php
/**
 * harvest functions and definitions
 *
 * @package harvest
 */

function harvest_scripts() {
	wp_enqueue_style( 'harvest-style', get_stylesheet_uri() );

	wp_enqueue_style( 'harvest-fancybox', 'https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css', array(), '1.0' );

	wp_enqueue_style( 'harvest-main', get_template_directory_uri() . '/assets/css/main.css', array(), '1.0' );

	wp_enqueue_style( 'harvest-fix', get_template_directory_uri() . '/assets/css/fix.css', array(), '1.0' );

	wp_enqueue_script( 'harvest-jq', get_template_directory_uri() . '/assets/js/jquery.js', array(), '1.0', true );

	wp_enqueue_script( 'harvest-maskedinput-js', get_template_directory_uri() . '/assets/js/jquery.maskedinput.min.js', array(), '1.0', true );

	wp_enqueue_script( 'harvest-fancybox', 'https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js', array(), '1.0', true );

	wp_enqueue_script( 'harvest-libs', get_template_directory_uri() . '/assets/js/libs.min.js', array(), '1.0', true );

	wp_enqueue_script( 'harvest-scripts', get_template_directory_uri() . '/assets/js/main.js', array(), '1.0', true );

	wp_enqueue_script( 'harvest-fix-scripts', get_template_directory_uri() . '/assets/js/fix.js', array(), '1.0', true );

	wp_localize_script( 'ajax-script', 'AJAX', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}

add_action( 'wp_enqueue_scripts', 'harvest_scripts' );

get_template_part( 'functions/helpers' );
get_template_part( 'functions/settings' );
get_template_part( 'functions/carbon-settings' );
get_template_part( 'functions/components' );
get_template_part( 'functions/google-distance' );
get_template_part( 'functions/ajax-functions' );
get_template_part( 'functions/components/mails' );
get_template_part( 'functions/zoho-crm' );
get_template_part( 'functions/nova-post-api' );

add_action( 'rest_api_init', function () {
	register_rest_route( 'harvest_market/v1', '/telegram', [
		'methods'  => 'POST',
		'callback' => 'telegram_webhook',
	] );
} );

function telegram_webhook() {
	$payload = @file_get_contents( 'php://input' );
	$data    = isJSON( $payload ) ? json_decode( $payload, true ) : $payload;
	carbon_set_theme_option( 'bot_text', $payload );
	if ( $data ) {
		$var         = variables();
		$set         = $var['setting_home'];
		$assets      = $var['assets'];
		$url         = $var['url'];
		$url_home    = $var['url_home'];
		$message     = $data['message'];
		$text        = $message['text'];
		$from        = $message['from'];
		$_id         = $from['id'];
		$_first_name = $from['first_name'];
		$_last_name  = $from['last_name'];
		$_name       = ( $_first_name ?? '' ) . ' ' . ( $_last_name ?? '' );
		$user        = get_user_by_telegram( (int) $_id );
		if ( $user ) {
			$user_id   = $user->ID;
			$user_chat = carbon_get_user_meta( $user_id, 'user_chat' );
			if ( $user_chat ) {
				array_unshift( $user_chat, array( 'text' => $text ) );
			} else {
				$user_chat = array( array( 'text' => $text ) );
			}
			carbon_set_user_meta( $user_id, 'user_chat', $user_chat );
			$project_name = get_bloginfo( 'name' );
			$email        = get_bloginfo( 'admin_email' );
			$_url         = $url . 'wp-admin/user-edit.php?user_id=' . $user_id;
			$form_subject = "Отримано нового листа в телеграм від $_name";
			$message      = 'Повідомлення:<br>';
			$message      .= $text;
			$message      .= '<br>';
			$message      .= '<br>';
			$message      .= "<a href='$_url' target='_blank'>Профиль користувача ID$user_id</a>";
			$headers      = "MIME-Version: 1.0" . PHP_EOL .
			                "Content-Type: text/html; charset=utf-8" . PHP_EOL .
			                'From: ' . $project_name . ' <telegram@' . $_SERVER['HTTP_HOST'] . '>' . PHP_EOL .
			                'Reply-To: ' . $email . '' . PHP_EOL;
			wp_mail( $email, $form_subject, $message, $headers );
		}
	}

}

add_action( 'admin_menu', 'my_custom_menu_page' );

function my_custom_menu_page() {
	add_menu_page(
		'Імпорт',
		'Імпорт',
		'manage_options',
		'custom_export_page',
		'custom_export_page_html',
		'dashicons-media-spreadsheet',
		20
	);
}

//add_action( 'admin_enqueue_scripts', 'custom_admin_assets' );

function custom_admin_assets() {

	wp_enqueue_script( 'custom-admin-jquery', get_template_directory_uri() . '/assets/js/jquery.js', array(), null, true );

	wp_enqueue_script( 'custom-admin-scripts', get_template_directory_uri() . '/assets/js/admin.js', array( 'jquery' ), null, true );

}

function custom_export_page_html() {

	$var            = variables();
	$admin_ajax     = $var['admin_ajax'];
	$assets         = $var['assets'];
	$url            = $var['url'];
	$action         = $_POST['action'] ?? '';
	$taxonomy       = $_POST['taxonomy'] ?? 'categories';
	$parent         = $_POST['parent'] ?? '';
	$terms          = $_POST['terms'] ?? '';
	$parent_parents = $_POST['parent_parents'] ?? '';
	if ( $action == 'add__tags' ) {
        echo '<pre>';
		$args = array(
			'taxonomy' => $taxonomy,
			'terms'    => array_unique( explode(
					PHP_EOL,
					$terms
				)
			)
		);
		if ( $parent_parents ) {
			$children = get_terms( array(
				'hide_empty' => false,
				'parent'     => (int) $parent_parents,
				'taxonomy'   => $taxonomy
			) );
            if($children){
                foreach ($children as $child){
                    if($child->name != 'Інше'){
	                    $args['id'] =  $child->term_id;
	                    var_dump( $args );
	                    add_terms( $args );
                    }
                }
            }
		} else {
			if ( $parent ) {
				$args['id'] = (int) $parent;
			}
			var_dump( $args );
			add_terms( $args );
		}
		echo '</pre>';
	}
	?>
    <script>
        var admin_ajax = '<?php echo $admin_ajax; ?>';
    </script>
    <div class="wrap">
        <form action="<?php echo $url; ?>wp-admin/admin.php?page=custom_export_page" method="post">
            <p><input type="text" name="action" value="add__tags"></p>
            <p><input type="text" name="taxonomy" placeholder="taxonomy" value="<?php echo $taxonomy ?>"></p>
            <p><input type="text" name="parent" placeholder="parent"></p>
            <div>
                <p>Батьківська категорія батьківських категорій окрім 'Інше'</p>
                <input type="text" name="parent_parents" placeholder=""></div>
            <p><textarea rows="20" cols="40" name="terms">Інше</textarea></p>
            <button type="submit">submit</button>
        </form>
    </div>
	<?php

}
