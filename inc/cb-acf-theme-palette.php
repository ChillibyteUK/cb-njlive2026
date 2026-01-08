<?php
// Field name: bg_color (change to your field name)
add_filter(
	'acf/load_field/name=bg_colour',
	function ( $field ) {
		// Prefer the theme palette; fall back to default/global if needed.
		$palette = wp_get_global_settings( [ 'color', 'palette', 'theme' ] );
		if ( empty( $palette ) ) {
			$palette = wp_get_global_settings( [ 'color', 'palette', 'default' ] );
		}

		$field['choices']    = [];
		$field['allow_null'] = 1;

		foreach ( (array) $palette as $c ) {
			if ( empty( $c['slug'] ) || empty( $c['name'] ) ) {
				continue;
			}
			$field['choices'][ $c['slug'] ] = $c['name'];
		}
		return $field;
	}
);
// Field name: fg_color (change to your field name)
add_filter(
	'acf/load_field/name=fg_colour',
	function ( $field ) {
		// Prefer the theme palette; fall back to default/global if needed.
		$palette = wp_get_global_settings( [ 'color', 'palette', 'theme' ] );
		if ( empty( $palette ) ) {
			$palette = wp_get_global_settings( [ 'color', 'palette', 'default' ] );
		}

		$field['choices']    = [];
		$field['allow_null'] = 1;

		foreach ( (array) $palette as $c ) {
			if ( empty( $c['slug'] ) || empty( $c['name'] ) ) {
				continue;
			}
			$field['choices'][ $c['slug'] ] = $c['name'];
		}
		return $field;
	}
);