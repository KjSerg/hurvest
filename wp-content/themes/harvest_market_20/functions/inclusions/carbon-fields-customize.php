<?php

add_filter( 'carbon_fields_association_field_title', 'bsn_8723_article_titles', 10, 5 );

function bsn_8723_article_titles( $title, $name, $id, $type, $subtype ) {
	if ( $type == 'user' ) {
		$user_tags = get_the_terms( $id, 'user_tags' );
		if ( $user_tags ) {
			$title .= ' [' . $user_tags . ']';
		}
	}

	return $title;
}