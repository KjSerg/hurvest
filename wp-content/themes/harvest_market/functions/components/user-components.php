<?php

function the_user_data_editing() {
	$var                 = variables();
	$set                 = $var['setting_home'];
	$assets              = $var['assets'];
	$url                 = $var['url'];
	$url_home            = $var['url_home'];
	$admin_ajax          = $var['admin_ajax'];
	$user_id             = get_current_user_id();
	$current_user        = get_user_by( 'ID', $user_id );
	$email               = $current_user->user_email ?: '';
	$display_name        = $current_user->display_name ?: '';
	$first_name          = $current_user->first_name ?: '';
	$last_name           = $current_user->last_name ?: '';
	$name                = $first_name ?: $display_name;
	$user_surname        = carbon_get_user_meta( $user_id, 'user_surname' ) ?: '';
	$user_city           = carbon_get_user_meta( $user_id, 'user_city' ) ?: '';
	$user_phone          = carbon_get_user_meta( $user_id, 'user_phone' ) ?: '';
	$user_avatar         = carbon_get_user_meta( $user_id, 'user_avatar' );
	$user_seller         = carbon_get_user_meta( $user_id, 'user_seller' );
	$telegram_id         = carbon_get_user_meta( $user_id, 'telegram_id' );
	$company_address     = carbon_get_user_meta( $user_id, 'user_company_address' );
	$company_name        = carbon_get_user_meta( $user_id, 'user_company_name' );
	$company_description = carbon_get_user_meta( $user_id, 'user_company_description' );
	$company_city        = carbon_get_user_meta( $user_id, 'user_company_city' );
	$avatar_url          = $user_avatar ? _u( $user_avatar, 1 ) : get_avatar_url( $email ?: $user_id );
	$BOT_USERNAME        = carbon_get_theme_option( 'telegram_bot_name' );
	$BOT_TOKEN           = carbon_get_theme_option( 'telegram_token' );
	$REDIRECT_URI        = get_the_permalink() ?: $url;
	$map_api_url         = carbon_get_theme_option( 'autocomplete_api_url' );
	$image_count         = carbon_get_theme_option( 'image_count' ) ?: 1;
	$user_post = carbon_get_user_meta($user_id, 'user_post');
	$author_link = false;
	if($user_post && get_post($user_post)){
		$author_link = get_the_permalink($user_post);
	}
	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	the_header_cabinet();
	?>
    <div class="create-item-main">
        <div class="personal-form-group">
            <div class="personal-form__left">
                <div class="cabinet-item">
                    <div class="personal-ava">
                        <form method="post" class="personal-ava__media upload-avatar-form form-js"
                              novalidate
                              id="upload-avatar"
                              enctype="multipart/form-data">
                            <input type="hidden" name="action" value="upload_avatar">
                            <label>
                                <input class="upfile_ava"
                                       type="file"
                                       name="upfile[]"
                                       accept="image/png, image/jpeg"
                                />
                            </label>
                            <img src="<?php echo $user_avatar ? _u( $user_avatar, 1 ) : ''; ?>"
                                 class="<?php echo $user_avatar ? 'visible' : ''; ?>"
                                 alt=""
                            />
                            <span class="remove-file remove-avatar"></span>
                        </form>
                        <div class="personal-ava__info">
                            <div class="personal-ava__suptitle">Вітаємо!</div>
                            <div class="personal-ava__title"><?php echo $name; ?></div>
                        </div>
                    </div>
                </div>
                <div class="cabinet-item">
                    <div class="form-description__item-title"> Змінити пароль</div>
                    <form class="form-js change-password-form" novalidate method="post" id="change-password-form">
                        <input type="hidden" name="action" value="change_password">
                        <div class="form-horizontal">
                            <div class="form-group half">
                                <input class="input_st" type="password"
                                       name="old_password"
                                       required="required"
                                       placeholder="Пароль*"/>
                                <div class="show_pass">
                                    <img src="<?php echo $assets; ?>img/show_pass.svg" alt=""/>
                                    <img src="<?php echo $assets; ?>img/hide_pass.svg" alt=""/>
                                </div>
                            </div>
                            <div class="form-group half">
                                <input class="input_st" type="password"
                                       name="new_password"
                                       required="required"
                                       placeholder="Новий пароль *"/>
                                <div class="show_pass">
                                    <img src="<?php echo $assets; ?>img/show_pass.svg" alt=""/>
                                    <img src="<?php echo $assets; ?>img/hide_pass.svg" alt=""/>
                                </div>
                            </div>
                        </div>
                        <div class="personal-form-btn">
                            <button class="btn_st" type="submit">
                                <span>Зберегти зміни </span>
                            </button>
                            <a class="btn_st b_yelloow modal_open" href="#modal-forgot">
                                <span>Забули пароль? </span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="personal-form__right">
                <div class="cabinet-item">
                    <div class="cabinet-item__title"> Особисті дані</div>
                    <form class="form-js change-user-data-form" id="change-user-data-form" novalidate method="post">
                        <input type="hidden" name="action" value="change_user_data">
                        <div class="form-horizontal">
                            <div class="form-group half">
                                <input class="input_st" type="text"
                                       name="first_name"
                                       value="<?php echo $first_name; ?>"
                                       required="required" placeholder="Ім'я"/>
                            </div>
                            <div class="form-group half">
                                <input class="input_st" type="text" name="last_name"
                                       value="<?php echo $last_name; ?>"
                                       required="required" placeholder="Прізвище"/>
                            </div>
                            <div class="form-group half">
                                <input class="input_st"
                                       type="text"
                                       value="<?php echo $user_surname; ?>"
                                       name="user_surname"
                                       required="required" placeholder="По батькові"/>
                            </div>
                            <div class="form-group half">
                                <input class="input_st" type="text"
                                       name="city"
                                       value="<?php echo $user_city; ?>"
                                       required="required" placeholder="Місто"/>
                            </div>
                            <div class="form-group half">
                                <input class="input_st" type="tel" name="tel"
                                       value="<?php echo $user_phone; ?>"
                                       required="required"
                                       placeholder="Номер телефону"/>
                            </div>
                            <div class="form-group half">
                                <input class="input_st" type="email"
                                       name="email"
                                       value="<?php echo $email; ?>"
                                       required="required"
                                       placeholder="E-mail"/>
                            </div>
                        </div>
                        <div class="personal-form-btn">
                            <button class="btn_st" type="submit"><span>Зберегти зміни </span></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
		<?php if ( $BOT_USERNAME && $BOT_TOKEN && ! $telegram_id ): ?>
            <div class="cabinet-item">
                <div class="accept-group">
                    <div class="accept-title">Додати Telegram</div>
                    <div class="accept-group__link">
                        <script src="https://telegram.org/js/telegram-widget.js?2"
                                data-telegram-login="<?= $BOT_USERNAME ?>"
                                data-size="large" async
                                data-userpic="false"
                                data-auth-url="<?php echo $url; ?>"
                                data-request-access="write"></script>
                    </div>
                </div>
            </div>
		<?php endif; ?>
		<?php if ( ! $user_seller ): ?>
            <div class="cabinet-item js-collapse">
                <div class="faq-item js-collapse-item">
                    <div class="faq-item__title js-collapse-title">
                        Створити фермерське господарство <span></span>
                    </div>
                    <div class="faq-item__content js-collapse-content">
                        <form class="form-js add-enterprise-form" id="add-enterprise-form" novalidate method="post">
                            <input type="hidden" name="action" value="add_enterprise">
                            <input type="hidden" id="user_post_code" name="postcode" value="">
                            <input type="hidden" id="user_country" name="country">
                            <input type="hidden" id="user_country_code" name="country_code">
                            <input type="hidden" id="user_city" name="city">
                            <input type="hidden" id="user_region" name="region">
                            <input type="hidden" id="lat" name="lat">
                            <input type="hidden" id="lng" name="lng">
                            <div class="form-horizontal">
                                <div class="form-group quarter">
                                    <input class="input_st" type="text"
                                           name="name" required="required"
                                           placeholder="Назва господарства*"/>
                                </div>
                                <div class="form-group quarter">
                                    <input class="input_st"
                                           type="tel"
                                           name="phone"
                                           required="required"
                                           title="Обовʼязкове поле"
                                           placeholder="Номер телефону*"/>
                                </div>
                                <div class="form-group half">
                                    <input class="input_st address-js" type="text"
                                           name="address"
                                           id="address-google"
                                           title="Обовʼязкове поле"
                                           placeholder="Місцезнаходження (Місто, індекс)*"
                                           required="required"/>
                                </div>
                                <div class="form-group">
                            <textarea class="input_st" name="text"
                                      placeholder="Опис фермерського господарства*"
                                      title="Обовʼязкове поле"
                                      required="required"></textarea>
                                </div>
                                <div class="cabinet-item" title="Загрузіть що найменше 1 фото">
                                    <div class="cabinet-item__title">Фото*</div>
                                    <div class="cabinet-item__text">Перше фото буде на обкладинці.</div>
                                    <div class="cabinet-item__photo">
                                        <div class="cabinet-item__photo-item cover-photo">
                                            <label>
                                                <input required
                                                       multiple
                                                       id="photos"
                                                       data-max="<?php echo $image_count; ?>"
                                                       class="upfile_product" type="file" name="upfile[]"
                                                       accept="image/heic, image/png, image/jpeg, image/webp"/>
                                            </label>
                                            <img src="" alt=""/>
                                            <span class="remove-file"></span>
                                        </div>
										<?php if ( $image_count > 1 ): for ( $a = 1; $a < $image_count; $a ++ ): ?>
                                            <div class="cabinet-item__photo-item">
                                                <label for="photos"></label>
                                                <img src="" alt=""/>
                                                <span class="remove-file"></span>
                                            </div>
										<?php endfor; endif; ?>
                                    </div>
                                </div>
                                <div class="personal-form-btn">
                                    <button class="btn_st" type="submit">
                                        <span>Додати господарство </span>
                                    </button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
		<?php else:
			$gallery = carbon_get_user_meta( $user_id, 'user_company_gallery' );
			?>
            <div class="cabinet-item js-collapse">
                <div class="faq-item js-collapse-item">
                    <div class="faq-item__title js-collapse-title">
                        Редагувати фермерське господарство <span></span>
                    </div>
                    <div class="faq-item__content js-collapse-content">
                        <form class="form-js add-enterprise-form" id="add-enterprise-form" novalidate method="post">
                            <input type="hidden" name="action" value="add_enterprise">
                            <input type="hidden" name="type" value="edit">
                            <input type="hidden" id="user_post_code" name="postcode" value="<?php echo carbon_get_user_meta( $user_id, 'user_company_postcode' ) ?>">
                            <input type="hidden" id="user_country" name="country" value="<?php echo carbon_get_user_meta( $user_id, 'user_company_country' ) ?>">
                            <input type="hidden" id="user_country_code" name="country_code" value="<?php echo carbon_get_user_meta( $user_id, 'user_company_country_code' ) ?>">
                            <input type="hidden" id="user_city" name="city" value="<?php echo $company_city; ?>">
                            <input type="hidden" id="user_region" name="region" value="<?php echo carbon_get_user_meta( $user_id, 'user_company_region' ) ?>">
                            <input type="hidden" id="lat" name="lat"  value="<?php echo carbon_get_user_meta( $user_id, 'user_company_latitude' ) ?>">
                            <input type="hidden" id="lng" name="lng" value="<?php echo carbon_get_user_meta( $user_id, 'user_company_longitude' ) ?>">
                            <div class="form-horizontal">
                                <div class="form-group quarter">
                                    <input class="input_st" type="text"
                                           name="name" required="required"
                                           title="Обовʼязкове поле"
                                           value="<?php echo $company_name; ?>"
                                           placeholder="Назва господарства*"/>
                                </div>
                                <div class="form-group quarter">
                                    <input class="input_st"
                                           type="tel"
                                           name="phone"
                                           value="<?php echo $user_phone; ?>"
                                           required="required"
                                           title="Обовʼязкове поле"
                                           placeholder="Номер телефону*"/>
                                </div>
                                <div class="form-group half">
                                    <input class="input_st address-js" type="text"
                                           name="address"
                                           value="<?php echo $company_address; ?>"
                                           data-selected="<?php echo $company_address; ?>"
                                           id="address-google"
                                           title="Обовʼязкове поле"
                                           placeholder="Місцезнаходження (Місто, індекс)*" required="required"/>
                                </div>
                                <div class="form-group">
                            <textarea class="input_st" name="text"
                                      placeholder="Опис фермерського господарства*"
                                      title="Обовʼязкове поле"
                                      required="required"><?php echo trim( strip_tags( $company_description ) ); ?></textarea>
                                </div>
                                <div class="cabinet-item">
                                    <div class="cabinet-item__title">Фото*</div>
                                    <div class="cabinet-item__text">Перше фото буде на обкладинці.</div>
                                    <div class="cabinet-item__photo">
                                        <div class="cabinet-item__photo-item cover-photo">
                                            <label>
                                                <input required
                                                       multiple
                                                       id="photos"
                                                       data-max="<?php echo $image_count; ?>"
                                                       class="upfile_product" type="file" name="upfile[]"
                                                       accept="iimage/heic, image/png, image/jpeg, image/webp"/>
                                            </label>
                                            <img src="<?php _u( $gallery[0] ); ?>" alt=""
                                                 class="<?php echo $gallery[0] && _u( $gallery[0], 1 ) ? 'visible add-to-buffer' : ''; ?>"/>
                                            <span class="remove-file"></span>
                                        </div>
										<?php if ( $image_count > 1 ): for ( $a = 1; $a < $image_count; $a ++ ): ?>
                                            <div class="cabinet-item__photo-item">
                                                <label for="photos"></label>
                                                <img src="<?php echo $gallery[ $a ] ? _u( $gallery[ $a ], 1 ) : ''; ?>"
                                                     class="<?php echo $gallery[ $a ] && _u( $gallery[ $a ], 1 ) ? 'visible add-to-buffer' : ''; ?>"
                                                     alt=""/>
                                                <span class="remove-file"></span>
                                            </div>
										<?php endfor; endif; ?>
                                    </div>
                                </div>
                                <div class="personal-form-btn">
                                    <button class="btn_st" type="submit">
                                        <span>Редагувати господарство </span>
                                    </button>
                                    <?php if($author_link): ?>
                                        <a href="<?php echo $author_link; ?>" class="btn_st">
                                            <span>Сторінка господарства</span>
                                        </a>
                                    <?php endif; ?>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
		<?php endif; ?>
    </div>
	<?php if ( $map_api_url ): ?>
        <script src="<?php echo $map_api_url; ?>" id="google-map-api" defer></script>
	<?php endif; ?>
	<?php
}

