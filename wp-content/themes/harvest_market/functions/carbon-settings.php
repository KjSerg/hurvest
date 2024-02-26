<?php

use Carbon_Fields\Container;
use Carbon_Fields\Field;

add_action( 'carbon_fields_register_fields', 'crb_attach_theme_options' );
function crb_attach_theme_options() {
	$screens_labels = array(
		'plural_name'   => 'секції',
		'singular_name' => 'секцію',
	);
	$labels         = array(
		'plural_name'   => 'елементи',
		'singular_name' => 'елемент',
	);
	$labels_modals  = array(
		'plural_name'   => 'модальні вікна',
		'singular_name' => 'модальне вікно',
	);
	Container::make( 'theme_options', "Налаштування сайту" )
	         ->add_fields( array(
		         Field::make( "separator", "crb_style_inform", "Шапка сайту" ),
		         Field::make( "image", "logo", "Логотип" )->set_width( 33 )->set_required( true ),
		         Field::make( "image", "logo_sm", "Логотип sm" )->set_width( 33 )->set_required( true ),
		         Field::make( "text", "header_tel", "Телефон в шапці" )->set_width( 33 )->set_required( true ),
		         Field::make( "separator", "crb_style_inform2", "Підвал сайту" ),
		         Field::make( "text", "copyright", "Копірайт" ),
	         ) );
	Container::make( 'theme_options', "Настройка доставки" )
	         ->set_page_parent( 'edit.php?post_type=products' )
	         ->add_fields( array(
		         Field::make( 'complex', 'delivery_types', 'Методи доставки' )
		              ->setup_labels( $labels )
		              ->add_fields( 'pickup', 'Самовивіз', array(
			              Field::make( "text", "title", "Заголовок" )->set_required( true ),
			              Field::make( "checkbox", "is_error", "Повідомлення помилки" ),
			              Field::make( "text", "text", "Текст повідомлення" ),
		              ) )
		              ->add_fields( 'market', 'Ринок', array(
			              Field::make( "text", "title", "Заголовок" )->set_required( true ),
			              Field::make( "checkbox", "is_error", "Повідомлення помилки" ),
			              Field::make( "text", "text", "Текст повідомлення" ),
		              ) )
		              ->add_fields( 'delivery_service', 'Служба доставки', array(
			              Field::make( "text", "title", "Заголовок" )->set_required( true ),
			              Field::make( "checkbox", "is_error", "Повідомлення помилки" ),
			              Field::make( "text", "text", "Текст повідомлення" ),
		              ) )
		              ->add_fields( 'own_service', 'Власна доставка', array(
			              Field::make( "text", "title", "Заголовок" )->set_required( true ),
			              Field::make( "checkbox", "is_error", "Повідомлення помилки" ),
			              Field::make( "text", "text", "Текст повідомлення" ),
		              ) )
	         ) );
	Container::make( 'theme_options', "Настройка магазину" )
	         ->set_page_parent( 'edit.php?post_type=products' )
	         ->add_fields( array(
		         Field::make( "text", "currency", "Валюта сайту" )->set_required( true ),
		         Field::make( 'complex', 'units_measurement', 'Одиниці виміру' )
		              ->setup_labels( $labels )
		              ->add_fields( array(
			              Field::make( "text", "unit", "Одиниця виміру" )->set_required( true ),
		              ) )
	         ) );
	Container::make( 'theme_options', "Настройка карти" )
	         ->set_page_parent( 'options-general.php' )
	         ->add_fields( array(
		         Field::make( "text", "map_api_url", 'Скрипт карти із оголошеннями' )->set_attribute( 'type', 'url' )->set_required( true ),
		         Field::make( "text", "autocomplete_api_url", 'Скрипт автозаповнення адресів' )->set_attribute( 'type', 'url' )->set_required( true )
	         ) );
	Container::make( 'theme_options', "Настройка оголошень" )
	         ->set_page_parent( 'edit.php?post_type=products' )
	         ->add_fields( array(
		         Field::make( "separator", "crb_style_options", "Налаштування оголошення" ),
		         Field::make( "text", "days_count", "Кількість днів активності оголошення" )
		              ->set_attribute( 'type', 'number' )
		              ->set_attribute( 'min', '1' )->set_width( 50 )
		              ->set_required( true ),
		         Field::make( "text", "image_count", "Кількість можливих зображень в оголошені" )
		              ->set_attribute( 'type', 'number' )
		              ->set_attribute( 'min', '1' )->set_width( 50 )
		              ->set_required( true ),
		         Field::make( "separator", "crb_style_options1", "Налаштування оголошень на головній" ),
		         Field::make( "text", "vip_product_count", "Кількість VIP-оголошень на сторінці" )
		              ->set_attribute( 'type', 'number' )->set_width( 50 )
		              ->set_attribute( 'min', '4' )
		              ->set_attribute( 'step', '4' )
		              ->set_required( true ),
		         Field::make( "text", "product_product_count", "Кількість ТОП-оголошень на сторінці" )
		              ->set_attribute( 'type', 'number' )->set_width( 50 )
		              ->set_attribute( 'min', '4' )
		              ->set_attribute( 'step', '4' )
		              ->set_required( true ),
	         ) );
	Container::make( 'theme_options', "Настройка сторінок" )
	         ->set_page_parent( 'edit.php?post_type=page' )
	         ->add_fields( array(
		         Field::make( 'association', 'checkout_page', "Сторінка оформлення замовлення" )
		              ->set_max( 1 )
		              ->set_types( array(
			              array(
				              'type'      => 'post',
				              'post_type' => 'page',
			              ),
		              ) ),
		         Field::make( 'association', 'thanks_page', "Сторінка подяки" )
		              ->set_max( 1 )
		              ->set_types( array(
			              array(
				              'type'      => 'post',
				              'post_type' => 'page',
			              ),
		              ) ),
		         Field::make( 'association', 'register_page', "Сторінка реєстрації" )
		              ->set_max( 1 )
		              ->set_types( array(
			              array(
				              'type'      => 'post',
				              'post_type' => 'page',
			              ),
		              ) ),
		         Field::make( 'association', 'login_page', "Сторінка авторизації" )
		              ->set_max( 1 )
		              ->set_types( array(
			              array(
				              'type'      => 'post',
				              'post_type' => 'page',
			              ),
		              ) ),
		         Field::make( 'association', 'personal_area_page', "Сторінка персонального кабінету" )
		              ->set_max( 1 )
		              ->set_types( array(
			              array(
				              'type'      => 'post',
				              'post_type' => 'page',
			              ),
		              ) ),
	         ) );
	Container::make( 'theme_options', "Настройка telegram" )
	         ->set_page_parent( 'options-general.php' )
	         ->add_fields( array(
		         Field::make( 'text', 'telegram_token' ),
		         Field::make( 'text', 'telegram_bot_name' ),
		         Field::make( 'text', 'bot_text' ),
		         Field::make( "html", "crb_information_text", '' )
		              ->set_html( 'telegram_link_html' )
	         ) );
}

