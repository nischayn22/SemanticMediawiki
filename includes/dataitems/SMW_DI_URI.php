<?php
/**
 * @file
 * @ingroup SMWDataItems
 */

/**
 * This class implements URI data items.
 *
 * @author Markus Krötzsch
 * @ingroup SMWDataItems
 */
class SMWDIURI extends SMWDataItem {

	/**
	 * URI scheme such as "html" or "mailto".
	 * @var string
	 */
	protected $m_scheme;
	/**
	 * "Hierpart" of the URI (usually some authority and path).
	 * @var string
	 */
	protected $m_hierpart;
	/**
	 * Query part of the URI.
	 * @var string
	 */
	protected $m_query;
	/**
	 * Fragment part of the URI.
	 * @var string
	 */
	protected $m_fragment;

	/**
	 * Initialise a URI by providing its scheme (e.g. "html"), 'hierpart'
	 * following "scheme:" (e.g. "//username@example.org/path), query (e.g.
	 * "q=Search+term", and fragment (e.g. "section-one"). The complete URI
	 * with these examples would be
	 * http://username@example.org/path?q=Search+term#section-one
	 * @param $scheme string for the scheme
	 * @param $hierpart string for the "hierpart"
	 * @param $query string for the query
	 * @param $fragment string for the fragment
	 * @param $typeid string SMW type id
	 *
	 * @todo Implement more validation here.
	 */
	public function __construct( $scheme, $hierpart, $query, $fragment, $typeid = '_uri' ) {
		if ( ( $scheme == '' ) || ( preg_match( '/[^a-zA-Z]/u', $scheme ) ) ) {
			throw new SMWDataItemException( "Illegal URI scheme \"$scheme\"." );
		}
		if ( $hierpart == '' ) {
			throw new SMWDataItemException( "Illegal URI hierpart \"$hierpart\"." );
		}
		parent::__construct( $typeid );
		$this->m_scheme   = $scheme;
		$this->m_hierpart = $hierpart;
		$this->m_query    = $query;
		$this->m_fragment = $fragment;
	}

	public function getDIType() {
		return SMWDataItem::TYPE_URI;
	}

	public function getURI() {
		return $this->m_scheme . ':' . $this->m_hierpart .
			( $this->m_query ? '?' . $this->m_query : '' ) . 
			( $this->m_fragment ? '#' . $this->m_fragment : '' );
	}

	public function getScheme() {
		return $this->m_scheme;
	}

	public function getHierpart() {
		return $this->m_hierpart;
	}

	public function getQuery() {
		return $this->m_query;
	}

	public function getFragment() {
		return $this->m_fragment;
	}

	public function getSerialization() {
		return $this->getURI();
	}

	/**
	 * Create a data item from the provided serialization string and type
	 * ID.
	 * @return SMWDIURI
	 */
	public static function doUnserialize( $serialization, $typeid ) {
		$parts = explode( ':', $serialization, 2 ); // try to split "schema:rest"
		if ( count( $parts ) <= 1 ) {
			throw new SMWDataItemException( "Unserialization failed: the string \"$serialization\" is no valid URI." );
		}
		$scheme = $parts[0];
		$parts = explode( '?', $parts[1], 2 ); // try to split "hier-part?queryfrag"
		if ( count( $parts ) == 2 ) {
			$hierpart = $parts[0];
			$parts = explode( '#', $parts[1], 2 ); // try to split "query#frag"
			$query = $parts[0];
			$fragment = ( count( $parts ) == 2 ) ? $parts[1] : '';
		} else {
			$query = '';
			$parts = explode( '?', $rest, 2 ); // try to split "hier-part#frag"
			$hierpart = $parts[0];
			$fragment = ( count( $parts ) == 2 ) ? $parts[1] : '';
		}
		return new SMWDIURI( $scheme, $hierpart, $query, $fragment, $typeid );
	}

}