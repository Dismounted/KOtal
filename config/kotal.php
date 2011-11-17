<?php defined('SYSPATH') or die('No direct script access.');
/**
 * KOtal configuration file.
 *
 * @package    KOtal
 * @category   Base
 * @author     Hanson Wong
 * @copyright  (c) 2010 Hanson Wong
 * @license    http://github.com/Dismounted/KOtal/blob/master/LICENSE
 */

return array(
	
	/**
	 * Whether KOtal is enabled for every view by default or not.
	 */
	'enabled' => TRUE,
	
	/**
	 * The file extension used for TAL view files. Default is 'php'.
	 */
	'ext' => 'php',

	/**
	 * List of controllers automatically excluded from TAL processing.
	 * Should be taken from the 'controller' parameter in routes.
	 */
	'exclude' => array(
		'codebench',
		'unittest',
		'userguide',
	),

);
