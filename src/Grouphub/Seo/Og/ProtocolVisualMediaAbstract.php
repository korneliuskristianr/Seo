<?php namespace Grouphub\Seo\Og;

use Grouphub\Seo\Og\ProtocolMediaAbstract;

abstract class ProtocolVisualMediaAbstract extends ProtocolMediaAbstract {
	/**
	 * Height of the media object in pixels
	 *
	 * @var int
	 * @since 1.3
	 */
	protected $height;

	/**
	 * Width of the media object in pixels
	 *
	 * @var int
	 * @since 1.3
	 */
	protected $width;

	/**
	 * @return int width in pixels
	 */
	public function getWidth() {
		return $this->width;
	}

	/**
	 * Set the object width
	 *
	 * @param int $width width in pixels
	 */
	public function setWidth( $width ) {
		if ( is_int($width) && $width >  0 )
			$this->width = $width;
		return $this;
	}

	/**
	 * @return int height in pixels
	 */
	public function getHeight() {
		return $this->height;
	}

	/**
	 * Set the height of the referenced object in pixels
	 * @var int height of the referenced object in pixels
	 */
	public function setHeight( $height ) {
		if ( is_int($height) && $height > 0 )
			$this->height = $height;
		return $this;
	}
}