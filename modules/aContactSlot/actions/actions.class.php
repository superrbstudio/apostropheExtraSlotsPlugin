<?php
class aContactSlotActions extends BaseaSlotActions
{
	// Image association is handled by a separate action
  public function executeImage(sfRequest $request)
  {
    if ($request->getParameter('aMediaCancel'))
    {
      return $this->redirectToPage();
    }

    $this->logMessage("====== in aImageSlotActions::executeImage", "info");
    $this->editSetup();
    $item = Doctrine::getTable('aMediaItem')->find($request->getParameter('aMediaId'));
    if ((!$item) || ($item->type !== 'image'))
    {
      return $this->redirectToPage();
    }
    $this->slot->unlink('MediaItems');
    $this->slot->link('MediaItems', array($item->id));
    $this->editSave();
  }

  public function executeEdit(sfRequest $request)
  {
    $this->editSetup();

    // Hyphen between slot and form to please our CSS
    // $value = $this->getRequestParameter('slot-form-' . $this->id);

    $values = $request->getParameterHolder()->getAll();
    $value = array();
    foreach ($values as $k => $v)
    {
      if (preg_match('/^slotform-' . $this->id . '-(.*)$/', $k, $matches))
      {
        $value[$matches[1]] = $v;
      }
    }

    // HTML is carefully filtered to allow only elements, attributes and styles that
    // make sense in the context of a rich text slot, and you can adjust that.
    // See aHtml::simplify(). You can do slot-specific overrides by setting the
    // allowed-tags, allowed-attributes and allowed-styles options

    $this->form = new aContactSlotEditForm($this->id, $this->options);
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
}
