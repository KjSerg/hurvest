<?php
function the_aside() {
	$var             = variables();
	$set             = $var['setting_home'];
	$assets          = $var['assets'];
	$url             = $var['url'];
	$url_home        = $var['url_home'];
	$admin_ajax      = $var['admin_ajax'];
	$id              = get_the_ID();
	$isLighthouse    = isLighthouse();
	$size            = $isLighthouse ? 'thumbnail' : 'full';
	$current_user_id = get_current_user_id();
	$logo            = carbon_get_theme_option( 'logo' );
	$logo_sm         = carbon_get_theme_option( 'logo_sm' );
	$user_id         = get_current_user_id();
	$current_user    = get_user_by( 'ID', $user_id );
	$email           = $current_user->user_email ?: '';
	$display_name    = $current_user->display_name ?: '';
	$first_name      = $current_user->first_name ?: '';
	$last_name       = $current_user->last_name ?: '';
	$name            = $first_name ?: $display_name;
	$user_avatar     = carbon_get_user_meta( $user_id, 'user_avatar' );
	$user_seller     = carbon_get_user_meta( $user_id, 'user_seller' );
	$avatar_url      = $user_avatar ? _u( $user_avatar, 1 ) : get_avatar_url( $email ?: $user_id );
	$personal_page   = carbon_get_theme_option( 'personal_area_page' );
	$_url            = $personal_page ? get_the_permalink( $personal_page[0]['id'] ) : '';
	$route           = $_GET['route'] ?? '';
	$is_manager      = is_manager( $user_id );
	$string_length   = string_length( $name );
	?>
    <div class="aside">
        <div class="aside-top">
            <div class="aside-logo">
                <div class="aside-tog"></div>
                <div class="aside-logo__content">
                    <a class="logo_cab" href="<?php echo $url; ?>">
                        <img src="<?php _u( $logo ); ?>" alt="<?php bloginfo( 'name' ); ?>"/>
                    </a>
                    <a class="logo_cab" href="<?php echo $url; ?>">
                        <img class="logo_cab_main" src="<?php _u( $logo ); ?>" alt="<?php bloginfo( 'name' ); ?>"/>
                        <img class="logo_cab_sm" src="<?php _u( $logo_sm ); ?>" alt="<?php bloginfo( 'name' ); ?>"/>
                    </a>
                </div>
            </div>
            <div class="aside-user">
                <div class="aside-user__content">
                    <div class="aside-user__media">
                        <img class="user-avatar" src="<?php echo $avatar_url; ?>" alt=""/>
                    </div>
                    <div class="aside-user__info">
                        <div class="aside-user__suptitle">Вітаємо!</div>
                        <div class="aside-user__title">
							<?php echo $string_length > 20 ? mb_strimwidth( $name, 0, 15, "..." ) : $name; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="aside-nav">
            <ul>
                <li>
                    <a class="<?php echo $route == '' ? 'active' : ''; ?>" href="<?php echo $_url; ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                             style="enable-background:new 0 0 12 15" viewBox="0 0 12 15">
                                    <path d="M5.9 7.2c1 0 1.8-.4 2.4-1.1.7-.7 1-1.6 1-2.6S9 1.6 8.3.9C7.7.4 6.9 0 5.9 0 5 0 4.1.4 3.5 1.1c-.7.7-1 1.6-1 2.6s.3 1.9 1 2.6c.6.6 1.5.9 2.4.9z"
                                          style="fill:#fff"/>
                            <path d="M12 11.5c0-.3-.1-.6-.1-.9-.1-.3-.1-.7-.2-1-.1-.3-.2-.6-.4-.9-.2-.3-.4-.6-.6-.8-.2-.2-.5-.4-.8-.5-.3-.1-.7-.2-1-.2-.1 0-.3.1-.6.2-.2.2-.4.3-.6.5-.2.1-.5.2-.8.3-.3.1-.6.2-.9.2s-.6-.1-.9-.2c-.3-.1-.6-.2-.8-.3-.2-.2-.4-.3-.6-.4-.2-.2-.4-.3-.5-.3-.4 0-.7.1-1 .2-.4.2-.7.4-.9.6-.2.2-.4.4-.6.7l-.3.9c-.1.3-.2.6-.2 1-.1.3-.2.6-.2.9v.9c0 .8.2 1.4.7 1.9.5.5 1.1.7 1.8.7h6.9c.7 0 1.4-.2 1.8-.7.5-.5.7-1.1.7-1.9.1-.3.1-.6.1-.9z"
                                  style="fill:#fff"/>
                                </svg>
                        <span>Особисті дані</span>
                    </a>
                </li>
				<?php if ( $user_seller || $is_manager ): ?>
                    <li>
                        <a class="<?php echo $route == 'advertisement' ? 'active' : ''; ?>"
                           href="<?php echo $_url . '?route=advertisement'; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                 style="enable-background:new 0 0 15 15" viewBox="0 0 15 15">
                                    <path d="M8 10c0-1.1.9-2 2-2h3c1.1 0 2 .9 2 2v3c0 1.1-.9 2-2 2h-3c-1.1 0-2-.9-2-2v-3zM8 2c0-1.1.9-2 2-2h3c1.1 0 2 .9 2 2v3c0 1.1-.9 2-2 2h-3c-1.1 0-2-.9-2-2V2zM0 2C0 .9.9 0 2 0h3c1.1 0 2 .9 2 2v3c0 1.1-.9 2-2 2H2C.9 7 0 6.1 0 5V2zM0 10c0-1.1.9-2 2-2h3c1.1 0 2 .9 2 2v3c0 1.1-.9 2-2 2H2c-1.1 0-2-.9-2-2v-3z"
                                          style="fill:#fff"/>
                                </svg>
                            <span>Мої оголошення</span>
                        </a>
                    </li>
				<?php endif; ?>
                <li>
                    <a
                            class="<?php echo $route == 'payment_history' ? 'active' : ''; ?>"
                            href="<?php echo $_url . '?route=payment_history'; ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                             style="enable-background:new 0 0 15 15" viewBox="0 0 15 15">
                                    <path d="M10.2 5.3c-2.7 0-4.8 2.2-4.8 4.8 0 2.7 2.2 4.8 4.8 4.8 2.7 0 4.8-2.2 4.8-4.8 0-2.6-2.2-4.8-4.8-4.8zm0 4.4c.7 0 1.3.6 1.3 1.3 0 .6-.4 1.1-.9 1.2v1h-.9v-1c-.5-.2-.9-.7-.9-1.2h.9c0 .2.2.4.4.4s.4-.2.4-.4-.2-.4-.4-.4c-.7 0-1.3-.6-1.3-1.3 0-.6.4-1.1.9-1.2v-1h.9v1c.5.2.9.7.9 1.2h-.9c0-.2-.2-.4-.4-.4-.3-.1-.5.1-.5.4 0 .2.2.4.5.4zM4.9 5.3c2.7 0 4.8-1.2 4.8-2.6S7.6 0 4.9 0 0 1.2 0 2.7s2.2 2.6 4.9 2.6zM0 9.9v.7c0 1.5 2.2 2.6 4.9 2.6h.5c-.3-.5-.6-1.1-.7-1.7-2-.1-3.8-.7-4.7-1.6zM4.5 10.6v-.4c0-.5.1-.9.2-1.3-2-.1-3.8-.7-4.7-1.6V8c0 1.4 2 2.5 4.5 2.6zM4.9 8c.3-.7.7-1.3 1.2-1.8H4.9C2.8 6.2 1 5.6 0 4.6v.7C0 6.8 2.2 8 4.9 8z"
                                          style="fill:#fff"/>
                                </svg>
                        <span>Історія оплат</span>
                    </a>
                </li>
				<?php if ( $user_seller ): ?>
                    <li>
                        <a class="<?php echo $route == 'packages' ? 'active' : ''; ?>"
                           href="<?php echo $_url . '?route=packages'; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                 style="enable-background:new 0 0 15 15" viewBox="0 0 15 15">
                                    <path d="M12.8 2.2C11.4.8 9.5 0 7.5 0S3.6.8 2.2 2.2C.8 3.6 0 5.5 0 7.5s.8 3.9 2.2 5.3C3.6 14.2 5.5 15 7.5 15s3.9-.8 5.3-2.2c1.4-1.4 2.2-3.3 2.2-5.3s-.8-3.9-2.2-5.3zM7.5 7c1.2 0 2.1 1 2.1 2.1 0 1-.7 1.8-1.5 2-.1.1-.1.1-.1.2v.4c0 .3-.2.5-.4.5-.3 0-.6-.2-.6-.5v-.4c0-.1 0-.1-.1-.1-.9-.2-1.5-1.1-1.5-2 0-.3.2-.5.5-.5s.5.2.5.5c0 .7.6 1.2 1.3 1.2.6 0 1-.5 1.1-1.1C8.7 8.5 8.2 8 7.5 8 6.3 8 5.4 7 5.4 5.9c0-1 .7-1.8 1.5-2 .1-.1.1-.1.1-.2v-.4c0-.3.2-.5.5-.5.2 0 .5.2.5.5v.4c0 .1 0 .1.1.1.9.2 1.5 1.1 1.5 2 0 .3-.2.5-.5.5s-.5-.2-.5-.5c0-.7-.6-1.2-1.3-1.2-.6 0-1 .5-1.1 1.1.1.8.6 1.3 1.3 1.3z"
                                          style="fill:#fff"/>
                                </svg>
                            <span>Доступні послуги</span>
                        </a>
                    </li>
				<?php endif; ?>
				<?php if ( $user_seller ): ?>
                    <li>
                        <a class="<?php echo $route == 'verification' ? 'active' : ''; ?>"
                           href="<?php echo $_url . '?route=verification'; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                 style="enable-background:new 0 0 15 15" viewBox="0 0 15 15">
                                    <path d="M7.5 0C3.4 0 0 3.4 0 7.5S3.4 15 7.5 15 15 11.6 15 7.5 11.6 0 7.5 0zm4.2 5.5-4.8 4.8c-.3.3-.7.3-1 0L3.3 8c-.3-.3-.3-.8 0-1.1.3-.3.8-.3 1.1 0l2 1.8 4.3-4.3c.3-.3.8-.3 1.1 0 .2.4.2.8-.1 1.1z"
                                          style="fill:#fff"/>
                                </svg>
                            <span>Верифікація</span>
                        </a>
                    </li>
				<?php endif; ?>
                <li>
                    <a class="<?php echo $route == 'message' ? 'active' : ''; ?>"
                       href="<?php echo $_url . '?route=message'; ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                             style="enable-background:new 0 0 14 14" viewBox="0 0 14 14">
                                    <path d="M10.8 0H3.2C1.4 0 0 1.4 0 3.2v5.1c0 1.5 1.1 2.8 2.5 3.1v2c0 .4.3.6.6.6.1 0 .2 0 .4-.1l3.7-2.4h3.6c1.8 0 3.2-1.4 3.2-3.2V3.2C14 1.4 12.6 0 10.8 0zM9.5 7.6h-5c-.4 0-.7-.2-.7-.6s.3-.6.6-.6h5.1c.4 0 .6.3.6.6s-.2.6-.6.6zm1.3-2.5H3.2c-.4 0-.6-.3-.6-.6s.3-.6.6-.6h7.6c.4 0 .6.3.6.6s-.2.6-.6.6z"
                                          style="fill:#fff"/>
                                </svg>
                        <span>Повідомлення</span>
                    </a>
                </li>
                <li>
                    <a class="<?php echo $route == 'history' ? 'active' : ''; ?>"
                       href="<?php echo $_url . '?route=history'; ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                             style="enable-background:new 0 0 15 15" viewBox="0 0 15 15">
                                    <path d="M14.7 5.1c-.2-.3-.5-.4-.9-.4h-.9L9.4.3c-.2-.3-.6-.4-.9-.2-.3.3-.3.7-.1 1l2.9 3.6H3.5l2.9-3.6c.2-.3.2-.7 0-.9-.3-.2-.7-.2-.9 0l-.1.1-3.5 4.4h-.8C.5 4.7 0 5.3 0 5.9v.2l1.5 7.5c.1.8.8 1.4 1.6 1.4h8.8c.8 0 1.5-.6 1.6-1.3L15 6.1c0-.3 0-.7-.3-1zm-9.1 6.2c0 .4-.3.7-.6.7s-.6-.3-.6-.7V8.6c-.1-.4.2-.6.6-.6.3 0 .6.3.6.7v2.6zm2.5 0c0 .4-.3.7-.6.7s-.6-.3-.6-.7V8.6c0-.4.2-.6.6-.6.3 0 .6.3.6.7v2.6zm2.6 0c0 .4-.3.7-.6.7s-.6-.3-.6-.7V8.6c-.1-.4.2-.6.5-.6s.6.3.6.7v2.6z"
                                          style="fill:#fff"/>
                                </svg>
                        <span>Історія замовлень</span>
                    </a>
                </li>
				<?php if ( $user_seller ): ?>
                    <li>
                        <a class="<?php echo $route == 'users' ? 'active' : ''; ?>"
                           href="<?php echo $_url . '?route=users'; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                 style="enable-background:new 0 0 15 13" viewBox="0 0 15 13">
                                    <path d="M12.8 7h-1.2c.1.3.2.7.2 1.1v4.5c0 .2 0 .3-.1.4h1.9c.7 0 1.3-.6 1.3-1.3V9.2C15 8 14 7 12.8 7zM3.1 8.1c0-.4.1-.7.2-1.1H2.2C1 7 0 8 0 9.2v2.4c0 .8.6 1.4 1.3 1.4h1.9c0-.1-.1-.3-.1-.4V8.1zM8.8 5.9H6.2C5 5.9 4 6.9 4 8.1v4.5c0 .2.2.4.4.4h6.1c.2 0 .4-.2.4-.4V8.1c.1-1.2-.9-2.2-2.1-2.2zM7.5 0C6.1 0 4.9 1.2 4.9 2.7c0 1 .5 1.9 1.3 2.3.4.2.8.3 1.3.3s.9-.1 1.3-.3c.8-.5 1.3-1.3 1.3-2.3C10.1 1.2 8.9 0 7.5 0zM2.9 2.5c-1 0-1.9.9-1.9 2s.9 2 1.9 2c.3 0 .5-.1.8-.2.4-.2.7-.5 1-.9.1-.3.2-.6.2-.9 0-1.1-.9-2-2-2zM12.1 2.5c-1.1 0-1.9.9-1.9 2 0 .3.1.7.2.9.2.4.5.7 1 .9.2.1.5.2.8.2 1.1 0 1.9-.9 1.9-2-.1-1.1-1-2-2-2z"
                                          style="fill:#fff"/>
                                </svg>
                            <span>Керування користувачами</span>
                        </a>
                    </li>
				<?php endif; ?>
            </ul>
        </div>
        <div class="aside-bot">
            <div class="aside-bot__content">
                <a class="exit-link" href="<?php echo wp_logout_url( $url ); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                         style="enable-background:new 0 0 15 14.9" viewBox="0 0 15 14.9">
                                <g>
                                    <path d="M9.4 8c-.4 0-.6.3-.6.7v2.5c0 .3-.3.6-.6.6H6.3V2.5c0-.5-.3-1-.9-1.2l-.2-.1h2.9c.3 0 .6.3.6.6v1.9c0 .3.3.6.6.6s.7-.3.7-.6V1.9C10 .8 9.2 0 8.1 0H1.2C.6 0 0 .6 0 1.2v11.1c0 .5.3 1 .9 1.2l3.8 1.2c.1 0 .3.1.4.1.7 0 1.3-.6 1.3-1.2V13h1.9c1 0 1.9-.8 1.9-1.9V8.7c-.2-.4-.5-.7-.8-.7z"
                                          style="fill:#fff"/>
                                    <path d="m14.8 5.7-2.5-2.5c-.2-.2-.4-.2-.7-.1-.2.1-.4.3-.4.6v1.9H8.8c-.3 0-.6.3-.6.6s.3.6.6.6h2.5v1.9c0 .2.2.5.4.6s.5 0 .7-.1l2.5-2.5c.2-.3.2-.7-.1-1z"
                                          style="fill:#fff"/>
                                </g>
                            </svg>
                    <span>Вийти</span>
                </a>
            </div>
        </div>
    </div>
	<?php
}

?>



