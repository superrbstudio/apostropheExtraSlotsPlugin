<?php    
class aPaypalButtonSlotEditForm extends BaseForm
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
    // ADD YOUR FIELDS HERE
    
    // A simple example: a slot with a single 'text' field with a maximum length of 100 characters
    $this->setWidgets(array('button_code' => new sfWidgetFormTextarea()));
    $this->widgetSchema->setHelp('button_code', 'Hint: generate your button code with <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_button-designer" target="paypal">PayPal\'s button designer</a>. Then paste it here.');
    $this->setValidators(array('button_code' => new sfValidatorAnd(array(new sfValidatorString(array('required' => true, 'max_length' => 5000)), new sfValidatorCallback(array('callback' => array($this, 'validateButtonCode')))))));
    
    // Ensures unique IDs throughout the page. Hyphen between slot and form to please our CSS
    $this->widgetSchema->setNameFormat('slot-form-' . $this->id . '[%s]');
    
    // You don't have to use our form formatter, but it makes things nice
    $this->widgetSchema->setFormFormatterName('aAdmin');
  }
  public function validateButtonCode($validator, $value)
  {
    if (!(preg_match('/^\s*\<form.*?paypal/', $value) && preg_match('/\/form\>\s*$/', $value)))
    {
      throw new sfValidatorError($validator, 'Hmm, that doesn\'t look like a button code from PayPal\'s button designer.');
    }
    return $value;
  }
}
