<?php

/**
 * Photo filter form base class.
 *
 * @package    PhpProject1
 * @subpackage filter
 * @author     Your name here
 */
abstract class BasePhotoFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id'     => new sfWidgetFormPropelChoice(array('model' => 'sfGuardUser', 'add_empty' => true)),
      'event_id'    => new sfWidgetFormPropelChoice(array('model' => 'Event', 'add_empty' => true)),
      'uploaded_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'title'       => new sfWidgetFormFilterInput(),
      'filename'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'path'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'visible'     => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'deleted'     => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'user_id'     => new sfValidatorPropelChoice(array('required' => false, 'model' => 'sfGuardUser', 'column' => 'id')),
      'event_id'    => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Event', 'column' => 'id')),
      'uploaded_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'title'       => new sfValidatorPass(array('required' => false)),
      'filename'    => new sfValidatorPass(array('required' => false)),
      'path'        => new sfValidatorPass(array('required' => false)),
      'visible'     => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'deleted'     => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('photo_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Photo';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'user_id'     => 'ForeignKey',
      'event_id'    => 'ForeignKey',
      'uploaded_at' => 'Date',
      'title'       => 'Text',
      'filename'    => 'Text',
      'path'        => 'Text',
      'visible'     => 'Boolean',
      'deleted'     => 'Boolean',
    );
  }
}
