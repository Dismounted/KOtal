<?php defined('SYSPATH') or die('No direct access allowed.');
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
	 * @var  bool  enable tal on this view
	 */
	protected $tal = TRUE;

	/**
	 * Overrides the default method, and processes the view using PHPTAL.
	 *
	 * @param   string  filename
	 * @param   array   variables
	 * @return  string
	 */
	protected static function capture($kohana_view_filename, array $kohana_view_data)
	{
		// Include PHPTAL if not already done so
		require_once Kohana::find_file('vendor', 'phptal/PHPTAL');

		// Create TAL object
		$template = new PHPTAL($kohana_view_filename);

		// Import the view variables to TAL namespace
		foreach ($kohana_view_data AS $name => $value)
		{
			$template->{$name} = $value;
		}

		if (View::$_global_data)
		{
			// Import the global view variables to TAL namespace and maintain references
			foreach (View::$_global_data AS $name => $value)
			{
				$template->{$name} =& $value;
			}
		}

		// Capture the view output
		ob_start();

		try
		{
			// Execute template
			echo $template->execute();
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

		if ($this->tal == FALSE)
		{
			// No TAL, just process as normal
			return parent::capture($this->_file, $this->_data);
		}
		else
		{
			// Combine local and global data and capture the output
			return self::capture($this->_file, $this->_data);
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
	 * @param    bool    whether to process using PHPTAL
	 * @return   void
	 */
	public function use_tal($tal)
	{
		$this->tal = (bool) $tal;
	}
}
