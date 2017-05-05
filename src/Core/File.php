<?php

namespace Colibri\Template\Core;

use Colibri\Template\Template;
use Colibri\Template\TemplateException;

/**
 * Class File
 * @package Colibri\Template\Core
 */
class File
{
  
  const SEPARATOR = '::';
  
  /**
   * @var Template
   */
  protected $template;
  
  /**
   * @var \SplFileInfo
   */
  protected $file;
  
  /**
   * File constructor.
   * @param $file
   * @param Template $template
   */
  public function __construct($file, Template $template)
  {
    $this->setTemplate($template);
    $this->setFile($file);
  }
  
  /**
   * @return boolean
   */
  public function exists()
  {
    return $this->file->isFile();
  }
  
  /**
   * @return Template
   */
  public function getTemplate()
  {
    return $this->template;
  }
  
  /**
   * @param Template $template
   * @return $this
   */
  public function setTemplate(Template $template)
  {
    $this->template = $template;
    
    return $this;
  }
  
  /**
   * @return \SplFileInfo
   */
  public function getFile()
  {
    return $this->file;
  }
  
  /**
   * @param $path
   * @return $this
   * @throws TemplateException
   */
  public function setFile($path)
  {
    $fullpath = $this->getRealPath($path);
    
    if (false === $fullpath) {
      throw new TemplateException('Cannot determine template file with path :name', ['name' => $path]);
    }
    
    $this->file = new \SplFileInfo($fullpath);
    
    return $this;
  }
  
  /**
   * @param $path
   * @return string
   * @throws TemplateException
   */
  protected function getRealPath($path)
  {
    if (strpos($path, static::SEPARATOR) !== false) {
      list($name, $path) = explode(static::SEPARATOR, $path);
      $directory = $this->template->getDirectory($name);
      
      if (null !== $directory) {
        return realpath($directory->getPath() . DIRECTORY_SEPARATOR . $path . '.php');
      }
    }
    
    return realpath($this->template->getDirectory() . DIRECTORY_SEPARATOR . $path . '.php');
  }
  
}