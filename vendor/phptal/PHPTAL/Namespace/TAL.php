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
 * @version  SVN: $Id: TAL.php 610 2009-05-24 00:32:13Z kornel $
 * @link     http://phptal.org/
 */

require_once 'PHPTAL/Php/Attribute/TAL/Comment.php';
require_once 'PHPTAL/Php/Attribute/TAL/Replace.php';
require_once 'PHPTAL/Php/Attribute/TAL/Content.php';
require_once 'PHPTAL/Php/Attribute/TAL/Condition.php';
require_once 'PHPTAL/Php/Attribute/TAL/Attributes.php';
require_once 'PHPTAL/Php/Attribute/TAL/Repeat.php';
require_once 'PHPTAL/Php/Attribute/TAL/Define.php';
require_once 'PHPTAL/Php/Attribute/TAL/OnError.php';
require_once 'PHPTAL/Php/Attribute/TAL/OmitTag.php';

/**
 * @package PHPTAL
 * @subpackage Namespace
 */
class PHPTAL_Namespace_TAL extends PHPTAL_Namespace_Builtin
{
    public function __construct()
    {
        parent::__construct('tal', 'http://xml.zope.org/namespaces/tal');
        $this->addAttribute(new PHPTAL_NamespaceAttributeSurround('define', 4));
        $this->addAttribute(new PHPTAL_NamespaceAttributeSurround('condition', 6));
        $this->addAttribute(new PHPTAL_NamespaceAttributeSurround('repeat', 8));
        $this->addAttribute(new PHPTAL_NamespaceAttributeContent('content', 11));
        $this->addAttribute(new PHPTAL_NamespaceAttributeReplace('replace', 9));
        $this->addAttribute(new PHPTAL_NamespaceAttributeSurround('attributes', 9));
        $this->addAttribute(new PHPTAL_NamespaceAttributeSurround('omit-tag', 0));
        $this->addAttribute(new PHPTAL_NamespaceAttributeSurround('comment', 12));
        $this->addAttribute(new PHPTAL_NamespaceAttributeSurround('on-error', 2));
    }
}