function the_user_advertisement() {
	$user_id     = get_current_user_id();
	$user_seller = carbon_get_user_meta( $user_id, 'user_seller' );
	if ( ! $user_seller ) {
		the_user_history();
	} else {
		the_header_cabinet();
		$var              = variables();
		$set              = $var['setting_home'];
		$assets           = $var['assets'];
		$url              = $var['url'];
		$url_home         = $var['url_home'];
		$admin_ajax       = $var['admin_ajax'];
		$permalink        = get_the_permalink() ?: $url;
		$route            = $_GET['route'] ?? '';
		$management_user  = $_GET['management_user'] ?? '';
		$categories       = get_terms( array(
			'taxonomy'   => 'categories',
			'hide_empty' => false,
			'parent'     => 0,
		) );
		$_order           = $_GET['order'] ?? '';
		$_orderby         = $_GET['orderby'] ?? '';
		$_title           = $_GET['title'] ?? '';
		$_categories      = $_GET['categories'] ?? '';
		$days_count       = carbon_get_theme_option( 'days_count' ) ?: 30;
		$posts_per_page   = get_option( 'posts_per_page' );
		$paged            = $_GET['pagenumber'] ?? 1;
		$post_status      = $_GET['post_status'] ?? 'publish';
		$current_url      = get_current_url();
		$management_users = sellers_management( $user_id );
		$args             = array(
			'post_type'      => 'products',
			'posts_per_page' => (int) $posts_per_page,
			'posts_status'   => 'publish',
			'paged'          => $paged,
			'post_status'    => $post_status,
			'author__in'     => array( $user_id ),
		);
		$is_active_manager = false;
		if ( $management_user ) {
			$is_active_manager = is_active_manager( $user_id, $management_user );
			if ( $is_active_manager ) {
				$args['author__in'] = array( (int) $management_user );
			}
		}
		if ( $_orderby ) {
			$args['orderby'] = $_orderby;
		}
		if ( $_order ) {
			$args['order'] = $_order;
		}
		if ( $_categories ) {
			$terms     = ! is_array( $_categories ) ? explode( ',', $_categories ) : $_categories;
			$tax_query = array(
				'taxonomy' => 'categories',
				'field'    => 'id',
				'terms'    => $terms,
			);
			if ( isset( $args['tax_query'] ) ) {
				$args['tax_query'][] = $tax_query;
			} else {
				$args['tax_query'] = array( $tax_query );
			}
		}
		if ( $_title ) {
			$args['s'] = $_title;
		}
		$query = new WP_Query( $args );
		?>
        <div class="create-item-main">
            <form action="<?php echo $permalink; ?>" method="get" class="sort-wrap ">
                <input type="hidden" name="route" value="<?php echo $route; ?>">
                <input type="hidden" name="management_user" value="<?php echo $management_user; ?>">
                <input type="hidden" name="order" value="desc">
                <input type="hidden" name="post_status" value="<?php echo $post_status; ?>">
                <div class="search-wrap">
                    <div>
                        <input class="input_st search_input"
                               type="text" required="required"
                               name="title"
                               value="<?php echo $_title; ?>"
                               placeholder="Шукати за заголовком"/>
                    </div>
                </div>
                <div class="sort-group">
                    <div class="form-horizontal">
						<?php if ( $categories ): ?>
                            <div class="half">
                                <select title="" class="select_st trigger-on-change" name="categories">
                                    <option value="">Будь-яка категорія</option>
									<?php foreach ( $categories as $category ): ?>
                                        <option
											<?php echo $category->term_id == $_categories ? 'selected' : ''; ?>
                                                value="<?php echo $category->term_id; ?>">
											<?php echo $category->name; ?>
                                        </option>
									<?php endforeach; ?>
                                </select>
                            </div>
						<?php endif; ?>
                        <div class="half">
                            <select title="" class="select_st sort-select trigger-on-change" name="orderby">
                                <option value="">Сортувати</option>
                                <option
									<?php echo $_order == 'desc' && $_orderby == 'date' ? 'selected' : ''; ?>
                                        value="date" data-order="desc">
                                    Спочатку новіші
                                </option>
                                <option
									<?php echo $_order == 'asc' && $_orderby == 'date' ? 'selected' : ''; ?>
                                        value="date" data-order="asc">
                                    Спочатку старіші
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
			<?php if ( $management_users ): ?>
                <div class="sort-users">
                    <div class="form-description__item-title">Керування господарством</div>
                    <div class="sort-users__form">
                        <form action="<?php echo $permalink; ?>" method="get">
                            <input type="hidden" name="route" value="<?php echo $route; ?>">
                            <input type="hidden" name="order" value="desc">
                            <input type="hidden" name="post_status" value="<?php echo $post_status; ?>">
                            <select class="select_st trigger-on-change" name="management_user">
                                <option value="">Виберіть користувача</option>
								<?php foreach ( $management_users as $user ):
									$ID = $user->ID;
									$is_active_manager = is_active_manager( $user_id, $ID );
									if ( $user->allcaps['edit_posts'] && $is_active_manager ):
										?>
                                        <option value="<?php echo $ID; ?>"
											<?php echo ( $management_user == $ID ) ? 'selected' : ''; ?>
                                                data-is_active="<?php echo $is_active_manager; ?>"
                                                data-val="<?php echo $user->allcaps['edit_posts']; ?>">
											<?php echo $user->first_name ? $user->first_name . ' ' . $user->last_name : $user->user_nicename; ?>
                                        </option>
									<?php endif; endforeach; ?>
                            </select>
                        </form>
                    </div>
                </div>
			<?php endif; ?>
            <ul class="nav-announcement">
                <li>
                    <a <?php echo $post_status == 'publish' ? 'class="active"' : ''; ?>
                            href="<?php echo $permalink . '?route=' . $route . '&post_status=publish&management_user=' . $management_user; ?>">
                        Активні
                    </a>
                </li>
                <li>
                    <a <?php echo $post_status == 'pending' ? 'class="active"' : ''; ?>
                            href="<?php echo $permalink . '?route=' . $route . '&post_status=pending&management_user=' . $management_user; ?>">
                        Очікуючі
                    </a>
                </li>
                <li>
                    <a <?php echo $post_status == 'archive' ? 'class="active"' : ''; ?>
                            href="<?php echo $permalink . '?route=' . $route . '&post_status=archive&management_user=' . $management_user; ?>">
                        Неактивні
                    </a>
                </li>
                <li>
                    <a <?php echo $post_status == 'draft' ? 'class="active"' : ''; ?>
                            href="<?php echo $permalink . '?route=' . $route . '&post_status=draft&management_user=' . $management_user; ?>">
                        Відхилені
                    </a>
                </li>
            </ul>
            <div class="all-list"> Всього оголошень: <?php echo $query->found_posts; ?></div>
			<?php if ( $query->have_posts() ) : ?>
                <div class="select-all">
                    <label class="check_st_item"><input class="check_st check_all"
                                                        type="checkbox"/><span> </span></label>
                    <div class="select-all__text">
                        Оберіть усі потрібні оголошення зі списку, щоб застосувати до них однакові дії
                    </div>
                </div>
                <ul class="select-all-link">

                    <li>
                        <a class="add-products-continue" href="#">
                            Автопродовження
                            <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                 style="enable-background:new 0 0 15 15"
                                 viewBox="0 0 15 15">
                                    <path d="m11.4 12.8-.5-1.7c-3.7 3.2-6.9.3-7.4-.6l.3-.2c.1-.1.2-.2.2-.4 0-.1-.1-.3-.2-.3L1.1 8.5H.9c-.1 0-.2 0-.2.1-.1 0-.2.1-.2.2v2.8c0 .1.1.3.2.3 0 .1.1.1.2.1s.1 0 .2-.1l.1-.1c2.4 3.7 7.8 4 10.8 1.6-.3 0-.5-.3-.6-.6zM2.6 8.5c-.3-1.6.8-4.8 4.5-5.2 0 .1.1.3.2.3h.2c.1 0 .2 0 .2-.1L10.1 2c.1-.1.2-.2.2-.3 0-.1-.1-.3-.2-.3L7.7.1C7.6 0 7.6 0 7.5 0h-.2c-.2.1-.2.2-.2.4v.4C3.5.8-.2 4 0 8.3c0 0 .5-.5.9-.5.3 0 .4.1 1.7.7zM15 10.7c0-.1-.1-.3-.3-.3l-.3-.1c.9-2.3.6-6.4-3.5-8.6v.2c0 .3-.2.7-.5.9l-1.3.8c1.2.3 4.1 2.5 2.8 6l-.2-.1h-.1c-.1 0-.2 0-.3.1-.1.1-.1.2-.1.4l.8 2.6c0 .1.1.2.3.3h.1c.1 0 .2 0 .3-.1l2.2-1.8c.1-.1.1-.2.1-.3z"
                                          style="fill:#4d76ff"/>
                                </svg>
                        </a>
                    </li>
                    <li>
                        <a class="deactivate-products" href="#">
                            Деактивувати
                            <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                 style="enable-background:new 0 0 15 13"
                                 viewBox="0 0 15 13">
                                    <path d="M7.5 2.7c1.9 0 3.4 1.5 3.4 3.4 0 .4-.1.9-.3 1.2l2 2c1-.9 1.8-2 2.3-3.2-1.1-3-4-5.1-7.4-5.1-1 0-1.8.2-2.7.5L6.2 3c.4-.2.9-.3 1.3-.3zM.7.9l1.8 1.8C1.4 3.6.5 4.8 0 6.2c1.2 3 4.1 5.1 7.5 5.1 1 0 2.1-.2 3-.5l2.3 2.3.9-.9L1.6 0 .7.9zm3.8 3.8 1 1v.5c0 1.2.9 2.1 2.1 2.1.1 0 .3 0 .5-.1l1 1c-.5.3-1 .4-1.5.4-1.9 0-3.4-1.5-3.4-3.4-.1-.6 0-1.1.3-1.5zm2.9-.6 2.1 2.1v-.1c0-1.2-.9-2.1-2.1-2 .1 0 .1 0 0 0z"
                                          style="fill:#fc3636"/>
                                </svg>
                        </a>
                    </li>
                </ul>
                <div class="select-product container-js">
					<?php while ( $query->have_posts() ) :
						$query->the_post();
						the_user_product();
					endwhile;
					?>
                </div>
                <div class="btn_center pagination-js">
					<?php echo __get_more_link( $query->max_num_pages ); ?>
                </div>
			<?php else: ?>
                <div class="no-announcement">
                    <div class="no-announcement__media">
                        <img src="<?php echo $assets; ?>img/help2.webp" alt=""/>
                    </div>
                    <div class="no-announcement__title">
                        Оголошення не знайдені
                        <br>
                    </div>
					<?php if ( $create_link = get_create_link() ): ?>
                        <a class="btn_st" href="<?php echo $create_link; ?>">
                    <span>Додати оголошення<svg xmlns="http://www.w3.org/2000/svg"
                                                xml:space="preserve"
                                                style="enable-background:new 0 0 13 13"
                                                viewBox="0 0 13 13">
                                    <path d="M11.8 5.3H7.9c-.1 0-.2-.1-.2-.2V1.2C7.7.5 7.1 0 6.5 0S5.3.5 5.3 1.2v3.9c0 .1-.1.2-.2.2H1.2C.5 5.3 0 5.9 0 6.5s.5 1.2 1.2 1.2h3.9c.1 0 .2.1.2.2v3.9c0 .6.5 1.2 1.2 1.2s1.2-.5 1.2-1.2V7.9c0-.1.1-.2.2-.2h3.9c.6 0 1.2-.5 1.2-1.2s-.5-1.2-1.2-1.2z"
                                          style="fill:#fff"/>
                                </svg></span>
                        </a>
					<?php endif; ?>
                </div>
			<?php endif; ?>
        </div>
		<?php
		wp_reset_postdata();
		wp_reset_query();
	}
}

