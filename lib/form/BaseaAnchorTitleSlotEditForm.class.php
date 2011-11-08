<?php    
class BaseaAnchorTitleSlotEditForm extends BaseForm
{
  // Ensures unique IDs throughout the page

  protected $id;
  protected $options;
	protected $value; 
	
  public function __construct($id, $value, $defaults = array(), $options = array(), $CSRFSecret = null)
  {
    $this->id = $id;
		$this->value = $value;
		$this->options = $options;
    parent::__construct($defaults, $options, $CSRFSecret);
  }

  public function configure()
  {
    $class = isset($this->options['class']) ? ($this->options['class'] . ' ') : '';
    $class .= 'aAnchor';

		$text = (isset($this->value['title']) && strlen($this->value['title'])) ? aHtml::toPlaintext($this->value['title']) : '';

    $class .= ' single-line';
    $this->options['class'] = $class;
    $this->setWidgets(array('title' => new sfWidgetFormInputText(array('default' => $text), $this->options))); 
    $this->setValidators(array('title' => new sfValidatorString(array('required' => false, 'max_length' => 100))));
    
    // Ensures unique IDs throughout the page. Hyphen between slot and form to please our CSS
    $this->widgetSchema->setNameFormat('slot-form-' . $this->id . '[%s]');
    
    // You don't have to use our form formatter, but it makes things nice
    $this->widgetSchema->setFormFormatterName('aAdmin');
  }
}
