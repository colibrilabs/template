<?php

namespace Subapp\Template;

use Subapp\Collection\Collection as ArrayCollection;
use Subapp\Template\Core\Compiler;
use Subapp\Template\Core\Directory;
use Subapp\Template\Core\ExtensionInterface;

/**
 * Class NullTemplate
 * @package Subapp\Template
 */
final class NullTemplate implements TemplateInterface
{
  
  /**
   * NullTemplate constructor.
   */
  final public function __construct()
  {
    
  }
  
  /**
   * @param $directory
   * @param array $data
   * @return static
   */
  public static function factory($directory, array $data = [])
  {
    new static();
  }
  
  /**
   * @param $key
   * @param $data
   * @return $this
   */
  public function set($key, $data)
  {
    $this->createException();
  }
  
  /**
   * @throws TemplateException
   */
  private function createException()
  {
    throw new TemplateException('This is instance of [:null_class], please configure and release [:class]', [
      'null_class' => static::class,
      'class' => Template::class,
    ]);
  }
  
  /**
   * @param array $data
   */
  public function batch(array $data = [])
  {
    $this->createException();
  }
  
  /**
   * @return ArrayCollection
   */
  public function getVariables()
  {
    $this->createException();
  }
  
  /**
   * @inheritdoc
   */
  public function render($path)
  {
    $this->createException();
  }
  
  /**
   * @param string $path
   * @param array $data
   * @return string
   */
  public function fetch($path, array $data = [])
  {
    $this->createException();
  }
  
  /**
   * @param $path
   * @return Compiler
   */
  public function compiler($path)
  {
    $this->createException();
  }
  
  /**
   * @param ExtensionInterface $extension
   * @return $this
   */
  public function registerExtension(ExtensionInterface $extension)
  {
    $this->createException();
  }
  
  /**
   * @param $name
   * @param callable $callback
   * @return $this
   * @throws TemplateException
   */
  public function registerFunction($name, $callback)
  {
    $this->createException();
  }
  
  /**
   * @param $name
   * @return $this
   */
  public function removeFunction($name)
  {
    $this->createException();
  }
  
  /**
   * @param $name
   * @param $arguments
   * @return mixed
   * @throws TemplateException
   */
  public function resolveFunction($name, $arguments)
  {
    $this->createException();
  }
  
  /**
   * @param $name
   * @param $directory
   * @return $this
   */
  public function addDirectory($name, $directory)
  {
    $this->createException();
  }
  
  /**
   * @param null $name
   * @return string|Directory
   */
  public function getDirectory($name = null)
  {
    $this->createException();
  }
  
  public function getLayouts()
  {
    $this->createException();
  }

  public function getSections()
  {
    $this->createException();
  }
  
}
