<?php

class BaseaInsetAreaSlotComponents extends aSlotComponents
{
	protected function setupOptions()
	{
		$this->options['tool'] = $this->getOption('tool','Main');
		$this->options['width'] = $this->getOption('width', 480);
		$this->options['areaTemplate'] = $this->getOption('areaTemplate', 'insetArea');
		$this->options['insetTemplate'] = $this->getOption('insetTemplate', 'topLeft');
		$this->options['description'] = $this->getOption('description', true);		
	}

  public function executeEditView()
  {
    $this->setup();
		$this->setupOptions();
    if (!isset($this->form))
    {
      $this->form = new aInsetAreaSlotForm($this->id, $this->options);
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
		$this->setupOptions();
    $data = $this->slot->getArrayValue();
    if ($this->options['description'])
    {
			if (isset($data['description'])) {
      	$this->options['description'] = $data['description'];
			}
			else
			{
      	$this->options['description'] = false;
			}
    }
  }
}