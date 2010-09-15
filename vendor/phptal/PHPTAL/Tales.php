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
 * @version  SVN: $Id: Tales.php 610 2009-05-24 00:32:13Z kornel $
 * @link     http://phptal.org/
 */

require_once 'PHPTAL/Php/TalesInternal.php';

/**
 * You can implement this interface to create custom tales modifiers
 *
 * Methods suitable for modifiers must be static.
 *
 * @package PHPTAL
 * @subpackage Php
 */
interface PHPTAL_Tales
{
}

 
 /**
  * translates TALES expression with alternatives into single PHP expression. 
  * Identical to phptal_tales() for singular expressions.
  * 
  * Please use this function rather than PHPTAL_Php_TalesInternal methods.
  *
  * @see PHPTAL_Php_TalesInternal::compileToPHPStatements()
  * @return string
 */
function phptal_tale($expression, $nothrow=false)
{
    return PHPTAL_Php_TalesInternal::compileToPHPExpression($expression,$nothrow);
}

/**
 * returns PHP code that will evaluate given TALES expression.
 * e.g. "string:foo${bar}" may be transformed to "'foo'.phptal_escape($ctx->bar)"
 *
 * Expressions with alternatives ("foo | bar") will cause it to return array
 * Use phptal_tale() if you always want string.
 *
 * @param bool $nothrow if true, invalid expression will return NULL (at run time) rather than throwing exception
 * @return string or array
 */
function phptal_tales($expression, $nothrow=false)
{
    return PHPTAL_Php_TalesInternal::compileToPHPStatements($expression,$nothrow);
}

