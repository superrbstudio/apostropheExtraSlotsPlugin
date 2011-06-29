<?php
class BaseaMapSlotEditForm extends BaseForm
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
			'title' => new sfWidgetFormInputText(array(), array()),
			'address' => new sfWidgetFormInputText(array(), array()),
			'longitude' => new sfWidgetFormInputHidden(array(), array()),
			'latitude' => new sfWidgetFormInputHidden(array(), array()),
  	));

    $this->setValidators(array(
			'title' => new sfValidatorString(array('required' => false, 'max_length' => 100)),
			'address' => new sfValidatorString(array('required' => true, 'max_length' => 300)),
			'longitude' => new sfValidatorString(array('required' => false)),
			'latitude' => new sfValidatorString(array('required' => false)),
		));

    // Ensures unique IDs throughout the page. Hyphen between slot and form to please our CSS
    $this->widgetSchema->setNameFormat('slot-form-' . $this->id . '[%s]');

    // You don't have to use our form formatter, but it makes things nice
    $this->widgetSchema->setFormFormatterName('aAdmin');

		$this->validatorSchema->setPostValidator(new sfValidatorCallback(array('callback' => array($this, 'validateAddress'))));
  }

	public function validateAddress($validator, $values)
	{
		$oldAddress = $this->getDefault('address');
		$address = $values['address'];
		if (($address !== $oldAddress) || (!strlen($values['latitude'])))
		{
			$geocode = @file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?'.http_build_query(array(
				'address' => $address,
				'sensor' => 'false'
			)));
			$geocode = @json_decode($geocode, true);
			if (!isset($geocode['results'][0]))
			{
				throw new sfValidatorError($validator, 'Invalid address');
			}
			$values['address'] = $geocode['results'][0]['formatted_address'];
			$values['latitude'] = (float) $geocode['results'][0]['geometry']['location']['lat'];
			$values['longitude'] = (float) $geocode['results'][0]['geometry']['location']['lng'];
		}
		return $values;
	}

}
