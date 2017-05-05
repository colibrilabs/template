<?php

namespace Colibri\Template\Extensions;

use Colibri\Template\Core\ExtensionInterface;
use Colibri\Template\Template;

/**
 * Class ExtensionDefault
 * @package Colibri\Template\Extensions
 */
class ExtensionDefault implements ExtensionInterface
{
  
  /**
   * @param Template $template
   */
  public function register(Template $template)
  {
    $template->registerFunction('upper', [$this, 'upper']);
    $template->registerFunction('lower', [$this, 'lower']);
    $template->registerFunction('escape', [$this, 'escape']);
    $template->registerFunction('e', [$this, 'escape']);
  }
  
  /**
   * @param $string
   * @return string
   */
  public function upper($string)
  {
    return strtoupper($string);
  }
  
  /**
   * @param $string
   * @return string
   */
  public function lower($string)
  {
    return strtolower($string);
  }
  
  /**
   * @param string $string
   * @return string
   */
  public function escape($string)
  {
    return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5 | ENT_DISALLOWED | ENT_SUBSTITUTE);
  }
  
}