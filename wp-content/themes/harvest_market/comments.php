<?php
global $post;
if ( 'open' !== $post->comment_status ) {
	return;
}
$reviews_count = $post->comment_count;
$var           = variables();
$set           = $var['setting_home'];
$assets        = $var['assets'];
$admin_ajax    = $var['admin_ajax'];
$id            = get_the_ID();
$rating        = carbon_get_post_meta( $id, 'product_rating' );
$user_id       = get_current_user_id();
$user          = get_user_by( 'ID', $user_id );
$first_name    = $user_id ? $user->first_name : '';
$last_name     = $user_id ? $user->last_name : '';
$user_email    = $user_id ? $user->user_email : '';
?>
<div class="product-testimonials" id="comments">
    <div class="product-testimonials__top">
        <div class="product-testimonials__top-item">
            <img src="<?php echo $assets; ?>img/star-gold.svg"
                 alt=""/><?php echo (float) ( $rating ?: 5 ); ?>
        </div>
        <div class="product-testimonials__top-item">
			<?php echo $reviews_count; ?> відгуків
        </div>
    </div>
    <div class="testimonials commentlist">
		<?php
		if ( $reviews_count > 0 ):
			wp_list_comments(
				array(
					'callback'          => 'harvy_comments_callback',
					'end-callback'      => 'harvy_comments_callback_end',
					'avatar_size'       => 100,
					'reverse_children'  => true,
					'reverse_top_level' => true,
				)
			);
		else: ?>
            <div class="testimonials-item__title">Залиште коментар першим</div>
		<?php endif; ?>
    </div>
	<?php if ( $reviews_count > 0 ): ?>
		<?php the_comments_navigation( array(
			'prev_text'          => '<span>Більше відгуків<svg
                                        xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                        style="enable-background:new 0 0 8 15" viewBox="0 0 8 15">
                                        <path d="M3.3.7v12l-2.2-2.2c-.6-.6-1.6.3-.9 1l3.3 3.4c.3.3.7.3.9 0l3.3-3.4c.2-.2.3-.4.3-.6 0-.6-.7-.9-1.1-.5l-2.2 2.2V.6c-.1-.9-1.4-.8-1.4.1z"
                                              style="fill:#fff"/>
                                    </svg></span>',
			'next_text'          => ' ',
			'screen_reader_text' => ''
		) ); ?>
	<?php endif; ?>
    <div class="cabinet-item">
        <div class="cabinet-item__title">Залишити відгук</div>
        <form action="<?php echo $admin_ajax; ?>" method="post"
              id="new-comment-form"
              class="new-comment-form comment-form form-js"
              novalidate>
            <input type="hidden" name="action" value="new_comment"/>
            <input type="hidden" name="post_id" value="<?php echo $id; ?>"/>
            <div class="form-horizontal">
                <div class="form-group half">
                    <input class="input_st" type="text" name="name"
                           value="<?php echo $first_name . ( $last_name ? ' ' . $last_name : '' ); ?>"
                           placeholder="Ім'я" required="required"/>
                </div>
                <div class="form-group half">
                    <input class="input_st" type="email" name="email"
                           data-reg="[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])"
                           value="<?php echo $user_email; ?>"
                           placeholder="E-mail" required="required"/>
                </div>
                <div class="form-group">
                    <div class="feedback-rating">
                        <div class="feedback-rating__text"> Оцініть товар:</div>
                        <div class="rating">
							<?php for ( $a = 1; $a <= 5; $a ++ ): ?>
                                <label class="rating-item">
                                    <input type="radio"
                                           name="rating"
                                           value="<?php echo $a; ?>"/>
                                    <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                         style="enable-background:new 0 0 12 11.2"
                                         viewBox="0 0 12 11.2">
                                                            <path d="M12 4.2c-.1-.2-.3-.4-.5-.4L8 3.5 6.6.4C6.5.1 6.3 0 6 0s-.5.1-.6.4L4 3.5l-3.4.3c-.3 0-.5.2-.6.4 0 .3 0 .5.2.7l2.6 2.2-.8 3.3c-.1.2 0 .5.2.6.1.1.2.1.4.1.1 0 .2 0 .3-.1l3-1.7 3 1.7c.2.1.5.1.7 0 .2-.1.3-.4.2-.6l-.6-3.3 2.6-2.2c.2-.2.2-.4.2-.7z"
                                                                  style="fill:#ffc327"/>
                                                        </svg>
                                </label>
							<?php endfor; ?>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                                        <textarea class="input_st" name="comment"
                                                  placeholder="Ваш коментар"
                                                  required="required"></textarea>
                </div>
            </div>
            <div class="form-bot">
				<?php if ( $privacy_policy_text = carbon_get_post_meta( $set, 'privacy_policy_text' ) ): ?>
                    <div class="form-consent">
                        <label>
                            <input class="check_st" name="consent" type="checkbox"/><span></span>
                        </label>
                        <div class="form-consent__text">
							<?php _t( $privacy_policy_text ); ?>
                        </div>
                    </div>
				<?php endif; ?>
                <button class="btn_st" type="submit"><span> Відправити відгук </span></button>
            </div>
        </form>
    </div>
</div>