<?php

/**
 * Event filter form base class.
 *
 * @package    PhpProject1
 * @subpackage filter
 * @author     Your name here
 */
abstract class BaseEventFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'               => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'place'              => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'date'               => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'key'                => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'event_type_id'      => new sfWidgetFormPropelChoice(array('model' => 'EventType', 'add_empty' => true)),
      'created_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'admin_id'           => new sfWidgetFormPropelChoice(array('model' => 'sfGuardUser', 'add_empty' => true)),
      'active'             => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'participation_list' => new sfWidgetFormPropelChoice(array('model' => 'sfGuardUser', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'name'               => new sfValidatorPass(array('required' => false)),
      'place'              => new sfValidatorPass(array('required' => false)),
      'date'               => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'key'                => new sfValidatorPass(array('required' => false)),
      'event_type_id'      => new sfValidatorPropelChoice(array('required' => false, 'model' => 'EventType', 'column' => 'id')),
      'created_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'admin_id'           => new sfValidatorPropelChoice(array('required' => false, 'model' => 'sfGuardUser', 'column' => 'id')),
      'active'             => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'participation_list' => new sfValidatorPropelChoice(array('model' => 'sfGuardUser', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('event_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function addParticipationListColumnCriteria(Criteria $criteria, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $criteria->addJoin(ParticipationPeer::EVENT_ID, EventPeer::ID);

    $value = array_pop($values);
    $criterion = $criteria->getNewCriterion(ParticipationPeer::USER_ID, $value);

    foreach ($values as $value)
    {
      $criterion->addOr($criteria->getNewCriterion(ParticipationPeer::USER_ID, $value));
    }

    $criteria->add($criterion);
  }

  public function getModelName()
  {
    return 'Event';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'name'               => 'Text',
      'place'              => 'Text',
      'date'               => 'Date',
      'key'                => 'Text',
      'event_type_id'      => 'ForeignKey',
      'created_at'         => 'Date',
      'admin_id'           => 'ForeignKey',
      'active'             => 'Boolean',
      'participation_list' => 'ManyKey',
    );
  }
}
