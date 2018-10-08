<?php

namespace Subapp\Template\Core;

use Subapp\Template\Template;

/**
 * Interface ExtensionInterface
 *
 * @package Subapp\Template\Core
 */
interface ExtensionInterface
{

  /**
   * @param Template $template
   * @return void
   */
  public function register(Template $template);
  
}