<?php
class BaseaBlogSlideshowSlotComponents extends BaseaBlogSlotComponents
{
	// The Blog Slideshow Slot extends the normal blog slot in the apostropheBlogPlugin
	// We are adding a range of options similar to the aSlideshowSlot
	protected function setupSlideshowOptions()
	{
		// Excerpt length for blog body text
    $this->options['excerptLength'] = $this->getOption('excerptLength', 100);
		$this->options['subtemplate'] = $this->getOption('subtemplate','slideshow');
		// The rest of this is pulled from the slideshowSlot options
    $this->options['width'] = $this->getOption('width', 440);
    $this->options['height'] = $this->getOption('height', false);
    $this->options['resizeType'] = $this->getOption('resizeType', 's');
    $this->options['flexHeight'] = $this->getOption('flexHeight');
    $this->options['interval'] = $this->getOption('interval', 0) + 0;
    $this->options['arrows'] = $this->getOption('arrows', true);
    $this->options['transition'] = ($this->options['height']) ? $this->getOption('transition', 'normal') : 'normal-forced';
    $this->options['duration'] = $this->getOption('duration', 300) + 0;
    $this->options['position'] = $this->getOption('position', false);
    $this->options['random'] = $this->getOption('random', false);
    // idSuffix works with the Blog Slot slideshows
    // Creates unique ids for the same slideshows if they show up in separate slots on a single page.
    $this->options['idSuffix'] = $this->getOption('idSuffix', false);

	}

  public function executeNormalView()
  {
		// execute the normalView of the normal blog slot
		parent::executeNormalView();
		// and add the options we need for the slideshow
		$this->setupSlideshowOptions();
  }

}