<?php

function create_advertisements() {
	$var                  = variables();
	$set                  = $var['setting_home'];
	$assets               = $var['assets'];
	$url                  = $var['url'];
	$url_home             = $var['url_home'];
	$admin_ajax           = $var['admin_ajax'];
	$management_user      = $_GET['management_user'] ?? '';
	$user_id              = get_current_user_id();
	$id                   = get_the_ID();
	$isLighthouse         = isLighthouse();
	$size                 = $isLighthouse ? 'thumbnail' : 'full';
	$current_user_id      = $user_id;
	$current_user         = get_user_by( 'ID', $user_id );
	$email                = $current_user->user_email ?: '';
	$display_name         = $current_user->display_name ?: '';
	$first_name           = $current_user->first_name ?: '';
	$last_name            = $current_user->last_name ?: '';
	$name                 = $first_name ?: $display_name;
	$user_company_phone   = carbon_get_user_meta( $user_id, 'user_company_phone' );
	$user_company_name    = carbon_get_user_meta( $user_id, 'user_company_name' );
	$user_company_address = carbon_get_user_meta( $user_id, 'user_company_address' );
	$personal_page        = carbon_get_theme_option( 'personal_area_page' );
	$product_types        = get_terms( array(
		'taxonomy'   => 'product_type',
		'hide_empty' => false,
	) );
	$categories           = get_terms( array(
		'taxonomy'   => 'categories',
		'hide_empty' => false,
		'parent'     => 0,
	) );
	$processing_types     = get_terms( array(
		'taxonomy'   => 'processing_type',
		'hide_empty' => false,
	) );
	$packages             = get_terms( array(
		'taxonomy'   => 'package',
		'hide_empty' => false,
	) );
	$certificates         = get_terms( array(
		'taxonomy'   => 'certificates',
		'hide_empty' => false,
	) );
	$delivery_types       = carbon_get_theme_option( 'delivery_types' );
	$units_measurement    = carbon_get_theme_option( 'units_measurement' );
	$current_year         = date( "Y" );
	$args                 = array(
		'post_type'      => 'products',
		'posts_per_page' => - 1,
		'author'         => $current_user_id,
		'post_status'    => 'publish'
	);
	$map_api_url          = carbon_get_theme_option( 'autocomplete_api_url' );
	$image_count          = carbon_get_theme_option( 'image_count' ) ?: 1;
	$logo                 = carbon_get_theme_option( 'logo' );
	$logo_sm              = carbon_get_theme_option( 'logo_sm' );
	$management_users     = sellers_management( $user_id );
	$user_seller          = carbon_get_user_meta( $user_id, 'user_seller' );
	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	?>

	<?php the_header_cabinet(); ?>
    <div class="create-item-main">
        <form action="<?php echo $admin_ajax; ?>"
              method="post"
              novalidate
              id="new-advertisement-form"
              class="new-advertisement-js form-js"
              enctype="multipart/form-data">
            <input type="hidden" name="action" value="new_product">
            <input type="hidden" id="user_post_code" name="user_postcode">
            <input type="hidden" id="user_country" name="user_country">
            <input type="hidden" id="user_country_code" name="user_country_code">
            <input type="hidden" id="user_city" name="user_city">
            <input type="hidden" id="user_region" name="user_region">
            <input type="hidden" id="lat" name="lat">
            <input type="hidden" id="lng" name="lng">
            <div class="cabinet-item pad_bot_15">
                <div class="cabinet-item__title">Заповніть будь-ласка інформацію нижче</div>
                <div class="form-horizontal">
					<?php if ( $product_types ): ?>
                        <div class="form-group quarter">
                            <select class="select_st" required name="product_type">
                                <option disabled="disabled" selected="selected">Тип продукту</option>
								<?php foreach ( $product_types as $item ): ?>
                                    <option value="<?php echo $item->term_id; ?>">
										<?php echo $item->name; ?>
                                    </option>
								<?php endforeach; ?>
                            </select>
                        </div>
					<?php endif; ?>
					<?php if ( $categories ): ?>
                        <div class="form-group quarter">
                            <select class="select_st categories-select-js"
                                    data-selector=".sub-categories-select-js"
                                    required name="categories[]">
                                <option disabled="disabled" selected="selected">Категорія продукту</option>
								<?php foreach ( $categories as $item ): ?>
                                    <option value="<?php echo $item->term_id; ?>">
										<?php echo $item->name; ?>
                                    </option>
								<?php endforeach; ?>
                            </select>
                        </div>
					<?php endif; ?>
                    <div class="form-group quarter not-active">
                        <select class="select_st sub-categories-select-js categories-select-js"
                                name="categories[]"
                                data-selector=".internal-categories-select-js">
                            <option disabled="disabled" selected="selected">Підкатегорія продукту</option>
                        </select>
                    </div>
                    <div class="form-group quarter not-active">
                        <select class="select_st categories-select-js internal-categories-select-js"
                                name="categories[]"
                                data-selector=".sub-internal-categories-select-js">
                            <option disabled="disabled">Тип або вид продукту</option>
                        </select>
                    </div>
                    <div class="form-group quarter not-active">
                        <select class="select_st  sub-internal-categories-select-js"
                                name="categories[]">
                            <option disabled="disabled">Підкатегорія типу або виду продукту</option>
                        </select>
                    </div>
                    <div class="form-group quarter not-active">
                        <select class="select_st filter-select-js" multiple
                                name="filters[]">
                            <option disabled="disabled">Додаткові фільтри продукту</option>
                        </select>
                    </div>
					<?php if ( $processing_types ): ?>
                        <div class="form-group quarter">
                            <select class="select_st" name="processing_type">
                                <option disabled="disabled" selected="selected">Тип обробки</option>
								<?php foreach ( $processing_types as $item ): ?>
                                    <option value="<?php echo $item->term_id; ?>">
										<?php echo $item->name; ?>
                                    </option>
								<?php endforeach; ?>
                            </select>
                        </div>
					<?php endif; ?>
					<?php if ( $units_measurement ): ?>
                        <div class="form-group quarter">
                            <select class="select_st" required name="units_measurement">
                                <option disabled="disabled" selected="selected">Одиниці вимірювання</option>
								<?php foreach ( $units_measurement as $item ): ?>
                                    <option value="<?php echo $item['unit']; ?>">
										<?php echo $item['unit']; ?>
                                    </option>
								<?php endforeach; ?>
                            </select>
                        </div>
					<?php endif; ?>
                    <div class="form-group quarter">
                        <input class="input_st number-input" type="text" name="product_max_value"
                               placeholder="В наявності"/>
                    </div>
                    <div class="form-group quarter">
                        <input class="input_st number-input" type="text" name="product_min_order"
                               placeholder="Мінімальне замовлення"/>
                    </div>
					<?php if ( $packages ): ?>
                        <div class="form-group quarter">
                            <select class="select_st" name="package">
                                <option disabled="disabled" selected="selected">Упаковка</option>
								<?php foreach ( $packages as $item ): ?>
                                    <option value="<?php echo $item->term_id; ?>">
										<?php echo $item->name; ?>
                                    </option>
								<?php endforeach; ?>
                            </select>
                        </div>
					<?php endif; ?>
					<?php if ( $delivery_types ): ?>
                        <div class="form-group quarter">
                            <select multiple class="select_st" required name="delivery_types[]">
                                <option disabled="disabled" value="">Умови доставки (виберіть один або декілька)</option>
								<?php foreach ( $delivery_types as $item ):
									$_type_item = $item['_type'];
									$_title_item = $item['title'];
									?>
                                    <option value="<?php echo $_title_item . "[$_type_item]"; ?>">
										<?php echo $_title_item; ?>
                                    </option>
								<?php endforeach; ?>
                            </select>
                        </div>
					<?php endif; ?>
					<?php if ( $certificates ): ?>
                        <div class="form-group quarter">
                            <select class="select_st "
                                    name="certificates[]">
                                <option disabled="disabled" selected="selected">Сертифікати</option>
								<?php foreach ( $certificates as $item ): ?>
                                    <option value="<?php echo $item->term_id; ?>">
										<?php echo $item->name; ?>
                                    </option>
								<?php endforeach; ?>
                            </select>
                        </div>
					<?php endif; ?>
                    <div class="form-group quarter">
                        <select class="select_st" name="year">
                            <option disabled="disabled" selected="selected">Якого року врожай</option>
							<?php for ( $a = $current_year; $a >= ( $current_year - 60 ); $a -- ): ?>
                                <option><?php echo $a; ?></option>
							<?php endfor; ?>
                        </select>
                    </div>
                    <div class="form-group quarter flex-align-items-center">
                        <a href="#category-name-input" class="show-element">Не знайшли своєї категорії?</a>
                        <input class="input_st hidden" id="category-name-input" type="text" name="category_name"
                               placeholder="Назва категорії">
                    </div>
                </div>
            </div>
            <div class="create-item__group">
                <div class="create-item__group-left">
                    <div class="cabinet-item">
                        <div class="cabinet-item__title">Опишіть у подробицях</div>
                        <div class="form-description">
                            <div class="form-description__item">
                                <div class="form-group">
                                    <input class="input_st" type="text" name="title" placeholder="Вкажіть назву*"
                                           required="required"/>
                                    <div class="form-group__info">Наприклад, Яблуко Голден</div>
                                </div>

                                <div class="form-group position-static">
                                        <textarea class="input_st content-field"
                                                  name="content"
                                                  data-more="<!--more-->"
                                                  placeholder="Що ви хотіли би додати до оголошення?"></textarea>
                                    <div class="form-group__info content-count-js">0/9000</div>
                                    <nav class="context-menu">
                                        <ul class="context-menu__items">
                                            <li class="context-menu__item">
                                                <a href="#" class="context-menu__link btn_st add-tag-more-js">
                                                    <span>Вставити позначку "Читати далі"</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            <div class="form-description__item">
                                <div class="form-description__item-title">Ціна</div>
                                <div class="form-group">
                                    <input class="input_st number-input" type="text" name="price" placeholder="50"
                                           required="required"/>
                                </div>
                            </div>
                            <div class="form-description__item">

                                <div class="continue-group form-group" title="<?php echo $user_company_address; ?>">
                                    <div class="continue-group__text">
                                        <div class="form-description__item-title">Місцезнаходження оголошення</div>
                                        <div class="cabinet-item__text">
                                            Місцезнаходження оголошення однакове з адресою господарства
                                        </div>
                                    </div>
                                    <label class="switch_st">
                                        <input name="is_company_address" class="company-address-checkbox"
                                               value="true"
                                               data-element=".product-custom-address-container"
                                               checked type="checkbox"/><span></span>
                                    </label>
                                </div>
                                <div class="product-custom-address-container hidden">
                                    <div class="form-description__item-title">Місцезнаходження</div>
                                    <div class="form-group">
                                        <input class="input_st address-js" type="text"
                                               name="address"
                                               id="address-google"
                                               placeholder="Місцезнаходження (Місто, індекс)"/>
                                    </div>
                                    <div class="form-horizontal">
                                        <div class="form-group half">
                                            <input class="input_st" type="text" name="pick_up_address[]"
                                                   placeholder="Адреса самовивозу"/>
                                        </div>
                                        <div class="form-group half">
                                            <input class="input_st" type="text" name="pick_up_work_time[]"
                                                   placeholder="09:00 - 22:00"/>
                                        </div>
                                    </div>
                                    <div class="wrap-new-adr">
                                        <div class="wrap-new-adr__hide"></div>
                                        <a class="btn_st b_yelloow add-new-adr" href="#">
                                            <span>Додати ще адесу самовивозу<svg xmlns="http://www.w3.org/2000/svg"
                                                                                 xml:space="preserve"
                                                                                 style="enable-background:new 0 0 13 13"
                                                                                 viewBox="0 0 13 13">
                                                            <path d="M11.8 5.3H7.9c-.1 0-.2-.1-.2-.2V1.2C7.7.5 7.1 0 6.5 0S5.3.5 5.3 1.2v3.9c0 .1-.1.2-.2.2H1.2C.5 5.3 0 5.9 0 6.5s.5 1.2 1.2 1.2h3.9c.1 0 .2.1.2.2v3.9c0 .6.5 1.2 1.2 1.2s1.2-.5 1.2-1.2V7.9c0-.1.1-.2.2-.2h3.9c.6 0 1.2-.5 1.2-1.2s-.5-1.2-1.2-1.2z"
                                                                  style="fill:#fff"/>
                                                        </svg></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
							<?php if ( $management_users ): ?>
                                <div class="form-group">
                                    <div class="form-description__item-title">
                                        Для якого господарства створюється оголошення
                                    </div>
                                    <select class="select_st" name="author_id">
										<?php if ( $user_seller ): ?>
                                            <option value="<?php echo $user_id; ?>">
												<?php echo $user_company_name; ?>
                                            </option>
										<?php endif; ?>
										<?php foreach ( $management_users as $l => $user ):
											$ID = $user->ID;
											$is_active_manager = is_active_manager( $user_id, $ID );
											if ( $user->allcaps['edit_posts'] && $is_active_manager ):
												$attr = $l == 0 ? 'selected' : '';
												$attr = $management_user == $ID ? 'selected' : '';

												?>
                                                <option value="<?php echo $ID; ?>"
													<?php echo $attr; ?>
                                                        data-is_active="<?php echo $is_active_manager; ?>"
                                                        data-val="<?php echo $user->allcaps['edit_posts']; ?>">
													<?php echo carbon_get_user_meta( $ID, 'user_company_name' ); ?>
                                                </option>
											<?php endif; endforeach; ?>
                                    </select>
                                </div>
							<?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="create-item__group-right">
                    <div class="cabinet-item">
                        <div class="cabinet-item__title">Фото</div>
                        <div class="cabinet-item__text">Перше фото буде на обкладинці оголошення.</div>
                        <div class="cabinet-item__photo">
                            <div class="cabinet-item__photo-item">
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
                    <div class="cabinet-item">
                        <div class="continue-group">
                            <div class="continue-group__text">
                                <div class="form-description__item-title">Автовидалення</div>
                                <div class="cabinet-item__text">Ваш товар буде видалено автоматично через 30 днів
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
			<?php
			$query = new WP_Query( $args );
			if ( $query->have_posts() ) :
				?>
                <div class="cabinet-item">
                    <div class="cabinet-item__title">
                        Краще смакує разом
                    </div>
                    <div class="cabinet-item__text">
                        Ви можете додати додаткові оголошення, які будуть відображатися в карточці товару.
                    </div>
                    <div class="cabinet-together">
                        <div class="all-list">
                            Всього оголошень: <?php echo $query->found_posts; ?>
                        </div>
                        <div class="select-all">
                            <label class="check_st_item">
                                <input class="check_st check_all" type="checkbox"/>
                                <span> </span>
                            </label>
                            <div class="select-all__text">
                                Оберіть усі потрібні оголошення зі списку, щоб застосувати до них однакові дії
                            </div>
                        </div>
                        <div class="select-product">
							<?php
							while ( $query->have_posts() ) :
								$query->the_post();
								$_id = get_the_ID();
								the_product_connected( $_id );
							endwhile; ?>
                        </div>
                    </div>
                </div>
			<?php else: ?>
                <div class="cabinet-item">
                    <div class="cabinet-item__title">Краще смакує разом</div>
                    <div class="cabinet-item__text">
                        Ви можете додати додаткові оголошення, які будуть відображатися в карточці товару.
                        <br/>Наразі у вас немає створених оголошень, які ви може додати
                    </div>
                </div>
			<?php endif; ?>
            <div class="cabinet-item">
                <div class="accept-group">
                    <div class="accept-title">Перевірте та опублікуйте оголошення</div>
                    <div class="accept-group__link">
                        <a class="btn_st b_yelloow"
                           href="<?php echo $personal_page ? get_the_permalink( $personal_page[0]['id'] ) . '?route=advertisement' : $url; ?>">
                            <span>Скасувати</span>
                        </a>
                        <button class="btn_st" type="submit">
                            <span>Опублікувати</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
	<?php if ( $map_api_url ): ?>
        <script src="<?php echo $map_api_url; ?>" id="google-map-api" defer></script>
	<?php endif; ?>
    <div class="modal modal-sm" id="created">
        <div class="modal-content text-center">
            <div class="modal-title">
                <div class="modal-title__main">Оголошення створено</div>
                <div class="modal-title__subtitle">Ваше оголошення успішно створене. Наразі воно на модерації</div>
            </div>
            <a class="btn_st" href="#">
                <span>Мої оголошення </span>
            </a>
        </div>
    </div>
	<?php
}

