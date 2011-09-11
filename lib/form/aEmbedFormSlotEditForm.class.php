<?php    
class aEmbedFormSlotEditForm extends BaseForm
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
    $this->setWidgets(array('embed_code' => new sfWidgetFormTextarea()));
    $this->setValidators(array('embed_code' => new sfValidatorCallback(array('required' => true, 'callback' => array($this, 'validateEmbedCode')))));
    $this->widgetSchema->setHelp('embed_code', 'Paste the embed code for a <a href="https://docs.google.com/support/bin/answer.py?answer=87809">Google Docs form</a>');
    // Ensures unique IDs throughout the page. Hyphen between slot and form to please our CSS
    $this->widgetSchema->setNameFormat('slot-form-' . $this->id . '[%s]');
    
    // You don't have to use our form formatter, but it makes things nice
    $this->widgetSchema->setFormFormatterName('aAdmin');
  }
    
  public function validateEmbedCode($validator, $value)
  {
    $service = new aEmbedFormService();
    $value = $service->canonicalize($value);
    if (is_null($value))
    {
		  throw new sfValidatorError($validator, 'Not a valid Google Forms embed code');
		}
		return $value;
  }
}
