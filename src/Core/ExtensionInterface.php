<?php

namespace Colibri\Template\Core;

use Colibri\Template\Template;

/**
 * Interface ExtensionInterface
 *
 * @package Colibri\Template\Core
 */
interface ExtensionInterface
{

  /**
   * @param Template $template
   * @return void
   */
  public function register(Template $template);
  
}