function edit_advertisement() {
	$current_user_id = get_current_user_id();
	$ID              = $_GET['product'] ?? '';
	$management_user = $_GET['management_user'] ?? '';
	$user_id         = get_current_user_id();
	if ( $management_user ) {
		$is_active_manager = is_active_manager( $user_id, $management_user );
		if ( ! $is_active_manager ) {
			create_advertisements();

			return;
		}
	}
	$args = array(
		'post_type'      => 'products',
		'posts_per_page' => - 1,
		'author'         => $current_user_id,
		'post_status'    => 'publish',
		'post__not_in'   => array( $ID )
	);
	if ( $management_user ) {
		$args['author'] = $management_user;
	}
	if ( ! $ID || ! get_post( $ID ) ) {
		create_advertisements();

		return;
	}
	$var               = variables();
	$set               = $var['setting_home'];
	$assets            = $var['assets'];
	$url               = $var['url'];
	$url_home          = $var['url_home'];
	$admin_ajax        = $var['admin_ajax'];
	$id                = get_the_ID();
	$isLighthouse      = isLighthouse();
	$size              = $isLighthouse ? 'thumbnail' : 'full';
	$author_id         = get_post_field( 'post_author', $ID );
	$current_user      = get_user_by( 'ID', $author_id );
	$email             = $current_user->user_email ?: '';
	$display_name      = $current_user->display_name ?: '';
	$first_name        = $current_user->first_name ?: '';
	$last_name         = $current_user->last_name ?: '';
	$name              = $first_name ?: $display_name;
	$pick_up_address   = carbon_get_post_meta( $ID, 'pick_up_address' );
	$gallery           = carbon_get_post_meta( $ID, 'product_gallery' );
	$_unit             = carbon_get_post_meta( $ID, 'product_unit' );
	$_delivery_methods = carbon_get_post_meta( $ID, 'product_delivery_methods' );
	$_year             = carbon_get_post_meta( $ID, 'product_year' );
	$_products         = carbon_get_post_meta( $ID, 'product_products' );
	$product_types     = get_terms( array(
		'taxonomy' => 'product_type',
		'hide'     => false,
	) );
	$categories        = get_terms( array(
		'taxonomy'   => 'categories',
		'hide_empty' => false,
		'parent'     => 0,
	) );
	$processing_types  = get_terms( array(
		'taxonomy'   => 'processing_type',
		'hide_empty' => false,
	) );
	$packages          = get_terms( array(
		'taxonomy'   => 'package',
		'hide_empty' => false,
	) );
	$certificates      = get_terms( array(
		'taxonomy'   => 'certificates',
		'hide_empty' => false,
	) );
	$_product_types    = get_the_terms( $ID, 'product_type' );
	$_categories       = get_the_terms( $ID, 'categories' );
	$_processing_types = get_the_terms( $ID, 'processing_type' );
	$_packages         = get_the_terms( $ID, 'package' );
	$delivery_types    = carbon_get_theme_option( 'delivery_types' );
	$units_measurement = carbon_get_theme_option( 'units_measurement' );
	$current_year      = date( "Y" );
	$map_api_url       = carbon_get_theme_option( 'autocomplete_api_url' );
	$image_count       = carbon_get_theme_option( 'image_count' ) ?: 1;
	$logo              = carbon_get_theme_option( 'logo' );
	$logo_sm           = carbon_get_theme_option( 'logo_sm' );
	$personal_page     = carbon_get_theme_option( 'personal_area_page' );
	if ( $_products ) {
		$_products = explode( ',', $_products );
	}
	$management_users     = sellers_management( $user_id );
	$user_seller          = carbon_get_user_meta( $author_id, 'user_seller' );
	$user_company_phone   = carbon_get_user_meta( $author_id, 'user_company_phone' );
	$user_company_name    = carbon_get_user_meta( $author_id, 'user_company_name' );
	$user_company_address = carbon_get_user_meta( $author_id, 'user_company_address' );
	$personal_page        = carbon_get_theme_option( 'personal_area_page' );
	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	?>
	<?php the_header_cabinet(); ?>
    <div class="create-item-main">
        <form action="<?php echo $admin_ajax; ?>" method="post"
              novalidate
              id="new-advertisement-form"
              class="new-advertisement-js form-js"
              enctype="multipart/form-data">
            <div class="hidden">
                <input type="hidden" name="action" value="new_product">
                <input type="hidden" id="user_post_code" name="user_postcode"
                       value="<?php echo carbon_get_post_meta( $ID, 'product_user_postcode' ); ?>">
                <input type="hidden" id="user_country" name="user_country"
                       value="<?php echo carbon_get_post_meta( $ID, 'product_user_country' ); ?>">
                <input type="hidden" id="user_country_code" name="user_country_code"
                       value="<?php echo carbon_get_post_meta( $ID, 'product_user_country_code' ); ?>">
                <input type="hidden" id="user_city" name="user_city"
                       value="<?php echo carbon_get_post_meta( $ID, 'product_city' ); ?>">
                <input type="hidden" id="user_region" name="user_region"
                       value="<?php echo carbon_get_post_meta( $ID, 'product_region' ); ?>">
                <input type="hidden" id="lat" name="lat"
                       value="<?php echo carbon_get_post_meta( $ID, 'product_latitude' ); ?>">
                <input type="hidden" id="lng" name="lng"
                       value="<?php echo carbon_get_post_meta( $ID, 'product_longitude' ); ?>">
                <input type="hidden" name="ID" value="<?php echo $ID; ?>">
                <input type="hidden" name="management_user" value="<?php echo $management_user; ?>">
                <input type="hidden" name="author_id" value="<?php echo $author_id; ?>">
            </div>
            <div class="cabinet-item pad_bot_15">
                <div class="cabinet-item__title">Заповніть будь-ласка інформацію нижче</div>
                <div class="form-horizontal">
			        <?php if ( $product_types ): ?>
                        <div class="form-group quarter">
                            <select class="select_st" required name="product_type">
                                <option disabled="disabled" selected="selected">Тип продукту</option>
						        <?php foreach ( $product_types as $item ):
							        ?>
                                    <option value="<?php echo $item->term_id; ?>" <?php echo $_product_types[0]->term_id == $item->term_id ? "selected" : ''; ?>>
								        <?php echo $item->name; ?>
                                    </option>
						        <?php endforeach; ?>
                            </select>
                        </div>
			        <?php endif; ?>
			        <?php if ( $categories ): ?>
                        <div class="form-group quarter">
                            <select
                                    class="select_st categories-select-js"
                                    data-id="<?php echo $ID; ?>"
                                    data-selector=".sub-categories-select-js"
                                    required name="categories[]"
                            >
                                <option disabled="disabled" selected="selected">Категорія продукту</option>
						        <?php foreach ( $categories as $item ):
							        $attr = '';
							        if ( $_categories ) {
								        foreach ( $_categories as $_category ) {
									        if ( $_category->term_id == $item->term_id ) {
										        $attr = 'selected';
									        }
								        }
							        }
							        ?>
                                    <option value="<?php echo $item->term_id; ?>" <?php echo $attr; ?>>
								        <?php echo $item->name; ?>
                                    </option>
						        <?php endforeach; ?>
                            </select>
                        </div>
			        <?php endif; ?>

                    <div class="form-group quarter not-active">
                        <select class="select_st sub-categories-select-js categories-select-js"
                                name="categories[]"
                                data-id="<?php echo $ID; ?>"
                                data-selector=".internal-categories-select-js">
                            <option disabled="disabled" selected="selected">Підкатегорія продукту</option>
                        </select>
                    </div>
                    <div class="form-group quarter not-active">
                        <select class="select_st categories-select-js internal-categories-select-js"
                                name="categories[]"
                                data-id="<?php echo $ID; ?>"
                                data-selector=".sub-internal-categories-select-js">
                            <option disabled="disabled">Тип або вид продукту</option>
                        </select>
                    </div>
                    <div class="form-group quarter not-active">
                        <select class="select_st  sub-internal-categories-select-js"

                                data-id="<?php echo $ID; ?>"
                                name="categories[]">
                            <option disabled="disabled">Підкатегорія типу або виду продукту</option>
                        </select>
                    </div>

                    <div class="form-group quarter not-active">
                        <select class="select_st filter-select-js" multiple
                                data-id="<?php echo $ID; ?>"
                                name="filters[]">
                            <option disabled="disabled">Додаткові фільтри продукту</option>
                        </select>
                    </div>
			        <?php if ( $processing_types ): ?>
                        <div class="form-group quarter">
                            <select class="select_st" name="processing_type">
                                <option disabled="disabled" selected="selected">Тип обробки</option>
						        <?php foreach ( $processing_types as $item ):
							        $attr = '';
							        if ( $_processing_types ) {
								        foreach ( $_processing_types as $_item ) {
									        if ( $_item->term_id == $item->term_id ) {
										        $attr = 'selected';
									        }
								        }
							        }
							        ?>
                                    <option value="<?php echo $item->term_id; ?>" <?php echo $attr; ?>>
								        <?php echo $item->name; ?>
                                    </option>
						        <?php endforeach; ?>
                            </select>
                        </div>
			        <?php endif; ?>
			        <?php if ( $units_measurement ): ?>
                        <div class="form-group quarter">
                            <select class="select_st" required name="units_measurement">
                                <option disabled="disabled" selected="selected">Одиниці вимірювання</option>
						        <?php foreach ( $units_measurement as $item ):
							        $attr = $item['unit'] == $_unit ? 'selected' : '';
							        ?>
                                    <option value="<?php echo $item['unit']; ?>" <?php echo $attr; ?>>
								        <?php echo $item['unit']; ?>
                                    </option>
						        <?php endforeach; ?>
                            </select>
                        </div>
			        <?php endif; ?>
                    <div class="form-group quarter">
                        <input class="input_st number-input" type="text"
                               name="product_max_value"
                               value="<?php echo carbon_get_post_meta( $ID, 'product_max_value' ); ?>"
                               placeholder="В наявності"/>
                    </div>
                    <div class="form-group quarter">
                        <input class="input_st number-input" type="text" name="product_min_order"
                               value="<?php echo carbon_get_post_meta( $ID, 'product_min_order' ); ?>"
                               placeholder="Мінімальне замовлення"/>
                    </div>
			        <?php if ( $packages ): ?>
                        <div class="form-group quarter">
                            <select class="select_st" name="package">
                                <option disabled="disabled" selected="selected">Упаковка</option>
						        <?php foreach ( $packages as $item ):
							        $attr = '';
							        if ( $_packages ) {
								        foreach ( $_packages as $_item ) {
									        if ( $_item->term_id == $item->term_id ) {
										        $attr = 'selected';
									        }
								        }
							        }
							        ?>
                                    <option value="<?php echo $item->term_id; ?>" <?php echo $attr; ?>>
								        <?php echo $item->name; ?>
                                    </option>
						        <?php endforeach; ?>
                            </select>
                        </div>
			        <?php endif; ?>
			        <?php if ( $delivery_types ): ?>
                        <div class="form-group quarter">
                            <select multiple class="select_st" required name="delivery_types[]">
                                <option disabled="disabled" selected="selected">Умови доставки (виберіть один або декілька)</option>
						        <?php foreach ( $delivery_types as $item ):
							        $_type_item = $item['_type'];
							        $_title_item = $item['title'];
							        $attr = in_array( $_title_item . "[$_type_item]", $_delivery_methods ) ? 'selected' : '';
							        ?>
                                    <option value="<?php echo $_title_item . "[$_type_item]"; ?>" <?php echo $attr; ?>>
								        <?php echo $_title_item; ?>
                                    </option>
						        <?php endforeach; ?>
                            </select>
                        </div>
			        <?php endif; ?>
			        <?php if ( $certificates ): ?>
                        <div class="form-group quarter">
                            <select class="select_st "
                                    name="certificates[]">
                                <option disabled="disabled" selected="selected">Сертифікати</option>
						        <?php foreach ( $certificates as $item ): ?>
                                    <option value="<?php echo $item->term_id; ?>">
								        <?php echo $item->name; ?>
                                    </option>
						        <?php endforeach; ?>
                            </select>
                        </div>
			        <?php endif; ?>
                    <div class="form-group quarter">
                        <select class="select_st" name="year">
                            <option disabled="disabled" selected="selected">Якого року врожай</option>
					        <?php for ( $a = $current_year; $a >= ( $current_year - 60 ); $a -- ):
						        $attr = $_year == $a ? 'selected' : '';
						        ?>
                                <option <?php echo $attr; ?>><?php echo $a; ?></option>
					        <?php endfor; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="create-item__group">
                <div class="create-item__group-left">
                    <div class="cabinet-item">
                        <div class="cabinet-item__title">Опишіть у подробицях</div>
                        <div class="form-description">
                            <div class="form-description__item">
                                <div class="form-group">
                                    <input class="input_st" type="text" name="title" placeholder="Вкажіть назву*"
                                           value="<?php echo get_the_title( $ID ); ?>"
                                           required="required"/>
                                    <div class="form-group__info">Наприклад, Яблуко Голден</div>
                                </div>
                                <div class="form-group position-static">
                                        <textarea class="input_st content-field"
                                                  name="content"
                                                  data-more="<!--more-->"
                                                  placeholder="Що ви хотіли би додати до оголошення?"
                                        ><?php echo strip_tags( get_content_by_id( $ID ) ); ?></textarea>
                                    <div class="form-group__info content-count-js">-/9000</div>
                                    <nav class="context-menu">
                                        <ul class="context-menu__items">
                                            <li class="context-menu__item">
                                                <a href="#" class="context-menu__link btn_st add-tag-more-js">
                                                    <span>Вставити позначку "Читати далі"</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            <div class="form-description__item">
                                <div class="form-description__item-title">Ціна</div>
                                <div class="form-group">
                                    <input
                                            class="input_st number-input"
                                            type="text"
                                            name="price"
                                            value="<?php echo carbon_get_post_meta( $ID, 'product_price' ); ?>"
                                            placeholder="50"
                                            required="required"
                                    />
                                </div>
                            </div>



                            <div class="form-description__item">

                                <div class="continue-group form-group" title="<?php echo $user_company_address; ?>">
                                    <div class="continue-group__text">
                                        <div class="form-description__item-title">Місцезнаходження оголошення</div>
                                        <div class="cabinet-item__text">
                                            Місцезнаходження оголошення однакове з адресою господарства
                                        </div>
                                    </div>
                                    <label class="switch_st">
                                        <input name="is_company_address" class="company-address-checkbox"
	                                        <?php echo carbon_get_post_meta($ID, 'is_company_address') ? 'checked' : ''; ?>
                                               value="true"
                                               data-element=".product-custom-address-container"
                                                type="checkbox"/><span></span>
                                    </label>
                                </div>
                                <div class="product-custom-address-container  <?php echo carbon_get_post_meta($ID, 'is_company_address') ? 'hidden' : ''; ?>">
                                    <div class="form-description__item-title">Місцезнаходження</div>
                                    <div class="form-group">
                                        <input class="input_st address-js" type="text"
                                               name="address"
	                                        <?php echo !carbon_get_post_meta($ID, 'is_company_address') ? 'required' : ''; ?>
                                               id="address-google"
                                               value="<?php echo carbon_get_post_meta( $ID, 'product_address' ); ?>"
                                               placeholder="Місцезнаходження (Місто, індекс)"/>
                                    </div>
                                    <div class="form-horizontal">
                                        <div class="form-group half">
                                            <input class="input_st"
                                                   type="text"
                                                   value="<?php echo $pick_up_address ? $pick_up_address[0]['address'] : ''; ?>"
                                                   name="pick_up_address[]"
                                                   placeholder="Адреса самовивозу"/>
                                        </div>
                                        <div class="form-group half">
                                            <input class="input_st"
                                                   type="text"
                                                   value="<?php echo $pick_up_address ? $pick_up_address[0]['work_time'] : ''; ?>"
                                                   name="pick_up_work_time[]"
                                                   placeholder="09:00 - 22:00"/>
                                        </div>
                                    </div>
	                                <?php if ( $pick_up_address && count( $pick_up_address ) > 1 ): ?>
                                        <div class="wrap-new-adr">
                                            <div class="wrap-new-adr__hide">
				                                <?php foreach ( $pick_up_address as $item ): ?>
                                                    <div class="wrap-new-adr__hide-item append_item">
                                                        <div class="form-description__item-title">Aдеса самовивозу</div>
                                                        <div class="form-horizontal">
                                                            <div class="form-group half">
                                                                <input class="input_st" type="text"
                                                                       name="pick_up_address[]"
                                                                       value="<?php echo $item['address']; ?>"
                                                                       placeholder="Адреса самовивозу" required="required">
                                                            </div>
                                                            <div class="form-group half">
                                                                <input class="input_st" type="text"
                                                                       value="<?php echo $item['address']; ?>"
                                                                       name="pick_up_work_time[]"
                                                                       placeholder="09:00 - 22:00" required="required">
                                                            </div>
                                                        </div>
                                                        <div class="remove-adr">Видалити адресу</div>
                                                    </div>
				                                <?php endforeach; ?>
                                            </div>
                                            <a class="btn_st b_yelloow add-new-adr" href="#">
                                            <span>Додати ще адесу самовивозу<svg xmlns="http://www.w3.org/2000/svg"
                                                                                 xml:space="preserve"
                                                                                 style="enable-background:new 0 0 13 13"
                                                                                 viewBox="0 0 13 13">
                                                            <path d="M11.8 5.3H7.9c-.1 0-.2-.1-.2-.2V1.2C7.7.5 7.1 0 6.5 0S5.3.5 5.3 1.2v3.9c0 .1-.1.2-.2.2H1.2C.5 5.3 0 5.9 0 6.5s.5 1.2 1.2 1.2h3.9c.1 0 .2.1.2.2v3.9c0 .6.5 1.2 1.2 1.2s1.2-.5 1.2-1.2V7.9c0-.1.1-.2.2-.2h3.9c.6 0 1.2-.5 1.2-1.2s-.5-1.2-1.2-1.2z"
                                                                  style="fill:#fff"></path>
                                                        </svg></span>
                                            </a>
                                        </div>
	                                <?php else: ?>
                                        <div class="wrap-new-adr">
                                            <div class="wrap-new-adr__hide"></div>
                                            <a class="btn_st b_yelloow add-new-adr" href="#">
                                            <span>Додати ще адесу самовивозу<svg xmlns="http://www.w3.org/2000/svg"
                                                                                 xml:space="preserve"
                                                                                 style="enable-background:new 0 0 13 13"
                                                                                 viewBox="0 0 13 13">
                                                            <path d="M11.8 5.3H7.9c-.1 0-.2-.1-.2-.2V1.2C7.7.5 7.1 0 6.5 0S5.3.5 5.3 1.2v3.9c0 .1-.1.2-.2.2H1.2C.5 5.3 0 5.9 0 6.5s.5 1.2 1.2 1.2h3.9c.1 0 .2.1.2.2v3.9c0 .6.5 1.2 1.2 1.2s1.2-.5 1.2-1.2V7.9c0-.1.1-.2.2-.2h3.9c.6 0 1.2-.5 1.2-1.2s-.5-1.2-1.2-1.2z"
                                                                  style="fill:#fff"/>
                                                        </svg></span>
                                            </a>
                                        </div>
	                                <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="create-item__group-right">
                    <div class="cabinet-item">
                        <div class="cabinet-item__title">Фото</div>
                        <div class="cabinet-item__text">Перше фото буде на обкладинці оголошення.</div>
                        <div class="cabinet-item__photo">
							<?php if ( $gallery ): ?>
                                <div class="cabinet-item__photo-item">
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
							<?php else: ?>
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
							<?php endif; ?>
                        </div>
                    </div>

                </div>
            </div>
			<?php
			$query = new WP_Query( $args );
			if ( $query->have_posts() ) :
				?>
                <div class="cabinet-item">
                    <div class="cabinet-item__title">
                        Краще смакує разом
                    </div>
                    <div class="cabinet-item__text">
                        Ви можете додати додаткові оголошення, які будуть відображатися в карточці товару.
                    </div>
                    <div class="cabinet-together">
                        <div class="all-list">
                            Всього оголошень: <?php echo $query->found_posts; ?>
                        </div>
                        <div class="select-all">
                            <label class="check_st_item">
                                <input class="check_st check_all" type="checkbox"/>
                                <span> </span>
                            </label>
                            <div class="select-all__text">
                                Оберіть усі потрібні оголошення зі списку, щоб застосувати до них однакові дії
                            </div>
                        </div>
                        <div class="select-product">
							<?php
							while ( $query->have_posts() ) :
								$query->the_post();
								the_product_connected( get_the_ID(), $_products );
							endwhile; ?>
                        </div>
                    </div>
                </div>
			<?php else: ?>
                <div class="cabinet-item">
                    <div class="cabinet-item__title">Краще смакує разом</div>
                    <div class="cabinet-item__text">
                        Ви можете додати додаткові оголошення, які будуть відображатися в карточці товару.
                        <br/>Наразі у вас немає створених оголошень, які ви може додати
                    </div>
                </div>
			<?php endif;
			wp_reset_query();
			wp_reset_postdata(); ?>
            <div class="cabinet-item">
                <div class="accept-group">
                    <div class="accept-title">Перевірте та опублікуйте оголошення</div>
                    <div class="accept-group__link">
						<?php

						?>
                        <a class="btn_st b_yelloow"
                           href="<?php echo $personal_page ? get_the_permalink( $personal_page[0]['id'] ) . '?route=advertisement' : $url; ?>">
                            <span>Скасувати</span>
                        </a>
                        <button class="btn_st" type="submit">
                            <span>Оновити</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
	<?php if ( $map_api_url ): ?>
        <script src="<?php echo $map_api_url; ?>" id="google-map-api" defer></script>
	<?php endif; ?>
    <div class="modal modal-sm" id="created">
        <div class="modal-content text-center">
            <div class="modal-title">
                <div class="modal-title__main">Оголошення оновлено</div>
                <div class="modal-title__subtitle">Наразі воно на модерації</div>
            </div>
            <a class="btn_st"
               href="<?php echo $personal_page ? get_the_permalink( $personal_page[0]['id'] ) . '?route=advertisement' : $url; ?>">
                <span>Мої оголошення </span>
            </a>
        </div>
    </div>
	<?php
}

