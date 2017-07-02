<?php

namespace Colibri\Template\Core;

use Colibri\Collection\ArrayCollection;
use Colibri\Template\Template;
use Colibri\Template\TemplateException;

/**
 * Class Compiler
 * @package Colibri\Template\Core
 */
class Compiler
{
  
  /**
   * @var Template
   */
  protected $template = null;
  
  /**
   * @var File
   */
  protected $renderable = null;
  
  /**
   * Compiler constructor.
   * @param $path
   * @param Template $template
   */
  public function __construct($path, Template $template)
  {
    $this->template = $template;
    $this->renderable = new File($path, $template);
  }
  
  /**
   * @param $name
   * @param $arguments
   * @return mixed
   */
  public function __call($name, $arguments)
  {
    return $this->template->resolveFunction($name, $arguments);
  }
  
  /**
   * @return null|string
   * @throws \Exception
   */
  public function render()
  {
    $content = null;
    
    try {
      ob_start();
      
      $collectionIterator = $this->template->getVariables()->getIterator();
      extract($collectionIterator->getArrayCopy());
      
      if (!$this->exists()) {
        throw new TemplateException(sprintf(
          'Template compiler cannot find layout file [%s]', $this->debugFilename()));
      }
      
      include $this->path();
      
      $content = ob_get_clean();

    } catch (\Exception $e) {
      ob_get_clean();
      throw $e;
    }
    
    return $content;
  }
  
  /**
   * @return bool
   */
  protected function exists()
  {
    return $this->renderable->exists();
  }
  
  /**
   * @return string
   */
  protected function debugFilename()
  {
    return "HIDDEN/{$this->renderable->getFile()->getFilename()}";
  }
  
  /**
   * @return string
   */
  protected function path()
  {
    return $this->renderable->getFile()->getPathname();
  }
  
  /**
   * @return ArrayCollection
   */
  protected function getSections()
  {
    return $this->template->getSections();
  }
  
  /**
   * @param $path
   * @param array $data
   * @return string
   */
  protected function fetch($path, array $data = [])
  {
    return $this->template->fetch($path, $data);
  }
  
  /**
   * @param string $name
   * @param array $data
   * @return $this
   */
  protected function layout($name, array $data = [])
  {
    $this->template->getLayouts()->append($name);
    $this->template->getVariables()->batch($data);

    return $this;
  }
  
  /**
   * @param string $name
   * @return $this
   * @throws TemplateException
   */
  protected function start($name)
  {
    if ($name === 'content') {
      throw new TemplateException('Section name "content" is reserved');
    }
    
    $this->template->getSections()->set($name, '');
    ob_start();
    
    return $this;
  }
  
  /**
   * @return $this
   * @throws TemplateException
   */
  protected function stop()
  {
    if (!$this->template->getSections()->exists()) {
      throw new TemplateException('You should start section before stopping');
    }
    
    $keys = $this->template->getSections()->keys();
    $this->template->getSections()->set($keys[count($keys) - 1], ob_get_clean());
    
    return $this;
  }
  
  /**
   * @param string $name
   * @param null $default
   * @return string
   */
  protected function section($name, $default = null)
  {
    return $this->template->getSections()->has($name)
      ? $this->template->getSections()->get($name) : $default;
  }
  
  /**
   * @param string $name
   * @param string $content
   * @return $this
   */
  protected function setSection($name, $content)
  {
    $this->template->getSections()->set($name, $content);
    
    return $this;
  }
  
  /**
   * @param $name
   * @return bool
   */
  protected function hasSection($name)
  {
    return $this->template->getSections()->has($name);
  }
  
  
}