add_action( 'carbon_fields_register_fields', 'crb_attach_in_front_page' );
function crb_attach_in_front_page() {
	$screens_labels = array(
		'plural_name'   => 'секції',
		'singular_name' => 'секцію',
	);
	$labels         = array(
		'plural_name'   => 'елементи',
		'singular_name' => 'елемент',
	);
	$labels_modals  = array(
		'plural_name'   => 'модальні вікна',
		'singular_name' => 'модальне вікно',
	);
	Container::make( 'post_meta', 'Секції' )
	         ->show_on_template( 'index.php' )
	         ->add_fields( array(
		         Field::make( 'multiselect', 'home_categories', 'Категорії' )
		              ->add_options( 'get_top_categories' ),
		         Field::make( "rich_text", "home_title", "Заголовок" )->set_required( true ),
		         Field::make( 'complex', 'screens', 'Додаткові секції' )
		              ->set_layout( 'tabbed-vertical' )
		              ->setup_labels( $screens_labels )
		              ->add_fields( 'screen_seo', 'SEO-текст', array(
			              Field::make( "separator", "crb_style_screen_off", "Виключити секцію?" ),
			              Field::make( 'checkbox', 'screen_off', 'Виключити секцію?' ),
			              Field::make( "separator", "crb_style_inform", "Інформація" ),
			              get_field_id(),
			              Field::make( "rich_text", "title", "Заголовок" )->set_required( true ),
			              Field::make( "text", "subtitle", "Підзаголовок" ),
			              Field::make( "rich_text", "text", "Текст" )->set_required( true ),
			              Field::make( "separator", "crb_style_inform1", "Зображення фону" ),
			              Field::make( "image", "image", "Зображення фону" )->set_required( true ),
		              ) )
	         ) );
	Container::make( 'post_meta', 'Політика конфіденційності' )
	         ->show_on_template( 'index.php' )
	         ->add_fields( array(
		         Field::make( "rich_text", "privacy_policy_text", "Текст політики конфіденційності" )
	         ) );
	Container::make( 'post_meta', 'Промо-текста сторінки верифікації' )
	         ->show_on_template( 'index.php' )
	         ->add_tab( 'Текст сторінки верифікації', array(
		         Field::make( "text", "verification_title", "Заголовок верифікації" ),
		         Field::make( "rich_text", "verification_text", "Текст верифікації" ),
		         Field::make( "image", "verification_image", "Зображення верифікації" ),
	         ) )
	         ->add_tab( 'Текст сторінки Історія оплат', array(
		         Field::make( "text", "history_page_title", "Заголовок верифікації" ),
		         Field::make( "rich_text", "history_page_text", "Текст верифікації" ),
		         Field::make( "image", "history_page_image", "Зображення верифікації" ),
	         ) );
	Container::make( 'post_meta', 'Повідомлення' )
	         ->show_on_template( 'index.php' )
	         ->add_fields( array(
		         Field::make( "rich_text", "invitation_letter", "Лист-запрошення користувачу для управління оголошеннями" )
		              ->set_help_text( invitation_letter_help_text() ),
		         Field::make( "rich_text", "register_letter", "Лист користувачу про успішну реєстрацію" )
		              ->set_help_text( '%url% - замінить на посилання на сторінку авторизації' ),
	         ) );
}

