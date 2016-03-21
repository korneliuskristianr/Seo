<?php namespace Grouphub\Seo\Og;

use Grouphub\Seo\Og\ProtocolAbstract;
use Grouphub\Seo\Og\Image;
use Grouphub\Seo\Og\Video;
use Grouphub\Seo\Og\Audio;

class Protocol extends ProtocolAbstract implements ProtocolInterface {	

	/**
	 * Page classification according to a pre-defined set of base types.
	 *
	 * @var string
	 * @since 1.0
	 */
	protected $type;

	/**
	 * The title of your object as it should appear within the graph.
	 *
	 * @var string
	 * @since 1.0
	 */
	protected $title;

	/**
	 * If your object is part of a larger web site, the name which should be displayed for the overall site.
	 *
	 * @var string
	 * @since 1.0
	 */
	protected $siteName;

	/**
	 * A one to two sentence description of your object.
	 *
	 * @var string
	 * @since 1.0
	 */
	protected $description;

	/**
	 * The canonical URL of your object that will be used as its permanent ID in the graph.
	 *
	 * @var string
	 * @since 1.0
	 */
	protected $url;

	/**
	 * The word that appears before this object's title in a sentence
	 *
	 * @var string
	 * @since 1.3
	 */
	protected $determiner;

	/**
	 * Language and optional territory of page content.
	 * @var string
	 * @since 1.3
	 */
	protected $locale;

	/**
	 * An array of ProtocolImage objects
	 *
	 * @var array
	 * @since 1.0
	 */
	protected $image;

	/**
	 * An array of ProtocolAudio objects
	 *
	 * @var array
	 * @since 1.2
	 */
	protected $audio;

	/**
	 * An array of ProtocolVideo objects
	 *
	 * @var array
	 * @since 1.2
	 */
	protected $video;

	/**
	 * Facebook maps languages to a default territory and only accepts locales in this list. A few popular languages such as English and French support multiple territories.
	 * Map the Facebook list to avoid throwing errors in Facebook parsers that prevent further content indexing
	 *
	 * @link https://www.facebook.com/translations/FacebookLocales.xml Facebook locales
	 * @param bool $keysOnly return only keys
	 * @return array associative array of locale code and locale name. locale code is in the format language_TERRITORY where language is an ISO 639-1 alpha-2 code and territory is an ISO 3166-1 alpha-2 code with special regions 'AR' and 'LA' for Arab region and Latin America respectively.
	 */
	public function supportedLocales( $keysOnly=false ) 
	{
		$locales = \Config::get('seo::supported_locales');

		if ( $keysOnly === true )  return array_keys($locales);		

		return $locales;		
	}

	/**
	 * A list of allowed page types in the Open Graph Protocol
	 *
	 * @param Bool $flatten true for grouped types one level deep
	 * @link http://ogp.me/#types Open Graph Protocol object types
	 * @return array Array of Open Graph Protocol object types
	 */
	public static function supportedTypes( $flatten=false ) 
	{
		$types = \Config::get('seo::supported_types');

		if ( $flatten === true ) {
			$types_values = array();
			foreach ( $types as $category=>$values ) {
				$types_values = array_merge( $types_values, array_keys($values) );
			}
			return $types_values;
		} 

		return $types;
	
	}

	/**
	 * @return String the type slug
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 *
	 * @param String type slug
	 */
	public function setType( $type ) {
		if ( is_string($type) && in_array( $type, self::supportedTypes(true), true ) ) $this->type = $type;
		else throw new Exception("{$type} is not supported");
		return $this;
	}

	/**
	 * @return String document title
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param String $title document title
	 */
	public function setTitle( $title ) {
		if ( is_string($title) ) {
			$title = trim( $title );
			if ( strlen( $title ) > 128 )
				$this->title = substr( $title, 0, 128 );
			else
				$this->title = $title;
		}
		return $this;
	}

