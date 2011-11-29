<?php

class apostropheExtraSlotsPluginConfiguration extends sfPluginConfiguration
{
  static $registered = false;
  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
    // Yes, this can get called twice. This is Fabien's workaround:
    // http://trac.symfony-project.org/ticket/8026
    
    if (!self::$registered)
    {
      $this->dispatcher->connect('a.migrateSchemaAdditions', array($this, 'migrate'));
      // This was inadvertently removed just prior to 1.4. Now apostrophe:migrate hooks up properly again
      self::$registered = true;
    }
  }
  
  public function migrate($event)
  {
    $migrate = $event->getSubject();
    if (!$migrate->tableExists('a_reusable_slot'))
    {
      $migrate->sql(array('CREATE TABLE a_reusable_slot (id BIGINT AUTO_INCREMENT, label VARCHAR(100) NOT NULL, type VARCHAR(100) NOT NULL, page_id BIGINT NOT NULL, area_name TEXT NOT NULL, permid BIGINT NOT NULL, PRIMARY KEY(id)) ENGINE = INNODB DEFAULT CHARSET=utf8;'));
    }
  }
}
