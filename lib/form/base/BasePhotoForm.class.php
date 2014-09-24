<?php

/**
 * Photo form base class.
 *
 * @method Photo getObject() Returns the current form's model object
 *
 * @package    PhpProject1
 * @subpackage form
 * @author     Your name here
 */
abstract class BasePhotoForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'user_id'     => new sfWidgetFormPropelChoice(array('model' => 'sfGuardUser', 'add_empty' => false)),
      'event_id'    => new sfWidgetFormPropelChoice(array('model' => 'Event', 'add_empty' => false)),
      'uploaded_at' => new sfWidgetFormDateTime(),
      'title'       => new sfWidgetFormInputText(),
      'filename'    => new sfWidgetFormInputText(),
      'path'        => new sfWidgetFormInputText(),
      'visible'     => new sfWidgetFormInputCheckbox(),
      'deleted'     => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'user_id'     => new sfValidatorPropelChoice(array('model' => 'sfGuardUser', 'column' => 'id')),
      'event_id'    => new sfValidatorPropelChoice(array('model' => 'Event', 'column' => 'id')),
      'uploaded_at' => new sfValidatorDateTime(array('required' => false)),
      'title'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'filename'    => new sfValidatorString(array('max_length' => 255)),
      'path'        => new sfValidatorString(array('max_length' => 255)),
      'visible'     => new sfValidatorBoolean(array('required' => false)),
      'deleted'     => new sfValidatorBoolean(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('photo[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Photo';
  }


}
