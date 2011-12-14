<?php

class aReusableSlideshowAdminForm extends BaseForm
{
  public function configure()
  {
    $this->setWidget('label', new sfWidgetFormInput());
    $this->widgetSchema->setHelp('label', 'To merge two reusable slideshows, change the name to match another existing reusable slideshow. All references to the former will become references to the latter.');
    $this->setValidator('label', new sfValidatorString(array('required' => true)));
    $this->widgetSchema->setNameFormat('slideshow[%s]');
    $this->widgetSchema->setFormFormatterName('aAdmin');
  }
}
