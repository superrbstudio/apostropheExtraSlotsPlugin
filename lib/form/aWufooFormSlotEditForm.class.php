<?php    
class aWufooFormSlotEditForm extends BaseForm
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
    $this->setWidgets(array('wufoo_code' => new sfWidgetFormTextarea()));
    $this->setValidators(array('wufoo_code' => new sfValidatorString(array('required' => false, 'max_length' => 100))));
    $this->widgetSchema->setHelp('wufoo_code', 'Hint: generate your form on <a href="http://wufoo.com/" target="application">wufoo.com</a>, then click "Code," then "Embed Form Code." Copy and paste the "Javascript Version" of the code here.');
    $this->setValidators(array('wufoo_code' => new sfValidatorString(array('required' => true, 'max_length' => 5000))));
    
    $this->validatorSchema->setPostValidator(new sfValidatorCallback(array('callback' => array($this, 'validateWufooCode'))));
    // Ensures unique IDs throughout the page. Hyphen between slot and form to please our CSS
    $this->widgetSchema->setNameFormat('slot-form-' . $this->id . '[%s]');
    
    // You don't have to use our form formatter, but it makes things nice
    $this->widgetSchema->setFormFormatterName('aAdmin');
  }
  public function validateWufooCode($validator, $values)
  {
    $error = false;
    if (preg_match('/new WufooForm/', $values['wufoo_code']))
    {
      // All is well
    }
    else
    {
      $error = true;
    }
    if ($error)
    {
      throw new sfValidatorErrorSchema($validator, array('wufoo_code' => new sfValidatorError($validator, 'Hmm, that doesn\'t look like JavaScript embed form code from Wufoo.')));
    }
    return $values;
  }
}
