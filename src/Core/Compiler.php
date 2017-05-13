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
   * @var string|null
   */
  protected $layoutName = null;
  
  /**
   * @var array
   */
  protected $layoutData = [];
  
  /**
   * @var File
   */
  protected $renderable = null;
  
  /**
   * @var ArrayCollection
   */
  protected $sections = null;
  
  /**
   * Compiler constructor.
   * @param $path
   * @param Template $template
   */
  public function __construct($path, Template $template)
  {
    $this->template = $template;
    $this->renderable = new File($path, $template);
    $this->sections = new ArrayCollection();
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
      
      $collectionIterator = $this->template->data()->getIterator();
      extract($collectionIterator->getArrayCopy());
      
      if (!$this->exists()) {
        throw new TemplateException('Template compiler cannot find layout file :file', ['file' => $this->debugFilename()]);
      }
      
      include $this->path();
      
      $content = ob_get_clean();
      
      if ($this->layoutName) {
        $layout = $this->template->compiler($this->layoutName);
        $layout->copySectionFrom($this);
        $this->template->data()->batch($this->layoutData);
        $this->template->set('content', $content);
        $content = $layout->render();
      }
      
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
   * @param Compiler $compiler
   * @return $this
   */
  protected function copySectionFrom(Compiler $compiler)
  {
    $this->getSections()->batch($compiler->getSections()->toArray());
    
    return $this;
  }
  
  /**
   * @return ArrayCollection
   */
  protected function getSections()
  {
    return $this->sections;
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
    $this->layoutName = $name;
    $this->layoutData = $data;
    
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
    
    $this->sections->set($name, '');
    ob_start();
    
    return $this;
  }
  
  /**
   * @return $this
   * @throws TemplateException
   */
  protected function stop()
  {
    if (!$this->sections->exists()) {
      throw new TemplateException('You should start section before stopping');
    }
    
    $keys = $this->sections->keys();
    $this->sections->set($keys[count($keys) - 1], ob_get_clean());
    
    return $this;
  }
  
  /**
   * @param string $name
   * @param null $default
   * @return string
   */
  protected function section($name, $default = null)
  {
    return $this->sections->has($name) ? $this->sections->get($name) : $default;
  }
  
  /**
   * @param string $name
   * @param string $content
   * @return $this
   */
  protected function setSection($name, $content)
  {
    $this->sections->set($name, $content);
    
    return $this;
  }
  
  /**
   * @param $name
   * @return bool
   */
  protected function hasSection($name)
  {
    return $this->sections->has($name);
  }
  
  
}