function the_product_connected( $id = false, $_products = array() ) {
	$_id                  = $id ?: get_the_ID();
	$post_status          = get_post_status( $_id );
	$img                  = get_the_post_thumbnail_url( $_id );
	$title                = get_the_title( $_id );
	$link                 = get_the_permalink( $_id );
	$city                 = carbon_get_post_meta( $_id, 'product_city' );
	$address              = carbon_get_post_meta( $_id, 'product_address' );
	$gallery              = carbon_get_post_meta( $_id, 'product_gallery' );
	$delivery_methods     = carbon_get_post_meta( $_id, 'product_delivery_methods' );
	$min_order            = carbon_get_post_meta( $_id, 'product_min_order' );
	$max_value            = carbon_get_post_meta( $_id, 'product_max_value' );
	$unit                 = carbon_get_post_meta( $_id, 'product_unit' );
	$author_id            = get_post_field( 'post_author', $id );
	$company_name         = carbon_get_post_meta( $id, 'product_company_name' ) ?: carbon_get_user_meta( $author_id, 'user_company_name' );
	$delivery_methods_str = '';
	if ( ! $img && $gallery ) {
		$img = _u( $gallery[0], 1 );
	}
	if ( $delivery_methods ) {
		$delivery_methods_str = implode( ', <br>', $delivery_methods );
		$delivery_methods_str = str_replace(
			array( '[pickup]', '[market]', '[delivery_service]' ),
			array( '', '', '' ),
			$delivery_methods_str
		);
	}
	?>
    <div class="select-product__item">
        <label class="check_st_item">
            <input class="check_st check_all_sub" name="products[]"
                   value="<?php echo $_id; ?>"
				<?php echo $_products && in_array( $_id, $_products ) ? 'checked' : ''; ?>
                   type="checkbox"/>
            <span> </span>
        </label>
        <div class="select-product__item-main">
            <div class="select-product__item-left">
                <div class="select-product__item-media">
					<?php if ( $img ): ?>
                        <img src="<?php echo $img; ?>" alt="<?php echo $title; ?>"/>
					<?php endif; ?>
                </div>
                <div class="select-product__item-info">
                    <div class="select-product__item-id">ID:<?php echo $_id; ?></div>
                    <div class="select-product__item-suptitle">
						<?php echo $company_name; ?>
                    </div>
                    <a class="product-item__title" href="<?php echo $link; ?>">
						<?php echo $title; ?>
                    </a>
                    <div class="select-product__item-place">
						<?php echo $city ?: $address; ?>
                    </div>
                </div>
            </div>
            <div class="select-product__item-right">
                <ul class="select-product__info">
                    <li>
                        <span>Доставка: </span>
                        <strong><?php echo $delivery_methods_str; ?></strong>
                    </li>
					<?php if ( $min_order ): ?>
                        <li>
                            <span>Мінімальний заказ:</span>
                            <strong>від <?php echo $min_order . $unit; ?></strong>
                        </li>
					<?php endif; ?>
					<?php if ( $max_value ): ?>
                        <li>
                            <span>В наявності: </span>
                            <strong><?php echo $max_value . $unit; ?></strong>
                        </li>
					<?php endif; ?>
                </ul>
                <div class="product-item__price">
					<?php echo get_price_html( $id ); ?>
                </div>
            </div>
        </div>
    </div>
	<?php
}