<?php

namespace SyncRaindrop\Data;

/**
 * Data object for Raindrop bookmarks.
 */
class Raindrop_Collection {

	/**
	 * @var int
	 */
	public $id = 0;

	/**
	 * Access level:
	 *
	 * 1 = read only (public = true)
	 * 2 = collaborator with read-only access
	 * 3 = collaborator with write-only access
	 * 4 = owner
	 *
	 * @var int
	 */
	public $access_level;

	/**
	 * Readable based on access level
	 *
	 * @var boolean
	 */
	public $readable;

	/**
	 * Count of items in collection
	 *
	 * @var int
	 */
	public $count;

	/**
	 * This will be a Unix timestamp
	 *
	 * @var int
	 */
	public $created = 0;

	/**
	 * The last update - this will be a Unix timestamp
	 *
	 * @var int
	 */
	public $last_update = 0;

	/**
	 * Is the collection public
	 *
	 * @var boolean
	 */
	public $public = false;

	/**
	 * The link title
	 *
	 * @var string
	 */
	public $title = '';

	/**
	 * Create a new instance from an array.
	 *
	 * @param  StdClass $data The data to create from.
	 * @return self
	 */
	public static function from_api_object( $data ) {
		$collection = new self();
		foreach ( $data as $key => $value ) {
			if ( property_exists( $collection, $key ) ) {
				if ( in_array( $key, array( 'created', 'last_update' ), true ) ) {
					$collection->$key = strtotime( $value );
				} else {
					$collection->$key = $value;
				}
				continue;
			}
			if ( $key === '_id' ) {
				$collection->id = $value;
				continue;
			}
			if ( $key === 'access' ) {
				$collection->access_level = $value->level;
				$collection->readable     = in_array( $value->level, array( 1, 2, 4 ) );
				continue;
			}
		}
		return $collection;
	}
}
