<?php
class aContactSlotComponents extends BaseaSlotComponents
{
  public function executeEditView()
  {
    $this->setup();
    // Careful, don't clobber a form object provided to us with validation errors
    // from an earlier pass
    if (!isset($this->form))
    {
      $this->form = new aContactSlotEditForm($this->id);
      $value = $this->slot->getArrayValue();
      if (isset($value['description']))
      {
        $this->form->setDefault('description', $value['description']);      
      }
    }
  }
  public function executeNormalView()
  {
    $this->setup();
    $this->options['constraints'] = $this->getOption('constraints', array());
    $this->options['width'] = $this->getOption('width', 120);
    $this->options['height'] = $this->getOption('height', false);
    $this->options['resizeType'] = $this->getOption('resizeType', 's');
    $this->options['flexHeight'] = $this->getOption('flexHeight', true);
    $this->options['description'] = $this->getOption('description', true);

    $data = $this->slot->getArrayValue(); // Behave well if it's not set yet!

    if (isset($data['description']) && $this->options['description'])
    {
      $this->options['description'] = $data['description'];
    }
		else
		{
			$this->options['description'] = false;
		}

    // Behave well if it's not set yet!
    if (!count($this->slot->MediaItems))
    {
      $this->item = false;
      $this->itemId = false;
    }
    else
    {
      $this->item = $this->slot->MediaItems[0];
      $this->itemId = $this->item->id;
      $this->dimensions = aDimensions::constrain(
        $this->item->width, 
        $this->item->height,
        $this->item->format, 
        array("width" => $this->options['width'],
          "height" => $this->options['flexHeight'] ? false : $this->options['height'],
          "resizeType" => $this->options['resizeType']));
      $this->embed = $this->item->getEmbedCode($this->dimensions['width'], $this->dimensions['height'], $this->dimensions['resizeType'], $this->dimensions['format'], false);
    }
  }
}
