<?php
/* Template Name: Шаблон сторінки реєстрації  */
user_redirect();
get_header();
$var          = variables();
$set          = $var['setting_home'];
$assets       = $var['assets'];
$url          = $var['url'];
$url_home     = $var['url_home'];
$admin_ajax   = $var['admin_ajax'];
$id           = get_the_ID();
$isLighthouse = isLighthouse();
$size         = $isLighthouse ? 'thumbnail' : 'full';
$img          = get_the_post_thumbnail_url() ?: $assets . 'img/bg_register.webp';
$login_page   = carbon_get_theme_option( 'login_page' );
?>

    <div class="main-bg" style="background:url(<?php echo $img; ?>) no-repeat top center/cover"></div>

    <div class="container pad_section">
        <div class="form-enter">
            <div class="title-sm text-center"><?php echo get_the_title(); ?></div>
            <form method="post" class="form-js" id="create-user-form" novalidate>
                <input type="hidden" name="action" value="create_new_user">
                <div class="form-group">
                    <input class="input_st" type="text" required name="first_name" placeholder="Імя*">
                </div>
                <div class="form-group">
                    <input class="input_st" type="text" required name="last_name" placeholder="Прізвище*">
                </div>
                <div class="form-group">
                    <input class="input_st"
                           type="email"
                           required
                           name="email"
                           data-reg="[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])"
                           placeholder="E-mail*">
                </div>
                <div class="form-group">
                    <input class="input_st" type="tel" required name="tel" placeholder="Телефон*">
                </div>
                <div class="form-group">
                    <input class="input_st" type="password" required name="password" placeholder="Пароль*">
                    <div class="show_pass">
                        <img src="<?php echo $assets; ?>img/show_pass.svg" alt="">
                        <img src="<?php echo $assets; ?>img/hide_pass.svg" alt="">
                    </div>
                </div>

                <div class="form-button-group">
                    <button class="btn_st" type="submit">
                        <span>Зареєструватися</span>
                    </button>
					<?php if ( $login_page ): ?>
                        <a class="btn_st b_yelloow" href="<?php echo get_the_permalink( $login_page[0]['id'] ); ?>">
                            <span>Увійти </span>
                        </a>
					<?php endif; ?>
                </div>
				<?php
				if ( class_exists( 'NextendSocialLogin', false ) ): ?>
                    <div class="enter-social">
                        <div class="enter-social__title">
                            <span>Або продовжити за допомогою</span>
                        </div>
                        <div class="enter-social__list">
							<?php echo NextendSocialLogin::renderButtonsWithContainer(); ?>
                        </div>
                    </div>
				<?php endif; ?>
                <div class="enter-form-bot">
					<?php the_post();
					the_content(); ?>
                </div>
            </form>
        </div>
    </div>

<?php get_footer();
