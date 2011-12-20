<?php

class aReusableSlideshowAdminActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->sql = new aMysql();
    // This is a table listing all the slots available for reuse in an efficient way
    $reusableSlotInfos = $this->sql->query('SELECT * FROM a_reusable_slot WHERE type = "aReusableSlideshow"');
    foreach ($reusableSlotInfos as $reusableSlotInfo)
    {
      $reusableSlotInfo['reuses'] = array();
      $reusableSlotInfosByAId[$this->aId($reusableSlotInfo)] = $reusableSlotInfo;
    }
    // These are the actual reusable slots - actually both the reuses and the reusables, we sort them out.
    // These aren't hydrated, they are pulled with aMysql for acceptable performance and to not
    // fall over from lack of memory etc.
    $slotInfos = $this->getAllRelevantSlotInfos();
    foreach ($slotInfos as $slotInfo) 
    {
      $value = unserialize($slotInfo['value']);
      if (!isset($value['reuse']))
      {
        $aId = $this->aId($slotInfo);
        if (isset($reusableSlotInfosByAId[$aId]))
        {
          $reusableSlotInfosByAId[$aId]['valid'] = true;
        }
      }
      else
      {
        $aId = $this->aId($value['reuse']);
        if (isset($reusableSlotInfosByAId[$aId]))
        {
          $infos = aPageTable::getPagesInfo(array('ids' => array($slotInfo['page_id'])));
          if (count($infos))
          {
            $slotInfo['page_info'] = $infos[0];
            $reusableSlotInfosByAId[$aId]['reuses'][] = $slotInfo;
          }
        }
      }
    }
    $this->reusables = array();
    foreach ($reusableSlotInfosByAId as $aId => $reusableSlotInfo)
    {
      if (isset($reusableSlotInfo['valid']))
      {
        $infos = aPageTable::getPagesInfo(array('ids' => array($reusableSlotInfo['page_id'])));
        if (count($infos))
        {
          $reusableSlotInfo['page_info'] = $infos[0];
          $this->reusables[$aId] = $reusableSlotInfo;
        }
      }
    }
    usort($this->reusables, array($this, 'compareSlideshowInfos'));
  }

  public function compareSlideshowInfos($a, $b) {
    return strcmp($a['label'], $b['label']);
  }
  
  public function executeEdit(sfWebRequest $request)
  {
    $this->reusableSlot = Doctrine::getTable('aReusableSlot')->find($request->getParameter('id'));
    $this->forward404Unless($this->reusableSlot);
    $this->reusedSlot = $this->reusableSlot->getReusedSlot();
    $this->form = new aReusableSlideshowAdminForm(array('label' => $this->reusableSlot['label']));
    if ($request->hasParameter('slideshow'))
    {
      $this->form->bind($request->getParameter('slideshow'));
      if ($this->form->isValid())
      {
        $existing = Doctrine::getTable('aReusableSlot')->findOneByLabel($this->form->getValue('label'));
        if ($existing && ($existing->id !== $this->reusableSlot->id))
        {
          $this->mergeReusableSlots($this->reusableSlot, $existing);
        }
        else
        {
          // Simple rename operation
          $this->reusableSlot['label'] = $this->form->getValue('label');
          $this->reusableSlot->save();
          $values = $this->reusedSlot->getArrayValue();
          $values['label'] = $this->form->getValue('label');
          $this->reusedSlot->setArrayValue($values);
          $this->reusedSlot->save();
        }
        return $this->redirect('@reusable_slideshow_admin_index');
      }
    }
  }
  
  protected function mergeReusableSlots($loser, $winner)
  {
    $this->sql = new aMysql();
    $loserReusedSlot = $loser->getReusedSlot();
    $values = $loserReusedSlot->getArrayValue();
    $values['reuse'] = array('page_id' => $winner['page_id'], 'area_name' => $winner['area_name'], 'permid' => $winner['permid']);
    unset($values['order']);
    unset($values['label']);
    $loserReusedSlot->setArrayValue($values);
    $loserReusedSlot->save();
    // Now the hard part: update every slot that reuses this slot. Our schema isn't designed for this
    // query so we have to zip through all of the slideshows. Fortunately in the real world this is
    // fairly fast even on a big site when we stick to aMysql
    $slotInfos = $this->getAllRelevantSlotInfos();
    foreach ($slotInfos as $slotInfo)
    {
      $values = unserialize($slotInfo['value']);
      if (isset($values['reuse']))
      {
        $reuse = $values['reuse'];
        if (isset($reuse['id']) && ($reuse['id'] === $loser['id']))
        {
          $values['reuse']['page_id'] = $winner['page_id'];
          $values['reuse']['area_name'] = $winner['area_name'];
          $values['reuse']['permid'] = $winner['permid'];
          $values['reuse']['label'] = $winner['label'];
          $values['reuse']['id'] = $winner['id'];
          error_log(json_encode($values));
          $this->sql->update('a_slot', $slotInfo['id'], array('value' => serialize($values)));
        }
      }
    }
    $loser->delete();
  }
  
  public function aId($info)
  {
    $page_id = isset($info['page_id']) ? $info['page_id'] : $info['id'];
    return $page_id . '-' . $info['area_name'] . '-' . $info['permid'];
  }
  
  protected function getAllRelevantSlotInfos()
  {
    return $this->sql->query('SELECT p.id AS page_id, a.name AS area_name, avs.permid AS permid, s.value AS value, s.id AS id FROM a_page p INNER JOIN a_area a ON a.page_id = p.id INNER JOIN a_area_version av ON av.area_id = a.id AND av.version = a.latest_version INNER JOIN a_area_version_slot avs ON avs.area_version_id = av.id INNER JOIN a_slot s ON avs.slot_id = s.id AND s.type = "aReusableSlideshow"');
  }
}