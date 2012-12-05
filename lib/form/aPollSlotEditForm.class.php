<?php
class aPollSlotEditForm extends BaseForm
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
    $this->setWidgets(array(
      #'text' => new sfWidgetFormTextarea(),
      'poll_id'  => new sfWidgetFormDoctrineChoice(array(
        'model'     => 'aPoll',
        'add_empty' => 'Select a Poll',
        'method' => 'getQuestion',
        'order_by'  => array('question', 'asc')
      )),
    ));

    $this->setValidators(array('poll_id' => new sfValidatorDoctrineChoice(array('required' => true, 'model' => 'aPoll'))));

    // Ensures unique IDs throughout the page. Hyphen between slot and form to please our CSS
    $this->widgetSchema->setNameFormat('slot-form-' . $this->id . '[%s]');

    // You don't have to use our form formatter, but it makes things nice
    $this->widgetSchema->setFormFormatterName('aAdmin');
  }
}
