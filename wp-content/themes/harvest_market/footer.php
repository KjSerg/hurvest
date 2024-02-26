<?php
global $wp_query;
$var                = variables();
$set                = $var['setting_home'];
$assets             = $var['assets'];
$url                = $var['url'];
$admin_ajax         = $var['admin_ajax'];
$id                 = get_the_ID();
$isLighthouse       = isLighthouse();
$size               = $isLighthouse ? 'thumbnail' : 'full';
$policy_page_id     = (int) get_option( 'wp_page_for_privacy_policy' );
$logo               = carbon_get_theme_option( 'footer_logo' );
$description        = carbon_get_theme_option( 'footer_description' );
$contacts           = carbon_get_theme_option( 'footer_contacts' );
$products_data      = get_products_data();
$get_route          = $_GET['route'] ?? '';
$get_place          = $_GET['place'] ?? '';
$_user_city         = $_COOKIE['user_city'] ?? '';
$_user_confirm_city = $_COOKIE['user_confirm_city'] ?? '';
$_radius            = $_GET['radius'] ?? '10';
$_min_price         = $_GET['min-price'] ?? ( $products_data['min_price'] ?: '1' );
$_max_price         = $_GET['max-price'] ?? ( $products_data['max_price'] ?: '10000000' );
$product_types      = get_terms( array(
	'taxonomy'   => 'product_type',
	'hide_empty' => false
) );
$processing_types   = get_terms( array(
	'taxonomy'   => 'processing_type',
	'hide_empty' => false
) );
$package            = get_terms( array(
	'taxonomy'   => 'package',
	'hide_empty' => false
) );
$categories         = get_terms( array(
	'taxonomy'   => 'categories',
	'hide_empty' => false,
	'parent'     => 0,
) );
$certificates       = get_terms( array(
	'taxonomy'   => 'certificates',
	'hide_empty' => false,
) );
$units_measurement  = carbon_get_theme_option( 'units_measurement' );
$delivery_types     = get_delivery_methods();
$current_year       = date( "Y" );
$_type              = $_GET['type'] ?? '';
$_certificates      = $_GET['certificate'] ?? '';
$_processing_types  = $_GET['processing_types'] ?? '';
$_units_measurement = $_GET['units_measurement'] ?? '';
$_packages          = $_GET['packages'] ?? '';
$_delivery_methods  = $_GET['delivery_methods'] ?? '';
$_y                 = $_GET['y'] ?? '';
$personal_area_page = carbon_get_theme_option( 'personal_area_page' );
$is_personal_area   = $personal_area_page && ( (int) $personal_area_page[0]['id'] == $id );
$route              = $_GET['route'] ?? '';
$order              = $_GET['order'] ?? '';
$orderby            = $_GET['orderby'] ?? '';
$filter             = $_GET['filter'] ?? '';
$filters            = array();
$category           = $_GET['category'] ?? '';
$category           = explode( ',', $category );
$filter_form_url    = $url;
$user_id            = get_current_user_id();
if ( $_certificates ) {
	$_certificates = ! is_array( $_certificates ) ? explode( ',', $_certificates ) : $_certificates;
}
if ( $_type ) {
	$_type = ! is_array( $_type ) ? explode( ',', $_type ) : $_type;
}
if ( $_processing_types ) {
	$_processing_types = ! is_array( $_processing_types ) ? explode( ',', $_processing_types ) : $_processing_types;
}
if ( $_packages ) {
	$_packages = ! is_array( $_packages ) ? explode( ',', $_packages ) : $_packages;
}
if ( $_units_measurement ) {
	$_units_measurement = ! is_array( $_units_measurement ) ? explode( ',', $_units_measurement ) : $_units_measurement;
}
if ( $_delivery_methods ) {
	$_delivery_methods = ! is_array( $_delivery_methods ) ? explode( ',', $_delivery_methods ) : $_delivery_methods;
}
if ( is_author() ) {
	$current_author    = $wp_query->get_queried_object();
	$current_author_id = $current_author->ID;
	$filter_form_url   = get_author_posts_url( $current_author_id );
}
$user_location = get_user_location();
?>
</main>
<?php if ( ! $is_personal_area ): ?>
    <div class="filter">
        <div class="filter-top">
            <div class="filter-top__title">Фільтри</div>
            <div class="filter-close"></div>
        </div>
        <div class="filter-list-wrap">
            <form class="filter-form" method="post" action="<?php echo $filter_form_url; ?>">
                <div class="filter-form-box" style="display:none;"></div>
				<?php
				if ( $order ) {
					echo "<input type='hidden' name='order' value='$order'>";
				}
				if ( $orderby ) {
					echo "<input type='hidden' name='orderby' value='$orderby'>";
				}
				?>
                <div class="filter-list js-collapse">
                    <div class="filter-list__item js-collapse-item">
                        <div class="filter-list__item-title js-collapse-title"> Населений пункт</div>
                        <div class="filter-list__item-content js-collapse-content position-relative">
                            <input class="input_st filter-place-input" type="text" name="place"
                                   value="<?php echo $get_place; ?>"
                                   placeholder="Назва населеного пункту"/>
                            <div class="products-places-list"></div>
                        </div>
                    </div>
                    <div class="filter-list__item js-collapse-item">
                        <div class="filter-list__item-title js-collapse-title"> Радіус у кілометрах</div>
                        <div class="filter-list__item-content js-collapse-content">
                            <div class="filter-range">
                                <div class="filter-range__info">
                                    До<input class="input_st js-input-from js-input-radius" type="text"
                                             data-name="radius"
                                             readonly
                                             value=""/>
                                </div>
                                <input class="js-range" type="text" data-min="5" data-max="100"
                                       data-from="<?php echo $_radius; ?>"/>
                            </div>
                        </div>
                    </div>
					<?php if ( $categories ): $subtitem = 0; ?>
                        <div class="filter-list__item js-collapse-item">
                            <div class="filter-list__item-title js-collapse-title"> Категорія продукту</div>
                            <div class="filter-list__item-content js-collapse-content">
                                <div class="form-group quarter ">
                                    <select class="select_st categories-select-js "
                                            data-selector=".sub-categories-select-js" data-name="category"
                                    >
                                        <option disabled="disabled" selected="selected">Категорія продукту</option>
										<?php foreach ( $categories as $item ):
											$attr = '';
											if ( in_array( $item->term_id, $category ) ) {
												$attr     = 'selected';
												$subtitem = $item->term_id;
												$filters  = get_filter_by_category( $subtitem );
											}
											?>
                                            <option <?php echo $attr ?> value="<?php echo $item->term_id; ?>">
												<?php echo $item->name; ?>
                                            </option>
										<?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="<?php echo $subtitem ? '' : 'hidden'; ?> filter-list__item js-collapse-item">
                            <div class="filter-list__item-title js-collapse-title"> Підкатегорія продукту</div>
                            <div class="filter-list__item-content js-collapse-content">
                                <div class="form-group quarter <?php echo $subtitem ? '' : ' not-active'; ?>">
                                    <select class="select_st sub-categories-select-js categories-select-js"
                                            data-name="category"
                                            data-selector=".internal-categories-select-js">
                                        <option disabled="disabled" selected="selected">Підкатегорія продукту</option>
                                        <option value="">Зробіть вибір</option>
										<?php if ( $subtitem ) {
											$subcategories = get_terms( array(
												'taxonomy'   => 'categories',
												'hide_empty' => false,
												'parent'     => $subtitem,
											) );
											$subtitem      = 0;
											if ( $subcategories ) {
												foreach ( $subcategories as $subcategory ) {
													$attr = '';
													if ( in_array( $subcategory->term_id, $category ) ) {
														$attr     = 'selected';
														$subtitem = $subcategory->term_id;
														$filters  = get_filter_by_category( $subtitem );
													}
													?>
                                                    <option <?php echo $attr ?>
                                                            value="<?php echo $subcategory->term_id; ?>">
														<?php echo $subcategory->name; ?>
                                                    </option>
													<?php
												}
											}
										} ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="<?php echo $subtitem ? '' : 'hidden'; ?> filter-list__item js-collapse-item">
                            <div class="filter-list__item-title js-collapse-title"> Тип або вид продукту</div>
                            <div class="filter-list__item-content js-collapse-content">
                                <div class="form-group quarter <?php echo $subtitem ? '' : ' not-active'; ?>">
                                    <select class="select_st categories-select-js internal-categories-select-js"
                                            data-name="category"
                                            data-selector=".sub-internal-categories-select-js">
                                        <option disabled="disabled">Тип або вид продукту</option>
                                        <option value="">Зробіть вибір</option>
										<?php if ( $subtitem ) {
											$subcategories = get_terms( array(
												'taxonomy'   => 'categories',
												'hide_empty' => false,
												'parent'     => $subtitem,
											) );
											$subtitem      = 0;
											if ( $subcategories ) {
												foreach ( $subcategories as $subcategory ) {
													$attr = '';
													if ( in_array( $subcategory->term_id, $category ) ) {
														$attr     = 'selected';
														$subtitem = $subcategory->term_id;
														$filters  = get_filter_by_category( $subtitem );
													}
													?>
                                                    <option <?php echo $attr ?>
                                                            value="<?php echo $subcategory->term_id; ?>">
														<?php echo $subcategory->name; ?>
                                                    </option>
													<?php
												}
											}
										} ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="<?php echo $subtitem ? '' : 'hidden'; ?> filter-list__item js-collapse-item">
                            <div class="filter-list__item-title js-collapse-title"> Підкатегорія типу або виду
                                продукту
                            </div>
                            <div class="filter-list__item-content js-collapse-content">
                                <div class="form-group quarter <?php echo $subtitem ? '' : ' not-active'; ?>">
                                    <select class="select_st  sub-internal-categories-select-js"
                                            data-name="category"
                                    >
                                        <option disabled="disabled">Підкатегорія типу або виду продукту</option>
                                        <option value="">Зробіть вибір</option>
										<?php if ( $subtitem ) {
											$subcategories = get_terms( array(
												'taxonomy'   => 'categories',
												'hide_empty' => false,
												'parent'     => $subtitem,
											) );
											$subtitem      = 0;
											if ( $subcategories ) {
												foreach ( $subcategories as $subcategory ) {
													$attr = '';
													if ( in_array( $subcategory->term_id, $category ) ) {
														$attr     = 'selected';
														$subtitem = $subcategory->term_id;
														$filters  = get_filter_by_category( $subtitem );
													}
													?>
                                                    <option <?php echo $attr ?>
                                                            value="<?php echo $subcategory->term_id; ?>">
														<?php echo $subcategory->name; ?>
                                                    </option>
													<?php
												}
											}
										} ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="<?php echo $filters ? '' : 'hidden'; ?> filter-list__item js-collapse-item">
                            <div class="filter-list__item-title js-collapse-title"> Додаткові фільтри продукту</div>
                            <div class="filter-list__item-content js-collapse-content">
                                <div class="form-group quarter <?php echo $filters ? '' : ' not-active'; ?>">
                                    <select class="select_st filter-select-js" multiple
                                            name="filter[]">
                                        <option disabled="disabled">Додаткові фільтри продукту</option>
										<?php
										if ( $filters ) {
											foreach ( $filters as $filter_id ) {
												$filter_item = get_term_by( 'id', $filter_id, 'filters' );
												if ( $filter_item ) {
													$attr_filter_item = 'disabled';
													?>
                                                    <option <?php echo $attr_filter_item; ?>
                                                    value="<?php echo $filter_item->term_id; ?>"><?php echo $filter_item->name; ?></option><?php
													$values = get_term_children( $filter_id, 'filters' );
													if ( $values ) {
														foreach ( $values as $value ) {
															$child = get_term_by( 'id', $value, 'filters' );
															$attr  = '';
															if ( $filter ) {
																foreach ( $filter as $_filter ) {
																	if ( in_array( $child->term_id, $filter ) ) {
																		$attr = 'selected';
																	}
																}
															}
															?>
                                                        <option <?php echo $attr; ?>
                                                                value="<?php echo $child->term_id; ?>">
                                                            --<?php echo $child->name; ?></option><?php
														}
													}
												}
											}
										}

										?>
                                    </select>
                                </div>
                            </div>
                        </div>
					<?php endif; ?>
                    <div class="form-wrap-hidden <?php echo isset( $_GET['min-price'] ) ? '' : 'hidden'; ?>">
                        <div class="filter-list__item js-collapse-item">
                            <div class="filter-list__item-title js-collapse-title">Ціна</div>
                            <div class="filter-list__item-content js-collapse-content">
                                <div class="filter-range">
                                    <div class="filter-range__info">
                                        Від<input class="input_st js-input-from" name="min-price" type="text" readonly
                                                  value="<?php echo $_min_price; ?>"/>
                                        до <input class="input_st js-input-to" name="max-price" type="text" readonly
                                                  value="<?php echo $_max_price; ?>"/>
                                    </div>
                                    <input class="js-range" type="text"
                                           data-min="<?php echo( $products_data['min_price'] ?: '1' ); ?>"
                                           data-max="<?php echo( $products_data['max_price'] ?: '10000000' ); ?>"
                                           data-from="<?php echo $_min_price; ?>"
                                           data-to="<?php echo $_max_price; ?>" data-type="double"/>
                                </div>
                            </div>
                        </div>
						<?php if ( $product_types ): ?>
                            <div class="filter-list__item js-collapse-item">
                                <div class="filter-list__item-title js-collapse-title"> Тип продукту</div>
                                <div class="filter-list__item-content js-collapse-content">
                                    <div class="filter-check">
										<?php foreach ( $product_types as $type ):
											$test = $_type && in_array( $type->term_id, $_type );
											$attr = $test ? 'checked' : '';
											?>
                                            <div class="filter-check__item">
                                                <label class="check-item">
                                                    <input class="check_st filter-check-input"
                                                           data-name="type"
														<?php echo $attr; ?>
                                                           value="<?php echo $type->term_id; ?>" type="checkbox"/>
                                                    <span></span>
                                                    <i class="check-item__text"><?php echo $type->name; ?></i>
                                                </label>
                                            </div>
										<?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
						<?php endif; ?>
						<?php if ( $certificates ): ?>
                            <div class="filter-list__item js-collapse-item">
                                <div class="filter-list__item-title js-collapse-title"> Сертифікати</div>
                                <div class="filter-list__item-content js-collapse-content">
                                    <div class="filter-check">
										<?php foreach ( $certificates as $type ):
											$test = $_certificates && in_array( $type->term_id, $_certificates );
											$attr = $test ? 'checked' : '';
											?>
                                            <div class="filter-check__item">
                                                <label class="check-item">
                                                    <input class="check_st filter-check-input"
                                                           data-name="certificate"
														<?php echo $attr; ?>
                                                           value="<?php echo $type->term_id; ?>" type="checkbox"/>
                                                    <span></span>
                                                    <i class="check-item__text"><?php echo $type->name; ?></i>
                                                </label>
                                            </div>
										<?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
						<?php endif; ?>
						<?php if ( $processing_types ): ?>
                            <div class="filter-list__item js-collapse-item">
                                <div class="filter-list__item-title js-collapse-title">Тип обробки</div>
                                <div class="filter-list__item-content js-collapse-content">
                                    <div class="filter-check">
										<?php foreach ( $processing_types as $type ):
											$test = $_processing_types && in_array( $type->term_id, $_processing_types );
											$attr = $test ? 'checked' : '';
											?>
                                            <div class="filter-check__item">
                                                <label class="check-item">
                                                    <input class="check_st filter-check-input"
                                                           data-name="processing_types" <?php echo $attr; ?>
                                                           value="<?php echo $type->term_id; ?>"
                                                           type="checkbox"
                                                    />
                                                    <span></span>
                                                    <i class="check-item__text"><?php echo $type->name; ?></i>
                                                </label>
                                            </div>
										<?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
						<?php endif; ?>
						<?php if ( $package ): ?>
                            <div class="filter-list__item js-collapse-item">
                                <div class="filter-list__item-title js-collapse-title">Тип пакування</div>
                                <div class="filter-list__item-content js-collapse-content">
                                    <div class="filter-check">
										<?php foreach ( $package as $item ):
											$test = $_packages && in_array( $item->term_id, $_packages );
											$attr = $test ? 'checked' : '';
											?>
                                            <div class="filter-check__item">
                                                <label class="check-item">
                                                    <input class="check_st filter-check-input"
                                                           data-name="packages" <?php echo $attr; ?>
                                                           value="<?php echo $item->term_id; ?>"
                                                           type="checkbox"/>
                                                    <span> </span>
                                                    <i class="check-item__text"><?php echo $item->name; ?></i>
                                                </label>
                                            </div>
										<?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
						<?php endif; ?>
						<?php if ( $units_measurement ): ?>
                            <div class="filter-list__item js-collapse-item">
                                <div class="filter-list__item-title js-collapse-title">Одиниці вимірювання</div>
                                <div class="filter-list__item-content js-collapse-content">
                                    <div class="filter-check">
										<?php foreach ( $units_measurement as $item ):
											$test = $_units_measurement && in_array( $item['unit'], $_units_measurement );
											$attr = $test ? 'checked' : '';
											?>
                                            <div class="filter-check__item">
                                                <label class="check-item">
                                                    <input class="check_st filter-check-input"
                                                           data-name="units_measurement"
                                                           value="<?php echo $item['unit']; ?>"
														<?php echo $attr; ?>
                                                           type="checkbox"/>
                                                    <span> </span>
                                                    <i class="check-item__text"><?php echo $item['unit']; ?></i>
                                                </label>
                                            </div>
										<?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
						<?php endif; ?>
						<?php if ( $delivery_types ): ?>
                            <div class="filter-list__item js-collapse-item">
                                <div class="filter-list__item-title js-collapse-title">Умови доставки</div>
                                <div class="filter-list__item-content js-collapse-content">
                                    <div class="filter-check">

										<?php foreach ( $delivery_types as $key => $item ):
											$test = $_delivery_methods && in_array( $key, $_delivery_methods );
											$attr = $test ? 'checked' : '';
											?>
                                            <div class="filter-check__item">
                                                <label class="check-item">
                                                    <input class="check_st filter-check-input"
                                                           data-name="delivery_methods"
                                                           value="<?php echo $key; ?>"
														<?php echo $attr; ?>
                                                           type="checkbox"/>
                                                    <span> </span>
                                                    <i class="check-item__text"><?php echo $item; ?></i>
                                                </label>
                                            </div>
										<?php endforeach; ?>

                                    </div>
                                </div>
                            </div>
						<?php endif; ?>
                        <div class="filter-list__item js-collapse-item">
                            <div class="filter-list__item-title js-collapse-title">Рік врожаю/виготовлення</div>
                            <div class="filter-list__item-content js-collapse-content">
                                <select class="select_st trigger-submit-on-change" name="y">
                                    <option value="">Зробіть вибір</option>
									<?php for ( $a = $current_year; $a >= ( $current_year - 60 ); $a -- ):
										$attr = $a == $_y ? 'selected' : '';
										?>
                                        <option value="<?php echo $a; ?>" <?php echo $attr; ?>>
											<?php echo $a; ?>
                                        </option>
									<?php endfor; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="filter-bot">
            <a class="btn_st w100" href="<?php echo $filter_form_url; ?>">
            <span>Очистити фільтр<svg xmlns="http://www.w3.org/2000/svg"
                                      xml:space="preserve"
                                      style="enable-background:new 0 0 17.3 20"
                                      viewBox="0 0 17.3 20">
                            <path d="M5.8 0c-.5 0-.9.4-.9.9v1.8h-4c-.5 0-.9.4-.9.9s.4.9.9.9h15.6c.5 0 .9-.4.9-.9s-.4-.9-.9-.9h-4V.9c0-.5-.4-.9-.9-.9H5.8zm.9 2.6v-.8h4.1v.8H6.7zM15.5 6.4H1.7v10.2c0 2 1.4 3.5 3.3 3.5h7.2c1.9 0 3.3-1.5 3.3-3.5V6.4zm-8 3.2c0-.5-.4-.9-.9-.9s-.9.4-.9.9v6.1c0 .5.4.9.9.9s.9-.4.9-.9V9.6zm4.1 0c0-.5-.4-.9-.9-.9s-.9.4-.9.9v6.1c0 .5.4.9.9.9s.9-.4.9-.9V9.6z"
                                  style="fill-rule:evenodd;clip-rule:evenodd;fill:#fff"/>
                        </svg></span>
            </a>
        </div>
    </div>
    <footer class="footer <?php echo ! is_front_page() ? 'line_top' : ''; ?>">
        <div class="container">
            <div class="footer-bot">
                <div class="copyright"><?php echo carbon_get_theme_option( 'copyright' ); ?></div>
                <ul class="footer-nav">
					<?php
					$menu = wp_nav_menu(
						array(
							'theme_location' => 'footer_menu',
							'menu'           => 'Меню в підвалі',
							'items_wrap'     => '%3$s',
							'container'      => '',
							'link_before'    => '',
							'link_after'     => '',
							'echo'           => 0
						)
					);
					echo $menu;
					?>
                </ul>
            </div>
        </div>
    </footer>
