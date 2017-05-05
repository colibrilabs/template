<?php

namespace Colibri\Template\Core;

use Colibri\Template\Template;

interface ExtensionInterface
{
  
  public function register(Template $template);
  
}