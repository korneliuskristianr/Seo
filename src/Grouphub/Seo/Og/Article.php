<?php namespace Grouphub\Seo\Og;

use Grouphub\Seo\Og\ProtocolAbstract;

class Article extends ProtocolAbstract {
	/**
	 * Property prefix
	 * @var string
	 */
	const PREFIX = 'article';

	/**
	 * prefix namespace
	 * @var string
	 */
	const NS = 'http://ogp.me/ns/article#';

	/**
	 * When the article was first published.
	 * ISO 8601 formatted string.
	 * @var string
	 */
	protected $publishedTime;

	/**
	 * When the article was last changed
	 * ISO 8601 formatted string.
	 * @var string
	 */
	protected $modifiedTime;

	/**
	 * When the article is considered out-of-date
	 * ISO 8601 formatted string.
	 * @var string
	 */
	protected $expirationTime;

	/**
	 * Writers of the article.
	 * Array of author URIs
	 * @var array
	 */
	protected $author;

	/**
	 * High-level section or category
	 * @var string
	 */
	protected $section;

	/**
	 * Content tag
	 * Array of tag strings
	 * @var array
	 */
	protected $tag;

	/**
	 * Initialize arrays
	 */
	public function __construct() {
		$this->author = array();
		$this->tag = array();
	}

	/**
	 * When the article was first published
	 * @return string ISO 8601 formatted publication date and optional time
	 */
	public function getPublishedTime() {
		return $this->publishedTime;
	}

	/**
	 * Set when the article was first published
	 * @param DateTime|string $pubdate ISO 8601 formatted datetime string or DateTime object for conversion
	 */
	public function setPublishedTime( $pubdate ) {
		if ( $pubdate instanceof DateTime )
			$this->publishedTime = static::datetimeToIso8601($pubdate);
		else if ( is_string($pubdate) && strlen($pubdate) >= 10 ) // at least YYYY-MM-DD
			$this->publishedTime = $pubdate;
		return $this;
	}

	/**
	 * When article was last changed
	 * @return string ISO 8601 formatted modified date and optional time
	 */
	public function getModifiedTime() {
		return $this->modifiedTime;
	}

	/**
	 * Set when the article was last changed
	 * @param DateTime|string $updated ISO 8601 formatted datetime string or DateTime object for conversion
	 */
	public function setModifiedTime( $updated ) {
		if ( $updated instanceof DateTime )
			$this->modifiedTime = static::datetimeToIso8601($updated);
		else if ( is_string($updated) && strlen($updated) >= 10 ) // at least YYYY-MM-DD
			$this->modifiedTime = $updated;
		return $this;
	}

	/**
	 * Time the article content expires
	 * @return string ISO 8601 formatted expiration date and optional time
	 */
	public function getExpirationTime() {
		return $this->expirationTime;
	}

	/**
	 * Set when the article content expires
	 * @param DateTime|string $expires ISO formatted datetime string or DateTime object for conversion
	 */
	public function setExpirationTime( $expires ) {
		if ( $expires instanceof DateTime )
			$this->expirationTime = static::datetimeToIso8601($expires);
		else if ( is_string($expires) && strlen($expires) >= 10 )
			$this->expirationTime = $expires;
		return $this;
	}

	/**
	 * Article author URIs
	 * @return array Article author URIs
	 */
	public function getAuthors() {
		return $this->author;
	}

	/**
	 * Add an author URI
	 * @param string $authorUri Author URI
	 */
	public function addAuthor( $authorUri ) {
		if ( static::isValidUrl($authorUri) && !in_array($authorUri, $this->author))
			$this->author[] = $authorUri;
		return $this;
	}

	/**
	 * High-level section name
	 */
	public function getSection() {
		return $this->section;
	}

	/**
	 * Set the top-level content section
	 * @param string $section
	 */
	public function setSection( $section ) {
		if ( is_string($section) && !empty($section) )
			$this->section = $section;
		return $this;
	}

	/**
	 * Content tags
	 * @return array content tags
	 */
	public function getTags() {
		return $this->tag;
	}

	/**
	 * Add a content tag
	 * @param string $tag content tag
	 */
	public function addTag( $tag ) {
		if ( is_string($tag) && !empty($tag) )
			$this->tag[] = $tag;
		return $this;
	}
}
