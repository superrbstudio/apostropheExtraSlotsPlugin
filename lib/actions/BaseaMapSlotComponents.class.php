<?php
class BaseaMapSlotComponents extends aSlotComponents
{
  public function executeEditView()
  {
    // Must be at the start of both view components
    $this->setup();
		$this->map = $this->slot->getArrayValue();
		    
    // Careful, don't clobber a form object provided to us with validation errors
    // from an earlier pass
    if (!isset($this->form))
    {
      $this->form = new aMapSlotEditForm($this->id, $this->map);
    }

  }
  public function executeNormalView()
  {
    $this->setup();
    $this->map = $this->slot->getArrayValue();
  }
}
