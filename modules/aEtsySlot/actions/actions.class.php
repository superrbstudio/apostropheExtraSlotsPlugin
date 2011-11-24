<?php
class aEtsySlotActions extends aSlotActions
{
  public function executeEdit(sfRequest $request)
  {
    $this->editSetup();

    // Hyphen between slot and form to please our CSS
    $value = $this->getRequestParameter('slot-form-' . $this->id);
    $this->form = new aEtsySlotEditForm($this->id, array());
    $this->form->bind($value);
    if ($this->form->isValid())
    {
      $values = $this->form->getValues();
      $this->slot->setArrayValue($values);
      return $this->editSave();
    }
    else
    {
      // Makes $this->form available to the next iteration of the
      // edit view so that validation errors can be seen, if any
      return $this->editRetry();
    }
  }
}
  