function the_user_product( $id = false ) {
	$management_user  = $_GET['management_user'] ?? '';
	$days_count       = carbon_get_theme_option( 'days_count' ) ?: 30;
	$id               = $id ?: get_the_ID();
	$img              = get_the_post_thumbnail_url( $id );
	$title            = get_the_title( $id );
	$permalink        = get_the_permalink( $id );
	$unit             = carbon_get_post_meta( $id, 'product_unit' );
	$post_status      = get_post_status( $id );
	$author_id        = get_post_field( 'post_author', $id );
	$company_name     = carbon_get_post_meta( $id, 'product_company_name' ) ?: carbon_get_user_meta( $author_id, 'user_company_name' );
	$city             = carbon_get_post_meta( $id, 'product_city' );
	$auto_continue    = carbon_get_post_meta( $id, 'product_auto_continue' );
	$min_order        = carbon_get_post_meta( $id, 'product_min_order' );
	$max_value        = carbon_get_post_meta( $id, 'product_max_value' );
	$delivery_methods = carbon_get_post_meta( $id, 'product_delivery_methods' );
	$created_date     = get_the_date( 'd.m.Y', $id );
	$end_date         = date( 'd.m.Y', strtotime( "+$days_count day", strtotime( get_the_date( 'Y-m-d', $id ) ) ) );
	$cls              = '';
	if ( $post_status != 'publish' ) {
		$cls .= ' no-active';
	}
	if ( $post_status == 'draft'  ) {
		$cls .= ' not-active';
	}
	$personal_page = carbon_get_theme_option( 'personal_area_page' );
	$_url          = $personal_page ? get_the_permalink( $personal_page[0]['id'] ) : $url;
	?>
    <div class="select-product__item <?php echo $cls; ?> " data-id="<?php echo $id; ?>">
        <label class="check_st_item">
            <input class="check_st check_all_sub checked-product"
                   data-id="<?php echo $id; ?>" type="checkbox"/>
            <span></span>
        </label>
        <div class="select-product__item-main">
            <div class="select-product__item-left">
                <div class="select-product__item-media">
					<?php if ( $img ): ?>
                        <img
                                src="<?php echo $img; ?>"
                                alt="<?php echo $title; ?>"
                        />
					<?php endif; ?>
                </div>
                <div class="select-product__item-info">
                    <div class="select-product__item-id">
                        ID:<?php echo $id; ?>
                    </div>
                    <div class="select-product__item-suptitle">
						<?php echo $company_name; ?>
                    </div>
                    <a class="product-item__title" href="<?php echo $permalink; ?>">
						<?php echo $title; ?>
                    </a>
                    <div class="select-product__item-place">
						<?php echo $city; ?>
                    </div>
                    <div class="select-product__item-info-bot">
                        <div class="select-product__item-date">
                            <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                 style="enable-background:new 0 0 15 15" viewBox="0 0 15 15">
                                                    <path d="M15 2v2.1H0V2c0-.4.2-.8.5-1S1.2.6 1.6.6h1.1v1c0 .4.2.8.5 1.1.3.3.7.5 1.1.5.4 0 .8-.2 1.1-.5.3-.3.5-.7.5-1.1v-1h3.2v1c0 .4.2.8.5 1.1.3.3.7.5 1.1.5.4 0 .8-.2 1.1-.5.3-.3.5-.7.5-1.1v-1h1.1c.4 0 .8.2 1.1.4.3.2.5.6.5 1zM0 5.2v8.3c0 .4.2.8.5 1.1.3.2.7.4 1.1.4h11.8c.4 0 .8-.2 1.1-.4.3-.3.5-.7.5-1.1V5.2H0zm4.8 7.2c0 .1-.1.3-.2.4s-.2.2-.4.2h-1c-.1 0-.3-.1-.4-.2-.1-.1-.2-.2-.2-.4v-1c0-.1.1-.3.2-.4.1-.1.2-.2.4-.2h1.1c.1 0 .3.1.4.2.1.1.2.2.2.4v1zm0-3.6c0 .1-.1.3-.2.4 0 .1-.2.1-.3.1H3.2c-.1 0-.3-.1-.4-.2-.1 0-.1-.2-.1-.3v-1c0-.1.1-.3.2-.4.1-.1.2-.2.4-.2h1.1c.1 0 .3.1.4.2.1.1.2.2.2.4v1zm3.8 3.6c0 .1-.1.3-.2.4s-.2.1-.4.1H7c-.1 0-.3-.1-.4-.2s-.2-.2-.2-.4v-1c0-.1.1-.3.2-.4.1-.1.2-.2.4-.2h1c.1 0 .3.1.4.2.1.1.2.2.2.4v1.1zm0-3.6c0 .1-.1.3-.2.4-.1.1-.2.1-.4.1H7c-.1 0-.3-.1-.4-.2-.1 0-.2-.2-.2-.3v-1c0-.1.1-.3.2-.4.1-.1.2-.2.4-.2h1c.1 0 .3.1.4.2.1.1.2.2.2.4v1zm3.7 0c0 .1-.1.3-.2.4-.1.1-.2.2-.4.2h-1.1c-.1 0-.3-.1-.4-.2-.1-.1-.2-.2-.2-.4v-1c0-.1.1-.3.2-.4.1-.1.2-.2.4-.2h1.1c.1 0 .3.1.4.2.1.1.2.2.2.4v1z"
                                                          style="fill:#4d76ff"/>
                                <path d="M4.8.5v1c0 .1-.1.3-.2.4 0 .1-.2.2-.3.2-.2 0-.3-.1-.4-.2-.1-.1-.1-.2-.1-.3v-1c0-.1.1-.3.2-.4 0-.1.1-.2.3-.2.1 0 .3.1.4.2.1 0 .1.2.1.3zM11.2.5v1c0 .1-.1.3-.2.4 0 .1-.1.2-.3.2-.1 0-.3-.1-.4-.2-.1-.1-.2-.2-.2-.4v-1c0-.1.1-.3.2-.4.1 0 .3-.1.4-.1.1 0 .3.1.4.2.1 0 .1.2.1.3z"
                                      style="fill:#4d76ff"/>
                                                </svg>
                            <span><?php echo $created_date; ?> - <?php echo $end_date; ?></span>
                        </div>
                        <div class="select-product__item-continue">
                            <label class="switch_st">
                                <input type="checkbox" class="change-auto-continue"
                                       value="<?php echo $id; ?>"
									<?php echo $auto_continue ? 'checked' : ''; ?>/>
                                <span></span>
                            </label>
                            <span>Автопродовження</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="select-product__item-right">
                <ul class="select-product__info">
					<?php if ( $delivery_methods ):
						$delivery_methods_str = implode( ', <br>', $delivery_methods );
						$delivery_methods_str = str_replace(
							get_delivery_methods_types(),
							array( '', '', '' ),
							$delivery_methods_str
						);
						?>
                        <li>
                            <span>Доставка: </span>
                            <strong><?php echo $delivery_methods_str; ?></strong>
                        </li>
					<?php endif; ?>
					<?php if ( $min_order ): ?>
                        <li>
                            <span>Мінімальне замовлення:</span>
                            <strong>від <?php echo $min_order . ' ' . $unit; ?></strong>
                        </li>
					<?php endif; ?>
					<?php if ( $max_value ): ?>
                        <li>
                            <span>В наявності: </span>
                            <strong><?php echo $max_value . ' ' . $unit; ?></strong>
                        </li>
					<?php endif; ?>
                </ul>
                <div class="product-item__price">
					<?php echo get_price_html( $id ); ?>
                </div>
                <div class="select-product__item-right-bot">
                    <div class="tog-hide-info"></div>
                </div>
            </div>
            <div class="select-product__item-hide">
                <div class="select-product__item-hide-content">
                    <ul class="management-link">
                        <li>
                            <a class="move-to-edit <?php echo $cls; ?>"
                               data-id="<?php echo $id; ?>"
                               href="<?php echo get_edit_link( $id ); ?>">
                                <span>Редагувати<svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve"
                                            style="enable-background:new 0 0 15 15" viewBox="0 0 15 15">
                                                            <defs>
                                                                <path id="a" d="M0 0h15v15H0z"/>
                                                            </defs>
                                                            <clipPath id="b">
                                                                <use xlink:href="#a" style="overflow:visible"/>
                                                            </clipPath>
                                                            <g style="clip-path:url(#b)">
                                                                <path d="M7.9 2.9.7 10.1c-.3.3-.4.6-.5 1L0 13.6c0 .2 0 .4.1.5.1.2.1.3.3.5.1.1.3.2.4.3.1.1.3.1.5.1h.1l2.5-.3c.4 0 .7-.2 1-.5L12.1 7 7.9 2.9zM14.5 2.3 12.7.5c-.2-.2-.3-.3-.5-.4-.2-.1-.4-.1-.7-.1-.2 0-.4 0-.6.1-.2.1-.4.2-.5.4L8.7 2.2l4.1 4.1 1.7-1.7c.2-.2.3-.3.4-.5.1-.2.1-.4.1-.6s0-.4-.1-.6c-.1-.3-.2-.4-.4-.6z"
                                                                      style="fill:#4d76ff"/>
                                                            </g>
                                                        </svg></span>
                            </a>
                        </li>
                        <li>
                            <a class="<?php echo ( $post_status != 'publish' ) ? 'disable' : ''; ?>"
                               href="<?php echo $_url . '?route=packages&product=' . $id; ?>">
                                <span>Рекламувати<svg
                                            xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                            style="enable-background:new 0 0 14 15" viewBox="0 0 14 15">
                                                            <path d="m6.2 11.3-1.4 1.4c-.6.6-1.5.6-2.1 0l-1.4-1.4c-.6-.6-.6-1.5 0-2.1l1.4-1.4 3.5 3.5zM13 7.4 6.6 1C6.5.9 6.3.9 6.2.9c-.2 0-.3.1-.4.2L2.9 6.6l4.5 4.5 5.4-2.9c.2-.1.3-.2.3-.4 0-.1 0-.3-.1-.4zm-11 6L.6 12c-.2-.2-.4-.4-.5-.7-.2.7-.1 1.5.5 2.1.4.4.9.6 1.4.6.2 0 .5 0 .7-.1-.3-.1-.5-.3-.7-.5zm5.8-.5L7 12l-1.4 1.4c-.1.1-.3.2-.4.3l.9.9c.2.3.5.4.8.4s.7-.1.9-.4c.5-.5.5-1.2 0-1.7zM9.5 1.5v-1C9.5.2 9.3 0 9 0s-.5.2-.5.5v1c0 .3.2.5.5.5s.5-.2.5-.5zM14 5c0-.3-.2-.5-.5-.5h-1c-.3 0-.5.2-.5.5s.2.5.5.5h1c.3 0 .5-.2.5-.5zm-2.6-1.6 1-1c.2-.2.2-.5 0-.7-.2-.2-.5-.2-.7 0l-1 1c-.2.2-.2.5 0 .7.1.1.2.1.4.1 0 0 .2 0 .3-.1z"
                                                                  style="fill:#02ad51"/>
                                                        </svg></span>
                            </a>
                        </li>
						<?php if ( $post_status == 'archive' ): ?>
                            <li>
                                <a href="#"
                                   class="activating-product"
                                   data-id="<?php echo $id; ?>"> <span>Aктивувати<svg
                                                xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                                style="enable-background:new 0 0 15 10" viewBox="0 0 15 10">
                                                            <path d="M7.5 7.4c1.3 0 2.3-1.1 2.3-2.4 0-1.3-1-2.4-2.3-2.4S5.2 3.7 5.2 5c0 1.3 1 2.4 2.3 2.4z"
                                                                  style="fill:#fc3636"/>
                                                            <path d="M14.8 4.3C13 2.1 10.3 0 7.5 0S2 2.1.2 4.3c-.3.4-.3.9 0 1.3.4.6 1.4 1.6 2.6 2.6 3.1 2.4 6.1 2.4 9.3 0 1.2-.9 2.2-2 2.6-2.6.4-.3.4-.9.1-1.3zM7.5 1.7c1.8 0 3.2 1.5 3.2 3.3 0 1.8-1.5 3.3-3.2 3.3-1.8 0-3.2-1.5-3.2-3.3 0-1.8 1.4-3.3 3.2-3.3z"
                                                                  style="fill:#fc3636"/>
                                                        </svg></span></a>
                            </li>
						<?php endif; ?>
                    </ul>
                    <a class="chat-link " href="<?php echo get_correspondences_link( $id ); ?>"
                    >
                        <span>Чат<svg xmlns="http://www.w3.org/2000/svg"
                                      xml:space="preserve"
                                      style="enable-background:new 0 0 14 13"
                                      viewBox="0 0 14 13">
                                                    <path d="M7 0C3.1 0 0 2.7 0 6.1 0 7.5.6 8.9 1.6 10c.2.8 0 1.7-.7 2.3-.2.3 0 .7.3.7 1.2 0 2.3-.5 3.1-1.3.9.3 1.8.5 2.7.5 3.8 0 7-2.7 7-6.1S10.8 0 7 0zM3.7 7.3c-.7 0-1.2-.5-1.2-1.2s.6-1.2 1.2-1.2c.7 0 1.3.5 1.3 1.2s-.6 1.2-1.3 1.2zm3.3 0c-.7 0-1.2-.5-1.2-1.2S6.3 4.9 7 4.9s1.2.5 1.2 1.2S7.7 7.3 7 7.3zm3.3 0c-.7 0-1.2-.5-1.2-1.2s.6-1.2 1.2-1.2c.7 0 1.2.5 1.2 1.2s-.5 1.2-1.2 1.2z"
                                                          style="fill:#4d76ff"/>
                                                </svg></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
	<?php
}

