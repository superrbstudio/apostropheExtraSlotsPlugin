<?php    
class aEcwidProductSlotEditForm extends BaseForm
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
    $this->setWidgets(array('ecwid_code' => new sfWidgetFormTextarea(array('label' => 'Ecwid product HTML code'))));
    $this->widgetSchema->setHelp('ecwid_code', 'Hint: go to <a href="http://ecwid.com/" target="application">ecwid.com</a>, log into your store, click "Catalog," click on the product, then click "HTML Code" and copy the code block shown.');
    $this->setValidators(array('ecwid_code' => new sfValidatorString(array('required' => true, 'max_length' => 5000))));
    
    $this->validatorSchema->setPostValidator(new sfValidatorCallback(array('callback' => array($this, 'validateEcwidCode'))));
    // Ensures unique IDs throughout the page. Hyphen between slot and form to please our CSS
    $this->widgetSchema->setNameFormat('slot-form-' . $this->id . '[%s]');
    
    // You don't have to use our form formatter, but it makes things nice
    $this->widgetSchema->setFormFormatterName('aAdmin');
  }
  public function validateEcwidCode($validator, $values)
  {
    $error = false;
    if (preg_match('/ecwid-Product/', $values['ecwid_code']))
    {
      // All is well
    }
    else
    {
      $error = true;
    }
    if ($error)
    {
      throw new sfValidatorErrorSchema($validator, array('ecwid_code' => new sfValidatorError($validator, 'Hmm, that doesn\'t look like product HTML code from Ecwid.')));
    }
    return $values;
  }
}
