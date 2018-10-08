<?php

namespace Subapp\Template\Core;

use Subapp\Template\TemplateException;

/**
 * Class Directory
 * @package Subapp\Template\Core
 */
class Directory
{
  
  /**
   * @var string
   */
  protected $path;
  
  /**
   * Directory constructor.
   * @param $path
   */
  public function __construct($path)
  {
    $this->setPath($path);
  }
  
  /**
   * @return mixed
   */
  public function getPath()
  {
    return $this->path;
  }
  
  /**
   * @param mixed $path
   * @return $this
   * @throws TemplateException
   */
  public function setPath($path)
  {
    if (!is_dir($path) || !is_readable($path)) {
      throw new TemplateException('Directory path :path does not exists or not readable', ['path' => $path]);
    }
    
    $this->path = $path;
    
    return $this;
  }
  
  
}