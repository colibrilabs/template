<?php

namespace Colibri\Template;

use Colibri\Collection\ArrayCollection;
use Colibri\Template\Core\Compiler;
use Colibri\Template\Core\Directory;
use Colibri\Template\Core\ExtensionInterface;
use Colibri\Template\Core\File;
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
  protected $variables = null;
  
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
   * @var ArrayCollection
   */
  protected $layouts = null;

  /**
   * @var ArrayCollection
   */
  protected $sections;

  /**
   * Template constructor.
   * @param string $directory
   * @param array $data
   */
  public function __construct($directory, array $data = [])
  {
    $this->directory = new Directory($directory);
    $this->variables = new ArrayCollection($data);
    $this->sections = new ArrayCollection();
    
    $this->directories = new ArrayCollection([], Directory::class);
    $this->functions = new ArrayCollection([], null);
    $this->layouts = new ArrayCollection([], null);
    
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
    $this->variables->set($key, $data);
    
    return $this;
  }
  
  /**
   * @param array $data
   */
  public function batch(array $data = [])
  {
    $this->variables->batch($data);
  }
  
  /**
   * @return ArrayCollection
   */
  public function getVariables()
  {
    return $this->variables;
  }
  
  /**
   * @param string $path
   * @param array $data
   * @return string
   */
  public function fetch($path, array $data = [])
  {
    $this->variables->batch($data);
    
    return $this->compiler($path)->render();
  }
  
  /**
   * @param string $path
   * @return string
   */
  public function render($path)
  {
    $content = $this->compiler($path)->render();

    $layouts = $this->getLayouts();
    if ($layouts->count() > 0) {
      $layouts->each(function($index, $layout) use ($layouts, &$content) {
        $layouts->remove($index);
        $this->set('content', $content);
        $content = $this->compiler($layout)->render();
      });
    }

    return $content;
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

  /**
   * @return ArrayCollection
   */
  public function getLayouts()
  {
    return $this->layouts;
  }

  /**
   * @return ArrayCollection
   */
  public function getSections()
  {
    return $this->sections;
  }

  /**
   * @param $name
   * @return $this
   */
  public function addLayout($name)
  {
    $this->layouts->push($name);

    return $this;
  }
  
}