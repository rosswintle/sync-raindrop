<?php

namespace SyncRaindrop\Data;

/**
 * Data object for Raindrop bookmarks.
 */
class Raindrop_Bookmark {

	/**
	 * @var int
	 */
	public $id = 0;

	/**
	 * This will be a Unix timestamp
	 *
	 * @var int
	 */
	public $created = 0;

	/**
	 * The description of the bookmark.
	 *
	 * @var string
	 */
	public $excerpt = '';

	/**
	 * The last update - this will be a Unix timestamp
	 *
	 * @var int
	 */
	public $last_update = 0;

	/**
	 * The bookmark link
	 *
	 * @var string
	 */
	public $link = '';

	/**
	 * The tags
	 *
	 * @var string[]
	 */
	public $tags = [];

	/**
	 * The link title
	 *
	 * @var string
	 */
	public $title = '';

	/**
	 * The link type. Can be link, article, image, video, document or audio
	 *
	 * @var string
	 */
	public $type = '';

	/**
	 * Create a new instance from an array.
	 *
	 * @param  StdClass $data The data to create from.
	 * @return self
	 */
	public static function from_api_object( $data ) {
		$bookmark = new self();
		foreach ( $data as $key => $value ) {
			if ( property_exists( $bookmark, $key ) ) {
				if ( in_array( $key, [ 'created', 'last_updated' ], true ) ) {
					$bookmark->$key = strtotime( $value );
				} else {
					$bookmark->$key = $value;
				}
			}
			if ( $key === '_id' ) {
				$bookmark->id = $value;
			}
		}
		return $bookmark;
	}
}
