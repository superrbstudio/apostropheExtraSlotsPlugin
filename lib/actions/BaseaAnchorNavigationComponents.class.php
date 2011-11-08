<?php
class BaseaAnchorNavigationComponents extends aSlotComponents
{
	/**
	 * Executes aAnchorNavigation
	 * Must be used in an Apostrophe page. Don't try to include this component in a non-page;
	 *
	 * @param sfRequest $request A request object
	 */
	public function executeAAnchorNavigation()
	{
		// Get the current page
		$page = aTools::getCurrentPage();

		// Get an array of area names to look for anchors | Defaults to 'body'
		$areaNames = isset($this->areas) ? $this->areas : array('body');

		// Get the navigation name | defaults to 'inline-anchor'
		$this->name = isset($this->name) ? $this->name : 'inline-anchor';

		// Building navigation
		$navigation = array();

		foreach ($areaNames as $areaName)
		{
			$slots = $page->getSlotsByAreaName($areaName);
			foreach ($slots as $slot)
			{
				if ($slot instanceof aAnchorTitleSlot)
				{
					// Storing the Title and the Anchor
					$navigation[] = array('title' => $slot->getTitle(), 'anchor' => $slot->getAnchor());
				}
			}
		}
		
		// Pass it into the component
		$this->navigation = $navigation;
	}
}
