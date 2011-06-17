<?php
class BaseaMapSlotComponents extends aSlotComponents
{
	protected function setupOptions()
	{
    $this->options['width'] = $this->getOption('width', 440);
    $this->options['height'] = $this->getOption('height', 440);
		$this->options['zoom'] = $this->getOption('zoom', 14);
		$this->options['mapType'] = $this->getOption('mapType', 'roadmap');		
		$this->options['controls'] = $this->getOption('controls', array(
			'pan' => false,
			'zoom' => false,
			'scale' => false,
		));
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

		// 1. Is there a slot option set?
		// 2. Is there a slot value set?
		// 3. Use this default so nothing blows up (Punk Ave Studio)

    $this->options['title'] = $this->getOption('title', ((isset($this->map['title'])) ? $this->map['title'] : false));
    $this->options['address'] = $this->getOption('address', ((isset($this->map['address'])) ? $this->map['address'] : false));
    $this->options['longitude'] = $this->getOption('longitude', ((isset($this->map['longitude'])) ? $this->map['longitude'] : false));
    $this->options['latitude'] = $this->getOption('latitude', ((isset($this->map['latitude'])) ? $this->map['latitude'] : false));

  }
}
