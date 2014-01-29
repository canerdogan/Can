<?php
/**
 *
 * @author canerdogan
 * @version 
 */
require_once 'Zend/View/Interface.php';

/**
 * Esc helper
 *
 * @uses viewHelper Can
 */
class Can_View_Helper_Esc extends Zend_View_Helper_Abstract
{	
	private $_allowedentitynames = array(
		'nbsp',    'iexcl',  'cent',    'pound',  'curren', 'yen',
		'brvbar',  'sect',   'uml',     'copy',   'ordf',   'laquo',
		'not',     'shy',    'reg',     'macr',   'deg',    'plusmn',
		'acute',   'micro',  'para',    'middot', 'cedil',  'ordm',
		'raquo',   'iquest', 'Agrave',  'Aacute', 'Acirc',  'Atilde',
		'Auml',    'Aring',  'AElig',   'Ccedil', 'Egrave', 'Eacute',
		'Ecirc',   'Euml',   'Igrave',  'Iacute', 'Icirc',  'Iuml',
		'ETH',     'Ntilde', 'Ograve',  'Oacute', 'Ocirc',  'Otilde',
		'Ouml',    'times',  'Oslash',  'Ugrave', 'Uacute', 'Ucirc',
		'Uuml',    'Yacute', 'THORN',   'szlig',  'agrave', 'aacute',
		'acirc',   'atilde', 'auml',    'aring',  'aelig',  'ccedil',
		'egrave',  'eacute', 'ecirc',   'euml',   'igrave', 'iacute',
		'icirc',   'iuml',   'eth',     'ntilde', 'ograve', 'oacute',
		'ocirc',   'otilde', 'ouml',    'divide', 'oslash', 'ugrave',
		'uacute',  'ucirc',  'uuml',    'yacute', 'thorn',  'yuml',
		'quot',    'amp',    'lt',      'gt',     'apos',   'OElig',
		'oelig',   'Scaron', 'scaron',  'Yuml',   'circ',   'tilde',
		'ensp',    'emsp',   'thinsp',  'zwnj',   'zwj',    'lrm',
		'rlm',     'ndash',  'mdash',   'lsquo',  'rsquo',  'sbquo',
		'ldquo',   'rdquo',  'bdquo',   'dagger', 'Dagger', 'permil',
		'lsaquo',  'rsaquo', 'euro',    'fnof',   'Alpha',  'Beta',
		'Gamma',   'Delta',  'Epsilon', 'Zeta',   'Eta',    'Theta',
		'Iota',    'Kappa',  'Lambda',  'Mu',     'Nu',     'Xi',
		'Omicron', 'Pi',     'Rho',     'Sigma',  'Tau',    'Upsilon',
		'Phi',     'Chi',    'Psi',     'Omega',  'alpha',  'beta',
		'gamma',   'delta',  'epsilon', 'zeta',   'eta',    'theta',
		'iota',    'kappa',  'lambda',  'mu',     'nu',     'xi',
		'omicron', 'pi',     'rho',     'sigmaf', 'sigma',  'tau',
		'upsilon', 'phi',    'chi',     'psi',    'omega',  'thetasym',
		'upsih',   'piv',    'bull',    'hellip', 'prime',  'Prime',
		'oline',   'frasl',  'weierp',  'image',  'real',   'trade',
		'alefsym', 'larr',   'uarr',    'rarr',   'darr',   'harr',
		'crarr',   'lArr',   'uArr',    'rArr',   'dArr',   'hArr',
		'forall',  'part',   'exist',   'empty',  'nabla',  'isin',
		'notin',   'ni',     'prod',    'sum',    'minus',  'lowast',
		'radic',   'prop',   'infin',   'ang',    'and',    'or',
		'cap',     'cup',    'int',     'sim',    'cong',   'asymp',
		'ne',      'equiv',  'le',      'ge',     'sub',    'sup',
		'nsub',    'sube',   'supe',    'oplus',  'otimes', 'perp',
		'sdot',    'lceil',  'rceil',   'lfloor', 'rfloor', 'lang',
		'rang',    'loz',    'spades',  'clubs',  'hearts', 'diams',
	);
	
	/**
	 * Escaping for HTML attributes.
	 *
	 * @param string $text
	 * @return string
	 */
	public function esc( $text ) {
		$safe_text = $this->_specialchars( $text, ENT_QUOTES );
		return $safe_text;
	}
	
