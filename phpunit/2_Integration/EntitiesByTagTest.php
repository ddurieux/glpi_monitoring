<?php

class EntitiesByTagTest extends RestoreDatabase_TestCase {

   /**
    * @test
    */
   public function test2Entities_tagA() {

      $entity   = new Entity();
      $pmEntity = new PluginMonitoringEntity();

      $entities_id = $entity->add(array(
         'name'        => 'entityA',
         'entities_id' => 0,
         'comment'     => ''
      ));
      $pmEntity->add(array(
         'entities_id' => $entities_id,
         'tag' => 'tagA'
      ));

      $entities_id = $entity->add(array(
         'name'        => 'entityA2',
         'entities_id' => 0,
         'comment'     => ''
      ));
      $pmEntity->add(array(
         'entities_id' => $entities_id,
         'tag' => 'tagA'
      ));

      $entities_id = $entity->add(array(
         'name'        => 'entityB',
         'entities_id' => 0,
         'comment'     => ''
      ));
      $pmEntity->add(array(
         'entities_id' => $entities_id,
         'tag' => 'tagB'
      ));

      $entities_id = $entity->add(array(
         'name'        => 'entityC',
         'entities_id' => 0,
         'comment'     => ''
      ));
      $pmEntity->add(array(
         'entities_id' => $entities_id,
         'tag' => ''
      ));

      $ent_list = $pmEntity->getEntitiesByTag('tagA');

      $a_reference = array(
         1 => 1,
         2 => 2
      );

      $this->assertEquals($a_reference, $ent_list, "May have 2 entities");
   }


   /**
    * @depends test2Entities_tagA
    */
   public function test2Entities_tagB() {

      $pmEntity = new PluginMonitoringEntity();

      $ent_list = $pmEntity->getEntitiesByTag('tagB');

      $a_reference = array(
         3 => 3
      );

      $this->assertEquals($a_reference, $ent_list, "May have 1 entity");
   }


   /**
    * @depends test2Entities_tagA
    */
   public function test2Entities_notag() {

      $pmEntity = new PluginMonitoringEntity();

      $ent_list = $pmEntity->getEntitiesByTag('');

      $a_reference = array(
         -1 => -1
      );

      $this->assertEquals($a_reference, $ent_list, "May have 1 entity");
   }


   /**
    * @depends test2Entities_tagA
    */
   public function test2Entities_tagnotexist() {

      $pmEntity = new PluginMonitoringEntity();

      $ent_list = $pmEntity->getEntitiesByTag('tagxx');

      $a_reference = array();

      $this->assertEquals($a_reference, $ent_list, "May have 0 entity");
   }

}

?>