function get_correspondences_link( $product_id ) {
	$var           = variables();
	$set           = $var['setting_home'];
	$assets        = $var['assets'];
	$url           = $var['url'];
	$url_home      = $var['url_home'];
	$admin_ajax    = $var['admin_ajax'];
	$personal_page = carbon_get_theme_option( 'personal_area_page' );
	$_url          = $personal_page ? get_the_permalink( $personal_page[0]['id'] ) : $url;

	return $_url . '?route=message&product_id=' . $product_id;
}

function get_edit_link( $id ) {
	$management_user    = $_GET['management_user'] ?? '';
	$personal_area_page = carbon_get_theme_option( 'personal_area_page' );
	if ( $personal_area_page ) {
		$link = get_the_permalink( $personal_area_page[0]['id'] ) . '?route=edit&product=' . $id;
		if ( $management_user ) {
			$link .= '&management_user=' . $management_user;
		}

		return $link;
	}

	return false;
}

function get_create_link() {
	$var                = variables();
	$set                = $var['setting_home'];
	$assets             = $var['assets'];
	$url                = $var['url'];
	$url_home           = $var['url_home'];
	$user_id            = get_current_user_id();
	$management_user    = $_GET['management_user'] ?? '';
	$personal_area_page = carbon_get_theme_option( 'personal_area_page' );
	if ( $personal_area_page ) {
		return get_the_permalink( $personal_area_page[0]['id'] ) . '?route=create&management_user=' . $management_user;
	}

	return false;
}

