<?php

/**
 * PluginaPhotoGridSlot
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class PluginaPhotoGridSlot extends BaseaPhotoGridSlot
{
  /**
   * Return the ids of the associated media items in the desired order.
   * It's OK to return ids of items that no longer exist, 
   * getOrderedMediaItems() will clean that up via a query.
   * @return @array | null
   */
  public function getMediaItemOrder()
  {
    $data = $this->getArrayValue();
    if (isset($data['order']))
    {
      return $data['order'];
    }
    return null;
  }
}