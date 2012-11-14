<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Allows PHPTAL to take advantage of the Kohana i18n tranlator.
 *
 * @package    KOtal
 * @category   Base
 * @author     Hanson Wong
 * @author     johanlindblad
 * @copyright  (c) 2010 Hanson Wong
 * @license    http://github.com/Dismounted/KOtal/blob/master/LICENSE
 */

class Kotal_TranslationService implements PHPTAL_TranslationService {

	/**
	 * @var array Replace all key with values in translated strings
	 */
	protected $_vars = array();

	/**
	 * Set an interpolation var.
	 *
	 * @param string
	 * @param string
	 */
	public function setVar($key, $value)
	{
		$this->_vars[':'.$key] = $value;
	}

	/**
	 * Translate given key.
	 *
	 * @param string Key to translate
	 * @param bool If true, output will be HTML-escaped
	 */
	public function translate($key, $htmlescape = true)
	{
		// Replace ${key} with :key and run through the translator
		$text = __(preg_replace('/\$\{(.+?)\}/', ':$1', $key), $this->_vars);

		// If comment is removed, you'll only be able to use <tal:block> for
		// i18n:name attributes.
		if ($htmlescape)
		{
			//return htmlentities($text);
		}

		return $text;
	}

	// Not implemented and probably not needed
	public function setLanguage()
	{
	}

	public function setEncoding($encoding)
	{
	}

	public function useDomain($domain)
	{
	}
}
