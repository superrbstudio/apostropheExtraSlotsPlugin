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
    if (isset($this->options['slideshowLabel']))
    {
      $values = $this->slot->getArrayValue();
      if (isset($values['reuse']['id']))
      {
        $aReusableSlot = Doctrine::getTable('aReusableSlot')->find($values['reuse']['id']);
        if (!$aReusableSlot)
        {
          error_log("Offending id is " . $values['reuse']['id']);
          $this->label = 'Error';
        }
        else
        {
          $this->label = $aReusableSlot->label;
        }
      }
      else
      {
        $this->label = isset($values['label']) ? $values['label'] : null;
      }
    }
    else
    {
      $this->label = null;
    }
  }
  // The smarts you're looking for are probably in getOrderedMediaItems in the model class
}
