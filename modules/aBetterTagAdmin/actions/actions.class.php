<?php

class aBetterTagAdminActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $sql = new aMysql();
    // For now let's not break out tag counts for pages, because they are often
    // virtual pages which would double count blog posts. With a fussy query I
    // could address this. There would be a performance cost for doing so
    $data = $sql->queryScalar('SELECT DISTINCT(ti.taggable_model) FROM tagging ti WHERE ti.taggable_model <> "aPage"');
    $this->models = array();
    $modelLabels = array(
      'aMediaItem' => 'Media',
      'aBlogPost' => 'Blog',
      'aEvent' => 'Event'
    );

    if (class_exists('aEntityTools'))
    {
      $infos = aEntityTools::getClassInfos();
      foreach ($infos as $class => $info)
      {
        $modelLabels[$class] = $info['singular'];
      }
    }

    foreach ($data as $class)
    {
      $this->models[] = array('name' => $class, 'label' => isset($modelLabels[$class]) ? $modelLabels[$class] : $class);
    }

    $q = 'SELECT t.id as id, t.name as name, ti.taggable_model as model, ';
    if (class_exists('aEntityTools'))
    {
      $q .= 'e.type as type, ';
    }
    $q .= 'COUNT(ti.taggable_id) AS count_for_model FROM tag t ';
    if (class_exists('aEntityTools')) 
    {
      $q .= 'LEFT JOIN a_entity e ON e.name = t.name ';
    }
    $q .= 'LEFT JOIN tagging ti ON t.id = ti.tag_id ';
    $q .= 'GROUP BY t.id, ti.taggable_model ORDER BY t.name ';
    $data = $sql->query($q);
    $infoByTag = array();
    foreach ($data as $row)
    {
      $infosByTag[$row['name']][$row['model']] = $row['count_for_model'];
      $infosByTag[$row['name']]['name']= $row['name'];
      if (isset($row['type']))
      {
        $infosByTag[$row['name']]['type'] = $row['type'];
      }
      $infosByTag[$row['name']]['id'] = $row['id'];
    }
    $this->tagInfos = array_values($infosByTag);
    if (class_exists('aEntityTools'))
    {
      $this->classInfos = aEntityTools::getClassInfos();
    }
  }

  public function executeDelete(sfWebRequest $request)
  {
    $sql = new aMysql();
    $id = $this->getRequestParameter('id');
    $id += 0;
    if ($id)
    {
      $sql->query('DELETE FROM tagging WHERE tag_id = :id', array('id' => $id));
      $sql->delete('tag', $id);
      return $this->jsonResponse(array('status' => 'ok', 'deleted' => $id));
    }
    else
    {
      return $this->jsonResponse(array('status' => 'invalid'));
    }
  }

  protected function jsonResponse($array)
  {
    echo(json_encode($array));
    exit(0);
  }

  public function executeRename(sfWebRequest $request)
  {
    $sql = new aMysql();
    $id = $this->getRequestParameter('id');
    $id += 0;
    $name = trim($this->getRequestParameter('name'));
    if ($id && strlen($name))
    {
      $existing = $sql->queryOne('SELECT * FROM tag WHERE name = :name', array('name' => $name));
      $result = array();
      if ($existing && ($existing['id'] !== $id))
      {
        // Merge
        $sql->query('UPDATE tagging SET tag_id = :existing_id WHERE tag_id = :id', array('id' => $id, 'existing_id' => $existing['id']));
        $sql->delete('tag', $id);
        return $this->jsonResponse(array('status' => 'ok', 'action' => 'merged', 'from' => $id, 'to' => $existing['id']));
      }
      else
      {
        $sql->query('UPDATE tag SET name = :name WHERE id = :id', array('name' => $name, 'id' => $id));
        return $this->jsonResponse(array('status' => 'ok', 'action' => 'renamed'));
      }
    }
    else
    {
      return $this->jsonResponse(array('status' => 'invalid'));
    }
  }

  /**
   * Only makes sense if you're using apostropheEntitiesPlugin
   */

  public function executeCreateEntity(sfWebRequest $request)
  {
    $sql = new aMysql();
    $id = $this->getRequestParameter('id');
    $classes = array_keys(aEntityTools::getClassInfos());
    $tag = $sql->queryOne('SELECT * FROM tag WHERE id = :id', array('id' => $id));
    if (!$tag)
    {
      return $this->jsonResponse(array('status' => 'invalid'));
    }
    $entity = Doctrine::getTable('aEntity')->findOneByName($tag['name']);
    if ($entity)
    {
      $class = get_class($entity);
    }
    else
    {
      $class = $this->getRequestParameter('className');
      $entity = new $class;
      $entity->setName($tag['name']);
      $entity->save();
    }
    $info = aEntityTools::getClassInfo($class);
    if (!$info)
    {
      return $this->jsonResponse(array('status' => 'invalid'));
    }
    if (count($classes))
    {
      $taggings = $sql->query('SELECT * FROM tagging WHERE tag_id = :id AND taggable_model IN :classes', array('id' => $id, 'classes' => $classes));
      foreach ($taggings as $tagging)
      {
        // Entity relationships are bidirectional
        try
        {
          $sql->insertOrUpdate('a_entity_to_entity', array('entity_1_id' => $tagging['taggable_id'], 'entity_2_id' => $entity['id']));
          $sql->insertOrUpdate('a_entity_to_entity', array('entity_2_id' => $tagging['taggable_id'], 'entity_1_id' => $entity['id']));
        } catch (Exception $e)
        {
          // Don't bomb if the entity no longer exists. This can happen
          // with tags because they lack referential integrity
        }
      }
    }
    $taggings = $sql->query('SELECT * FROM tagging WHERE tag_id = :id AND taggable_model IN ("aBlogPost", "aEvent")', array('id' => $id));
    foreach ($taggings as $tagging)
    {
      try
      {
        $sql->insertOrUpdate('a_entity_to_blog_item', array('blog_item_id' => $tagging['taggable_id'], 'entity_id' => $entity['id']));
      } catch (Exception $e)
      {
        // Don't bomb if the blog post no longer exists. This can happen
        // with tags because they lack referential integrity
      }
    }
    return $this->jsonResponse(array('status' => 'ok'));
  }
}
