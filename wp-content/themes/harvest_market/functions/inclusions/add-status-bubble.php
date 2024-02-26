<?php

function wph_cut_the_title( $title ) {
	$title       = esc_attr( $title );
	$findthese   = array(
		'#Защищено:#',
		'#Личное:#'
	);
	$replacewith = array(
		'', //можно указать замену для "Защищено"
		''  //можно указать замену для "Личное"
	);
	$title       = preg_replace( $findthese, $replacewith, $title );

	return $title;
}

add_filter( 'the_title', 'wph_cut_the_title' );


function true_status_custom() {
	register_post_status( 'archive', array(
		'label'                     => 'Неактивний',
		'label_count'               => _n_noop( 'Неактивний <span class="count">(%s)</span>', 'Неактивний <span class="count">(%s)</span>' ),
		'public'                    => true,
		'show_in_admin_status_list' => true
		// если установить этот параметр равным false, то следующий параметр можно удалить
	) );
	register_post_status( 'archive', array(
		'label'                     => 'Неактивний',
		'label_count'               => _n_noop( 'Неактивний <span class="count">(%s)</span>', 'Неактивний <span class="count">(%s)</span>' ),
		'public'                    => true,
		'show_in_admin_status_list' => true
		// если установить этот параметр равным false, то следующий параметр можно удалить
	) );
}

add_action( 'init', 'true_status_custom' );

function true_append_post_status_list() {
	global $post;
	$optionselected = '';
	$statusname     = '';
	if ( $post->post_type == 'products' ) { // если хотите, можете указать тип поста, для которого регистрируем статус, а можете и вовсе избавиться от этого условия
		if ( $post->post_status == 'archive' ) { // если посту присвоен статус архива
			$optionselected = ' selected="selected"';
			$statusname     = "$('#post-status-display').text('Неактивний');";
		}
		/*
		 * Код jQuery мы просто выводим в футере
		 */
		echo "<script>
		jQuery(function($){
			$('select#post_status').append('<option value=\"archive\"$optionselected>Неактивний</option>');
			$statusname
		});
		</script>";
	}
}

add_action( 'admin_footer-post-new.php', 'true_append_post_status_list' ); // страница создания нового поста
add_action( 'admin_footer-post.php', 'true_append_post_status_list' ); // страница редактирования поста

function true_status_display( $statuses ) {
	global $post;
	if ( get_query_var( 'post_status' ) != 'archive' ) { // проверка, что мы не находимся на странице всех постов данного статуса
		if ( $post->post_status == 'archive' ) { // если статус поста - Архив
			return array( 'Неактивний' );
		}
	}

	return $statuses;
}

add_filter( 'display_post_states', 'true_status_display' );

add_action( 'admin_footer-edit.php', 'add_status' );

function add_status() {
	echo "<script>
	jQuery(document).ready( function($) { 
		$( 'select[name=\"_status\"]' ).append( '<option value=\"archive\">Неактивний</option>' );
	});
	</script>";
}

add_action( 'admin_menu', 'add_user_menu_bubble' );
function add_user_menu_bubble() {
	global $menu;
	$count1 = wp_count_posts( 'products' )->pending;
	$count2 = wp_count_posts( 'orders' )->pending;
	$count3 = wp_count_posts( 'applications' )->pending;
	$count4 = wp_count_posts( 'reviews' )->pending;
	$count5 = wp_count_posts( 'purchased' )->pending;
	if ( $count1 ) {
		foreach ( $menu as $key => $value ) {
			if ( $menu[ $key ][2] == 'edit.php?post_type=products' ) {
				$menu[ $key ][0] .= ' <span class="awaiting-mod"><span class="pending-count">' . $count1 . '</span></span>';
				break;
			}
		}
	}
	if ( $count2 ) {
		foreach ( $menu as $key => $value ) {
			if ( $menu[ $key ][2] == 'edit.php?post_type=orders' ) {
				$menu[ $key ][0] .= ' <span class="awaiting-mod"><span class="pending-count">' . $count2 . '</span></span>';
				break;
			}
		}
	}
	if ( $count3 ) {
		foreach ( $menu as $key => $value ) {
			if ( $menu[ $key ][2] == 'edit.php?post_type=applications' ) {
				$menu[ $key ][0] .= ' <span class="awaiting-mod"><span class="pending-count">' . $count3 . '</span></span>';
				break;
			}
		}
	}
	if ( $count4 ) {
		foreach ( $menu as $key => $value ) {
			if ( $menu[ $key ][2] == 'edit.php?post_type=reviews' ) {
				$menu[ $key ][0] .= ' <span class="awaiting-mod"><span class="pending-count">' . $count4 . '</span></span>';
				break;
			}
		}
	}
	if ( $count5 ) {
		foreach ( $menu as $key => $value ) {
			if ( $menu[ $key ][2] == 'edit.php?post_type=purchased' ) {
				$menu[ $key ][0] .= ' <span class="awaiting-mod"><span class="pending-count">' . $count5 . '</span></span>';
				break;
			}
		}
	}
}