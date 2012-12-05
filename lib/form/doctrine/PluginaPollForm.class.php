<?php

/**
 * PluginaPoll form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage form
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginaPollForm extends BaseaPollForm
{
  public function configure()
  {
    parent::configure();
    $object = $this->getObject();
    $existing = $object->getChoices();
    $choices = aArray::getChoices($existing);

    $this->setWidget('choices_list',
      new sfWidgetFormChoice(array('multiple' => true, 'choices' => $choices)));
    $this->setDefault('choices_list', array_keys($choices));
    $this->setValidator('choices_list',
      new sfValidatorChoice(array('multiple' => true, 'choices' => array_keys($choices), 'required' => false)));

    $this->setWidget('choices_list_add',
      new sfWidgetFormInputText());
    $this->setValidator('choices_list_add',
      new sfValidatorPass(array('required' => false)));
  }

  protected function doSave($con = null)
  {
    parent::doSave($con);
    $this->updateChoices($this->getValue('choices_list'));
    if (isset($this['choices_list_add']))
    {
      $this->addChoices($this->getValue('choices_list_add'));
    }
  }

  public function updateChoices($choices)
  {
    $sql = new aMysql();
    if (!is_array($choices))
    {
      $choices = array();
    }
    if (!$this->getObject()->isNew())
    {
      if (!count($choices))
      {
        $sql->query('DELETE FROM a_poll_choice WHERE poll_id = :poll_id', array('poll_id' => $this->getObject()->getId()));
      }
      else
      {
        $sql->query('DELETE FROM a_poll_choice WHERE poll_id = :poll_id AND id NOT IN :choices', array('poll_id' => $this->getObject()->getId(), 'choices' => $choices));
      }
    }
  }

  public function addChoices($addValues)
  {
    // Add any new choices (choices_list_add)
    
    $link = array();
    if (!is_array($addValues))
    {
      $addValues = array();
    }
    foreach ($addValues as $value)
    {
      $this->addChoice($value);
    }
  }

  public function addChoice($value)
  {
    $value .= '';
    foreach ($this->getObject()->getChoices() as $existingChoice)
    {
      if ($existingChoice->getName() === $value)
      {
        return;
      }
    }
    $choice = new aPollChoice();
    $choice->setName($value);
    $choice->setPoll($this->getObject());
    $choice->save();
  }
}
