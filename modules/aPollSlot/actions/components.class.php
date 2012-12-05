<?php
class aPollSlotComponents extends aSlotComponents
{
  public function executeEditView()
  {
    // Must be at the start of both view components
    $this->setup();
    
    // Careful, don't clobber a form object provided to us with validation errors
    // from an earlier pass
    if (!isset($this->form))
    {
      $this->form = new aPollSlotEditForm($this->id, $this->slot->getArrayValue());
    }
  }
  public function executeNormalView()
  {
    $this->setup();
    $this->values = $this->slot->getArrayValue();
    $this->poll = null;
    if (isset($this->values['poll_id']))
    {
      $this->poll = Doctrine::getTable('aPoll')->findOneById($this->values['poll_id']);
    }
  }
}
