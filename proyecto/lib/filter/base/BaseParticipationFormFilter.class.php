<?php

/**
 * Participation filter form base class.
 *
 * @package    PhpProject1
 * @subpackage filter
 * @author     Your name here
 */
abstract class BaseParticipationFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'joined_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
    ));

    $this->setValidators(array(
      'joined_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('participation_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Participation';
  }

  public function getFields()
  {
    return array(
      'user_id'   => 'ForeignKey',
      'event_id'  => 'ForeignKey',
      'joined_at' => 'Date',
    );
  }
}