	/**
	 * @return String Site name
	 */
	public function getSiteName() {
		return $this->siteName;
	}

	/**
	 * @param String $siteName Site name
	 */
	public function setSiteName( $siteName ) {
		if ( is_string($siteName) && !empty($siteName) ) {
			$siteName = trim( $siteName );
			if ( strlen( $siteName ) > 128 )
				$this->siteName = substr( $siteName, 0, 128 );
			else
				$this->siteName = $siteName;
		}
		return $this;
	}

	/**
	 * @return String Description
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param String $description Document description
	 */
	public function setDescription( $description ) {
		if ( is_string($description) && !empty($description) ) {
			$description = trim( $description );
			if ( strlen( $description ) > 255 )
				$this->description = substr( $description, 0, 255 );
			else
				$this->description = $description;
		}
		return $this;
	}

	/**
	 * @return String URL
	 */
	public function getURL() {
		return $this->url;
	}

	/**
	 * @param String $url Canonical URL
	 */
	public function setURL( $url ) {
		if ( is_string( $url ) && !empty( $url ) ) {

			$url = trim($url);

			if (self::VERIFY_URLS) {
				$url = self::isValidUrl( $url, array( 'text/html', 'application/xhtml+xml' ) );
			}

			if ( !empty( $url ) ) $this->url = $url;
		}
		return $this;
	}

	/**
	 * @return string the determiner
	 */
	public function getDeterminer() {
		return $this->determiner;
	}

	public function setDeterminer( $determiner ) {
		if ( in_array($determiner, array('a','an','auto','the'), true) )
			$this->determiner = $determiner;

		return $this;
	}

	/**
	 * @return string language_TERRITORY
	 */
	public function getLocale() {
		return $this->locale;
	}

	/**
	 * @var string $locale locale in the format language_TERRITORY
	 */
	public function setLocale( $locale ) {
		if ( is_string($locale) && in_array($locale, static::supportedLocales(true)) )
			$this->locale = $locale;

		return $this;
	}
	/**
	 * @return array ProtocolImage array
	 */
	public function getImage() {
		return $this->image;
	}

	/**
	 * Add an image.
	 * The first image added is given priority by the Open Graph Protocol spec. Implementors may choose a different image based on size requirements or preferences.
	 *
	 * @param ProtocolImage $image image object to add
	 */
	public function addImage(Image $image ) {
		$image_url = $image->getURL();
		if ( empty($image_url) )
			return;
		$image->removeURL();
		$value = array( $image_url, array($image) );
		
		if ( ! isset( $this->image ) )
			$this->image = array( $value );
		else
			$this->image[] = $value;
		return $this;
	}

	/**
	 * @return array Audio objects
	 */
	public function getAudio() {
		return $this->audio;
	}

	/**
	 * Add an audio reference
	 * The first audio is given priority by the Open Graph protocol spec.
	 *
	 * @param Audio $audio audio object to add
	 */
	public function addAudio( Audio $audio ) {
		$audio_url = $audio->getURL();
		if ( empty($audio_url) )
			return;
		$audio->removeURL();
		$value = array( $audio_url, array($audio) );
		if ( ! isset($this->audio) )
			$this->audio = array($value);
		else
			$this->audio[] = $value;
		return $this;
	}

	/**
	 * @return array Video objects
	 */
	public function getVideo() {
		return $this->video;
	}

	/**
	 * Add a video reference
	 * The first video is given priority by the Open Graph protocol spec. Implementors may choose a different video based on size requirements or preferences.
	 *
	 * @param Video $video video object to add
	 */
	public function addVideo( Video $video ) {
		$video_url = $video->getURL();
		if ( empty($video_url) )
			return;
		$video->removeURL();
		$value = array( $video_url, array($video) );
		if ( ! isset( $this->video ) )
			$this->video = array( $value );
		else
			$this->video[] = $value;
		return $this;
	}
}