add_action( 'carbon_fields_register_fields', 'crb_attach_in_about_page' );
function crb_attach_in_about_page() {
	$screens_labels = array(
		'plural_name'   => 'секції',
		'singular_name' => 'секцію',
	);
	$labels         = array(
		'plural_name'   => 'елементи',
		'singular_name' => 'елемент',
	);
	$labels_modals  = array(
		'plural_name'   => 'модальні вікна',
		'singular_name' => 'модальне вікно',
	);
	Container::make( 'post_meta', 'Секції' )
	         ->show_on_template( 'about-page.php' )
	         ->add_fields( array(
		         Field::make( 'complex', 'about_screens', 'Секції' )
		              ->set_layout( 'tabbed-vertical' )
		              ->setup_labels( $screens_labels )
		              ->add_fields( 'screen_1', 'Текстова секція', array(
			              Field::make( "separator", "crb_style_screen_off", "Виключити секцію?" ),
			              Field::make( 'checkbox', 'screen_off', 'Виключити секцію?' ),
			              Field::make( "separator", "crb_style_inform", "Інформація" ),
			              get_field_id(),
			              Field::make( "rich_text", "title", "Заголовок" )->set_required( true ),
			              Field::make( "rich_text", "text", "Текст" )->set_required( true ),
		              ) )
		              ->add_fields( 'screen_2', 'Секція із зображенням', array(
			              Field::make( "separator", "crb_style_screen_off", "Виключити секцію?" ),
			              Field::make( 'checkbox', 'screen_off', 'Виключити секцію?' ),
			              Field::make( "separator", "crb_style_inform1", "Зображення фону" ),
			              Field::make( "image", "image", "Зображення фону" )->set_required( true ),
		              ) )
		              ->add_fields( 'screen_3', 'Наші переваги', array(
			              Field::make( "separator", "crb_style_screen_off", "Виключити секцію?" ),
			              Field::make( 'checkbox', 'screen_off', 'Виключити секцію?' ),
			              Field::make( "separator", "crb_style_inform", "Інформація" ),
			              get_field_id(),
			              Field::make( "rich_text", "title", "Заголовок" )->set_width( 50 ),
			              Field::make( "rich_text", "subtitle", "Текст" )->set_width( 50 ),
			              Field::make( 'complex', 'list', 'Список' )
			                   ->set_layout( 'tabbed-vertical' )
			                   ->setup_labels( $labels )
			                   ->add_fields( array(
				                   Field::make( "text", "title", "Заголовок" )->set_required( true )->set_width( 80 ),
				                   Field::make( "image", "image", "Зображення" )->set_required( true )->set_width( 20 ),
				                   Field::make( "text", "text", "Текст" )->set_required( true ),
			                   ) )->set_header_template( '
			                        <%- $_index + 1 %>.
			                        <% if (title) { %>
			                            "<%- title %>"
			                        <% } %>
			                    ' ),
			              Field::make( "separator", "crb_style_inform1", "Зображення фону" ),
			              Field::make( "image", "image", "Зображення фону" )->set_required( true ),
		              ) )
		              ->add_fields( 'screen_4', 'Наша команда', array(
			              Field::make( "separator", "crb_style_screen_off", "Виключити секцію?" ),
			              Field::make( 'checkbox', 'screen_off', 'Виключити секцію?' ),
			              Field::make( "separator", "crb_style_inform", "Інформація" ),
			              get_field_id(),
			              Field::make( "rich_text", "title", "Заголовок" )->set_width( 50 ),
			              Field::make( "rich_text", "subtitle", "Текст" )->set_width( 50 ),
			              Field::make( 'association', 'team', 'Співробітники' )
			                   ->set_types( array(
				                   array(
					                   'type'      => 'post',
					                   'post_type' => 'team',
				                   ),
			                   ) )
		              ) )
		              ->add_fields( 'screen_4_1', 'Секція із співробітником', array(
			              Field::make( "separator", "crb_style_screen_off", "Виключити секцію?" ),
			              Field::make( 'checkbox', 'screen_off', 'Виключити секцію?' ),
			              Field::make( "separator", "crb_style_inform", "Інформація" ),
			              get_field_id(),
			              Field::make( 'association', 'team', 'Співробітник' )
			                   ->set_max( 1 )
			                   ->set_types( array(
				                   array(
					                   'type'      => 'post',
					                   'post_type' => 'team',
				                   ),
			                   ) )
		              ) )
		              ->add_fields( 'screen_5', 'FAQ', array(
			              Field::make( "separator", "crb_style_screen_off", "Виключити секцію?" ),
			              Field::make( 'checkbox', 'screen_off', 'Виключити секцію?' ),
			              Field::make( "separator", "crb_style_inform", "Інформація" ),
			              get_field_id(),
			              Field::make( "rich_text", "title", "Заголовок" ),
			              Field::make( 'complex', 'list', 'Список' )
			                   ->setup_labels( $labels )
			                   ->add_fields( array(
				                   Field::make( "text", "title", "Питання" )->set_required( true ),
				                   Field::make( "rich_text", "text", "Відповідь" )->set_required( true ),
			                   ) ),
		              ) )
		              ->add_fields( 'screen_6', 'Секція із формою зворотнього звязку', array(
			              Field::make( "separator", "crb_style_screen_off", "Виключити секцію?" ),
			              Field::make( 'checkbox', 'screen_off', 'Виключити секцію?' ),
			              Field::make( "separator", "crb_style_inform", "Інформація" ),
			              get_field_id(),
			              Field::make( "rich_text", "title", "Заголовок" )->set_required( true ),
			              Field::make( "text", "form", "Шорт-код форми" )->set_required( true ),
			              Field::make( "separator", "crb_style_inform1", "Зображення фону" ),
			              Field::make( "image", "image", "Зображення фону" )->set_required( true ),
		              ) )
	         ) );
}

add_action( 'carbon_fields_register_fields', 'crb_attach_in_contacts_page' );
function crb_attach_in_contacts_page() {
	$screens_labels = array(
		'plural_name'   => 'секції',
		'singular_name' => 'секцію',
	);
	$labels         = array(
		'plural_name'   => 'елементи',
		'singular_name' => 'елемент',
	);
	$labels_modals  = array(
		'plural_name'   => 'модальні вікна',
		'singular_name' => 'модальне вікно',
	);
	Container::make( 'post_meta', 'Секції' )
	         ->show_on_template( 'contacts-page.php' )
	         ->add_fields( array(
		         Field::make( 'complex', 'contacts_screens', 'Секції' )
		              ->set_layout( 'tabbed-vertical' )
		              ->setup_labels( $screens_labels )
		              ->add_fields( 'screen_1', 'Секція із контактами', array(
			              Field::make( "separator", "crb_style_screen_off", "Виключити секцію?" ),
			              Field::make( 'checkbox', 'screen_off', 'Виключити секцію?' ),
			              Field::make( "separator", "crb_style_inform", "Інформація" ),
			              get_field_id(),
			              Field::make( "rich_text", "title", "Заголовок" )->set_width( 80 )->set_required( true ),
			              Field::make( "image", "image", "Зображення" )->set_width( 20 ),
			              Field::make( "rich_text", "text", "Текст" )->set_required( true ),
			              Field::make( 'complex', 'list', 'Список контактів' )
			                   ->setup_labels( $labels )
			                   ->set_layout( 'tabbed-vertical' )
			                   ->add_fields( 'tel_list', 'Список телефонів',
				                   array(
					                   Field::make( 'complex', 'list', 'Список' )
					                        ->setup_labels( $labels )->set_required( true )
					                        ->add_fields( array(
						                        Field::make( "text", "tel", "Телефон" )->set_required( true ),
					                        ) )
				                   )
			                   )
			                   ->add_fields( 'email_list', 'Список поштових скриньок',
				                   array(
					                   Field::make( 'complex', 'list', 'Список' )
					                        ->setup_labels( $labels )->set_required( true )
					                        ->add_fields( array(
						                        Field::make( "text", "email", "Email" )
						                             ->set_attribute( 'type', 'email' )
						                             ->set_required( true ),
					                        ) )
				                   )
			                   )
		              ) )
		              ->add_fields( 'screen_6', 'Секція із формою зворотнього звязку', array(
			              Field::make( "separator", "crb_style_screen_off", "Виключити секцію?" ),
			              Field::make( 'checkbox', 'screen_off', 'Виключити секцію?' ),
			              Field::make( "separator", "crb_style_inform", "Інформація" ),
			              get_field_id(),
			              Field::make( "rich_text", "title", "Заголовок" )->set_required( true ),
			              Field::make( "text", "form", "Шорт-код форми" )->set_required( true ),
			              Field::make( "separator", "crb_style_inform1", "Зображення фону" ),
			              Field::make( "image", "image", "Зображення фону" )->set_required( true ),
		              ) )
	         ) );
}

add_action( 'carbon_fields_register_fields', 'crb_attach_in_buyers_page' );
function crb_attach_in_buyers_page() {
	$screens_labels = array(
		'plural_name'   => 'секції',
		'singular_name' => 'секцію',
	);
	$labels         = array(
		'plural_name'   => 'елементи',
		'singular_name' => 'елемент',
	);
	$labels_modals  = array(
		'plural_name'   => 'модальні вікна',
		'singular_name' => 'модальне вікно',
	);
	Container::make( 'post_meta', 'Секції' )
	         ->show_on_template( 'buyers-page.php' )
	         ->add_fields( array(
		         Field::make( 'complex', 'buyers_screens', 'Секції' )
		              ->set_layout( 'tabbed-vertical' )
		              ->setup_labels( $screens_labels )
		              ->add_fields( 'screen_1', 'Текстова секція', array(
			              Field::make( "separator", "crb_style_screen_off", "Виключити секцію?" ),
			              Field::make( 'checkbox', 'screen_off', 'Виключити секцію?' ),
			              Field::make( "separator", "crb_style_inform", "Інформація" ),
			              get_field_id(),
			              Field::make( "rich_text", "title", "Заголовок" )->set_required( true ),
			              Field::make( "rich_text", "text", "Текст" )->set_required( true ),
			              Field::make( "separator", "crb_style_inform1", "Зображення" ),
			              Field::make( "image", "image", "Зображення" )
		              ) )
		              ->add_fields( 'screen_2', 'Оголошення', array(
			              Field::make( "separator", "crb_style_screen_off", "Виключити секцію?" ),
			              Field::make( 'checkbox', 'screen_off', 'Виключити секцію?' ),
			              Field::make( "separator", "crb_style_inform", "Інформація" ),
			              get_field_id(),
			              Field::make( "rich_text", "title", "Заголовок" )->set_width( 50 )->set_required( true ),
			              Field::make( "rich_text", "subtitle", "Підзаголовок" )->set_width( 50 ),
			              Field::make( 'complex', 'list', 'Список' )
			                   ->set_layout( 'tabbed-vertical' )
			                   ->setup_labels( $labels )
			                   ->add_fields( array(
				                   Field::make( "text", "title", "Заголовок" )->set_required( true )->set_width( 80 ),
				                   Field::make( "image", "image", "Зображення" )->set_required( true )->set_width( 20 ),
				                   Field::make( "text", "text", "Текст" )->set_required( true ),
			                   ) )->set_header_template( '
			                        <%- $_index + 1 %>.
			                        <% if (title) { %>
			                            "<%- title %>"
			                        <% } %>
			                    ' ),
			              Field::make( "separator", "crb_style_inform1", "Зображення" ),
			              Field::make( "image", "image", "Зображення" )
		              ) )
		              ->add_fields( 'screen_3', 'Розумний вибір', array(
			              Field::make( "separator", "crb_style_screen_off", "Виключити секцію?" ),
			              Field::make( 'checkbox', 'screen_off', 'Виключити секцію?' ),
			              Field::make( "separator", "crb_style_inform", "Інформація" ),
			              get_field_id(),
			              Field::make( "rich_text", "title", "Заголовок" )->set_width( 50 )->set_required( true ),
			              Field::make( "rich_text", "subtitle", "Підзаголовок" )->set_width( 50 ),
			              Field::make( 'complex', 'list', 'Список' )
			                   ->set_layout( 'tabbed-vertical' )
			                   ->setup_labels( $labels )
			                   ->add_fields( array(
				                   Field::make( "text", "title", "Заголовок" )->set_required( true )->set_width( 80 ),
				                   Field::make( "image", "image", "Зображення" )->set_required( true )->set_width( 20 ),
			                   ) )->set_header_template( '
			                        <%- $_index + 1 %>.
			                        <% if (title) { %>
			                            "<%- title %>"
			                        <% } %>
			                    ' ),
			              add_button()
		              ) )
		              ->add_fields( 'screen_4', 'Секція із формою зворотнього звязку', array(
			              Field::make( "separator", "crb_style_screen_off", "Виключити секцію?" ),
			              Field::make( 'checkbox', 'screen_off', 'Виключити секцію?' ),
			              Field::make( "separator", "crb_style_inform", "Інформація" ),
			              get_field_id(),
			              Field::make( "rich_text", "title", "Заголовок" )->set_required( true ),
			              Field::make( "text", "form", "Шорт-код форми" )->set_required( true ),
			              Field::make( "separator", "crb_style_inform1", "Зображення фону" ),
			              Field::make( "image", "image", "Зображення фону" )->set_required( true ),
		              ) )
	         ) );
}

add_action( 'carbon_fields_register_fields', 'crb_attach_in_personal_area_page' );
function crb_attach_in_personal_area_page() {
	Container::make( 'post_meta', 'Інформація' )
	         ->show_on_template( 'personal-area-page.php' )
	         ->add_fields( array(
		         Field::make( "text", "short_code_form_support", "Шорт-код форми підтримки" )
	         ) );
}

add_action( 'carbon_fields_register_fields', 'crb_attach_in_products' );
function crb_attach_in_products() {
	$labels = array(
		'plural_name'   => 'елементи',
		'singular_name' => 'елемент',
	);
	Container::make( 'post_meta', 'Інформація' )
	         ->show_on_post_type( 'products' )
	         ->add_tab(
		         'Інформація',
		         array(
			         Field::make( "separator", "crb_style_inform", "Основна інформація" ),
			         Field::make( 'text', 'product_price', 'Ціна' )->set_width( 40 )
			              ->set_attribute( 'type', 'number' ),
			         Field::make( "html", "crb_information_text", 'Активна валюта' )->set_width( 20 )
			              ->set_html( 'get_currency_string' ),
			         Field::make( 'select', 'product_unit', 'Одиниця виміру товару' )->set_width( 40 )
			              ->add_options( 'get_units' ),
			         Field::make( "separator", "crb_style_inform1", "Замовлення" ),
			         Field::make( 'text', 'product_min_order', 'Мінімальне замовлення' )->set_width( 33 )
			              ->set_attribute( 'type', 'number' ),
			         Field::make( 'text', 'product_max_value', 'В наявності' )->set_width( 33 )
			              ->set_attribute( 'type', 'number' ),
			         Field::make( 'text', 'product_year', 'Рік' )->set_width( 33 ),
			         Field::make( "separator", "crb_style_inform4", "Звязані товари" ),
			         Field::make( 'text', 'product_products', 'ID звязаних товарів' ),
			         Field::make( "separator", "crb_style_inform5", "Оцінка товару" ),
			         Field::make( 'text', 'product_rating', 'Оцінка' )
			              ->set_attribute( 'type', 'number' ),
			         Field::make( "separator", "crb_style_inform78", "Додаткова інформація" ),
			         Field::make( 'text', 'product_user_name', 'Контактна особа' )->set_width( 50 ),
			         Field::make( 'text', 'product_company_name', 'Назва підприємства' )->set_width( 50 ),
			         Field::make( 'text', 'product_user_phone', 'Номер телефону' )->set_width( 50 ),
			         Field::make( 'text', 'product_user_email', 'Email' )->set_width( 50 ),
		         )
	         )
	         ->add_tab( 'Координати і доставка', array(
		         Field::make( "separator", "crb_style_inform2", "Доставка" ),
		         Field::make( 'multiselect', 'product_delivery_methods', 'Можливі методи доставки' )
		              ->add_options( 'get_delivery_methods' ),
		         Field::make( "separator", "crb_style_inform22", "Координати" ),
		         Field::make( 'text', 'product_latitude', 'Широта' )
		              ->set_width( 50 )
		              ->set_attribute( 'type', 'number' ),
		         Field::make( 'text', 'product_longitude', 'Довгота' )
		              ->set_width( 50 )
		              ->set_attribute( 'type', 'number' ),
		         Field::make( "separator", "crb_style_inform23", "Адреса" ),
		         Field::make( 'text', 'product_address', 'Адреса' )->set_width( 33 ),
		         Field::make( 'text', 'product_city', 'Місто' )->set_width( 33 ),
		         Field::make( 'text', 'product_region', 'Регіон, область' )->set_width( 33 ),
		         Field::make( 'complex', 'pick_up_address', 'Адреси самовивозу' )
		              ->set_layout( 'tabbed-vertical' )
		              ->setup_labels( $labels )
		              ->add_fields( array(
			              Field::make( 'text', 'address', 'Адреса' )->set_width( 50 ),
			              Field::make( 'text', 'work_time', 'Час роботи' )->set_width( 50 ),
		              ) )->set_header_template( '
                        <%- $_index + 1 %>.
                        <% if (address) { %>
                            "<%- address %>"
                        <% } %>
                    ' ),
		         Field::make( 'text', 'product_user_postcode', 'Поштовий індекс місця' )->set_width( 33 ),
		         Field::make( 'text', 'product_user_country', 'Країна' )->set_width( 33 ),
		         Field::make( 'text', 'product_user_country_code', 'Код країни' )->set_width( 33 ),
	         ) )
	         ->add_tab( 'Галерея', array(
		         Field::make( "separator", "crb_style_inform3", "Галерея" ),
		         Field::make( 'media_gallery', 'product_gallery', 'Галерея' ),
	         ) )
	         ->add_tab( 'Додаткові налаштування', array(
		         Field::make( 'checkbox', 'product_auto_continue', 'Автопродовження' ),
		         Field::make( 'select', 'product_is_vip', 'VIP-оголошення' )->set_width( 50 )
		              ->add_options( array(
			              ''    => '',
			              'vip' => 'VIP-оголошення активоване',
		              ) ),
		         Field::make( 'date_time', 'product_start_vip', 'Початок VIP-оголошення' )
		              ->set_storage_format( 'U' )
		              ->set_width( 25 ),
		         Field::make( 'date_time', 'product_end_vip', 'Закінчення VIP-оголошення' )
		              ->set_storage_format( 'U' )
		              ->set_width( 25 ),

		         Field::make( 'select', 'product_is_top', 'TOP-оголошення' )
		              ->set_width( 50 )
		              ->add_options( array(
			              ''    => '',
			              'top' => 'TOP-оголошення активоване',
		              ) ),
		         Field::make( 'date_time', 'product_start_top', 'Початок TOP-оголошення' )
		              ->set_storage_format( 'U' )
		              ->set_width( 25 ),
		         Field::make( 'date_time', 'product_end_top', 'Закінчення TOP-оголошення' )
		              ->set_storage_format( 'U' )
		              ->set_width( 25 ),

		         Field::make( 'select', 'product_is_urgently', 'Термінове оголошення' )
		              ->set_width( 50 )
		              ->add_options( array(
			              ''         => '',
			              'urgently' => 'Термінове оголошення',
		              ) ),
		         Field::make( 'date_time', 'product_start_urgently', 'Початок термінового оголошення' )
		              ->set_storage_format( 'U' )
		              ->set_width( 25 ),
		         Field::make( 'date_time', 'product_end_urgently', 'Закінчення термінового оголошення' )
		              ->set_storage_format( 'U' )
		              ->set_width( 25 ),
		         Field::make( 'text', 'product_boost_time' ),
	         ) )
	         ->add_tab( 'Здійсненні покупки', array(
		         Field::make( 'text', 'product_views', 'Переглянуто' )->set_attribute( 'type', 'number' ),
		         Field::make( 'text', 'product_purchased', 'Куплено' )->set_attribute( 'type', 'number' ),
		         Field::make( 'complex', 'product_purchases', 'Покупки' )
		              ->setup_labels( $labels )
		              ->add_fields( array(
			              Field::make( 'text', 'order_id', 'ID замовлення' )->set_width( 50 ),
			              Field::make( 'text', 'purchased', 'Куплено' )->set_width( 50 ),
		              ) )->set_header_template( '
                        <%- $_index + 1 %>.
                        <% if (order_id) { %>
                            ID:<%- order_id %>
                        <% } %> <% if (purchased) { %>
                            [<%- purchased %>]
                        <% } %>
                    ' ),
	         ) )
	         ->add_tab( 'Категорія', array(
		         Field::make( 'text', 'product_custom_category', 'Назва не знайденої категорії' )
	         ) );
}

add_action( 'carbon_fields_register_fields', 'crb_attach_in_post' );
function crb_attach_in_post(){
	Container::make( 'post_meta', 'Інформація' )
	         ->show_on_post_type( 'post' )
	         ->add_fields( array(
		         Field::make( "text", "author_id" )
	         ));
}

add_action( 'carbon_fields_register_fields', 'crb_attach_in_orders' );
function crb_attach_in_orders() {
	$labels = array(
		'plural_name'   => 'елементи',
		'singular_name' => 'елемент',
	);
	Container::make( 'post_meta', 'Інформація' )
	         ->show_on_post_type( 'orders' )
	         ->add_tab(
		         'Інформація',
		         array(
			         Field::make( "separator", "crb_style_inform", "Основна інформація" ),
			         Field::make( "select", "payment_status", "Статус оплати" )
			              ->add_options( array(
				              'not_paid' => 'Не оплачено',
				              'paid'     => 'Оплачено',
			              ) ),
			         Field::make( "select", "delivery_status", "Статус замовлення" )
			              ->add_options( array(
				              ''           => 'Зробіть вибір',
				              'in_process' => 'В процесі',
				              'delivered'  => 'Доставлено',
			              ) ),
			         Field::make( 'text', 'order_user_name', 'Імя' ),
			         Field::make( 'text', 'order_user_tel', 'Телефон' ),
			         Field::make( 'text', 'order_user_email', 'Email' ),
			         Field::make( 'text', 'order_user_city', 'Місто' ),
			         Field::make( 'text', 'order_seller_id', 'ID продавця' ),
			         Field::make( 'text', 'order_delivery_method', 'Спосіб доставки' ),
			         Field::make( 'text', 'order_delivery_address', 'Адрес доставки' ),
			         Field::make( 'text', 'order_post_office', 'Поштове відділення' ),
			         Field::make( 'text', 'order_payment_method', 'Метод плати' ),
		         )
	         )
	         ->add_tab(
		         'Замовлення',
		         array(
			         Field::make( "separator", "crb_style_inform2", "Товари" ),
			         Field::make( 'complex', 'order_cart', 'Кошик' )
			              ->set_layout( 'tabbed-vertical' )
			              ->setup_labels( $labels )
			              ->add_fields( array(
				              Field::make( 'image', 'image', 'Зображення' )->set_width( 20 ),
				              Field::make( 'text', 'title', 'Назва' )->set_width( 20 ),
				              Field::make( 'text', 'id', 'ID' )->set_width( 20 ),
				              Field::make( 'text', 'price', 'Ціна' )->set_width( 10 ),
				              Field::make( 'text', 'qnt', 'Кількість' )->set_width( 10 ),
				              Field::make( 'text', 'sum', 'Сума' )->set_width( 20 ),
			              ) )
			              ->set_header_template( '
                        <%- $_index + 1 %>.
                        <% if (title) { %>
                            "<%- title %>"
                        <% } %>
                    ' ),
			         Field::make( "separator", "crb_style_inform3", "Підрахунки" ),
			         Field::make( 'text', 'order_product_sum', 'Загалом по товарах' ),
			         Field::make( 'text', 'order_discount', 'Скидка' )->set_width( 50 ),
			         Field::make( 'text', 'order_promo', 'Промокод' )->set_width( 50 ),
			         Field::make( 'text', 'order_delivery_price', 'Ціна доставки' ),
			         Field::make( 'text', 'order_sum', 'Сума' ),
		         )
	         );
}

add_action( 'carbon_fields_register_fields', 'crb_attach_in_coupon' );
function crb_attach_in_coupon() {
	Container::make( 'post_meta', 'Налаштування' )
	         ->show_on_post_type( 'coupon' )
	         ->add_fields( array(
		         Field::make( "text", "coupon_discount", "Скидка в %" )
		              ->set_required( true )
		              ->set_attribute( 'type', 'number' )
		              ->set_attribute( 'min', '0' )
		              ->set_attribute( 'max', '100' )
		              ->set_attribute( 'step', '0.01' ),
		         Field::make( 'association', 'active_users', 'Доступно користувачам' )
		              ->set_types( array(
			              array(
				              'type' => 'user',
			              ),
		              ) ),
		         Field::make( "checkbox", "coupon_send_notification", "Відправити промокод користувачам?" ),
		         Field::make( "hidden", "coupon_sent", " " ),
	         ) );
}

add_action( 'carbon_fields_register_fields', 'crb_attach_in_payment' );
function crb_attach_in_payment() {
	Container::make( 'post_meta', 'Інформація' )
	         ->show_on_post_type( 'payment' )
	         ->add_fields( array(
		         Field::make( "text", "payment_sum", "Сума" )
		              ->set_attribute( 'type', 'number' )
	         ) );
}

add_action( 'carbon_fields_register_fields', 'crb_attach_in_message' );
function crb_attach_in_message() {
	Container::make( 'post_meta', 'Інформація' )
	         ->show_on_post_type( 'message' )
	         ->add_fields( array(
		         Field::make( "text", "message_sender_id", "Відправник" ),
		         Field::make( "text", "message_recipient_id", "Отримувач" ),
		         Field::make( "text", "message_product_id", "Товар" ),
		         Field::make( "text", "message_notification_id", "ID сповіщення" ),
		         Field::make( "checkbox", "message_is_read", "Прочитано" ),
		         Field::make( "text", "message_media", "Зображення" ),
	         ) );
}

add_action( 'carbon_fields_register_fields', 'crb_attach_in_reviews' );
function crb_attach_in_reviews() {
	Container::make( 'post_meta', 'Налаштування' )
	         ->show_on_post_type( 'reviews' )
	         ->add_fields(
		         array(
			         Field::make( "text", "review_seller_id", "ID продавця" ),
			         Field::make( "text", "review_user_id", "ID користувача" ),
			         Field::make( "text", "review_rating", "Оцінка" )
			              ->set_required( true )
			              ->set_attribute( 'type', 'number' )
			              ->set_attribute( 'min', '1' )
			              ->set_attribute( 'max', '5' )
			              ->set_attribute( 'step', '1' ),
			         Field::make( "text", "review_author_email", "Email" ),
		         )
	         );
}

add_action( 'carbon_fields_register_fields', 'crb_attach_in_applications' );
function crb_attach_in_applications() {
	$labels = array(
		'plural_name'   => 'елементи',
		'singular_name' => 'елемент',
	);
	Container::make( 'post_meta', 'Інформація' )
	         ->show_on_post_type( 'applications' )
	         ->add_fields( array(
		         Field::make( "text", "application_address", "Місцезнаходження" ),
		         Field::make( "text", "application_city", "Місто" ),
		         Field::make( "text", "application_phone", "Телефон" ),
	         ) );
	Container::make( 'post_meta', 'Документи' )
	         ->show_on_post_type( 'applications' )
	         ->add_fields( array(
		         Field::make( 'complex', 'application_documents', 'Документи' )
		              ->setup_labels( $labels )
		              ->add_fields( array(
			              Field::make( 'image', 'image', 'Зображення' )->set_width( 20 ),
			              Field::make( 'text', 'url', 'Посилання на документ' )->set_width( 80 ),
		              ) )
	         ) );
}

add_action( 'carbon_fields_register_fields', 'crb_attach_in_services' );
function crb_attach_in_services() {
	$labels = array(
		'plural_name'   => 'елементи',
		'singular_name' => 'елемент',
	);
	Container::make( 'post_meta', 'Інформація' )
	         ->show_on_post_type( 'services' )
	         ->add_fields( array(
		         Field::make( "separator", "crb_style_inform", "Налаштування" ),
		         Field::make( "text", "service_price", "Ціна одиниці пакета для області" )
		              ->set_required( true )->set_width( 50 )
		              ->set_attribute( 'type', 'number' ),
		         Field::make( "text", "service_full_price", "Ціна одиниці пакета для країни" )
		              ->set_required( true )->set_width( 50 )
		              ->set_attribute( 'type', 'number' ),
		         Field::make( "select", "service_term", "Термін роботи пакета" )
		              ->add_options(
			              array(
				              1  => '1 день',
				              30 => '30 днів',
			              )
		              )->set_help_text( 'Діє для опцій: <br><strong>"Додати в ТОП", "Додати в VIP", "Додати в Терміново"</strong>' ),
		         Field::make( "text", "service_regions_qnt", "Кількість доступних регіонів" )
		              ->set_attribute( 'min', '2' )
		              ->set_attribute( 'type', 'number' ),
		         Field::make( "text", "service_hint", "Підказка" )->set_width( 50 ),
		         Field::make( "text", "service_qnt_suffix", "Суфікс кількості" )->set_width( 50 ),
		         Field::make( "separator", "crb_style_options", "Опції" ),
		         Field::make( "checkbox", "service_top", "Додати в ТОП" ),
		         Field::make( "checkbox", "service_vip", "Додати в VIP" ),
		         Field::make( "checkbox", "service_urgently", "Додати в Терміново" ),
		         Field::make( "checkbox", "service_boost", "Підняти оголошення" )
		              ->set_help_text( 'Діє одноразово. Оновляє дату публікації і тим самим піднімає оголошення в каталозі. <strong>Не працює із другими послугами в одному пакеті</strong>' ),
		         Field::make( "checkbox", "service_telegram", "Добавити telegram" )
		              ->set_help_text( 'Відповідає повідомлення через telegram-бот' ),
		         Field::make( "separator", "crb_style_inform_list", "Список текстової інформації" ),
		         Field::make( 'complex', 'service_list', 'Список текстової інформації' )
		              ->setup_labels( $labels )
		              ->add_fields( array(
			              Field::make( "text", "text", "Текст" )->set_required( true ),
		              ) )
	         ) );
}

add_action( 'carbon_fields_register_fields', 'crb_attach_in_purchased' );
function crb_attach_in_purchased() {
	$labels = array(
		'plural_name'   => 'елементи',
		'singular_name' => 'елемент',
	);
	Container::make( 'post_meta', 'Інформація' )
	         ->show_on_post_type( 'purchased' )
	         ->add_fields( array(
		         Field::make( 'complex', 'purchased_order', 'Замовлення' )
		              ->setup_labels( $labels )
		              ->set_layout( 'tabbed-vertical' )
		              ->add_fields(
			              array(
				              Field::make( "text", "name", "Назва послуги" )->set_width( 33 ),
				              Field::make( "date", "date", "Дата початку" )->set_width( 33 )->set_storage_format( 'U' ),
				              Field::make( "text", "sub_sum", "Підсумок" )->set_width( 33 ),
				              Field::make( "text", "service_id", "ID послуги" )->set_width( 10 ),
				              Field::make( "text", "qnt", "Кількість" )->set_width( 10 ),
				              Field::make( "text", "product_id", "ID товару" )->set_width( 10 ),
				              Field::make( "text", "regions", "Області" )->set_width( 10 ),
				              Field::make( "checkbox", "is_not_active", "Неактивне" )->set_width( 10 ),
				              Field::make( "checkbox", "auto_continue", "Продовження" )->set_width( 10 ),
				              Field::make( 'complex', 'stops', 'Зупинки' )
				                   ->setup_labels( $labels )
				                   ->add_fields(
					                   array(
						                   Field::make( "date_time", "date_stop", "Дата зупинки" )->set_width( 33 )->set_storage_format( 'U' ),
						                   Field::make( "date_time", "date_start", "Дата запуску" )->set_width( 33 )->set_storage_format( 'U' ),
						                   Field::make( "text", "remains", "Залишилось часу" )->set_width( 33 )->set_attribute( 'type', 'number' ),
					                   )
				                   )
			              )
		              )->set_header_template( '
                        <%- $_index + 1 %>.
                        <% if (name) { %>
                            <%- name %>
                        <% } %>
                    ' ),
		         Field::make( 'text', 'purchased_sum', 'Сума Замовлення' )
	         ) );
}

add_action( 'carbon_fields_register_fields', 'crb_attach_in_team' );
function crb_attach_in_team() {
	Container::make( 'post_meta', 'Інформація' )
	         ->show_on_post_type( 'team' )
	         ->add_fields( array(
		         Field::make( "text", "position", "Посада" ),
		         Field::make( "text", "employee_description", "Короткий опис" ),
	         ) );
}

add_action( 'carbon_fields_register_fields', 'crb_attach_in_notifications' );
function crb_attach_in_notifications() {
	Container::make( 'post_meta', 'Інформація' )
	         ->show_on_post_type( 'notifications' )
	         ->add_fields( array(
		         Field::make( "text", "notification_text", "Текст повідомлення" ),
		         Field::make( "text", "notification_sender_id", "Відправник" ),
		         Field::make( "text", "notification_recipient_id", "Отримувач" ),
		         Field::make( "select", "notification_is_read", "Статус" )
		              ->add_options( array(
			              'not_read' => 'Не прочитано',
			              'read'     => 'Прочитано',
		              ) )
	         ) );
}

add_action( 'carbon_fields_register_fields', 'crb_attach_in_categories' );
function crb_attach_in_categories() {
	Container::make( 'term_meta', 'Налаштування' )
	         ->show_on_taxonomy( 'categories' )
	         ->add_fields( array(
		         Field::make( "image", "category_image", "Зображення категорії" ),
		         Field::make( "multiselect", "category_filters", "Фільтри для категорії" )
			         ->add_options('get_filter_list')
		              ->set_help_text( 'та всіх її дочірніх елементів' )
	         ) );
}

add_action( 'carbon_fields_register_fields', 'crb_attach_in_comments' );
function crb_attach_in_comments() {
	Container::make( 'comment_meta', 'Інформація' )
	         ->add_fields(
		         array(
			         Field::make( 'text', 'comment_rating', 'Оцінка' )
			              ->set_attribute( 'type', 'number' )
			              ->set_attribute( 'min', '0' )
			              ->set_attribute( 'max', '5' )
			              ->set_attribute( 'step', '1' )
		         )
	         );
}

add_action( 'carbon_fields_register_fields', 'crb_attach_in_users' );
function crb_attach_in_users() {
	$labels = array(
		'plural_name'   => 'элементы',
		'singular_name' => 'элемент',
	);
	Container::make( 'user_meta', 'Інформація' )
	         ->add_fields(
		         array(
			         Field::make( 'text', 'user_tags', 'Мітки' ),
			         Field::make( 'text', 'user_city', 'Місто' ),
			         Field::make( 'text', 'user_surname', 'По батькові' ),
			         Field::make( 'text', 'user_phone', 'Номер телефону' ),
			         Field::make( 'image', 'user_avatar', 'Аватар' ),
			         Field::make( 'hidden', 'user_online', ' ' ),
			         Field::make( 'hidden', 'user_favorites', ' ' ),
		         )
	         );
	Container::make( 'user_meta', 'Інформація підприємства' )
	         ->add_fields(
		         array(
			         Field::make( 'checkbox', 'user_seller', 'Продавець' ),
			         Field::make( 'checkbox', 'user_verification', 'Верифікований' ),
			         Field::make( 'checkbox', 'user_fop', 'ФОП' ),
			         Field::make( 'text', 'user_company_phone', 'Номер телефону компанії' ),
			         Field::make( 'text', 'user_company_city', 'Місто' ),
			         Field::make( 'text', 'user_company_address', 'Адрес' ),
			         Field::make( 'text', 'user_company_name', 'Назва компанії або господарства' ),
			         Field::make( 'textarea', 'user_company_description', 'Опис компанії або господарства' ),
			         Field::make( 'media_gallery', 'user_company_gallery', 'Фото' ),
			         Field::make( 'color', 'user_company_color', 'Колір сторінки продавця' ),
			         Field::make( 'hidden', 'user_post', ' ' ),
		         )
	         );
	Container::make( 'user_meta', 'Telegram' )
	         ->add_fields(
		         array(
			         Field::make( 'text', 'telegram', 'Telegram' ),
			         Field::make( 'text', 'telegram_id', 'Telegram ID' ),
			         Field::make( 'text', 'telegram_image', 'Telegram аватар' ),
			         Field::make( 'text', 'user_accounts_id', 'ID звязаних акаунтів' ),
		         )
	         );
	Container::make( 'user_meta', 'Доставки' )
	         ->add_fields(
		         array(
			         Field::make( 'text', 'delivery_count', 'Кількість успішних доставок' ),
		         )
	         );
	Container::make( 'user_meta', 'Довірені користувачі' )
	         ->add_fields( array(
		         Field::make( 'complex', 'trusted_users', 'Довірені користувачі' )
		              ->setup_labels( $labels )
		              ->add_fields( array(
			              Field::make( 'text', 'user_id', 'ID користувача' )->set_width( 50 ),
			              Field::make( 'select', 'user_status', 'Статус користувача' )
			                   ->add_options( array(
				                   'expected'   => 'Очікується',
				                   'active'     => 'Активний',
				                   'not_active' => 'Не активний',
			                   ) )
			                   ->set_width( 50 ),
		              ) )
	         ) );
	Container::make( 'user_meta', 'Чат' )
	         ->add_fields( array(
		         Field::make( 'complex', 'user_chat', 'Чат' )
		              ->setup_labels( $labels )
		              ->add_fields(
			              array(
				              Field::make( "text", "text", "Лист" )
			              )
		              ),
	         ) );
}

add_action( 'after_setup_theme', 'crb_load' );
function crb_load() {
	get_template_part( 'vendor/autoload' );
	\Carbon_Fields\Carbon_Fields::boot();
}

add_filter( 'crb_media_buttons_html', function ( $html, $field_name ) {
	if (
		$field_name === 'register_letter' ||
		$field_name === 'history_page_text' ||
		$field_name === 'invitation_letter' ||
		$field_name === 'verification_text' ||
		$field_name === 'privacy_policy_text' ||
		$field_name === 'home_title' ||
		$field_name === 'footer_contacts' ||
		$field_name === 'text' ||
		$field_name === 'subtitle' ||
		$field_name === 'title'
	) {
		return;
	}

	return $html;
}, 10, 2 );

function get_field_id() {
	return Field::make( "text", "id", "ID секції (унікальне значення)" )
	            ->set_attribute( 'pattern', '^[a-z0-9\-]+$' )
	            ->set_help_text( 'Слово на латиниці без прогалин. Можливий символ: "-" <br><strong>Значення ID не повинно повторюватися!</strong>' )
	            ->set_required( true );
}

function add_button( $args = array() ) {
	$id     = $args['id'] ?? 'links';
	$name   = $args['name'] ?? 'Кнопки';
	$max    = $args['max'] ?? 1;
	$labels = array(
		'plural_name'   => 'елементи',
		'singular_name' => 'елемент',
	);

	return Field::make( 'complex', $id, $name )
	            ->setup_labels( $labels )
	            ->set_max( $max )
	            ->add_fields( 'link', 'Посилання', array(
		            Field::make( 'text', 'button_text', 'Текст' )
		                 ->set_width( 50 )
		                 ->set_required( true ),
		            Field::make( 'text', 'link', 'Посилання' )
		                 ->set_width( 50 )
		                 ->set_attribute( 'type', 'url' )
		                 ->set_required( true ),
	            ) );
}
