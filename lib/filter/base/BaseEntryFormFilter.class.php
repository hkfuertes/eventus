<?php

/**
 * Entry filter form base class.
 *
 * @package    PhpProject1
 * @subpackage filter
 * @author     Your name here
 */
abstract class BaseEntryFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'event_id' => new sfWidgetFormPropelChoice(array('model' => 'Event', 'add_empty' => true)),
      'time'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'act'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'event_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Event', 'column' => 'id')),
      'time'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'act'      => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('entry_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Entry';
  }

  public function getFields()
  {
    return array(
      'id'       => 'Number',
      'event_id' => 'ForeignKey',
      'time'     => 'Date',
      'act'      => 'Text',
    );
  }
}
