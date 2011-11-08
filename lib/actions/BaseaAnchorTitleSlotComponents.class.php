<?php
class BaseaAnchorTitleSlotComponents extends aSlotComponents
{
  public function executeEditView()
  {
    // Must be at the start of both view components
    $this->setup();
 		$this->setupOptions();
    // Careful, don't clobber a form object provided to us with validation errors
    // from an earlier pass
    if (!isset($this->form))
    {
      $this->form = new aAnchorTitleSlotEditForm($this->id, $this->slot->getArrayValue());
    }
		$this->getSlotValues();
  }

  public function executeNormalView()
  {
    $this->setup();
		$this->setupOptions();
		$this->getSlotValues();
  }

	protected function getSlotValues()
	{
		$this->values = array('title' => $this->slot->getTitle(), 'anchor' => $this->slot->getAnchor());
	}
	
	protected function setupOptions()
	{
		// Title = True : displays a title on the page as a heading to a section of content
		// Title = False : only shows a placeholder when logged-in
    $this->options['title'] = $this->getOption('title', true);
	}

}
