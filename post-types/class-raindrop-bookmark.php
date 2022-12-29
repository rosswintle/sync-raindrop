<?php

namespace SyncRaindrop;

class Raindrop_Bookmark {

	public static function with_hash( $hash ) {
		$posts = get_posts([
			'post_type'  => 'raindrop-bookmark',
			'meta_query' => [
				[
					'key'   => 'hash',
					'value' => $hash,
				],
			],
		]);
		if (is_array($posts)) {
			return current($posts);
		} else {
			return null;
		}
	}

}
