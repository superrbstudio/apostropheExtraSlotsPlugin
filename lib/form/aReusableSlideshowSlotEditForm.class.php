<?php    
class aReusableSlideshowSlotEditForm extends BaseForm
{
  protected $id;
  protected $aReusableSlot;
  protected $slot;
  protected $reuseChoices;
  
  public function __construct($id, $aReusableSlot, $slot)
  {
    $this->id = $id;
    
    $this->aReusableSlot = $aReusableSlot;
    $this->slot = $slot;
    $values = $slot->getArrayValue();
    $defaults = array();
    if (!$this->slot->id)
    {
      $defaults['label_or_reuse'] = 'reuse';
    }
    else
    {
      if (isset($values['reuse']))
      {
        $defaults['reuse'] = $values['reuse']['id'];
        $defaults['label_or_reuse'] = 'reuse';
      }
      else
      {
        $defaults['label'] = $aReusableSlot['label'];
        $defaults['blurb'] = $aReusableSlot['blurb'];
        $defaults['label_or_reuse'] = 'label';
      }
    }
    parent::__construct($defaults);
  }
  
  public function configure()
  {
    // label_or_reuse determines which of the other widgets are relevant. When there is
    // only one choice we use javascript to hide the nonavailable choice later. In general we
    // rely on a smart postvalidator in this form
    
    if ($this->slot->id && (!isset($values['reuse'])))
    {
      $choices = array('label' => 'Label for Reuse', 'reuse' => 'Reuse Another Slideshow');
    }
    else
    {
      $choices = array('reuse' => 'Reuse Another Slideshow');
    }
    $this->setWidget('label_or_reuse', new sfWidgetFormChoice(array('choices' => $choices)));
    $this->setValidator('label_or_reuse', new sfValidatorPass(array('required' => false))); // ('label_or_reuse', new sfValidatorChoice(array('choices' => array_keys($choices))));

    $this->setWidget('label', new sfWidgetFormInputText());
    // See validateCallback
    $this->setValidator('label', new sfValidatorPass(array('required' => false)));
    
    // The rest of the options passed become attributes of the widget
    $this->setWidget('blurb', new aWidgetFormRichTextarea());
    $this->setValidator('blurb', new sfValidatorHtml(array('required' => false)));
    
    $page = aTools::getCurrentPage();
    $reusableSlots = Doctrine::getTable('aReusableSlot')->createQuery('r')->where('r.type = ? AND r.id <> ? AND r.page_id <> ?', array($this->slot->type, $this->aReusableSlot ? $this->aReusableSlot->id : 0, $page ? $page->id : 0))->orderBy('r.label')->fetchArray();
    
    // Filter to make sure only slots that currently exist remain on the list
    $filteredSlots = array();
    foreach ($reusableSlots as $reusableSlot)
    {
      if (aReusableSlotTable::getReusedSlot($reusableSlot))
      {
        $filteredSlots[] = $reusableSlot;
      }
    }
    $reusableSlots = $filteredSlots;
    
    $this->reuseChoices = array();
    foreach ($reusableSlots as $reusableSlot)
    {
      $this->reuseChoices[$reusableSlot['id']] = $reusableSlot['label'];
    }
    $this->setWidget('reuse', new sfWidgetFormChoice(array('choices' => array('' => 'Choose One') + $this->reuseChoices)));
    $this->setValidator('reuse', new sfValidatorPass(array('required' => false)));
    
    $this->validatorSchema->setPostValidator(new sfValidatorCallback(array('callback' => array($this, 'validateCallback'))));
    
    // There are problems with AJAX plus FCK plus Symfony forms. FCK insists on making the name and ID
    // the same and brackets are not valid in IDs which can lead to problems in strict settings
    // like AJAX in IE. Work around this by not attempting to use brackets here
    $this->widgetSchema->setNameFormat('slot-form-' . $this->id . '-%s');
 
    
    // You don't have to use our form formatter, but it makes things nice
    $this->widgetSchema->setFormFormatterName('aAdmin');
  }

  /**
   * There are two sets of validators to apply depending whether the user
   * picks 'label' or 'reuse'. Symfony form validators aren't great at dealing with this,
   * so just validate in one pass
   */
  public function validateCallback($validator, $values)
  {
    if ($values['label_or_reuse'] === 'label')
    {
      if (strlen($values['label']) < 1)
      {
        throw new sfValidatorErrorSchema($validator, array('label' => new sfValidatorError($validator, 'min_length', array('min_length' => 1))));
      }
      if (strlen($values['label']) > 100)
      {
        throw new sfValidatorErrorSchema($validator, array('label' => new sfValidatorError($validator, 'max_length', array('max_length' => 100))));
      }
      $page = aTools::getCurrentPage();
      aReusableSlotTable::purgeOrphanByLabel($values['label'], array('exclude_page_id' => $page ? $page->id : null));
      $existing = Doctrine::getTable('aReusableSlot')->createQuery('r')->where('r.label = ? AND r.type = ? AND r.page_id <> ?', array($values['label'], $this->slot->type, $page ? $page->id : 0))->fetchOne();
      if ($existing && ((!$this->aReusableSlot) || ($existing->id !== $this->aReusableSlot->id)))
      {
        throw new sfValidatorErrorSchema($validator, array('label' => new sfValidatorError($validator, 'unique')));
      }
    }
    elseif ($values['label_or_reuse'] === 'reuse')
    {
      if (!$values['reuse'])
      {
        throw new sfValidatorErrorSchema($validator, array('reuse' => new sfValidatorError($validator, 'required')));
      }
      if (!isset($this->reuseChoices[$values['reuse']]))
      {
        throw new sfValidatorErrorSchema($validator, array('reuse' => new sfValidatorError($validator, 'invalid')));
      }
    }
    else
    {
      throw new sfException('Unexpected value for label_or_reuse');
    }
    return $values;
  }
}
