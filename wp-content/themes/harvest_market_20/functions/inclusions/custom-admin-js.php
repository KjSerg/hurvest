<?php
function custom_admin_js() {

	$s    = 'input[type="hidden"]';
	$html = 'html[lang="uk"]';
	$a    = variables()['admin_ajax'];

	echo "
        <style>
            .cf-complex__groups {
                z-index: 0!important;
            }
        </style>
       <script>
       var _adminAjax = '$a';
       jQuery(document).ready(function(){
             
           setTimeout(function () {
               jQuery(document).find('.cf-file__inner').each(function () {
                                            var t = jQuery(this);
                                            var id = t.find('$s').eq(0).val();
                                            jQuery.ajax({
                                                type: 'POST',
                                                url: '$a',
                                                data: {
                                                    action: 'get_attach_by_id',
                                                    id: id
                                                }
                                            }).done(function (r) {
                                                t.find('.cf-file__image').attr('src', r);                        
                                            });
                        
                                        });
               jQuery(document).find('$html .cf-complex__inserter-button').each(function () {                                    
                                            var t = jQuery(this);
                                            var text = t.text();
                                            t.text(text.replaceAll('Add', 'Додати'));
                                 });
               jQuery(document).find('$html .cf-media-gallery__browse').each(function () {                                    
                                            var t = jQuery(this);
                                            var text = t.text();
                                            t.text(text.replaceAll('Select Attachments', 'Виберіть вкладення'));
                                 });
               jQuery(document).find('$html .cf-file__browse').each(function () {                                    
                                            var t = jQuery(this);
                                            var text = t.text();
                                            t.text(text.replaceAll('Select Image', 'Виберіть Зображення'));
                                 });
               jQuery(document).find('$html input.button').each(function () {                                    
                                            var t = jQuery(this);
                                            var text = t.val();
                                            t.val(text.replaceAll('Save Changes', 'Зберегти'));
                                 });
               }, 1000);
           jQuery(document).on('click', '.cf-complex__inserter-button, .cf-complex__inserter-item', function (e){              
                setTimeout(function () {
                    jQuery(document).find('$html .cf-complex__inserter-button').each(function () {                                    
                                            var t = jQuery(this);
                                            var text = t.text();
                                            t.text(text.replaceAll('Add', 'Додати'));
                                 });
               }, 500);
           });
       });  
    </script>
    ";
}

add_action( 'admin_footer', 'custom_admin_js' );

add_action( 'admin_footer-edit.php', 'add_status_to_pages' );

function add_status_to_pages() {

	echo '<script>jQuery(document).ready( function() {';
	$page = carbon_get_theme_option( 'checkout_page' );
	$page = $page ? $page[0]['id'] : 0;
	echo "jQuery( '#post-' + $page ).find('strong').append( ' — Cторінка оформлення замовлення' );";
	$page = carbon_get_theme_option( 'thanks_page' );
	$page = $page ? $page[0]['id'] : 0;
	echo "jQuery( '#post-' + $page ).find('strong').append( ' — Cторінка подяки' );";
	$page = carbon_get_theme_option( 'register_page' );
	$page = $page ? $page[0]['id'] : 0;
	echo "jQuery( '#post-' + $page ).find('strong').append( ' — Cторінка реєстрації' );";
	$page = carbon_get_theme_option( 'login_page' );
	$page = $page ? $page[0]['id'] : 0;
	echo "jQuery( '#post-' + $page ).find('strong').append( ' — Cторінка авторизації' );";
	$page = carbon_get_theme_option( 'personal_area_page' );
	$page = $page ? $page[0]['id'] : 0;
	echo "jQuery( '#post-' + $page ).find('strong').append( ' — Персональний кабінет' );";
	echo '});</script>';
}