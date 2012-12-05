<?php
class aPollSlotActions extends aSlotActions
{
  public function executeEdit(sfRequest $request)
  {
    $this->editSetup();

    // Hyphen between slot and form to please our CSS
    $value = $this->getRequestParameter('slot-form-' . $this->id);
    $this->form = new aPollSlotEditForm($this->id, array());
    $this->form->bind($value);
    if ($this->form->isValid())
    {
      // Serializes all of the values returned by the form into the 'value' column of the slot.
      // This is only one of many ways to save data in a slot. You can use custom columns,
      // including foreign key relationships (see schema.yml), or save a single text value 
      // directly in 'value'. serialize() and unserialize() are very useful here and much
      // faster than extra columns
      
      $this->slot->setArrayValue($this->form->getValues());
      return $this->editSave();
    }
    else
    {
      // Makes $this->form available to the next iteration of the
      // edit view so that validation errors can be seen, if any
      return $this->editRetry();
    }
  }

  public function executeVote(sfWebRequest $request)
  {
    $this->choice = Doctrine::getTable('aPollChoice')->find($request->getParameter('choice_id'));
    $this->forward404Unless($this->choice);
    $this->poll = $this->choice->getPoll();
    if ($this->getUser()->getAttribute('poll-' . $this->poll->getId()))
    {
      // Do nothing already voted
    }
    else
    {
      $this->getUser()->setAttribute('poll-' . $this->poll->getId(), true);
      $this->choice->count++;
      $this->choice->save();
    }
    echo(json_encode(array('count' => $this->choice->count, 'status' => 'ok')));
    exit(0);
  }
}
  
