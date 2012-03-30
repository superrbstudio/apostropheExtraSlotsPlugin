<?php
class aEcwidProductSlotComponents extends aSlotComponents
{
  public function executeEditView()
  {
    // Must be at the start of both view components
    $this->setup();
    
    // Careful, don't clobber a form object provided to us with validation errors
    // from an earlier pass
    if (!isset($this->form))
    {
      $this->form = new aEcwidProductSlotEditForm($this->id, $this->slot->getArrayValue());
    }
  }
  public function executeNormalView()
  {
    $this->setup();
    $this->values = $this->slot->getArrayValue();
    if (isset($this->values['ecwid_code']))
    {
      if (preg_match('/script.js\?(\d+)/', $this->values['ecwid_code'], $matches))
      {
        $this->storeId = $matches[1];
      }
    }
  }
}
