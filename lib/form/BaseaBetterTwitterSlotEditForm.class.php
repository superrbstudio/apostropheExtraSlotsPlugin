<?php
class BaseaBetterTwitterSlotEditForm extends BaseForm
{
  // Ensures unique IDs throughout the page
  protected $id;
  public function __construct($id, $defaults = array(), $options = array(), $CSRFSecret = null)
  {
    $this->id = $id;
    parent::__construct($defaults, $options, $CSRFSecret);
  }
  public function configure()
  {

    $class = isset($this->options['class']) ? ($this->options['class'] . ' ') : '';
    $class .= 'aBetterTwitter';

		$text = (isset($this->value['search']) && strlen($this->value['search'])) ? aHtml::toPlaintext($this->value['search']) : '@apostrophenow OR from:apostrophenow OR #apostrophenow';

    $this->setWidgets(array(
      'search' => new sfWidgetFormInputText(array('default' => $text), $this->options),
      'avatar' => new sfWidgetFormInputText(array('default' => 32), $this->options),
      'count' => new sfWidgetFormInputText(array('default' => 4), $this->options),
    ));

    $this->setValidators(array(
      'search' => new sfValidatorString(array('required' => false, 'max_length' => 255)),
      'avatar' => new sfValidatorNumber(array('required' => false, 'min' => 16, 'max' => 64, )),
      'count' => new sfValidatorNumber(array('required' => false, 'min' => 1, 'max' => 25)),
    ));
    
    $this->widgetSchema->setLabels(array(
      'search' => 'Search', 
      'avatar' => 'Icon Size', 
      'count' => 'Max Tweets', 
    ));
    $this->widgetSchema->setHelps(array(
      'search' => 'Examples: <i>@Username</i> <i>#hashtag</i> <i>from:Username</i><br/>You can use AND / OR operators too!. <a href="http://support.twitter.com/groups/31-twitter-basics/topics/110-search/articles/71577-how-to-use-advanced-twitter-search" rel="external">Need Help?</a>',
      'avatar' => 'Your twitter icon in pixels',
      'count' => 'Amount of tweets to show',   
    ));
    

    // Ensures unique IDs throughout the page. Hyphen between slot and form to please our CSS
    $this->widgetSchema->setNameFormat('slot-form-' . $this->id . '[%s]');

    // You don't have to use our form formatter, but it makes things nice
    $this->widgetSchema->setFormFormatterName('aAdmin');
  }
}
