<?php
class BaseaBetterTwitterSlotComponents extends aSlotComponents
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
      $this->form = new aBetterTwitterSlotEditForm($this->id, $this->slot->getArrayValue());
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
		$this->values = $this->slot->getArrayValue();
	}
	
	protected function setupOptions()
	{
    $this->options['join_text'] = $this->getOption('join_text', 'auto');
    $this->options['avatar_size'] = $this->getOption('avatar_size', false); // This can be set by the user. Setting it here will disable the users ability to change it
    $this->options['count'] = $this->getOption('count', false); // This can be set by the user. Setting it here will disable the users ability to change it
    $this->options['query'] = $this->getOption('query', false);
    $this->options['auto_join_text_default'] = $this->getOption('auto_join_text_default','we said,');
    $this->options['auto_join_text_ed'] = $this->getOption('auto_join_text_ed','we');                
    $this->options['auto_join_text_ing'] = $this->getOption('auto_join_text_ing','we were');
    $this->options['auto_join_text_reply'] = $this->getOption('auto_join_text_reply','we replied to');
    $this->options['auto_join_text_url'] = $this->getOption('auto_join_text_url','we were checking out');
    $this->options['loading_text'] = $this->getOption('loading_text','loading tweets...');                    
	}
	
}
