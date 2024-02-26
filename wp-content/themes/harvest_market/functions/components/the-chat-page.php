<?php
function the_chat_page() {
	the_header_cabinet();
	$var                  = variables();
	$set                  = $var['setting_home'];
	$assets               = $var['assets'];
	$url                  = $var['url'];
	$url_home             = $var['url_home'];
	$admin_ajax           = $var['admin_ajax'];
	$user_id              = get_current_user_id();
	$permalink            = get_the_permalink() ?: $url;
	$route                = $_GET['route'] ?? '';
	$subpage              = $_GET['subpage'] ?? '';
	$get_product_id       = $_GET['product_id'] ?? '';
	$personal_page        = carbon_get_theme_option( 'personal_area_page' );
	$_url                 = $personal_page ? get_the_permalink( $personal_page[0]['id'] ) : $url;
	$current_user         = get_user_by( 'ID', $user_id );
	$email                = $current_user->user_email ?: '';
	$display_name         = $current_user->display_name ?: '';
	$first_name           = $current_user->first_name ?: '';
	$last_name            = $current_user->last_name ?: '';
	$name                 = $first_name ?: $display_name;
	$user_avatar          = carbon_get_user_meta( $user_id, 'user_avatar' );
	$correspondence       = $_GET['correspondence'] ?? 0;
	$message_sender_id    = 0;
	$message_recipient_id = 0;
	$message_product_id   = 0;
	$message_user_id      = false;
	$message_user_avatar  = false;
	if ( $correspondence ) {
		$message_sender_id    = carbon_get_post_meta( $correspondence, 'message_sender_id' );
		$message_recipient_id = carbon_get_post_meta( $correspondence, 'message_recipient_id' );
		$message_product_id   = carbon_get_post_meta( $correspondence, 'message_product_id' );
		$message_user_id      = $message_sender_id == $user_id ? $message_recipient_id : $message_sender_id;
		$message_user_avatar  = carbon_get_user_meta( $message_user_id, 'user_avatar' );
	}
	$args = array(
		'post_type'      => 'message',
		'posts_per_page' => - 1,
		'post_status'    => 'publish',
		'post_parent'    => 0,
		'orderby'        => 'date',
		'order'          => 'asc',
		'meta_query'     => array()
	);
	if ( $get_product_id ) {
		$args['meta_query'][] = array(
			'key'   => '_message_product_id',
			'value' => $get_product_id
		);
	}
	require_once ABSPATH . 'wp-admin/includes/image.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	?>
    <div class="create-item-main">
        <div class="chat-group">
            <div class="chat-group__left js-tab">
                <ul class="nav-announcement">
                    <li>
                        <a class="js-tab-link <?php echo $message_recipient_id == $user_id || $message_recipient_id == 0 ? 'active' : ''; ?> "
                           href="#"
                           data-target="target_1">
                            Продаю
                        </a>
                    </li>
                    <li>
                        <a class="js-tab-link <?php echo $message_sender_id == $user_id ? 'active' : ''; ?> " href="#"
                           data-target="target_2">
                            Купую
                        </a>
                    </li>
                </ul>
                <div class="chat-tab">
                    <div class="chat-tab__item js-tab-item <?php echo $message_recipient_id == $user_id || $message_recipient_id == 0 ? 'active' : ''; ?>"
                         data-target="target_1">
						<?php
						$recipient_args                 = $args;
						$recipient_args['meta_query'][] = array(
							'key'   => '_message_recipient_id',
							'value' => $user_id
						);
						$query                          = new WP_Query( $recipient_args );
						if ( $query->have_posts() ):
							?>
                            <div class="chat-nav-wrap">
                                <div class="chat-contact">
									<?php
									while ( $query->have_posts() ):
										$query->the_post();
										$_id               = get_the_ID();
										$_sender_id        = carbon_get_post_meta( $_id, 'message_sender_id' );
										$_recipient_id     = carbon_get_post_meta( $_id, 'message_recipient_id' );
										$_product_id       = carbon_get_post_meta( $_id, 'message_product_id' );
										$userID            = $_sender_id == $user_id ? $_recipient_id : $_sender_id;
										$_user_avatar      = carbon_get_user_meta( $userID, 'user_avatar' );
										$_sender_user      = get_user_by( 'ID', $userID );
										$_email            = $_sender_user->user_email ?: '';
										$_display_name     = $_sender_user->display_name ?: '';
										$_first_name       = $_sender_user->first_name ?: '';
										$_last_name        = $_sender_user->last_name ?: '';
										$_name             = $_first_name ?: $_display_name;
										$last_message_data = get_last_message( $_id );
										$is_read           = $last_message_data['is_read'] ?? true;
										$cls               = '';
										if ( $correspondence == $_id ) {
											$cls = 'active';
										}
										if ( ! $is_read ) {
											$cls .= ' not-read';
										}
										?>
                                        <div data-url="<?php echo $_url . '?route=message&correspondence=' . $_id; ?>"
                                             data-product="<?php echo $_product_id; ?>"
                                             data-user="<?php echo $userID; ?>"
                                             class="chat-contact__item correspondence-link <?php echo $cls; ?> ">
                                            <div class="chat-contact__item-ava">
												<?php if ( $_user_avatar ): ?>
                                                    <img src="<?php _u( $_user_avatar ); ?>" alt=""/>
												<?php endif; ?>
                                            </div>
                                            <div class="chat-contact__item-info">
                                                <div class="chat-contact__item-title">
													<?php echo $_name; ?>
                                                </div>
                                                <div class="chat-contact__item-subtitle">
													<?php echo get_the_title( $_product_id ); ?>
                                                </div>
                                                <div class="chat-contact__item-date">
													<?php echo $last_message_data['time'] ?? ''; ?>
                                                </div>
                                            </div>
                                        </div>
									<?php endwhile; ?>
                                </div>
                            </div>
						<?php else: ?>
                            <div class="chat-nav-wrap">
                                <div class="chat-not-contact">
                                    <div class="text-group">
                                        <h6>Повідомлень поки немає</h6>
                                        <p>Як тільки ви отримаєте повідомлення, воно з'явиться тут.</p>
                                        <p>
                                            Якщо ви щось продаєте чи надаєте послуги, почніть з
                                            <a href="<?php echo $url; ?>">публікації оголошення</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
						<?php
						endif;
						wp_reset_postdata();
						wp_reset_query();
						?>
                    </div>
                    <div class="chat-tab__item js-tab-item <?php echo $message_sender_id == $user_id ? 'active' : ''; ?>"
                         data-target="target_2">
						<?php
						$sender_args                 = $args;
						$sender_args['meta_query'][] = array(
							'key'   => '_message_sender_id',
							'value' => $user_id
						);
						$query                       = new WP_Query( $sender_args );
						if ( $query->have_posts() ):
							?>
                            <div class="chat-nav-wrap">
                                <div class="chat-contact">
									<?php
									while ( $query->have_posts() ):
										$query->the_post();
										$_id               = get_the_ID();
										$_sender_id        = carbon_get_post_meta( $_id, 'message_sender_id' );
										$_recipient_id     = carbon_get_post_meta( $_id, 'message_recipient_id' );
										$_product_id       = carbon_get_post_meta( $_id, 'message_product_id' );
										$userID            = $_sender_id == $user_id ? $_recipient_id : $_sender_id;
										$_user_avatar      = carbon_get_user_meta( $userID, 'user_avatar' );
										$_sender_user      = get_user_by( 'ID', $userID );
										$_email            = $_sender_user->user_email ?: '';
										$_display_name     = $_sender_user->display_name ?: '';
										$_first_name       = $_sender_user->first_name ?: '';
										$_last_name        = $_sender_user->last_name ?: '';
										$_name             = $_first_name ?: $_display_name;
										$last_message_data = get_last_message( $_id );
										$is_read           = $last_message_data['is_read'] ?? true;
										$cls               = '';
										if ( $correspondence == $_id ) {
											$cls = 'active';
										}
										if ( ! $is_read ) {
											$cls .= ' not-read';
										}
										?>
                                        <div data-url="<?php echo $_url . '?route=message&correspondence=' . $_id; ?>"
                                             data-user="<?php echo $userID; ?>"
                                             data-product="<?php echo $_product_id; ?>"
                                             class="chat-contact__item correspondence-link <?php echo $cls; ?> ">
                                            <div class="chat-contact__item-ava">
												<?php if ( $_user_avatar ): ?>
                                                    <img src="<?php _u( $_user_avatar ); ?>" alt=""/>
												<?php endif; ?>
                                            </div>
                                            <div class="chat-contact__item-info">
                                                <div class="chat-contact__item-title">
													<?php echo $_name; ?>
                                                </div>
                                                <div class="chat-contact__item-subtitle">
													<?php echo get_the_title( $_product_id ); ?>
                                                </div>
                                                <div class="chat-contact__item-date">
													<?php echo $last_message_data['time'] ?? ''; ?>
                                                </div>
                                            </div>
                                        </div>
									<?php endwhile; ?>
                                </div>
                            </div>
						<?php else: ?>
                            <div class="chat-nav-wrap">
                                <div class="chat-not-contact">
                                    <div class="text-group">
                                        <h6>Повідомлень поки немає</h6>
                                        <p>Як тільки ви отримаєте повідомлення, воно з'явиться тут.</p>
                                        <p>
                                            Якщо ви щось продаєте чи надаєте послуги, почніть з
                                            <a href="<?php echo $url; ?>">публікації оголошення</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
						<?php
						endif;
						wp_reset_postdata();
						wp_reset_query();
						?>
                    </div>
                </div>
            </div>
            <div class="chat-group__right <?php echo $correspondence == 0 || ! get_post( $correspondence ) ? 'chat-empty' : ''; ?> ">
                <div class="chat-main">
                    <div class="chat-main__top">
                        <div class="chat-main__user">
                            <div class="chat-contact__item-ava">
								<?php if ( $message_user_avatar ): ?>
                                    <img src="<?php _u( $message_user_avatar ); ?>" alt=""/>
								<?php endif; ?>
                            </div>
                            <div class="chat-contact__item-info">
                                <div class="chat-contact__item-title">
									<?php echo get_user_by( 'ID', $message_user_id )->first_name ?: get_user_by( 'ID', $message_user_id )->nickname; ?>
                                </div>
                                <div class="chat-contact__item-date">
                                    Онлайн <?php echo get_user_last_time_online( $message_user_id ); ?>
                                </div>
                            </div>
                        </div>
                        <a class="remove-cart remove-correspondence" data-id="<?php echo $correspondence ?>" href="#">
                            <span>Видалити </span>
                            <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                 style="enable-background:new 0 0 17.3 20" viewBox="0 0 17.3 20">
                                            <path d="M5.8 0c-.5 0-.9.4-.9.9v1.8h-4c-.5 0-.9.4-.9.9s.4.9.9.9h15.6c.5 0 .9-.4.9-.9s-.4-.9-.9-.9h-4V.9c0-.5-.4-.9-.9-.9H5.8zm.9 2.6v-.8h4.1v.8H6.7zM15.5 6.4H1.7v10.2c0 2 1.4 3.5 3.3 3.5h7.2c1.9 0 3.3-1.5 3.3-3.5V6.4zm-8 3.2c0-.5-.4-.9-.9-.9s-.9.4-.9.9v6.1c0 .5.4.9.9.9s.9-.4.9-.9V9.6zm4.1 0c0-.5-.4-.9-.9-.9s-.9.4-.9.9v6.1c0 .5.4.9.9.9s.9-.4.9-.9V9.6z"
                                                  style="fill-rule:evenodd;clip-rule:evenodd;fill:#fff"/>
                                        </svg>
                        </a>
                    </div>
                    <div class="chat-main__content">
						<?php the_correspondence_messages( $correspondence ); ?>
                    </div>
                    <div class="chat-main__bot">
                        <form action="<?php echo $admin_ajax; ?>"
                              method="post"
                              novalidate
                              id="new-message-form"
                              class="new-message-js form-js"
                              enctype="multipart/form-data">
                            <input type="hidden" name="action" value="new_message">
                            <input type="hidden" name="correspondence" value="<?php echo $correspondence; ?>">
                            <input type="hidden" name="message_sender_id" value="<?php echo $user_id; ?>">
                            <input type="hidden" name="message_recipient_id" value="<?php echo $message_user_id; ?>">
                            <div class="chat-form">
                                <label class="chat-file">
                                    <input
                                            class="chat-file_input"
                                            type="file"
                                            name="upfile[]"
                                            accept="image/heic, image/png, image/jpeg, image/webp"/>
                                    <img
                                            src="<?php echo $assets; ?>img/chat-file.svg"
                                            data-src="<?php echo $assets; ?>img/chat-file.svg"
                                            alt=""/>
                                </label>
                                <textarea class="chat-input"
                                          data-autoresize="data-autoresize" required="required"
                                          name="text"
                                          placeholder="Напишіть повідомлення..."></textarea>
                                <button class="chat-btn" type="submit">
                                    <img src="<?php echo $assets; ?>img/send.svg" alt=""/>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="no-announcement">
                    <div class="no-announcement__media">
                        <img src="<?php echo $assets; ?>img/chat-empty.webp" alt=""/>
                    </div>
                    <div class="no-announcement__title"> Чат не вибрано</div>
                    <div class="no-announcement__subtitle">Виберіть повідомлення, щоб прочитати його</div>
                </div>
            </div>
        </div>
    </div>
	<?php
}

