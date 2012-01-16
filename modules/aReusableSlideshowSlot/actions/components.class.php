<?php
class aReusableSlideshowSlotComponents extends BaseaSlideshowSlotComponents
{
  public function executeEditView()
  {
    // Must be at the start of both view components
    $this->setup();
    
    // Careful, don't clobber a form object provided to us with validation errors
    // from an earlier pass
    if (!isset($this->form))
    {
      $aReusableSlot = Doctrine::getTable('aReusableSlot')->findOneBySlot($this->pageid, $this->name, $this->permid);
      $this->form = new aReusableSlideshowSlotEditForm($this->id, $aReusableSlot, $this->slot);
    }
  }
  
  public function executeNormalView()
  {
    parent::executeNormalView();
    $values = $this->slot->getArrayValue();
    $labelActive = isset($this->options['slideshowLabel']) && $this->options['slideshowLabel'];
    $blurbActive = isset($this->options['slideshowBlurb']) && $this->options['slideshowBlurb'];
    if ($labelActive || $blurbActive)
    {
      if (isset($values['reuse']['id']))
      {
        $aReusableSlot = Doctrine::getTable('aReusableSlot')->find($values['reuse']['id']);
        if (!$aReusableSlot)
        {
          error_log("Offending id is " . $values['reuse']['id']);
          $this->label = 'Error';
          $this->blurb = 'Error';
        }
        else
        {
          $this->label = $aReusableSlot->label;
          $this->blurb = $aReusableSlot->blurb;
        }
      }
      else
      {
        $this->label = isset($values['label']) ? $values['label'] : null;
        $this->blurb = isset($values['blurb']) ? $values['blurb'] : null;
      }
    }
    if (!$labelActive)
    {
      $this->label = null;
    }
    if (!$blurbActive)
    {
      $this->blurb = null;
    }
    
    $this->reusing = isset($values['reuse']);
  }
  // The smarts you're looking for are probably in getOrderedMediaItems in the model class
}
