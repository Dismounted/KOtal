<?php defined('SYSPATH') or die('No direct access allowed.');

return array(

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
