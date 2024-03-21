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
$user_id            = get_current_user_id();
?>
</main>
<div class="modal modal-sm" id="dialog" style="display: none">
	<div class="modal-content text-center">
		<div class="modal-title">
			<div class="modal-title__main"></div>
			<div class="modal-title__subtitle">Ваше оголошення успішно створене. Наразі воно на модерації</div>
		</div>
	</div>
</div>
<script>
    var URL = '<?php echo $url;  ?>';
    var userID = <?php echo $user_id;  ?>;
    var admin_ajax = '<?php echo $var['admin_ajax']; ?>';
    var inCartStr = "У кошик";
    var currency = "<?php echo carbon_get_theme_option( 'currency' ); ?>";
    var inCartAddedStr = "Додано";
    var verifiedSTR = "Верифікований";
    var locationErrorString = "Виберіть локацію зі списку запропонованих варіантів";
    var errorPswMsg = "Пароль має містити щонайменше 6 символів, цифру та латинські літери, причому принаймні одна з них має бути великою";
    var daysOfWeek = ["Нд", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"];
    var monthNames = ["Січень", "Лютий", "Березень", "Квітень", "Травень", "Червень", "Липень", "Серпень", "Вересень", "Жовтень", "Листопад", "Грудень"];
    var minDate = '<?php echo date( "d/m/Y", time() + strtotime( '1 day', 0 ) ); ?>';
</script>

<?php if ( ! $isLighthouse ): ?>
	<div class="preloader">
		<img src="<?php echo $assets; ?>img/loading.gif" alt="loading.gif">
	</div>
<?php endif; ?>

<?php wp_footer(); ?>

</body>

</html>