function the_correspondence_messages( $correspondence, $page_num = 1 ) {
	if ( $correspondence ) {
		$user_id                     = get_current_user_id();
		$correspondence_sender_id    = carbon_get_post_meta( $correspondence, 'message_sender_id' );
		$correspondence_recipient_id = carbon_get_post_meta( $correspondence, 'message_recipient_id' );
		if ( $user_id == $correspondence_sender_id || $user_id == $correspondence_recipient_id ) {
			if ( $data = get_segmented_messages( $correspondence, $page_num ) ) {
				$paged                        = $data['paged'];
				$found_posts                  = $data['found_posts'];
				$max_num_pages                = $data['max_num_pages'];
				$segmented_messages           = $data['messages'];
				foreach ( $segmented_messages as $date => $messages ) {
					if ( $messages ) {
						if ( $max_num_pages > $page_num ) {
							?>
                            <a href="#" style="display: block"
                               data-max_num_pages="<?php echo $max_num_pages ?>"
                               data-paged="<?php echo $paged ?>"
                               data-correspondence="<?php echo $correspondence ?>"
                               class="dialog-date load-oldest-messages">
                                <span>Завантажити старіші</span>
                            </a>
							<?php
						}
						echo "<div class='dialog-date' data-date='$date'><span>$date</span></div>";
						foreach ( $messages as $message_id ) {
							if ( get_post( $message_id ) ):
								$message_text = base64_decode( strip_tags( get_content_by_id( $message_id ) ) );
								$message_time = get_the_date( 'H:i', $message_id );
								$product_id   = carbon_get_post_meta( $message_id, 'message_product_id' );
								$sender_id    = carbon_get_post_meta( $message_id, 'message_sender_id' );
								$recipient_id = carbon_get_post_meta( $message_id, 'message_recipient_id' );
								$media        = carbon_get_post_meta( $message_id, 'message_media' );
								$is_read      = carbon_get_post_meta( $message_id, 'message_is_read' );
								$cls          = $user_id == $sender_id ? '' : 'self-message';
								if ( (int) $recipient_id == $user_id ) {
									carbon_set_post_meta( $message_id, 'message_is_read', true );
									carbon_set_post_meta( carbon_get_post_meta( $message_id, 'message_notification_id' ) ?: 0, 'notification_is_read', 'read' );
									if ( $is_read ) {
										$cls .= " read";
									} else {
										$cls .= " not-read";
									}
								}
								?>
                                <div class="dialog-item <?php echo $cls; ?> "
                                     id="message-<?php echo $message_id; ?>">
									<?php if ( $media ): ?>
                                        <div class="dialog-item__media">
                                            <img src="<?php echo $media; ?>" alt="">
                                        </div>
									<?php endif; ?>
                                    <div class="dialog-item__text" id="message-text-<?php echo $message_id; ?>">
										<?php echo $message_text; ?>
                                    </div>
                                    <div class="dialog-item__date">
										<?php echo $message_time; ?>
                                    </div>
                                </div>
							<?php
							endif;
						}
					}
				}
			} else {
				echo '';
			}
		} else {
			echo '';
		}
	} else {
		echo '';
	}
}

function get_segmented_messages( $correspondence, $page_num = 1 ) {
	$default_posts_per_page = get_option( 'posts_per_page' );
	$user_id                = get_current_user_id();
	$array                  = array();
	$args                   = array(
		'post_type'      => 'message',
		'posts_per_page' => 10,
		'post_parent'    => $correspondence,
		'post_status'    => 'publish',
		'paged'          => $page_num,
		'orderby'        => 'date',
		'order'          => 'DESC',
	);
	$query                  = new WP_Query( $args );
	$found_posts            = $query->found_posts;
	$max_num_pages          = $query->max_num_pages;
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$_id   = get_the_ID();
			$_date = get_the_date( 'd.m.Y' );
			$date  = date( 'd.m.Y', time() );
			if ( $date == $_date ) {
				$_date = 'Сьогодні';
			}
			if ( ! isset( $array[ $_date ] ) ) {
				$array[ $_date ]   = array();
				$array[ $_date ][] = $_id;
			} else {
				array_unshift( $array[ $_date ], $_id );
			}
		}

	}
	wp_reset_postdata();
	wp_reset_query();

	return array(
		'messages'      => $array,
		'paged'         => $page_num,
		'found_posts'   => $found_posts,
		'max_num_pages' => $max_num_pages,
	);
}