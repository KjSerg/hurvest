<?php
/* Template Name: Шаблон сторінки авторизацї  */
user_redirect();
get_header();
$var           = variables();
$set           = $var['setting_home'];
$assets        = $var['assets'];
$url           = $var['url'];
$url_home      = $var['url_home'];
$admin_ajax    = $var['admin_ajax'];
$id            = get_the_ID();
$isLighthouse  = isLighthouse();
$size          = $isLighthouse ? 'thumbnail' : 'full';
$img           = get_the_post_thumbnail_url() ?: $assets . 'img/bg_login.webp';
$register_page = carbon_get_theme_option( 'register_page' );
$email         = $_GET['email'] ?? '';
?>
    <div class="main-bg" style="background:url(<?php echo $img; ?>) no-repeat top center/cover"></div>

    <div class="container pad_section">
        <div class="form-enter">
            <div class="title-sm text-center"><?php echo get_the_title(); ?></div>
            <form class="form-js login-form" id="login-form" novalidate method="post">
                <input type="hidden" name="action" value="sign_in_user">
                <div class="form-group">
                    <input class="input_st" type="email" name="email"
                           value="<?php echo $email; ?>"
                           data-reg="[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])"
                           required
                           placeholder="E-mail*">
                </div>
                <div class="form-group">
                    <input class="input_st" name="password" type="password"
                           required
                           placeholder="Пароль*">
                    <div class="show_pass">
                        <img src="<?php echo $assets; ?>img/show_pass.svg" alt="">
                        <img src="<?php echo $assets; ?>img/hide_pass.svg" alt="">
                    </div>
                </div>
                <div class="form-link-center">
                    <a class="modal_open" href="#modal-forgot">
                        Забули пароль?
                    </a>
                </div>
                <div class="form-button-group">
                    <button class="btn_st" type="submit">
                        <span>Увійти</span>
                    </button>
					<?php if ( $register_page ): ?>
                        <a class="btn_st b_yelloow" href="<?php echo get_the_permalink( $register_page[0]['id'] ); ?>">
                            <span>Зареєструватися </span>
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

    <div class="modal modal-sm" id="modal-forgot">
        <div class="modal-content">
            <div class="modal-title text-center">
                <div class="modal-title__main">Забули пароль?</div>
            </div>
            <form class="form-js forgot-password-form" id="forgot-password-form" novalidate method="post">
                <input type="hidden" name="action" value="forgot_password">
                <div class="form-group">
                    <input class="input_st"
                           type="email"
                           placeholder="Ваш е-mail*"
                           name="email"
                           data-reg="[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])"
                           required
                    />
                </div>
                <button class="btn_st w100" type="submit">
                    <span>Відправити новий пароль </span>
                </button>
            </form>
        </div>
    </div>

<?php get_footer();