<?php

/**
 * PluginaInsetImageSlot
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class PluginaInsetImageSlot extends BaseaInsetImageSlot
{

  protected $editDefault = true;
  
  public function getSearchText()
  {
    // Convert from HTML to plaintext before indexing by Lucene
    
    // However first add line breaks after certain tags for better formatting
    // (this method is also used for generating informational diffs between versions).
    // This is a noncritical feature so it doesn't have to be as precise
    // as strip_tags and shouldn't try to substitute for it in the matter of 
    // actually removing the tags
		$value = $this->slot->getArrayValue();
		if (isset($value['description'])) 
		{
	    $this->value = preg_replace("/(<p>|<br.*?>|<blockquote>|<li>|<dt>|<dd>|<nl>|<ol>)/i", "$1\n", $value['description']);
	    return aHtml::toPlaintext($this->value);
		}
		return false;
  }

  /**
   * Returns the plaintext representation of this slot
   */
  public function getText()
  {
    return $this->getSearchText();
  }  

	/**
   * This function returns a basic HTML representation of your slot's comments
   * (passing the default settings of aHtml::simplify, for instance). Used for Google Calendar
   * buttons, RSS feeds and similar
   * @return string
   */
  public function getBasicHtml()
  {
    /* 
      Already cleaned by aHtml::simplify
    */
		$value = $this->slot->getArrayValue();
		return (isset($value['description'])) ? $value['description'] : false;
  }

}