function the_header_cabinet() {
	$create_link   = get_create_link();
	$route         = $_GET['route'] ?? '';
	$user_id       = get_current_user_id();
	$user_seller   = carbon_get_user_meta( $user_id, 'user_seller' );
	$var           = variables();
	$set           = $var['setting_home'];
	$assets        = $var['assets'];
	$url           = $var['url'];
	$url_home      = $var['url_home'];
	$admin_ajax    = $var['admin_ajax'];
	$personal_page = carbon_get_theme_option( 'personal_area_page' );
	$_url          = $personal_page ? get_the_permalink( $personal_page[0]['id'] ) : $url;
	?>
    <div class="header-cabinet">
        <div class="header-cabinet__title">
			<?php echo get_personal_title(); ?>
        </div>
        <div class="header-cabinet__btn">
			<?php
			if ( $user_seller ):
				if ( $create_link ):
					?>
                    <a class="btn_st" href="<?php echo $create_link; ?>"><span>Додати оголошення<svg
                                    xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                    style="enable-background:new 0 0 13 13" viewBox="0 0 13 13">
                                    <path d="M11.8 5.3H7.9c-.1 0-.2-.1-.2-.2V1.2C7.7.5 7.1 0 6.5 0S5.3.5 5.3 1.2v3.9c0 .1-.1.2-.2.2H1.2C.5 5.3 0 5.9 0 6.5s.5 1.2 1.2 1.2h3.9c.1 0 .2.1.2.2v3.9c0 .6.5 1.2 1.2 1.2s1.2-.5 1.2-1.2V7.9c0-.1.1-.2.2-.2h3.9c.6 0 1.2-.5 1.2-1.2s-.5-1.2-1.2-1.2z"
                                          style="fill:#fff"></path>
                                </svg></span> </a>
				<?php
				endif;
			endif;
			?>
            <a class="btn_notification" href="<?php echo $_url . '?route=notifications' ?>">
                <span class="<?php echo ( get_notifications_count() == 0 ) ? 'hidden' : ''; ?>"> </span>
                <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" style="enable-background:new 0 0 17 18"
                     viewBox="0 0 17 18">
                                <path d="M8.5 0C4.8 0 1.8 2.7 1.8 6.1v4.8l-1.5 2c-.5.8-.3 1.8.5 2.3.3.2.6.3 1 .3h13.4c1 0 1.8-.8 1.8-1.7 0-.3-.1-.6-.3-.9l-1.5-2V6.1c0-3.4-3-6.1-6.7-6.1zM8.5 18c1.1 0 2.1-.7 2.5-1.6H6c.3.9 1.4 1.6 2.5 1.6z"
                                      style="fill:#fff"></path>
                            </svg>
            </a>
        </div>
    </div>
	<?php
}

function the_user_history() {
	the_header_cabinet();
	$var           = variables();
	$set           = $var['setting_home'];
	$assets        = $var['assets'];
	$url           = $var['url'];
	$url_home      = $var['url_home'];
	$admin_ajax    = $var['admin_ajax'];
	$user_id       = get_current_user_id();
	$permalink     = get_the_permalink() ?: $url;
	$route         = $_GET['route'] ?? '';
	$subpage       = $_GET['subpage'] ?? '';
	$personal_page = carbon_get_theme_option( 'personal_area_page' );
	$_url          = $personal_page ? get_the_permalink( $personal_page[0]['id'] ) : $url;
	$orders        = array();
	$categories    = get_terms( array(
		'taxonomy'   => 'categories',
		'hide_empty' => false,
		'parent'     => 0,
	) );
	$user_seller   = carbon_get_user_meta( $user_id, 'user_seller' );
	$_order        = $_GET['order'] ?? '';
	$_orderby      = $_GET['orderby'] ?? '';
	$_title        = $_GET['title'] ?? '';
	$_categories   = $_GET['categories'] ?? '';
	$args          = array(
		'post_author'    => $user_id,
		'post_type'      => 'orders',
		'posts_per_page' => - 1,
		'post_status'    => array( 'publish', 'pending', 'draft' ),
	);
	if ( $subpage == '' ) {
		$args['author__in'] = array( $user_id );
	} elseif ( $subpage == 'sales' ) {
		$orders = get_sellers_orders( $user_id, $_title );
	}
	if ( $_orderby ) {
		$args['orderby'] = $_orderby;
	}
	if ( $_order ) {
		$args['order'] = $_order;
	}

	?>
    <div class="create-item-main">
        <form class="sort-wrap hidden-opacity">
            <input type="hidden" name="route" value="<?php echo $route; ?>">
            <input type="hidden" name="subpage" value="<?php echo $subpage; ?>">
            <input type="hidden" name="order" value="desc">
            <div class="search-wrap">
                <input class="input_st search_input" type="text" required="required"
                       name="title"
                       value="<?php echo $_title; ?>"
                       placeholder="Шукати за заголовком"/>
            </div>
            <div class="sort-group">
                <div class="form-horizontal">
					<?php if ( $categories ): ?>
                        <div class="half">
                            <select title="" class="select_st trigger-on-change" name="categories">
                                <option value="">Будь-яка категорія</option>
								<?php foreach ( $categories as $category ): ?>
                                    <option
										<?php echo $category->term_id == $_categories ? 'selected' : ''; ?>
                                            value="<?php echo $category->term_id; ?>">
										<?php echo $category->name; ?>
                                    </option>
								<?php endforeach; ?>
                            </select>
                        </div>
					<?php endif; ?>
                    <div class="half">
                        <select title="" class="select_st sort-select trigger-on-change" name="orderby">
                            <option value="">Сортувати</option>
                            <option
								<?php echo $_order == 'desc' && $_orderby == 'date' ? 'selected' : ''; ?>
                                    value="date" data-order="desc">Спочатку новіші
                            </option>
                            <option
								<?php echo $_order == 'asc' && $_orderby == 'date' ? 'selected' : ''; ?>
                                    value="date" data-order="asc">Спочатку старіші
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </form>
		<?php if ( $user_seller ): ?>
            <ul class="nav-announcement">
                <li>
                    <a class="<?php echo $subpage == '' ? 'active' : ''; ?>"
                       href="<?php echo $_url . '?route=history'; ?>">
                        Мої покупки
                    </a>
                </li>
                <li>
                    <a
                            class="<?php echo $subpage == 'sales' ? 'active' : ''; ?>"
                            href="<?php echo $_url . '?route=history&subpage=sales'; ?>">
                        Мої продажі
                    </a>
                </li>
            </ul>
		<?php endif; ?>
		<?php
		if ( $subpage == 'sales' && empty( $orders ) ):
			?>
            <div class="no-announcement">
                <div class="no-announcement__media">
                    <img src="<?php echo $assets; ?>img/help2.webp" alt=""/>
                </div>
                <div class="no-announcement__title">Тут пусто ...</div>
                <div class="no-announcement__subtitle">Наразі немає жодної покупки</div>
            </div>
		<?php
		else:
			if ( $_categories ):
				$post__in = get_orders_by_product_categories( $_categories, $_title );
				if ( $post__in ):
					$post__in = ! empty( $orders ) ? array_intersect( $orders, $post__in ) : $post__in;
					$args['post__in'] = $post__in;
					$query = new WP_Query( $args );
					if ( $query->have_posts() && ! empty( $post__in ) ) : ?>
                        <div class="orders-main">
							<?php
							while ( $query->have_posts() ) :
								$query->the_post();
								the_order_item();
							endwhile;
							?>
                        </div>
					<?php else: ?>
                        <div class="no-announcement">
                            <div class="no-announcement__media">
                                <img src="<?php echo $assets; ?>img/help2.webp" alt=""/>
                            </div>
                            <div class="no-announcement__title">Тут пусто ...</div>
                            <div class="no-announcement__subtitle">Наразі немає жодної покупки</div>
                        </div>
					<?php endif;
				else: ?>
                    <div class="no-announcement">
                        <div class="no-announcement__media">
                            <img src="<?php echo $assets; ?>img/help2.webp" alt=""/>
                        </div>
                        <div class="no-announcement__title">Тут пусто ...</div>
                        <div class="no-announcement__subtitle">Наразі немає жодної покупки</div>
                    </div>
				<?php
				endif;
			else:
				if ( ! empty( $orders ) ) {
					$args['post__in'] = $orders;
				}
				$query = new WP_Query( $args );
				if ( $query->have_posts() ) : ?>
                    <div class="orders-main">
						<?php
						while ( $query->have_posts() ) :
							$query->the_post();
							the_order_item();
						endwhile;
						?>
                    </div>
				<?php else: ?>
                    <div class="no-announcement">
                        <div class="no-announcement__media">
                            <img src="<?php echo $assets; ?>img/help2.webp" alt=""/>
                        </div>
                        <div class="no-announcement__title">Тут пусто ...</div>
                        <div class="no-announcement__subtitle">Наразі немає жодної покупки</div>
                    </div>
				<?php endif; ?>
			<?php endif; endif; ?>
    </div>
	<?php
	wp_reset_postdata();
	wp_reset_query();
}

