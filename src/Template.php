<?php

namespace Colibri\Template;

use Colibri\Collection\ArrayCollection;
use Colibri\Template\Core\Compiler;
use Colibri\Template\Core\Directory;
use Colibri\Template\Core\ExtensionInterface;
use Colibri\Template\Extensions\ExtensionDefault;

/**
 * Class Template
 * @package Colibri\Template
 */
class Template implements TemplateInterface
{
  
  /**
   * @var ArrayCollection
   */
  protected $data = null;
  
  /**
   * @var Directory
   */
  protected $directory = null;
  
  /**
   * @var ArrayCollection|Directory[]
   */
  protected $directories = null;
  
  /**
   * @var ArrayCollection
   */
  protected $functions = null;
  
  /**
   * @var null
   */
  protected $layout = null;
  
  /**
   * Template constructor.
   * @param string $directory
   * @param array $data
   */
  public function __construct($directory, array $data = [])
  {
    $this->directory = new Directory($directory);
    $this->data = new ArrayCollection($data);
    $this->directories = new ArrayCollection();
    $this->functions = new ArrayCollection();
    
    $this->registerExtension(new ExtensionDefault());
  }
  
  /**
   * @param ExtensionInterface $extension
   * @return $this
   */
  public function registerExtension(ExtensionInterface $extension)
  {
    $extension->register($this);
    
    return $this;
  }
  
  /**
   * @param $directory
   * @param array $data
   * @return static
   */
  public static function factory($directory, array $data = [])
  {
    return new static($directory, $data);
  }
  
  /**
   * @return string
   */
  public function __toString()
  {
    return static::class;
  }
  
  /**
   * @param $key
   * @param $data
   * @return $this
   */
  public function set($key, $data)
  {
    $this->data->set($key, $data);
    
    return $this;
  }
  
  /**
   * @param array $data
   */
  public function batch(array $data = [])
  {
    $this->data->batch($data);
  }
  
  /**
   * @return ArrayCollection
   */
  public function data()
  {
    return $this->data;
  }
  
  /**
   * @param string $path
   * @param array $data
   * @return string
   */
  public function fetch($path, array $data = [])
  {
    $this->data->batch($data);
    
    return $this->render($path);
  }
  
  /**
   * @param string $path
   * @return string
   */
  public function render($path)
  {
    return $this->compiler($path)->render();
  }
  
  /**
   * @param $path
   * @return Compiler
   */
  public function compiler($path)
  {
    return new Compiler($path, $this);
  }
  
  /**
   * @param $name
   * @param callable $callback
   * @return $this
   * @throws TemplateException
   */
  public function registerFunction($name, $callback)
  {
    if (!is_callable($callback, true)) {
      throw new TemplateException('Function ":name" was not callable', ['name' => $name]);
    }
    
    $this->functions->set($name, $callback);
    
    return $this;
  }
  
  /**
   * @param $name
   * @return $this
   */
  public function removeFunction($name)
  {
    $this->functions->remove($name);
    
    return $this;
  }
  
  /**
   * @param $name
   * @param $arguments
   * @return mixed
   * @throws TemplateException
   */
  public function resolveFunction($name, $arguments)
  {
    if (!$this->functions->has($name)) {
      throw new TemplateException('Function ":name" was not registered yet', ['name' => $name]);
    }
    
    return call_user_func_array($this->functions->get($name), $arguments);
  }
  
  /**
   * @param $name
   * @param $directory
   * @return $this
   */
  public function addDirectory($name, $directory)
  {
    $this->directories->set($name, new Directory($directory));
    
    return $this;
  }
  
  /**
   * @param null $name
   * @return string|Directory
   */
  public function getDirectory($name = null)
  {
    return null === $name ? $this->directory->getPath() : $this->directories->get($name);
  }
  
  
}