<?php


/**
 * This class defines the structure of the 'events' table.
 *
 *
 * This class was autogenerated by Propel 1.4.2 on:
 *
 * Tue Oct 14 17:19:37 2014
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    lib.model.map
 */
class EventTableMap extends TableMap {

	/**
	 * The (dot-path) name of this class
	 */
	const CLASS_NAME = 'lib.model.map.EventTableMap';

	/**
	 * Initialize the table attributes, columns and validators
	 * Relations are not initialized by this method since they are lazy loaded
	 *
	 * @return     void
	 * @throws     PropelException
	 */
	public function initialize()
	{
	  // attributes
		$this->setName('events');
		$this->setPhpName('Event');
		$this->setClassname('Event');
		$this->setPackage('lib.model');
		$this->setUseIdGenerator(true);
		// columns
		$this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
		$this->addColumn('NAME', 'Name', 'VARCHAR', true, 255, null);
		$this->addColumn('PLACE', 'Place', 'VARCHAR', true, 255, null);
		$this->addColumn('DATE', 'Date', 'TIMESTAMP', false, null, null);
		$this->addColumn('KEY', 'Key', 'VARCHAR', true, 255, null);
		$this->addForeignKey('EVENT_TYPE_ID', 'EventTypeId', 'INTEGER', 'events_type', 'ID', false, null, null);
		$this->addColumn('CREATED_AT', 'CreatedAt', 'TIMESTAMP', false, null, null);
		$this->addForeignKey('ADMIN_ID', 'AdminId', 'INTEGER', 'sf_guard_user', 'ID', true, null, null);
		$this->addColumn('ACTIVE', 'Active', 'BOOLEAN', false, null, true);
		// validators
	} // initialize()

	/**
	 * Build the RelationMap objects for this table relationships
	 */
	public function buildRelations()
	{
    $this->addRelation('EventType', 'EventType', RelationMap::MANY_TO_ONE, array('event_type_id' => 'id', ), null, null);
    $this->addRelation('sfGuardUser', 'sfGuardUser', RelationMap::MANY_TO_ONE, array('admin_id' => 'id', ), 'CASCADE', null);
    $this->addRelation('Entry', 'Entry', RelationMap::ONE_TO_MANY, array('id' => 'event_id', ), null, null);
    $this->addRelation('Participation', 'Participation', RelationMap::ONE_TO_MANY, array('id' => 'event_id', ), 'CASCADE', null);
    $this->addRelation('Photo', 'Photo', RelationMap::ONE_TO_MANY, array('id' => 'event_id', ), 'CASCADE', null);
	} // buildRelations()

	/**
	 * 
	 * Gets the list of behaviors registered for this table
	 * 
	 * @return array Associative array (name => parameters) of behaviors
	 */
	public function getBehaviors()
	{
		return array(
			'symfony' => array('form' => 'true', 'filter' => 'true', ),
			'symfony_behaviors' => array(),
			'symfony_timestampable' => array('create_column' => 'created_at', ),
		);
	} // getBehaviors()

} // EventTableMap
