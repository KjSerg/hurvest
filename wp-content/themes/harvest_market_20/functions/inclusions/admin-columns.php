<?php

function true_add_post_columns( $my_columns ) {
	$my_columns['status'] = 'Статус';
	$my_columns['amount'] = 'Сума';

	return $my_columns;
}

add_filter( 'manage_edit-purchased_columns', 'true_add_post_columns', 10, 1 );

function true_fill_post_columns( $column ) {
	global $post;
	$purchased_sum    = carbon_get_post_meta( $post->ID, 'purchased_sum' );
	$purchased_status = carbon_get_post_meta( $post->ID, 'purchased_status' );

	$status = $purchased_status === 'payed' ? '<br> <strong style="color: green">ОПЛАЧЕНО</strong>' : '<br> <strong style="color: red">Неоплачено</strong>';


	switch ( $column ) {
		case 'status':
			echo $status;
			break;
		case 'amount':
			echo $purchased_sum;
			break;
	}
}

add_action( 'manage_posts_custom_column', 'true_fill_post_columns', 10, 1 );