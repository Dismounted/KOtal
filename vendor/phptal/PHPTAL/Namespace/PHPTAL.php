<?php
/**
 * PHPTAL templating engine
 *
 * PHP Version 5
 *
 * @category HTML
 * @package  PHPTAL
 * @author   Laurent Bedubourg <lbedubourg@motion-twin.com>
 * @author   Kornel Lesiński <kornel@aardvarkmedia.co.uk>
 * @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @version  SVN: $Id: PHPTAL.php 610 2009-05-24 00:32:13Z kornel $
 * @link     http://phptal.org/
 */

require_once 'PHPTAL/Php/Attribute/PHPTAL/Tales.php';
require_once 'PHPTAL/Php/Attribute/PHPTAL/Debug.php';
require_once 'PHPTAL/Php/Attribute/PHPTAL/Id.php';
require_once 'PHPTAL/Php/Attribute/PHPTAL/Cache.php';

/**
 * @package PHPTAL
 * @subpackage Namespace
 */
class PHPTAL_Namespace_PHPTAL extends PHPTAL_Namespace_Builtin
{
    public function __construct()
    {
        parent::__construct('phptal', 'http://phptal.org/ns/phptal');
        $this->addAttribute(new PHPTAL_NamespaceAttributeSurround('tales', -1));
        $this->addAttribute(new PHPTAL_NamespaceAttributeSurround('debug', -2));
        $this->addAttribute(new PHPTAL_NamespaceAttributeSurround('id', 7));
        $this->addAttribute(new PHPTAL_NamespaceAttributeSurround('cache', -3));
    }
}
