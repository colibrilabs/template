<?php

namespace Colibri\Template;

use Exception;

/**
 * Class TemplateException
 * @package Colibri\Template
 */
class TemplateException extends \Exception
{
  /**
   * TemplateException constructor.
   * @param string $message
   * @param array $replacements
   * @param integer $code
   * @param Exception $previous
   */
  public function __construct($message, array $replacements = [], $code = 0, Exception $previous = null)
  {
    $keys = array_map(function ($key) {
      return ":{$key}";
    }, array_keys($replacements));
    $values = array_values($replacements);
    $message = str_replace($keys, $values, $message);
    
    parent::__construct($message, $code, $previous);
  }
  
}