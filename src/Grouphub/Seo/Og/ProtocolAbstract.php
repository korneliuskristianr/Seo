<?php namespace Grouphub\Seo\Og;

abstract class ProtocolAbstract {
	/**
	 * Version
	 * @var string
	 */
	const VERSION = '1.3';

	/**
	 * Should we remotely request each referenced URL to make sure it exists and returns the expected Internet media type?
	 * @var bool
	 */
	const VERIFY_URLS = false;	
	/**
	 * Meta attribute name. Use 'property' if you prefer RDF or 'name' if you prefer HTML validation
	 * @var string
	 */
	const META_ATTR = 'property';

	/**
	 * Property prefix
	 * @var string
	 */
	const PREFIX = 'og';

	/**
	 * prefix namespace
	 * @var string
	 */
	const NS = 'http://ogp.me/ns#';


	/**
	 * Convert a DateTime object to GMT and format as an ISO 8601 string.
	 *
	 * @param DateTime $date date to convert
	 * @return string ISO 8601 formatted datetime string
	 */
	public static function datetimeToIso8601( DateTime $date ) {
		$date->setTimezone(new DateTimeZone('GMT'));
		return $date->format('c');
	}
	
		/**
	 * Cleans a URL string, then checks to see if a given URL is addressable, returns a 200 OK response, and matches the accepted Internet media types (if provided).
	 *
	 * @param string $url Publicly addressable URL
	 * @param array $accepted_mimes Given URL correspond to an accepted Internet media (MIME) type.
	 * @return string cleaned URL string, or empty string on failure.
	 */
	public static function isValidUrl( $url, array $accepted_mimes = array() ) {
		if ( !is_string( $url ) || empty( $url ) )
			return '';

		/*
		 * Validate URI string by letting PHP break up the string and put it back together again
		 * Excludes username:password and port number URI parts
		 */
		if (self::VERIFY_URLS) {

			$url_parts = parse_url( $url );
			extract($url_parts);

			$url = '';
			if ( isset( $scheme ) && in_array( $scheme, array('http', 'https'), true ) ) {
				$url = "{$scheme}://{$host}{$path}";
				if ( empty( $path ) )
					$url .= '/';
				if ( !empty( $query ) )
					$url .= '?' . $query;
				if ( !empty( $fragment ) )
					$url .= '#' . $fragment;
			}

			if ( !empty( $url ) ) {
				return $this->checkUrl($url);
			}	
		}
			
		return $url;
	}

	/**
	 * Checking URL existence
	 *
	 * @param string
	 */
	protected function checkUrl($url)
	{
		// test if URL exists
		$ch = curl_init( $url );

		curl_setopt( $ch, CURLOPT_TIMEOUT, 5 );
		curl_setopt( $ch, CURLOPT_FORBID_REUSE, true );
		curl_setopt( $ch, CURLOPT_NOBODY, true ); // HEAD
		curl_setopt( $ch, CURLOPT_USERAGENT, 'Open Graph protocol validator ' . self::VERSION . ' (+http://ogp.me/)' );
		
		if ( !empty($accepted_mimes) )
			curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Accept: ' . implode( ',', $accepted_mimes ) ) );

		$response = curl_exec( $ch );

		if ( curl_getinfo( $ch, CURLINFO_HTTP_CODE ) == 200 ) {

			if ( !empty($accepted_mimes) ) {
				$content_type = explode( ';', curl_getinfo( $ch, CURLINFO_CONTENT_TYPE ) );

				if ( empty( $content_type ) || !in_array( $content_type[0], $accepted_mimes ) )
					return '';
			}

		}

		return '';		
	}

	/**
	 * Call uncallable function
	 */
	public function __call($method, $arguments)
	{
		if(!method_exists($this, $method)) throw new Exception("{$method} not found.");
	}

	/**
	 * Build Open Graph protocol HTML markup based on an array
	 *
	 * @param array $og associative array of OGP properties and values
	 * @param string $prefix optional prefix to prepend to all properties
	 * @example <meta property="og:image" content="http://example.com/rock.jpg" />
	 */
	public static function build( array $og, $prefix=self::PREFIX ) {
		if ( empty($og) ) return;

		$s = '';

		foreach ( $og as $property => $content ) {
			if ( is_object( $content ) || is_array( $content ) ) {
				if ( is_object( $content ) )
					$content = $content->toArray();
				if ( empty($property) || !is_string($property) )
					$s .= static::build( $content, $prefix );
				else
					$s .= static::build( $content, $prefix . ':' . $property );
			} elseif ( !empty($content) ) {
				$s .= '<meta ' . self::META_ATTR . '="' . $prefix;
				if ( is_string($property) && !empty($property) ) $s .= ':' . htmlspecialchars( $property );
				$s .= '" content="' . htmlspecialchars($content) . '"/>' . PHP_EOL;
			}
		}
		return $s;
	}

	/**
	 * Output the OpenGraphProtocol object as HTML elements string
	 *
	 * @return string meta elements
	 */
	public function render() {
		return rtrim( static::build( get_object_vars($this) ), PHP_EOL );
	}

}