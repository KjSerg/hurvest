<?php
$id        = get_the_ID();
$author_id = get_post_field( 'post_author', $id );
get_header( 'seller' );
global $wp_query;
$is_logged            = is_user_logged_in();
$var                  = variables();
$set                  = $var['setting_home'];
$assets               = $var['assets'];
$url                  = $var['url'];
$url_home             = $var['url_home'];
$id                   = get_the_ID();
$isLighthouse         = isLighthouse();
$size                 = isLighthouse() ? 'thumbnail' : 'full';
$current_author_id    = $author_id ?: $wp_query->get_queried_object()->ID;
$user_id              = (int) $current_author_id;
$current_user_id      = get_current_user_id();
$current_user         = get_user_by( 'ID', $user_id );
$email                = $current_user->user_email ?: '';
$display_name         = $current_user->display_name ?: '';
$first_name           = $current_user->first_name ?: '';
$last_name            = $current_user->last_name ?: '';
$name                 = $first_name ?: $display_name;
$user_avatar          = carbon_get_user_meta( $user_id, 'user_avatar' );
$user_seller          = carbon_get_user_meta( $user_id, 'user_seller' );
$avatar_url           = $user_avatar ? _u( $user_avatar, 1 ) : get_avatar_url( $user_id );
$_order               = $_GET['order'] ?? '';
$_orderby             = $_GET['orderby'] ?? '';
$pagenum              = $_GET['pagenum'] ?? 1;
$days_count           = carbon_get_theme_option( 'days_count' ) ?: 30;
$posts_per_page       = get_option( 'posts_per_page' );
$categories           = get_terms( array(
	'taxonomy'   => 'categories',
	'hide_empty' => false,
	'parent'     => 0,
) );
$company_description  = carbon_get_user_meta( $user_id, 'user_company_description' );
$verification         = carbon_get_user_meta( $user_id, 'user_verification' );
$user_company_name    = carbon_get_user_meta( $user_id, 'user_company_name' ) ?: '';
$delivery_count       = carbon_get_user_meta( $user_id, 'delivery_count' ) ?: 0;
$user_company_gallery = carbon_get_user_meta( $user_id, 'user_company_gallery' );
$user_phone           = carbon_get_user_meta( $user_id, 'user_phone' );
$user_company_address = carbon_get_user_meta( $user_id, 'user_company_address' );
$user_company_color   = carbon_get_user_meta( $user_id, 'user_company_color' );
$user_company_logo    = carbon_get_user_meta( $user_id, 'user_company_logo' );
$seller_rating        = get_seller_rating( $user_id );
$seller_count_review  = get_seller_count_review( $user_id );
$user_link            = get_seller_page_link( $author_id );
$head_banner          = $user_company_gallery ? _u( $user_company_gallery[0], 1 ) : $assets . 'img/user_head.webp';
$attr                 = $user_company_color ? "style='background-color:$user_company_color'" : '';

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

?>