	/**
	 * Converts a number of HTML entities into their special characters.
	 *
	 * Specifically deals with: &, <, >, ", and '.
	 *
	 * $quote_style can be set to ENT_COMPAT to decode " entities,
	 * or ENT_QUOTES to do both " and '. Default is ENT_NOQUOTES where no quotes are decoded.
	 *
	 * @since 2.8
	 *
	 * @param string $string The text which is to be decoded.
	 * @param mixed $quote_style Optional. Converts double quotes if set to ENT_COMPAT, both single and double if set to ENT_QUOTES or none if set to ENT_NOQUOTES. Also compatible with old _wp_specialchars() values; converting single quotes if set to 'single', double if set to 'double' or both if otherwise set. Default is ENT_NOQUOTES.
	 * @return string The decoded text without HTML entities.
	 */
	private function _specialchars_decode( $string, $quote_style = ENT_NOQUOTES ) {
		$string = (string) $string;
	
		if ( 0 === strlen( $string ) ) {
			return '';
		}
	
		// Don't bother if there are no entities - saves a lot of processing
		if ( strpos( $string, '&' ) === false ) {
			return $string;
		}
	
		// Match the previous behaviour of _wp_specialchars() when the $quote_style is not an accepted value
		if ( empty( $quote_style ) ) {
			$quote_style = ENT_NOQUOTES;
		} elseif ( !in_array( $quote_style, array( 0, 2, 3, 'single', 'double' ), true ) ) {
			$quote_style = ENT_QUOTES;
		}
	
		// More complete than get_html_translation_table( HTML_SPECIALCHARS )
		$single = array( '&#039;'  => '\'', '&#x27;' => '\'' );
		$single_preg = array( '/&#0*39;/'  => '&#039;', '/&#x0*27;/i' => '&#x27;' );
		$double = array( '&quot;' => '"', '&#034;'  => '"', '&#x22;' => '"' );
		$double_preg = array( '/&#0*34;/'  => '&#034;', '/&#x0*22;/i' => '&#x22;' );
		$others = array( '&lt;'   => '<', '&#060;'  => '<', '&gt;'   => '>', '&#062;'  => '>', '&amp;'  => '&', '&#038;'  => '&', '&#x26;' => '&' );
		$others_preg = array( '/&#0*60;/'  => '&#060;', '/&#0*62;/'  => '&#062;', '/&#0*38;/'  => '&#038;', '/&#x0*26;/i' => '&#x26;' );
	
		if ( $quote_style === ENT_QUOTES ) {
			$translation = array_merge( $single, $double, $others );
			$translation_preg = array_merge( $single_preg, $double_preg, $others_preg );
		} elseif ( $quote_style === ENT_COMPAT || $quote_style === 'double' ) {
			$translation = array_merge( $double, $others );
			$translation_preg = array_merge( $double_preg, $others_preg );
		} elseif ( $quote_style === 'single' ) {
			$translation = array_merge( $single, $others );
			$translation_preg = array_merge( $single_preg, $others_preg );
		} elseif ( $quote_style === ENT_NOQUOTES ) {
			$translation = $others;
			$translation_preg = $others_preg;
		}
	
		// Remove zero padding on numeric entities
		$string = preg_replace( array_keys( $translation_preg ), array_values( $translation_preg ), $string );
	
		// Replace characters according to translation table
		return strtr( $string, $translation );
	}
	
	/**
	 * Converts and fixes HTML entities.
	 *
	 * This function normalizes HTML entities. It will convert "AT&T" to the correct
	 * "AT&amp;T", "&#00058;" to "&#58;", "&#XYZZY;" to "&amp;#XYZZY;" and so on.
	 *
	 * @since 1.0.0
	 *
	 * @param string $string Content to normalize entities
	 * @return string Content with normalized entities
	 */
	private function _kses_normalize_entities($string) {
		# Disarm all entities by converting & to &amp;
	
		$string = str_replace('&', '&amp;', $string);
	
		# Change back the allowed entities in our entity whitelist
	
		$string = preg_replace_callback('/&amp;([A-Za-z]{2,8});/', array($this, '_kses_named_entities'), $string);
		$string = preg_replace_callback('/&amp;#(0*[0-9]{1,7});/', array($this, '_kses_normalize_entities2'), $string);
		$string = preg_replace_callback('/&amp;#[Xx](0*[0-9A-Fa-f]{1,6});/', array($this, '_kses_normalize_entities3'), $string);
	
		return $string;
	}


	/**
	 * Callback for _kses_normalize_entities() regular expression.
	 *
	 * This function only accepts valid named entity references, which are finite,
	 * case-sensitive, and highly scrutinized by HTML and XML validators.
	 *
	 * @param array $matches preg_replace_callback() matches array
	 * @return string Correctly encoded entity
	 */
	private function _kses_named_entities($matches) {
// 		global $allowedentitynames;
	
		if ( empty($matches[1]) )
			return '';
	
		$i = $matches[1];
		return ( ( ! in_array($i, $this->_allowedentitynames) ) ? "&amp;$i;" : "&$i;" );
	}
	
