<?php

/**
 * PluginaPoll form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage filter
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormFilterPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginaPollFormFilter extends BaseaPollFormFilter
{
  public function setup()
  {
    parent::setup();
    // Infeasible with thousands of items as is commonly the case
    unset($this['media_item_id']);
  }
}