<?php endif; ?>
<div class="modal modal-sm" id="dialog">
    <div class="modal-content text-center">
        <div class="modal-title">
            <div class="modal-title__main"></div>
            <div class="modal-title__subtitle">Ваше оголошення успішно створене. Наразі воно на модерації</div>
        </div>
    </div>
</div>
<script>
    var userID = <?php echo $user_id;  ?>;
    var admin_ajax = '<?php echo $var['admin_ajax']; ?>';
    var inCartStr = "У кошик";
    var inCartAddedStr = "Додано";
    var verifiedSTR = "Верифікований";
    var locationErrorString = "Виберіть локацію зі списку запропонованих варіантів";
    var errorPswMsg = "Пароль має містити щонайменше 6 символів, цифру та латинські літери, причому принаймні одна з них має бути великою";
    var daysOfWeek = ["Нд", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"];
    var monthNames = ["Січень", "Лютий", "Березень", "Квітень", "Травень", "Червень", "Липень", "Серпень", "Вересень", "Жовтень", "Листопад", "Грудень"];
    var minDate = '<?php echo date( "d/m/Y", time() + strtotime( '1 day', 0 ) ); ?>';
</script>
<div class="modal modal-sm" id="modal_place">
    <div class="modal-content">
        <div class="modal-title">
            <div class="modal-title__main">Місцезнаходження</div>
            <div class="modal-title__subtitle">
                Ваше місцезнаходження
				<?php if ( $_user_city ): ?>
                    <span style="color:#4D76FF" class="confirmed-city-js"><?php echo $_user_city; ?></span>
				<?php else: ?>
                    <span style="color:#4D76FF" class="city-js"></span>
				<?php endif; ?>
            </div>
        </div>
        <a class="btn_st b_yelloow w100 confirm-city" href="#"
           data-city="<?php echo trim( $_user_city ); ?>"
           data-lat="<?php echo trim( $user_location['lat'] ); ?>"
           data-lon="<?php echo trim( $user_location['lon'] ); ?>"
        >
            <span>Підтвердити</span>
        </a>
		<?php if ( $_user_confirm_city ): ?>
            <a class="btn_st b_yelloow w100 confirm-city" href="#"
               style="margin-top: 2rem;"
               data-city=""
               data-lat="<?php echo trim( $user_location['lat'] ); ?>"
               data-lon="<?php echo trim( $user_location['lon'] ); ?>"
            >
                <span>Вся Україна</span>
            </a>
		<?php endif; ?>
        <div class="change-place">
            <div class="modal-title">
                <div class="modal-title__main">Або вкажіть свою локацію</div>
            </div>
            <form class="products-places-form" method="post">
                <input type="hidden" name="action" value="get_products_places">
                <div class="form-group">
                    <input class="input_st products-places-autocomplete"
                           type="text"
                           autocomplete="off"
                           name="city"
                           placeholder="Ваше місто" required="required"/>
                    <ul class="products-places-list"></ul>
                </div>
                <a class="btn_st w100 confirm-city hidden" href="#">
                    <span>Підтвердити </span>
                </a>
            </form>
        </div>
    </div>
</div>
<?php if ( ! $isLighthouse ): ?>
    <div class="preloader">
        <img src="<?php echo $assets; ?>img/loading.gif" alt="loading.gif">
    </div>
<?php endif; ?>
<div class="scroll_top">
    <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" style="enable-background:new 0 0 8 15"
         viewBox="0 0 8 15">
            <path d="M4.7 14.3v-12l2.2 2.2c.6.6 1.6-.3.9-1L4.5.2c-.3-.3-.7-.3-.9 0L.2 3.6c-.1.1-.2.3-.2.5 0 .6.7.9 1.1.5l2.2-2.2v12c.1.9 1.4.8 1.4-.1z"
                  style="fill:#262c40"/>
        </svg>
</div>
<?php wp_footer(); ?>

</body>

</html>