function the_order_item( $id = false ) {
	$id               = $id ?: get_the_ID();
	$user_id          = get_current_user_id();
	$author_id        = get_post_field( 'post_author', $id );
	$post_status      = get_post_status( $id );
	$delivery_status  = carbon_get_post_meta( $id, 'delivery_status' );
	$order_sum        = carbon_get_post_meta( $id, 'order_sum' );
	$currency         = carbon_get_theme_option( 'currency' );
	$order_cart       = carbon_get_post_meta( $id, 'order_cart' );
	$delivery_method  = carbon_get_post_meta( $id, 'order_delivery_method' );
	$delivery_address = carbon_get_post_meta( $id, 'order_delivery_address' );
	$status           = '<span class="order-status">В процесі</span>';
	if ( $post_status == 'draft' ) {
		$status = '<span class="order-status none">Скасовано</span>';
	} elseif ( $delivery_status == 'delivered' && $post_status == 'publish' ) {
		$status = '<span class="order-status done">Доставлено</span>';
	}
	?>
    <div class="orders-main__item">
        <div class="orders-main__item-top">
            <div class="orders-main__item-info hidden-opacity">
				<?php if ( $user_id == $author_id ): ?>
					<?php echo $status; ?>
				<?php else: ?>
                    <select title="" class="select_st change-order-status " data-id="<?php echo $id; ?>">
                        <option value="" <?php echo $delivery_status == '' ? 'selected' : ''; ?> disabled>
                            Підтвердіть замовлення
                        </option>
                        <option
							<?php echo $delivery_status == 'in_process' ? 'selected' : ''; ?>
                                value="in_process">
                            В процесі
                        </option>
                        <option
							<?php echo $delivery_status == 'delivered' ? 'selected' : ''; ?>
                                value="delivered">
                            Доставлено
                        </option>
                    </select>
				<?php endif; ?>
                <div class="orders-main__item-info-main">
                    Замовлення № <?php echo $id; ?>, <?php echo get_the_date( 'd.m.Y H:i' ); ?>
                </div>
            </div>
            <div class="orders-main__item-price">
                <div class="product-item__price">
                    <strong><?php echo $order_sum; ?></strong><?php echo $currency; ?>
                </div>
            </div>
            <div class="orders-main__item-remove">
				<?php if ( $post_status == 'publish' && $delivery_status == 'in_process' && ( $user_id == $author_id ) ): ?>
                    <a class="remove-cart cancel-order" data-id="<?php echo $id; ?>" href="#">
                        <span>Скасувати замовлення </span>
                        <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                             style="enable-background:new 0 0 9.9 9.9" viewBox="0 0 9.9 9.9">
                                            <path d="M7.9.3 5.1 3.1c-.1.1-.2.1-.3 0L2 .3C1.5-.2.8-.2.4.3s-.5 1.2 0 1.6l2.8 2.8c.1.1.1.2 0 .3L.3 7.9c-.5.5-.5 1.2 0 1.6s1.2.5 1.7 0l2.8-2.8c.1-.1.2-.1.3 0l2.8 2.8c.5.5 1.2.5 1.6 0s.5-1.2 0-1.6L6.7 5.1c0-.1-.1-.2 0-.3L9.5 2c.5-.5.5-1.2 0-1.6S8.3-.1 7.9.3z"
                                                  style="fill:#fc3636"/>
                                        </svg>
                    </a>
				<?php endif; ?>
            </div>
            <div class="orders-main__item-right">
                <div class="orders-main__item-media">
					<?php if ( $order_cart ): if ( $img = $order_cart[0]['image'] ): ?>
                        <img src="<?php _u( $img ); ?>" alt=""/>
					<?php endif; endif; ?>
                </div>
                <div class="tog-hide-order"></div>
            </div>
        </div>
		<?php if ( $order_cart ): ?>
            <div class="orders-main__item-hide">
				<?php foreach ( $order_cart as $item ):
					$_id = $item['id'];
					if ( $_id && get_post( $_id ) ):
						$img = $item['image'];
						$author_id = get_post_field( 'post_author', $id );
						$company_name = carbon_get_user_meta( $author_id, 'user_company_name' );
						$company_phone = carbon_get_user_meta( $author_id, 'user_company_phone' );
						?>
                        <div class="order-product-main">
                            <div class="order-product__item">
                                <div class="order-product__item-media">
									<?php if ( $img ): ?>
                                        <img src="<?php _u( $img ); ?>" alt=""/>
									<?php elseif ( $img = get_the_post_thumbnail_url( $_id ) ): ?>
                                        <img src="<?php echo $img; ?>" alt=""/>
									<?php endif; ?>
                                </div>
                                <div class="order-product__item-content">
                                    <a class="product-item__title" href="<?php echo get_the_permalink( $_id ); ?>">
										<?php echo $item['title'] ?: get_the_title( $_id ); ?>
                                    </a>
                                    <div class="cart-product__item-subtitle"><?php echo $company_name; ?></div>
                                    <div class="order-product__bot">
                                        <div class="product-item__price">
											<?php echo $item['price']; ?>
                                        </div>
                                        <div class="order-product__count">
                                            x<?php echo $item['qnt']; ?>
                                        </div>
                                        <div class="product-item__price">
                                            <strong>
												<?php echo $item['sum'] . $currency; ?>
                                            </strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <ul class="order-product-description">
								<?php if ( $company_phone ): ?>
                                    <li><strong>тел.<?php echo $company_phone; ?></strong></li>
								<?php endif; ?>
								<?php if ( $delivery_method ):
									$str = explode( '[', $delivery_method );
									?>
                                    <li>
                                        Доставка: <?php echo $str[0]; ?>
                                    </li>
								<?php endif; ?>
								<?php if ( $delivery_address ): ?>
                                    <li>
										<?php echo $delivery_address; ?>
                                    </li>
								<?php endif; ?>
                            </ul>
                        </div>
					<?php endif; endforeach; ?>
            </div>
		<?php endif; ?>
    </div>
	<?php
}

function the_user_verification() {
	the_header_cabinet();
	$var                = variables();
	$set                = $var['setting_home'];
	$assets             = $var['assets'];
	$url                = $var['url'];
	$url_home           = $var['url_home'];
	$admin_ajax         = $var['admin_ajax'];
	$user_id            = get_current_user_id();
	$permalink          = get_the_permalink() ?: $url;
	$route              = $_GET['route'] ?? '';
	$subpage            = $_GET['subpage'] ?? '';
	$personal_page      = carbon_get_theme_option( 'personal_area_page' );
	$_url               = $personal_page ? get_the_permalink( $personal_page[0]['id'] ) : $url;
	$user_verification  = carbon_get_user_meta( $user_id, 'user_verification' );
	$image_count        = 10;
	$verification_text  = carbon_get_post_meta( $set, 'verification_text' );
	$verification_title = carbon_get_post_meta( $set, 'verification_title' );
	$verification_image = carbon_get_post_meta( $set, 'verification_image' );
	?>
    <div class="create-item-main">
		<?php the_verification_status_html(); ?>
		<?php if ( $user_verification ): ?>
			<?php if ( $verification_text ): ?>
                <div class="discount-info">
                    <div class="discount-info__content">
                        <div class="discount-info__title">
							<?php echo $verification_title; ?>
                        </div>
                        <div class="text-group">
							<?php _t( $verification_text ); ?>
                        </div>
                    </div>
					<?php if ( $verification_image ): ?>
                        <div class="discount-info__media"
                             style="background: url(<?php _u( $verification_image ); ?>)"></div>
					<?php endif; ?>
                </div>
			<?php endif; ?>
		<?php else:
			$application = get_application( $user_id, 1 );
			$documents = carbon_get_post_meta( $application, 'application_documents' );
			if ( $application && $documents ): ?>
                <div class="cabinet-item">
                    <div class="cabinet-item__title">Фото виробництво, себе або інше</div>
                    <div class="cabinet-item__text">Якийсь документ, який потрібно завантажити</div>
                    <div class="document-gal">
						<?php foreach ( $documents as $document ): ?>
                            <a class="document-gal__item"
                               href="<?php echo $document['url'] ?: _u( $document['image'], 1 ); ?>"
                               data-fancybox="document-gal">
                                <img src="<?php echo $document['url'] ?: _u( $document['image'], 1 ); ?>" alt=""/>
                            </a>
						<?php endforeach; ?>
                    </div>
                </div>
			<?php else: ?>
                <div class="cabinet-item">
                    <form action="<?php echo $admin_ajax; ?>" method="post"
                          novalidate
                          id="verification-form"
                          class="verification-form-js form-js"
                          enctype="multipart/form-data">
                        <div class="form-description">
                            <input type="hidden" name="action" value="verification_seller">
                            <div class="form-description__item">
                                <div class="cabinet-item__title">Завантажити фото виробництво, себе або інше</div>
                                <div class="cabinet-item__text">Якийсь документ, який потрібно завантажити</div>
                                <div class="cabinet-item__photo sm-block">
                                    <div class="cabinet-item__photo-item">
                                        <label>
                                            <input required
                                                   multiple
                                                   id="photos"
                                                   data-max="<?php echo $image_count; ?>"
                                                   class="upfile_product" type="file" name="upfile[]"
                                                   accept="iimage/heic, image/png, image/jpeg, image/webp"/>
                                        </label>
                                        <img src="" alt=""/>
                                        <span class="remove-file"></span>
                                    </div>
									<?php if ( $image_count > 1 ): for ( $a = 1; $a < $image_count; $a ++ ): ?>
                                        <div class="cabinet-item__photo-item">
                                            <label for="photos"></label>
                                            <img src="" alt=""/>
                                            <span class="remove-file"></span>
                                        </div>
									<?php endfor; endif; ?>
                                </div>
                            </div>
                        </div>
                        <button class="btn_st marg_top_15" type="submit">
                            <span>Верифікуватися </span>
                        </button>
                    </form>
                </div>
			<?php endif; ?>
		<?php endif; ?>
    </div>
	<?php
}

