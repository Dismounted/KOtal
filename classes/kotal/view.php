<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Replaces the current Kohana view wrapper and processes view data via PHPTAL.
 *
 * @package    KOtal
 * @category   Base
 * @author     Hanson Wong
 * @copyright  (c) 2010 Hanson Wong
 * @license    http://github.com/Dismounted/KOtal/blob/master/LICENSE
 */

class Kotal_View extends Kohana_View {

	/**
	 * @var   PHPTAL   working object, will be generated automatically when needed
	 */
	protected $tal;

	/**
	 * @var   bool   enable tal on this view
	 */
	protected $tal_enable = TRUE;

	/**
	 * @var   array   cached list of excluded controllers
	 */
	protected static $tal_exclude;

	/**
	 * Overrides default constructor to also include the PHPTAL library.
	 *
	 * @param   string  view filename
	 * @param   array   array of values
	 * @return  void
	 * @uses    View::set_filename
	 */
	public function __construct($file = NULL, array $data = NULL)
	{
		parent::__construct($file, $data);

		// Doing this now so we can access its constants
		require_once Kohana::find_file('vendor', 'phptal/PHPTAL');
	}

	/**
	 * Overrides the default method, and processes the view using PHPTAL.
	 *
	 * @param   string  filename
	 * @param   array   variables
	 * @param   PHPTAL  TAL working object
	 * @return  string
	 */
	protected static function capture($kohana_view_filename, array $kohana_view_data, PHPTAL &$tal = NULL)
	{
		// Create TAL object if it isn't given to us
		if (empty($tal))
		{
			$tal = new PHPTAL();
		}

		// Set TAL template file path
		$tal->setTemplate($kohana_view_filename);

		// Set the translator
		$tal->setTranslator(new Kotal_TranslationService);

		// Add the source resolver
		$tal->addSourceResolver(new Kotal_SourceResolver($kohana_view_filename));

		// Import the view variables to TAL namespace
		foreach ($kohana_view_data AS $name => $value)
		{
			$tal->{$name} = $value;
		}

		if (View::$_global_data)
		{
			// Import the global view variables to TAL namespace and maintain references
			foreach (View::$_global_data AS $name => $value)
			{
				$tal->{$name} =& $value;
			}
		}

		// Capture the view output
		ob_start();

		try
		{
			// Execute template
			echo $tal->execute();
		}
		catch (Exception $e)
		{
			// Delete the output buffer
			ob_end_clean();

			// Re-throw the exception
			throw $e;
		}

		// Get the captured output and close the buffer
		return ob_get_clean();
	}

	/**
	 * Renders the view object to a string.
	 *
	 * @param    string  view filename
	 * @return   string
	 * @throws   Kohana_View_Exception
	 * @uses     View::capture
	 * @uses     View::check_tal_exclusions
	 */
	public function render($file = NULL)
	{
		if ($file !== NULL)
		{
			$this->set_filename($file);
		}

		if (empty($this->_file))
		{
			throw new Kohana_View_Exception('You must set the file to use within your view before rendering');
		}

		if ($this->check_tal_exclusions() === FALSE)
		{
			// No TAL, just process as normal
			return parent::capture($this->_file, $this->_data);
		}
		else
		{
			// Combine local and global data and capture the output
			return View::capture($this->_file, $this->_data, $this->tal);
		}
	}

	/**
	 * Sets the view filename. Overrides extension if set.
	 *
	 * @param   string  view filename
	 * @return  View
	 * @throws  Kohana_View_Exception
	 */
	public function set_filename($file)
	{
		// This can fail if the TAL extension is changed and a non-TAL view used
		if (($path = Kohana::find_file('views', $file, Kohana::config('kotal.ext'))) === FALSE)
		{
			// Obviously not TAL then, but is it a 'normal' view?
			if (($path = Kohana::find_file('views', $file)) === FALSE)
			{
				throw new Kohana_View_Exception('The requested view :file could not be found', array(
					':file' => $file,
				));
			}
		}

		// Store the file path locally
		$this->_file = $path;

		return $this;
	}

	/**
	 * Sets whether PHPTAL should be used on this view. Default is TRUE.
	 *
	 * If no arguments are set, this method returns $tal_enable.
	 *
	 * @param    bool    whether to process using PHPTAL
	 * @return   View
	 * @return   bool
	 */
	public function use_tal($tal = NULL)
	{
		if ($tal === NULL)
		{
			return $this->tal_enable;
		}

		$this->tal_enable = (bool) $tal;
		return $this;
	}

	/**
	 * Sets PHPTAL output mode. Defaults to PHPTAL::XHTML.
	 *
	 * Current options are: PHPTAL::XML, PHPTAL::XHTML or PHPTAL::HTML5.
	 *
	 * @param    int    output mode to use for this view
	 * @return   View
	 */
	public function set_output_mode($mode)
	{
		if (empty($this->tal))
		{
			// Create PHPTAL object for this setting to take effect
			$this->tal = new PHPTAL();
		}

		// Set output mode (exception will be thrown on error)
		$this->tal->setOutputMode($mode);

		return $this;
	}

	/**
	 * Sets PHPTAL input/output encoding. Defaults to UTF-8. Case-insensitive.
	 *
	 * Save yourself the trouble and leave everything in UTF-8.
	 *
	 * @param    string    encoding name
	 * @return   View
	 */
	public function set_encoding($enc)
	{
		if (empty($this->tal))
		{
			// Create PHPTAL object for this setting to take effect
			$this->tal = new PHPTAL();
		}

		// Set encoding
		$this->tal->setEncoding($enc);

		return $this;
	}

	/**
	 * Check TAL exclusions set in config against current request. Mainly for
	 * modules that use normal views (e.g. userguide).
	 *
	 * @param    bool   clear the cache and check again if TRUE
	 * @return   bool   to use or not to use TAL, result from View::use_tal()
	 * @uses     View::use_tal
	 */
	protected function check_tal_exclusions($clear = FALSE)
	{
		// fetch current controller
		$controller = UTF8::strtolower(Request::current()->controller);

		// cache exclusion list if it doesn't exist (saves calling strtolower)
		if ($clear == TRUE OR self::$tal_exclude === NULL)
		{
			self::$tal_exclude = Arr::map('UTF8::strtolower', Kohana::config('kotal.exclude'));
		}

		// check if this request should be excluded
		if (in_array($controller, self::$tal_exclude))
		{
			$this->use_tal(FALSE);
		}

		return $this->use_tal();
	}
}
