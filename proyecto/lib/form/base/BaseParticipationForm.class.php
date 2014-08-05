<?php

/**
 * Participation form base class.
 *
 * @method Participation getObject() Returns the current form's model object
 *
 * @package    PhpProject1
 * @subpackage form
 * @author     Your name here
 */
abstract class BaseParticipationForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id'   => new sfWidgetFormInputHidden(),
      'event_id'  => new sfWidgetFormInputHidden(),
      'joined_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'user_id'   => new sfValidatorPropelChoice(array('model' => 'sfGuardUser', 'column' => 'id', 'required' => false)),
      'event_id'  => new sfValidatorChoice(array('choices' => array($this->getObject()->getEventId()), 'empty_value' => $this->getObject()->getEventId(), 'required' => false)),
      'joined_at' => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('participation[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Participation';
  }


}
