<?php
class BaseaPhotoGridSlotComponents extends aSlotComponents
{
  
  /**
   * DOCUMENT ME
   */
  public function executeEditView()
  {
    $this->setup();
  }


  /**
   * DOCUMENT ME
   */
  public function executeNormalView()
  {
    $this->setup();
    $this->setupOptions();
    $this->getLinkedItems();
    
    if ($this->options['uncropped'])
    {
      $newItems = array();
      foreach ($this->items as $item)
      {
        $item = $item->getCropOriginal();
        $newItems[] = $item;
      }
      $this->items = $newItems;
    }
    
    if ($this->options['random'] && count($this->items))
    {
      shuffle($this->items);
    }
  }

  
  /**
   * Get Media Items
   */
  protected function getLinkedItems()
  {
    $this->items = $this->slot->getOrderedMediaItems();
    $this->itemIds = aArray::getIds($this->items);
  }


  /**
   * Setup Options
   */
  protected function setupOptions()
  {
    // Lightbox Options
    $this->options['width'] = $this->getOption('width', 960);
    $this->options['height'] = $this->getOption('height', 680);
    $this->options['resizeType'] = $this->getOption('resizeType', 's');
    $this->options['flexHeight'] = $this->getOption('flexHeight');
    $this->options['title'] = $this->getOption('title', false);
    $this->options['description'] = $this->getOption('description', false);
    $this->options['credit'] = $this->getOption('credit');

    // Grid Options
    $this->options['gridWidth'] = $this->getOption('gridWidth', 480);
    $this->options['gridTemplate'] = $this->getOption('gridTemplate', 'three');
    $this->options['random'] = $this->getOption('random', false);

    // Ignore any manual crops by the user. This is useful if you want to use 'c' in an
    // alternative rendering of a slideshow where custom crops are normally welcome
    $this->options['uncropped'] = $this->getOption('uncropped', false);
    
    // We automatically set up the aspect ratio if the resizeType is set to 'c'
    $constraints = $this->getOption('constraints', array());
    if (($this->getOption('resizeType', 's') === 'c') && isset($constraints['minimum-width']) && isset($constraints['minimum-height']) && (!isset($constraints['aspect-width'])))
    {
      $constraints['aspect-width'] = $constraints['minimum-width'];
      $constraints['aspect-height'] = $constraints['minimum-height'];
    }
    $this->options['constraints'] = $constraints;
  }
  
}
