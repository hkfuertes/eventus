<?php

/**
 * AppToken form base class.
 *
 * @method AppToken getObject() Returns the current form's model object
 *
 * @package    PhpProject1
 * @subpackage form
 * @author     Your name here
 */
abstract class BaseAppTokenForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'token'      => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'name'       => new sfWidgetFormInputText(),
      'company'    => new sfWidgetFormInputText(),
      'os'         => new sfWidgetFormInputText(),
      'active'     => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'token'      => new sfValidatorString(array('max_length' => 255)),
      'created_at' => new sfValidatorDateTime(array('required' => false)),
      'name'       => new sfValidatorString(array('max_length' => 255)),
      'company'    => new sfValidatorString(array('max_length' => 255)),
      'os'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'active'     => new sfValidatorBoolean(array('required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorPropelUnique(array('model' => 'AppToken', 'column' => array('token')))
    );

    $this->widgetSchema->setNameFormat('app_token[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'AppToken';
  }


}
