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
    if (!$migrate->columnExists('a_reusable_slot', 'blurb'))
    {
      $migrate->sql(array('ALTER TABLE a_reusable_slot ADD COLUMN blurb LONGTEXT'));
    }
    if (!$migrate->tableExists('a_poll'))
    {
      $migrate->sql(array(
        'CREATE TABLE a_poll (id BIGINT AUTO_INCREMENT, media_item_id BIGINT, question VARCHAR(255) NOT NULL, INDEX media_item_id_idx (media_item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = INNODB;',
        'CREATE TABLE a_poll_choice (id BIGINT AUTO_INCREMENT, name VARCHAR(255) NOT NULL, count BIGINT DEFAULT 0 NOT NULL, poll_id BIGINT NOT NULL, INDEX poll_id_idx (poll_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = INNODB;',
        'ALTER TABLE a_poll ADD CONSTRAINT a_poll_media_item_id_a_media_item_id FOREIGN KEY (media_item_id) REFERENCES a_media_item(id) ON DELETE SET NULL;',
        'ALTER TABLE a_poll_choice ADD CONSTRAINT a_poll_choice_poll_id_a_poll_id FOREIGN KEY (poll_id) REFERENCES a_poll(id) ON DELETE CASCADE;'
      ));
    }
  }
}
