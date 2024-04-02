<?php
function harvy_comments_callback( $comment, $args, $depth ) {
	global $post;
	$post_id        = $post->ID;
	$comment_author = get_userdata( $comment->user_id )->ID;
	$user_avatar    = carbon_get_user_meta( $comment_author, 'user_avatar' );
	$r              = carbon_get_comment_meta( $comment->comment_ID, 'comment_rating' ) ?: 5;
	$icon           = '<svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
							     style="enable-background:new 0 0 12 11.2"
							     viewBox="0 0 12 11.2">
                                                            <path d="M12 4.2c-.1-.2-.3-.4-.5-.4L8 3.5 6.6.4C6.5.1 6.3 0 6 0s-.5.1-.6.4L4 3.5l-3.4.3c-.3 0-.5.2-.6.4 0 .3 0 .5.2.7l2.6 2.2-.8 3.3c-.1.2 0 .5.2.6.1.1.2.1.4.1.1 0 .2 0 .3-.1l3-1.7 3 1.7c.2.1.5.1.7 0 .2-.1.3-.4.2-.6l-.6-3.3 2.6-2.2c.2-.2.2-.4.2-.7z"
                                                                  style="fill:#ffc327"/>
                                                        </svg>';
	?>
<div class="testimonials-item" id="div-comment-<?php comment_ID() ?>">
    <div class="testimonials-item__top" id="comment-<?php comment_ID(); ?>">
        <div class="testimonials-item__ava">
			<?php if ( $user_avatar ): ?>
                <img src="<?php _u( $user_avatar ); ?>" alt=""/>
			<?php endif; ?>
        </div>
        <div class="testimonials-item__info">
            <div class="testimonials-item__info-top">
                <div class="testimonials-item__title">
                    <?php comment_author() ?>
                </div>
                <ul class="rating">
					<?php
					if ( $r ) {
						for ( $i = 1; $i <= 5; $i ++ ) {
							if ( $i <= $r ) {
								echo '<li class="active">' . $icon . '</li>';
							} else {
								echo '<li>' . $icon . '</li>';
							}
						}
					}
					?>
                </ul>
            </div>
            <div class="testimonials-item__date"><?php comment_date( 'j F Y' ) ?></div>
        </div>
    </div>
    <div class="testimonials-item__text">
        <div class="text-group">
			<?php comment_text(); ?>
        </div>
    </div>
	<?php
}

function harvy_comments_callback_end() {
	echo '</div>';
}