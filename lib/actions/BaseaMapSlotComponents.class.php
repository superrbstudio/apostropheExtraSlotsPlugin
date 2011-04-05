<?php
class BaseaMapSlotComponents extends aSlotComponents
{
	protected function setupOptions()
	{
    $this->options['width'] = $this->getOption('width', 440);
    $this->options['height'] = $this->getOption('height', 440);
    $this->options['title'] = $this->getOption('title', true);
		$this->options['zoom'] = $this->getOption('zoom', 8);		
	}

  public function executeEditView()
  {
    // Must be at the start of both view components
    $this->setup();
		$this->setupOptions();

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
		$this->setupOptions();

    $this->map = $this->slot->getArrayValue();
  }
}
