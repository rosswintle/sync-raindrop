<?php

namespace SyncRaindrop;

class Raindrop_Bookmark {

	public static function with_id( $id ) {
		$posts = get_posts([
			'post_type'  => 'raindrop-bookmark',
			'meta_query' => [
				[
					'key'   => 'raindrop_id',
					'value' => $id,
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