function the_verification_status_html( $user_id = false ) {
	$var               = variables();
	$set               = $var['setting_home'];
	$assets            = $var['assets'];
	$user_id           = $user_id ?: get_current_user_id();
	$user_verification = carbon_get_user_meta( $user_id, 'user_verification' );
	if ( $user_verification ) {
		?>
        <div class="verified-attention" style="border-color:#4D76FF; background: rgba(77, 118, 255, 0.10);">
            <div class="verified-attention__media" style="background:#4D76FF;">
                <img src="<?php echo $assets; ?>img/verified-done.svg" alt=""/>
            </div>
            <div class="verified-attention__content">
                <div class="verified-attention__title" style="color:#4D76FF">Вітаємо! Ви верифікований продавець.</div>
            </div>
        </div>
		<?php
	} else {
		$application = get_application( $user_id, 1 );
		$documents   = carbon_get_post_meta( $application, 'application_documents' );
		if ( $application && $documents ):
			?>
            <div class="verified-attention" style="border-color:#02AD51; background: rgba(2, 173, 81, 0.10);">
                <div class="verified-attention__media" style="background:#02AD51;">
                    <img src="<?php echo $assets; ?>img/verified-process.svg" alt=""/>
                </div>
                <div class="verified-attention__content">
                    <div class="verified-attention__title" style="color:#02AD51">Документи на верифікації</div>
                    <div class="verified-attention__subtitle">Ви відправили документи на верифікацію</div>
                </div>
            </div>
		<?php else: ?>
            <div class="verified-attention" style="border-color:#FC3636; background: rgba(252, 54, 54, 0.10);">
                <div class="verified-attention__media" style="background:#FC3636;"><img
                            src="<?php echo $assets; ?>img/verified-none.svg" alt=""/></div>
                <div class="verified-attention__content">
                    <div class="verified-attention__title" style="color:#FC3636">Ви не верифікований продавець.</div>
                    <div class="verified-attention__subtitle">Завантажте документи, щоб пройти верифікаціію</div>
                </div>
            </div>
		<?php endif; ?>
		<?php
	}
}

function the_users_settings() {
	the_header_cabinet();
	$var           = variables();
	$set           = $var['setting_home'];
	$assets        = $var['assets'];
	$url           = $var['url'];
	$url_home      = $var['url_home'];
	$admin_ajax    = $var['admin_ajax'];
	$user_id       = get_current_user_id();
	$permalink     = get_the_permalink() ?: $url;
	$route         = $_GET['route'] ?? '';
	$subpage       = $_GET['subpage'] ?? '';
	$personal_page = carbon_get_theme_option( 'personal_area_page' );
	$_url          = $personal_page ? get_the_permalink( $personal_page[0]['id'] ) : $url;
	$current_user  = get_user_by( 'ID', $user_id );
	$email         = $current_user->user_email ?: '';
	$display_name  = $current_user->display_name ?: '';
	$first_name    = $current_user->first_name ?: '';
	$last_name     = $current_user->last_name ?: '';
	$name          = $first_name ?: $display_name;
	$user_avatar   = carbon_get_user_meta( $user_id, 'user_avatar' );
	$user_seller   = carbon_get_user_meta( $user_id, 'user_seller' );
	$avatar_url    = $user_avatar ? _u( $user_avatar, 1 ) : get_avatar_url( $email ?: $user_id );
	$trusted_users = carbon_get_user_meta( $user_id, 'trusted_users' );
	?>
    <div class="create-item-main">
        <div class="sort-wrap">
            <div class="search-wrap">
                <input class="input_st search_input search-field-js "
                       data-selector=".search-list-selector"
                       data-wrapper-selector=".search-wrapper-selector"
                       type="text" required="required" placeholder="Шукати за ім'ям"/>
            </div>
            <div class="sort-group">
                <div class="form-horizontal">
                    <div class="half"></div>
                    <div class="half">
                        <a class="btn_st b_yelloow modal_open" href="#add-user-modal">
                                <span>Додати користувачів<svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                                              style="enable-background:new 0 0 13 13"
                                                              viewBox="0 0 13 13">
                                                    <path d="M11.8 5.3H7.9c-.1 0-.2-.1-.2-.2V1.2C7.7.5 7.1 0 6.5 0S5.3.5 5.3 1.2v3.9c0 .1-.1.2-.2.2H1.2C.5 5.3 0 5.9 0 6.5s.5 1.2 1.2 1.2h3.9c.1 0 .2.1.2.2v3.9c0 .6.5 1.2 1.2 1.2s1.2-.5 1.2-1.2V7.9c0-.1.1-.2.2-.2h3.9c.6 0 1.2-.5 1.2-1.2s-.5-1.2-1.2-1.2z"
                                                          style="fill:#fff"/>
                                                </svg></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="cabinet-item">
            <div class="table-cabinet__wrap">
                <div class="table-cabinet">
                    <div class="table-cabinet__head">
                        <div class="table-cabinet__head-item">
                            <div class="table-cabinet__head-item-main">Ім'я
                                <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                     style="enable-background:new 0 0 11 10" viewBox="0 0 11 10">
                                                <path d="M10.9 6.6c-.2-.2-.5-.2-.7 0L8.5 8.3V.5C8.5.2 8.3 0 8 0s-.5.2-.5.5v7.8L5.9 6.6c-.2-.2-.5-.2-.7 0-.2.3-.2.6-.1.8l2.5 2.5c.2.2.5.2.7 0l2.5-2.5c.2-.2.2-.6.1-.8zM5.9 2.6 3.4.1S3.3 0 3.2 0h-.4c-.1 0-.1.1-.2.1L.1 2.6c-.1.2-.1.6 0 .8.2.2.5.2.7 0l1.6-1.6v7.8c.1.2.3.4.6.4s.5-.2.5-.5V1.7l1.6 1.6c.2.2.5.2.7 0 .2-.2.2-.5.1-.7z"
                                                      style="fill:#6d6d6d"/>
                                            </svg>
                            </div>
                        </div>
                        <div class="table-cabinet__head-item">
                            <div class="table-cabinet__head-item-main">Роль
                                <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                     style="enable-background:new 0 0 11 10" viewBox="0 0 11 10">
                                                <path d="M10.9 6.6c-.2-.2-.5-.2-.7 0L8.5 8.3V.5C8.5.2 8.3 0 8 0s-.5.2-.5.5v7.8L5.9 6.6c-.2-.2-.5-.2-.7 0-.2.3-.2.6-.1.8l2.5 2.5c.2.2.5.2.7 0l2.5-2.5c.2-.2.2-.6.1-.8zM5.9 2.6 3.4.1S3.3 0 3.2 0h-.4c-.1 0-.1.1-.2.1L.1 2.6c-.1.2-.1.6 0 .8.2.2.5.2.7 0l1.6-1.6v7.8c.1.2.3.4.6.4s.5-.2.5-.5V1.7l1.6 1.6c.2.2.5.2.7 0 .2-.2.2-.5.1-.7z"
                                                      style="fill:#6d6d6d"/>
                                            </svg>
                            </div>
                        </div>
                        <div class="table-cabinet__head-item">
                            <div class="table-cabinet__head-item-main">Статус
                                <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                     style="enable-background:new 0 0 11 10" viewBox="0 0 11 10">
                                                <path d="M10.9 6.6c-.2-.2-.5-.2-.7 0L8.5 8.3V.5C8.5.2 8.3 0 8 0s-.5.2-.5.5v7.8L5.9 6.6c-.2-.2-.5-.2-.7 0-.2.3-.2.6-.1.8l2.5 2.5c.2.2.5.2.7 0l2.5-2.5c.2-.2.2-.6.1-.8zM5.9 2.6 3.4.1S3.3 0 3.2 0h-.4c-.1 0-.1.1-.2.1L.1 2.6c-.1.2-.1.6 0 .8.2.2.5.2.7 0l1.6-1.6v7.8c.1.2.3.4.6.4s.5-.2.5-.5V1.7l1.6 1.6c.2.2.5.2.7 0 .2-.2.2-.5.1-.7z"
                                                      style="fill:#6d6d6d"/>
                                            </svg>
                            </div>
                        </div>
                        <div class="table-cabinet__head-item">
                            <div class="table-cabinet__head-item-main">Дії</div>
                        </div>
                    </div>
                    <div class="table-cabinet__body">
                        <div class="table-cabinet__body-row">
                            <div class="table-cabinet__body-item">
                                <div class="table-user">
                                    <div class="table-user__media">
                                        <img src="<?php echo $avatar_url; ?>" alt=""/>
                                    </div>
                                    <div class="table-user__title"> <?php echo $name; ?> (Ви)</div>
                                </div>
                            </div>
                            <div class="table-cabinet__body-item"> Власник</div>
                            <div class="table-cabinet__body-item"><span class="user-status">Активний </span></div>
                        </div>
						<?php
						if ( $trusted_users ):
							foreach ( $trusted_users as $user ):
								the_user_row( $user );
							endforeach;
						endif;
						?>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<?php
}

function the_user_row( $user_data ) {
	$_user_id         = $user_data['user_id'];
	$user_status      = $user_data['user_status'];
	$_user            = get_user_by( 'ID', $_user_id );
	if ( $_user_id && $_user ):
		$current_user = get_user_by( 'ID', $_user_id );
		$email        = $current_user->user_email ?: '';
		$display_name = $current_user->display_name ?: '';
		$first_name   = $current_user->first_name ?: '';
		$last_name    = $current_user->last_name ?: '';
		$name         = $first_name ?: $display_name;
		$user_avatar  = carbon_get_user_meta( $_user_id, 'user_avatar' );
		$user_seller  = carbon_get_user_meta( $_user_id, 'user_seller' );
		$avatar_url   = $user_avatar ? _u( $user_avatar, 1 ) : get_avatar_url( $email ?: $_user_id );
		?>
        <div class="table-cabinet__body-row search-wrapper-selector">
            <div class="table-cabinet__body-item">
                <div class="table-user">
                    <div class="table-user__media">
                        <img src="<?php echo $avatar_url; ?>" alt=""/>
                    </div>
                    <a href="<?php echo get_author_posts_url( $_user_id ); ?>"
                       class="table-user__title search-list-selector">
						<?php echo $name; ?>
                    </a>
                </div>
            </div>
            <div class="table-cabinet__body-item"> Редактор</div>
            <div class="table-cabinet__body-item">
				<?php if ( $user_status == 'expected' ): ?>
                    <span class="user-status expected">Очікується</span>
				<?php elseif ( $user_status == 'active' ): ?>
                    <span class="user-status">Активний </span>
				<?php elseif ( $user_status == 'not_active' ): ?>
                    <span class="user-status no_active">Не активний</span>
				<?php endif; ?>
            </div>
            <div class="table-cabinet__body-item">
                <ul class="user-activity">
                    <li>
                        <span class="text-out-state"><?php echo ( $user_status == 'active' ) ? 'Відключити' : 'Увімкнути'; ?></span>
                        <label class="switch_st">
                            <input class="change-user-status"
                                   name="change_user_status"
                                   value="<?php echo $_user_id; ?>"
                                   data-id="<?php echo $_user_id; ?>"
                                   data-text-off="Відключити"
                                   data-text-on="Увімкнути"
								<?php echo ( $user_status == 'active' ) ? 'checked' : ''; ?>
                                   type="checkbox"/><span></span>
                        </label>
                    </li>
                    <li>
                        <a class="remove-cart remove-user" data-id="<?php echo $_user_id; ?>" href="#">
                            Видалити
                            <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                 style="enable-background:new 0 0 17.3 20"
                                 viewBox="0 0 17.3 20">
                                                            <path d="M5.8 0c-.5 0-.9.4-.9.9v1.8h-4c-.5 0-.9.4-.9.9s.4.9.9.9h15.6c.5 0 .9-.4.9-.9s-.4-.9-.9-.9h-4V.9c0-.5-.4-.9-.9-.9H5.8zm.9 2.6v-.8h4.1v.8H6.7zM15.5 6.4H1.7v10.2c0 2 1.4 3.5 3.3 3.5h7.2c1.9 0 3.3-1.5 3.3-3.5V6.4zm-8 3.2c0-.5-.4-.9-.9-.9s-.9.4-.9.9v6.1c0 .5.4.9.9.9s.9-.4.9-.9V9.6zm4.1 0c0-.5-.4-.9-.9-.9s-.9.4-.9.9v6.1c0 .5.4.9.9.9s.9-.4.9-.9V9.6z"
                                                                  style="fill-rule:evenodd;clip-rule:evenodd;fill:#fff"/>
                                                        </svg>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
	<?php
	endif;
}