	/**
	 * Callback for _kses_normalize_entities() regular expression.
	 *
	 * This function helps wp_kses_normalize_entities() to only accept 16-bit values
	 * and nothing more for &#number; entities.
	 *
	 * @access private
	 *
	 * @param array $matches preg_replace_callback() matches array
	 * @return string Correctly encoded entity
	 */
	private function _kses_normalize_entities2($matches) {
		if ( empty($matches[1]) )
			return '';
	
		$i = $matches[1];
		if (valid_unicode($i)) {
			$i = str_pad(ltrim($i,'0'), 3, '0', STR_PAD_LEFT);
			$i = "&#$i;";
		} else {
			$i = "&amp;#$i;";
		}
	
		return $i;
	}
	
	/**
	 * Callback for _kses_normalize_entities() for regular expression.
	 *
	 * This function helps wp_kses_normalize_entities() to only accept valid Unicode
	 * numeric entities in hex form.
	 *
	 * @access private
	 *
	 * @param array $matches preg_replace_callback() matches array
	 * @return string Correctly encoded entity
	 */
	private function _kses_normalize_entities3($matches) {
		if ( empty($matches[1]) )
			return '';
	
		$hexchars = $matches[1];
		return ( ( ! $this->_valid_unicode(hexdec($hexchars)) ) ? "&amp;#x$hexchars;" : '&#x'.ltrim($hexchars,'0').';' );
	}
	
	/**
	 * Helper function to determine if a Unicode value is valid.
	 *
	 * @param int $i Unicode value
	 * @return bool True if the value was a valid Unicode number
	 */
	private function _valid_unicode($i) {
		return ( $i == 0x9 || $i == 0xa || $i == 0xd ||
				($i >= 0x20 && $i <= 0xd7ff) ||
				($i >= 0xe000 && $i <= 0xfffd) ||
				($i >= 0x10000 && $i <= 0x10ffff) );
	}
	
	/**
	 * Converts a number of special characters into their HTML entities.
	 *
	 * Specifically deals with: &, <, >, ", and '.
	 *
	 * $quote_style can be set to ENT_COMPAT to encode " to
	 * &quot;, or ENT_QUOTES to do both. Default is ENT_NOQUOTES where no quotes are encoded.
	 *
	 * @param string $string The text which is to be encoded.
	 * @param mixed $quote_style Optional. Converts double quotes if set to ENT_COMPAT, both single and double if set to ENT_QUOTES or none if set to ENT_NOQUOTES. Also compatible with old values; converting single quotes if set to 'single', double if set to 'double' or both if otherwise set. Default is ENT_NOQUOTES.
	 * @param string $charset Optional. The character encoding of the string. Default is false.
	 * @param boolean $double_encode Optional. Whether to encode existing html entities. Default is false.
	 * @return string The encoded text with HTML entities.
	 */
	private function _specialchars( $string, $quote_style = ENT_NOQUOTES, $charset = 'UTF-8', $double_encode = false ) {
		$string = (string) $string;
	
		if ( 0 === strlen( $string ) )
			return '';
	
		// Don't bother if there are no specialchars - saves some processing
		if ( ! preg_match( '/[&<>"\']/', $string ) )
			return $string;
	
		// Account for the previous behaviour of the function when the $quote_style is not an accepted value
		if ( empty( $quote_style ) )
			$quote_style = ENT_NOQUOTES;
		elseif ( ! in_array( $quote_style, array( 0, 2, 3, 'single', 'double' ), true ) )
		$quote_style = ENT_QUOTES;

		$_quote_style = $quote_style;
	
		if ( $quote_style === 'double' ) {
			$quote_style = ENT_COMPAT;
			$_quote_style = ENT_COMPAT;
		} elseif ( $quote_style === 'single' ) {
			$quote_style = ENT_NOQUOTES;
		}
	
		// Handle double encoding ourselves
		if ( $double_encode ) {
			$string = @htmlspecialchars( $string, $quote_style, $charset );
		} else {
			// Decode &amp; into &
			$string = $this->_specialchars_decode( $string, $_quote_style );
	
			// Guarantee every &entity; is valid or re-encode the &
			$string = $this->_kses_normalize_entities( $string );
	
			// Now re-encode everything except &entity;
			$string = preg_split( '/(&#?x?[0-9a-z]+;)/i', $string, -1, PREG_SPLIT_DELIM_CAPTURE );
	
			for ( $i = 0; $i < count( $string ); $i += 2 )
				$string[$i] = @htmlspecialchars( $string[$i], $quote_style, $charset );
	
			$string = implode( '', $string );
		}
	
		// Backwards compatibility
		if ( 'single' === $_quote_style )
			$string = str_replace( "'", '&#039;', $string );
	
		return $string;
	}
}