<main class="content" <?php echo $attr; ?>>
	<?php if ( $user_company_gallery ): ?>
        <section class="section-head">
            <div class="container">
                <div class="head-slider head-seller-slider">
					<?php foreach ( $user_company_gallery as $image_id ): ?>
                        <div>
                            <div class="head-slider__item head-seller-slider__item">
                                <img src="<?php _u( $image_id ); ?>" alt="<?php echo $user_company_name; ?>"/>
                            </div>
                        </div>
					<?php endforeach; ?>
                </div>
            </div>
        </section>
	<?php endif; ?>
    <section class="section-info">
        <div class="container">
            <div class="info-top">
                <div class="info-top__user">
					<?php if ( $user_company_logo ): ?>
                        <div class="info-top__user-media">
                            <img src="<?php _u( $user_company_logo ); ?>" alt="<?php echo $user_company_name; ?>"/>
                        </div>
					<?php endif; ?>
                    <div class="info-top__user-content">
                        <div class="info-top__user-suptitle">Вас вітає!</div>
                        <div class="info-top__user-title"><?php echo $user_company_name; ?></div>
                    </div>
                </div>
                <div class="info-top__nav">
                    <a class="active" href="<?php echo $user_link; ?>">Каталог продавця</a>
                    <a href="<?php echo $user_link . '?route=about'; ?>">Про продавця</a>
                    <a href="<?php echo $user_link . '?route=reviews'; ?>">
                        Відгуки (<?php echo $seller_count_review ?: 0; ?>)
                    </a>
                    <a href="<?php echo $user_link . '?route=contacts'; ?>">Контакти</a>
                </div>
            </div>
            <div class="info-catalog">
                <div class="info-catalog__filter-wrap">
                    <div class="info-catalog__filter">
                        <div class="info-catalog__filter-top">
                            <div class="info-catalog__filter-title">Фільтр</div>
                            <div class="filter-close"></div>
                        </div>
                        <div class="filter-list-wrap"></div>
                        <form class="filter-form" method="get" action="<?php echo $user_link; ?>">
                            <div class="filter-form-box" style="display:none;"></div>
	                        <?php
	                        if ( $order ) {
		                        echo "<input type='hidden' name='order' value='$order'>";
	                        }
	                        if ( $orderby ) {
		                        echo "<input type='hidden' name='orderby' value='$orderby'>";
	                        }
	                        ?>
                            <div class="filter-list">
                                <div class="filter-list__item active">
                                    <div class="filter-list__item-title">Ціна</div>
                                    <div class="filter-list__item-content">
                                        <div class="filter-range">
                                            <div class="filter-range__info">
                                                Від<input class="input_st js-input-from" name="min-price" type="text"
                                                          readonly
                                                          value="<?php echo $_min_price; ?>"/>
                                                до <input class="input_st js-input-to" name="max-price" type="text"
                                                          readonly
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
								<?php if ( $categories ): $subtitem = 0; ?>
                                    <div class="filter-list__item ">
                                        <div class="filter-list__item-title "> Категорія продукту</div>
                                        <div class="filter-list__item-content js-collapse-content">
                                            <div class="form-group quarter ">
                                                <select class="select_st categories-select-js "
                                                        data-selector=".sub-categories-select-js" data-name="category"
                                                >
                                                    <option disabled="disabled" selected="selected">Категорія продукту
                                                    </option>
													<?php foreach ( $categories as $item ):
														$attr = '';
														if ( in_array( $item->term_id, $category ) ) {
															$attr     = 'selected';
															$subtitem = $item->term_id;
															$filters  = get_filter_by_category( $subtitem );
														}
														?>
                                                        <option <?php echo $attr ?>
                                                                value="<?php echo $item->term_id; ?>">
															<?php echo $item->name; ?>
                                                        </option>
													<?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="<?php echo $subtitem ? '' : 'hidden'; ?> filter-list__item ">
                                        <div class="filter-list__item-title "> Підкатегорія продукту</div>
                                        <div class="filter-list__item-content js-collapse-content">
                                            <div class="form-group quarter <?php echo $subtitem ? '' : ' not-active'; ?>">
                                                <select class="select_st sub-categories-select-js categories-select-js"
                                                        data-name="category"
                                                        data-selector=".internal-categories-select-js">
                                                    <option disabled="disabled" selected="selected">Підкатегорія
                                                        продукту
                                                    </option>
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

                                    <div class="<?php echo $subtitem ? '' : 'hidden'; ?> filter-list__item ">
                                        <div class="filter-list__item-title "> Тип або вид продукту</div>
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

                                    <div class="<?php echo $subtitem ? '' : 'hidden'; ?> filter-list__item ">
                                        <div class="filter-list__item-title ">
                                            Підкатегорія типу або виду продукту
                                        </div>
                                        <div class="filter-list__item-content js-collapse-content">
                                            <div class="form-group quarter <?php echo $subtitem ? '' : ' not-active'; ?>">
                                                <select class="select_st  sub-internal-categories-select-js"
                                                        data-name="category"
                                                >
                                                    <option disabled="disabled">Підкатегорія типу або виду продукту
                                                    </option>
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

                                    <div class="<?php echo $filters ? '' : 'hidden'; ?> filter-list__item ">
                                        <div class="filter-list__item-title "> Додаткові фільтри продукту</div>
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
								<?php if ( $product_types ): ?>
                                    <div class="filter-list__item ">
                                        <div class="filter-list__item-title "> Тип продукту</div>
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
                                                                   value="<?php echo $type->term_id; ?>"
                                                                   type="checkbox"/>
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
                                    <div class="filter-list__item " style="display:none;">
                                        <div class="filter-list__item-title "> Сертифікати</div>
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
                                                                   value="<?php echo $type->term_id; ?>"
                                                                   type="checkbox"/>
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
                                    <div class="filter-list__item ">
                                        <div class="filter-list__item-title ">Тип обробки</div>
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
                                    <div class="filter-list__item ">
                                        <div class="filter-list__item-title ">Тип пакування</div>
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
                                    <div class="filter-list__item ">
                                        <div class="filter-list__item-title ">Одиниці вимірювання</div>
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
                                    <div class="filter-list__item ">
                                        <div class="filter-list__item-title ">Умови доставки</div>
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
                                <div class="filter-list__item ">
                                    <div class="filter-list__item-title ">Рік врожаю/виготовлення</div>
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
                        </form>
                        <div class="filter-bot">
                            <a class="btn_st b_red w100" href="<?php echo $user_link; ?>">
                                <span> <svg
                                            xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                            style="enable-background:new 0 0 13 15" viewBox="0 0 13 15">
                                            <path d="M11.8 1.9H9v-.5C9 .6 8.3 0 7.5 0h-2C4.7 0 4 .6 4 1.4v.5H1.2C.6 1.9 0 2.4 0 3v1c0 .3.2.5.5.5h12c.3 0 .5-.3.5-.5V3c0-.6-.6-1.1-1.2-1.1zM5 1.4c0-.3.2-.5.5-.5h2c.3 0 .5.2.5.5v.5H5v-.5zM.9 5.4c-.1 0-.2.1-.2.2l.4 8.1c0 .8.7 1.3 1.5 1.3h7.6c.8 0 1.5-.6 1.5-1.3l.4-8.1c0-.1-.1-.2-.2-.2H.9zm7.6 1.2c0-.3.2-.5.5-.5s.5.2.5.5v6.1c0 .3-.2.5-.5.5s-.5-.2-.5-.5V6.6zM6 6.6c0-.3.2-.5.5-.5s.5.2.5.5v6.1c0 .3-.2.5-.5.5s-.5-.3-.5-.5V6.6zm-2.5 0c0-.3.2-.5.5-.5s.5.2.5.5v6.1c0 .3-.2.5-.5.5s-.5-.2-.5-.5V6.6z"
                                                  style="fill:#fc3636"/>
                                        </svg><span>Очистити фільтр</span></span></a></div>
                    </div>
                </div>
                <div class="info-catalog__main">
                    <div class="info-catalog__main-top">
                        <div class="info-catalog__main-title">Всі товари</div>
                        <form class="info-catalog-sort" method="get" action="<?php echo $user_link; ?>">
                            <input type="hidden" name="order" value="desc">
							<?php
							if ( $_GET ) {
								foreach ( $_GET as $name => $value ) {
									if ( $name && $value ) {
                                        if(is_array($value)){
                                            foreach ($value as $item){
	                                            echo "<input type='hidden' name='$name".'[]'."' value='$item'>";
                                            }
                                        }else{
	                                        echo "<input type='hidden' name='$name' value='$value'>";
                                        }
									}
								}
							}
							?>
                            <label class="info-catalog-sort__item">
                                <input type="radio" name="orderby"/>
                                <span> За популярністю</span>
                            </label>
                            <label class="info-catalog-sort__item">
                                <input type="radio" name="orderby"/>
                                <span> Дешевші</span>
                            </label>
                            <label class="info-catalog-sort__item">
                                <input type="radio" name="orderby"/>
                                <span> Дорожчі</span>
                            </label>
                        </form>
                    </div>
                    <div class="catalog container-js">
						<?php
						$query = get_query_index_data( array( 'author__in' => array( $user_id ) ) );
						if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>
							<?php the_mini_product(); ?>
						<?php endwhile; else : ?>
                            <div class="text-group" style="text-align: center; margin: 2rem; width: 100%;">
                                Не знайдено!
                            </div>
						<?php endif;
						wp_reset_postdata();
						wp_reset_query(); ?>
                    </div>
                    <div class="btn-center pagination-js">
						<?php echo _get_more_author_link( $query->max_num_pages, $author_id ); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer( 'seller' ); ?>
