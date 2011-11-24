<?php    
class aEtsySlotEditForm extends BaseForm
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
    $this->setWidgets(array('etsy_code' => new sfWidgetFormTextarea(array('label' => 'Etsy Mini code'))));
    $this->setValidators(array('etsy_code' => new sfValidatorString(array('required' => false, 'max_length' => 100))));
    $this->widgetSchema->setHelp('etsy_code', 'Hint: go to <a href="http://etsy.com/" target="application">etsy.com</a>, click "Your Shop," then click "Etsy Mini" under "Promote" in the left-hand column. Make your selections, then copy and paste the code that appears here.');
    $this->setValidators(array('etsy_code' => new sfValidatorString(array('required' => true, 'max_length' => 5000))));
    
    $this->validatorSchema->setPostValidator(new sfValidatorCallback(array('callback' => array($this, 'validateEtsyCode'))));
    // Ensures unique IDs throughout the page. Hyphen between slot and form to please our CSS
    $this->widgetSchema->setNameFormat('slot-form-' . $this->id . '[%s]');
    
    // You don't have to use our form formatter, but it makes things nice
    $this->widgetSchema->setFormFormatterName('aAdmin');
  }
  public function validateEtsyCode($validator, $values)
  {
    $error = false;
    if (preg_match('/new Etsy/', $values['etsy_code']))
    {
      // All is well
    }
    else
    {
      $error = true;
    }
    if ($error)
    {
      throw new sfValidatorErrorSchema($validator, array('etsy_code' => new sfValidatorError($validator, 'Hmm, that doesn\'t look like the Etsy Mini code from etsy.com.')));
    }
    return $values;
  }
}
