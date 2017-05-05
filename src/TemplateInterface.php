<?php

namespace Colibri\Template;

use Colibri\Template\Core\Collection\DataStorage;
use Colibri\Template\Core\Compiler;
use Colibri\Template\Core\ExtensionInterface;
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
   * @return DataStorage
   */
  public function data();
  
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
  
  
}