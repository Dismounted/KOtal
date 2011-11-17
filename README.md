KOtal
====

KOtal is a module for Kohana 3 that implements PHPTAL as a view processor.

PHPTAL is a PHP implementation of Zope Page Templates (ZPT). To be short, PHPTAL is a XML/XHTML template library for PHP.

Usage
----

To begin, store all the KOtal files under modules/kotal/ and enable it through bootstrap.php.

For the most part, simply create and call views like you would normally under Kohana 3. The only difference is that the view code itself is under TAL rules.

KOtal also routes i18n requests into native Kohana methods.

There are a few 'advanced' options, and they are shown in the examples below.

Example
----

Firstly, create a new view file called taltest.php and place it under views/.

	<h1 tal:content="title">Sample Title</h1>
	<p tal:repeat="person people" tal:content="person">Name</p>

Then in your controller, add the following code to generate and display your view.

	$view = View::factory('taltest');
	$people = array(
		'Alan',
		'Bob',
		'Jane',
		'Maria'
	);
	$view->title = 'People';
	$view->people = $people;

	$this->response->body($view);

Accessing this action, you should see:

	People

	Alan
	Bob
	Jane
	Maria

Now say we didn't want to store TAL views with a 'php' extension. We would like to use 'html' instead. Simply change the extension on your views and change the option in config/kotal.php.

	'ext' => 'html',

This is a global setting and will affect all views that are generated through KOtal.

Next, we would like to change how PHPTAL outputs out documents. Easy. There are two relevant methods.

	$view->set_output_mode(PHPTAL::XHTML)
	     ->set_encoding('utf-8');

These are the default settings, see the PHPTAL documentation for available options.

Say if you were in the middle of changing to TAL, and some views had yet been converted. No worries, we can just disable TAL processing on a per-view basis.

	$view->use_tal(FALSE);

Lastly, other features that appear in Kohana's default handler should work in KOtal, such as method chaining and setting the view file path just before rendering. Thus, our final example code could be:

	$view = View::factory()
		->set_output_mode(PHPTAL::XHTML)
		->set_encoding('utf-8')
		->set(array(
			'people' => array(
				'Alan',
				'Bob',
				'Jane',
				'Maria'
			),
			'title' => 'People'
		))
		->set_filename('taltest');
	$this->response->body($view);

Caveats
----

As KOtal overrides the default Kohana view handler, modules that use "normal" views will fail initially. You can fix this by using the controller exclude list inside config/kotal.php. Simply drop in the controller's name (as defined inside routes).

	'exclude' => array(
		'codebench',
		'unittest',
		'userguide',
	),

Alternatively, you can globally disable KOtal in config/kotal.php.

	'enabled' => FALSE,
	
Then, as you convert each view, you can switch KOtal on for that one view.

	$view->use_tal(TRUE);

Once all of your views have been upgraded to use PHPTAL, you can switch KOtal on globally and remove all the use_tal(TRUE) calls.

Modules that ship with Kohana 3 by default will already be covered in the default KOtal configuration. The only exception is the pagination module. If you would like to use this module with its supplied views, create a view before rendering like so:

	$view = View::factory(Kohana::config('pagination.default.view'));
	$view->use_tal(FALSE);
	$pagination->render($view);

Other
----

KOtal is licensed under the New BSD License. Credits to zombor's KOstache for the initial inspiration and naming idea.

PHPTAL is licensed under the terms of the GNU Lesser General Public License.

For more specific documentation on creating TAL views, see the [PHPTAL Website](http://phptal.org/).
