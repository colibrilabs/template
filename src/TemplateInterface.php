<?php

namespace Subapp\Template;

use Subapp\Collection\Collection as ArrayCollection;
use Subapp\Template\Core\Collection\DataStorage;
use Subapp\Template\Core\Compiler;
use Subapp\Template\Core\ExtensionInterface;
use Directory;

interface TemplateInterface
{
  
  
  /**
   * @param $directory
   * @param array $data
   * @return static
   */
  public static function factory($directory, array $data = []);
  
  /**
   * @param $key
   * @param $data
   * @return $this
   */
  public function set($key, $data);
  
  /**
   * @param array $data
   */
  public function batch(array $data = []);
  
  /**
   * @return ArrayCollection
   */
  public function getVariables();
  
  /**
   * @param string $path
   * @return string
   */
  public function render($path);
  
  /**
   * @param string $path
   * @param array $data
   * @return string
   */
  public function fetch($path, array $data = []);
  
  /**
   * @param $path
   * @return Compiler
   */
  public function compiler($path);
  
  /**
   * @param ExtensionInterface $extension
   * @return $this
   */
  public function registerExtension(ExtensionInterface $extension);
  
  /**
   * @param $name
   * @param callable $callback
   * @return $this
   * @throws TemplateException
   */
  public function registerFunction($name, $callback);
  
  /**
   * @param $name
   * @return $this
   */
  public function removeFunction($name);
  
  /**
   * @param $name
   * @param $arguments
   * @return mixed
   * @throws TemplateException
   */
  public function resolveFunction($name, $arguments);
  
  /**
   * @param $name
   * @param $directory
   * @return $this
   */
  public function addDirectory($name, $directory);
  
  /**
   * @param null $name
   * @return string|Directory
   */
  public function getDirectory($name = null);

  /**
   * @return ArrayCollection
   */
  public function getLayouts();

  /**
   * @return ArrayCollection
   */
  public function getSections();

}
