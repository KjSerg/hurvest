<?php
$var = variables();
$set = $var['setting_home'];
$assets = $var['assets'];
$url = $var['url'];
$id = get_the_ID();
$user_id = get_current_user_id();
$logo = carbon_get_theme_option( 'logo' );
$header_tel = carbon_get_theme_option( 'header_tel' );
$get_route = $_GET['route'] ?? '';
$body_class = $_COOKIE['body_class'] ?? '';
$personal_area_page = carbon_get_theme_option( 'personal_area_page' );
$login_page = carbon_get_theme_option( 'login_page' );
$is_personal_area = $personal_area_page && ( (int) $personal_area_page[0]['id'] == $id );
$enter_link_text = 'Мій профіль';
$favorites = carbon_get_user_meta( $user_id, 'user_favorites' ) ?: ( $_COOKIE['favorites'] ?? '' );
$favorites = $favorites ? explode( ",", $favorites ) : array();
if ( $user_id ) {
	$current_user    = get_user_by( 'ID', $user_id );
	$email           = $current_user->user_email ?: '';
	$display_name    = $current_user->display_name ?: '';
	$first_name      = $current_user->first_name ?: '';
	$last_name       = $current_user->last_name ?: '';
	$name            = $first_name ?: $display_name;
	$name            = explode( '@', $name )[0];
	$string_length   = string_length( $name );
	$enter_link_text = $string_length > 20 ? mb_strimwidth( $name, 0, 15, "..." ) : $name;
}
get_portmone_post_data();
onTelegramAuth();
?><!DOCTYPE html>
<html class="no-js  page" <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="theme-color" content="#014433">
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries-->
	<!-- WARNING: Respond.js doesn't work if you view the page via file://--><!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
	<title><?php wp_title(); ?></title>
	<?php wp_head(); ?>
</head>
<body class="<?php echo $body_class; ?>">
<header class="header">
	<div class="container">
		<div class="header-content">
            <a class="logo" href="<?php echo $url; ?>">
                <img src="<?php _u( $logo ); ?>" alt="<?php bloginfo( 'name' ); ?>"/>
            </a>
			<div class="search-wrap">
				<form  method="get" action="<?php echo $url ?>">
					<div class="search-group">
                        <input type="hidden" name="post_type" value="<?php echo $_GET['post_type'] ?? 'products'; ?>">
                        <input type="text" required class="search-input" name="s"
                               placeholder="Пошук..."
                               value="<?php echo $_GET['s'] ?? ''; ?>">
                        <button class="search-btn" type="submit">Знайти</button>
                    </div>
				</form>
			</div>
			<div class="user-links">
				<?php if ( $login_page ): ?>
                    <a class="enter-link"
                       href="<?php echo get_the_permalink( $login_page[0]['id'] ); ?>">
                        <span><?php echo $enter_link_text; ?></span>
                        <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                             style="enable-background:new 0 0 13 15"
                             viewBox="0 0 13 15">
                            <path d="M8.8 7c1-.7 1.6-1.8 1.6-3.1C10.4 1.7 8.7 0 6.5 0S2.6 1.7 2.6 3.8c0 1.3.6 2.4 1.6 3.1-2.5 1-4.2 3.3-4.2 6C0 14.1 1 15 2.2 15h8.7c1.2 0 2.2-.9 2.2-2.1-.1-2.7-1.8-5-4.3-5.9zM3.7 3.8c0-1.5 1.2-2.7 2.8-2.7s2.8 1.2 2.8 2.7S8 6.5 6.5 6.5 3.7 5.3 3.7 3.8zm7.1 10H2.2c-.5 0-1-.4-1-1 0-2.9 2.4-5.2 5.3-5.2s5.3 2.3 5.3 5.2c0 .6-.4 1-1 1z"
                                  style="fill:#fff"/></svg>
                    </a>
				<?php endif; ?>
                <a class="user-link " href="<?php echo $url . '?route=favorites' ?>">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         xml:space="preserve"
                         style="enable-background:new 0 0 16 14"
                         viewBox="0 0 16 14">
                            <path d="M4.8 1.3c1 0 1.9.5 2.6 1.6.3.4.9.4 1.1 0 .7-1.2 1.6-1.6 2.6-1.6s2 .4 2.6 1.2c1.3 1.3 1.4 3.8-.3 5.5L8 12.5 2.6 8C.9 6.3 1 3.8 2.2 2.4c.6-.7 1.6-1.1 2.6-1.1zm0-1.3C3.4 0 2.1.6 1.2 1.6c-1.6 1.9-1.7 5.1.5 7.3l5.8 4.9c.2.2.6.2.9 0l5.8-4.9c2.2-2.2 2.1-5.4.5-7.3-.9-1-2.2-1.6-3.6-1.6C10 0 8.9.7 8 1.7 7.1.7 6 0 4.8 0z"
                                  style="fill:#fff"/>
                        </svg>
                    <span class="favorites-count"><?php echo count( $favorites ); ?></span>
                </a>
            </div>
		</div>
	</div>
</header>
<main class="content">