function the_payment_history() {
	the_header_cabinet();
	$var                    = variables();
	$set                    = $var['setting_home'];
	$assets                 = $var['assets'];
	$url                    = $var['url'];
	$url_home               = $var['url_home'];
	$admin_ajax             = $var['admin_ajax'];
	$user_id                = get_current_user_id();
	$permalink              = get_the_permalink() ?: $url;
	$route                  = $_GET['route'] ?? '';
	$subpage                = $_GET['subpage'] ?? '';
	$personal_page          = carbon_get_theme_option( 'personal_area_page' );
	$_url                   = $personal_page ? get_the_permalink( $personal_page[0]['id'] ) : $url;
	$current_user           = get_user_by( 'ID', $user_id );
	$email                  = $current_user->user_email ?: '';
	$display_name           = $current_user->display_name ?: '';
	$first_name             = $current_user->first_name ?: '';
	$last_name              = $current_user->last_name ?: '';
	$name                   = $first_name ?: $display_name;
	$user_avatar            = carbon_get_user_meta( $user_id, 'user_avatar' );
	$page_title             = carbon_get_post_meta( $set, 'history_page_title' );
	$page_text              = carbon_get_post_meta( $set, 'history_page_text' );
	$page_image             = carbon_get_post_meta( $set, 'history_page_image' );
	$get_user_promo         = get_user_promo( $user_id );
	$default_posts_per_page = get_option( 'posts_per_page' );
	$paged                  = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$args                   = array(
		'post_type'      => 'payment',
		'posts_per_page' => $default_posts_per_page,
		'post_status'    => 'publish',
		'paged'          => $paged
	);
	$args['author__in']     = array( $user_id );
	$query                  = new WP_Query( $args );
	?>
    <div class="create-item-main">
        <div class="discount-group">
            <div class="discount-promo">
                <div class="discount-promo__title">
					<?php echo ( $get_user_promo ) ? 'Ваша знижка' : 'Активуйте промокод' ?>
                </div>
                <div class="discount-promo__bot">
                    <div class="discount-promo__main">
						<?php echo ( $get_user_promo ) ? '-' . carbon_get_post_meta( $get_user_promo, 'coupon_discount' ) . '%' : '' ?>
                    </div>
                    <a class="btn_st btn_yellow modal_open" href="#modal_promo">
                        <span>Активуйте промокод</span>
                    </a>
                    <div class="modal modal-sm" id="modal_promo">
                        <div class="modal-content">
                            <div class="modal-title text-center">
                                <div class="modal-title__main">Активуйте промокод</div>
                                <div class="modal-title__subtitle">
                                    Тут ви можете активувати промокод для отримання знижки!
                                </div>
                            </div>
                            <form novalidate method="post" class="form-js set-coupon" id="set-coupon">
                                <input type="hidden" name="action" value="set_coupon">
                                <div class="form-group">
                                    <input class="input_st"
                                           value="<?php echo $get_user_promo ? get_the_title( $get_user_promo ) : ''; ?>"
                                           type="text" name="promo" placeholder="Промокод"
                                           required="required"/>
                                </div>
                                <button class="btn_st w100" type="submit">
                                    <span>Активувати </span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
			<?php if ( $page_text ): ?>
                <div class="discount-info">
                    <div class="discount-info__content">
                        <div class="discount-info__title">
							<?php echo $page_title; ?>
                        </div>
                        <div class="text-group">
							<?php _t( $page_text ); ?>
                        </div>
                    </div>
					<?php if ( $page_image ): ?>
                        <div class="discount-info__media" style="background: url(<?php _u( $page_image ); ?>)"></div>
					<?php endif; ?>
                </div>
			<?php endif; ?>
        </div>
        <div class="cabinet-item__title">Історія платежів</div>
		<?php if ( $query->have_posts() ) : ?>
            <div class="cabinet-item">
                <div class="table-cabinet__wrap">
                    <div class="table-cabinet">
                        <div class="table-cabinet__head">
                            <div class="table-cabinet__head-item">
                                <div class="table-cabinet__head-item-main">Опис
                                    <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                         style="enable-background:new 0 0 11 10" viewBox="0 0 11 10">
                                                <path d="M10.9 6.6c-.2-.2-.5-.2-.7 0L8.5 8.3V.5C8.5.2 8.3 0 8 0s-.5.2-.5.5v7.8L5.9 6.6c-.2-.2-.5-.2-.7 0-.2.3-.2.6-.1.8l2.5 2.5c.2.2.5.2.7 0l2.5-2.5c.2-.2.2-.6.1-.8zM5.9 2.6 3.4.1S3.3 0 3.2 0h-.4c-.1 0-.1.1-.2.1L.1 2.6c-.1.2-.1.6 0 .8.2.2.5.2.7 0l1.6-1.6v7.8c.1.2.3.4.6.4s.5-.2.5-.5V1.7l1.6 1.6c.2.2.5.2.7 0 .2-.2.2-.5.1-.7z"
                                                      style="fill:#6d6d6d"/>
                                            </svg>
                                </div>
                            </div>
                            <div class="table-cabinet__head-item">
                                <div class="table-cabinet__head-item-main">Дата і час
                                    <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                         style="enable-background:new 0 0 11 10" viewBox="0 0 11 10">
                                                <path d="M10.9 6.6c-.2-.2-.5-.2-.7 0L8.5 8.3V.5C8.5.2 8.3 0 8 0s-.5.2-.5.5v7.8L5.9 6.6c-.2-.2-.5-.2-.7 0-.2.3-.2.6-.1.8l2.5 2.5c.2.2.5.2.7 0l2.5-2.5c.2-.2.2-.6.1-.8zM5.9 2.6 3.4.1S3.3 0 3.2 0h-.4c-.1 0-.1.1-.2.1L.1 2.6c-.1.2-.1.6 0 .8.2.2.5.2.7 0l1.6-1.6v7.8c.1.2.3.4.6.4s.5-.2.5-.5V1.7l1.6 1.6c.2.2.5.2.7 0 .2-.2.2-.5.1-.7z"
                                                      style="fill:#6d6d6d"/>
                                            </svg>
                                </div>
                            </div>
                            <div class="table-cabinet__head-item">
                                <div class="table-cabinet__head-item-main">Сума
                                    <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                         style="enable-background:new 0 0 11 10" viewBox="0 0 11 10">
                                                <path d="M10.9 6.6c-.2-.2-.5-.2-.7 0L8.5 8.3V.5C8.5.2 8.3 0 8 0s-.5.2-.5.5v7.8L5.9 6.6c-.2-.2-.5-.2-.7 0-.2.3-.2.6-.1.8l2.5 2.5c.2.2.5.2.7 0l2.5-2.5c.2-.2.2-.6.1-.8zM5.9 2.6 3.4.1S3.3 0 3.2 0h-.4c-.1 0-.1.1-.2.1L.1 2.6c-.1.2-.1.6 0 .8.2.2.5.2.7 0l1.6-1.6v7.8c.1.2.3.4.6.4s.5-.2.5-.5V1.7l1.6 1.6c.2.2.5.2.7 0 .2-.2.2-.5.1-.7z"
                                                      style="fill:#6d6d6d"/>
                                            </svg>
                                </div>
                            </div>
                        </div>
                        <div class="table-cabinet__body">
							<?php
							while ( $query->have_posts() ) :
								$query->the_post();
								the_payment_history_item();
							endwhile;
							?>
                        </div>
                    </div>
                </div>
                <div class="pagination-group">
					<?php
					if ( function_exists( 'wp_pagenavi' ) ) {
						wp_pagenavi( array( 'query' => $query ) );
					}
					?>
                    <div class="pagination-nav">
						<?php echo _get_previous_link(); ?>
						<?php echo _get_next_link( $query->max_num_pages ); ?>
                    </div>
                </div>
            </div>
		<?php else: ?>
            <div class="cabinet-item">
                <div class="no-announcement">
                    <div class="no-announcement__media">
                        <img src="<?php echo $assets; ?>img/help2.webp" alt=""/>
                    </div>
                    <div class="no-announcement__title">Тут пусто ...</div>
                    <div class="no-announcement__subtitle">Купуйте і користуйтеся всіма нашими платними послугами</div>
                </div>
            </div>
		<?php endif; ?>
    </div>
	<?php
	wp_reset_postdata();
	wp_reset_query();
}

function the_payment_history_item( $id = false ) {
	$id       = $id ?: get_the_ID();
	$currency = carbon_get_theme_option( 'currency' );
	?>
    <div class="table-cabinet__body-row">
        <div class="table-cabinet__body-item">
			<?php echo get_the_title(); ?>
        </div>
        <div class="table-cabinet__body-item">
			<?php echo get_the_date( 'd.m.Y, H:i' ); ?>
        </div>
        <div class="table-cabinet__body-item">
            <strong style="color:#FC3636">
				<?php echo carbon_get_post_meta( $id, 'payment_sum' ) . $currency; ?>
            </strong>
        </div>
    </div>
	<?php
}