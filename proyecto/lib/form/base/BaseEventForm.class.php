<?php

/**
 * Event form base class.
 *
 * @method Event getObject() Returns the current form's model object
 *
 * @package    PhpProject1
 * @subpackage form
 * @author     Your name here
 */
abstract class BaseEventForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'name'               => new sfWidgetFormInputText(),
      'place'              => new sfWidgetFormInputText(),
      'date'               => new sfWidgetFormDateTime(),
      'key'                => new sfWidgetFormInputText(),
      'event_type_id'      => new sfWidgetFormPropelChoice(array('model' => 'EventType', 'add_empty' => true)),
      'created_at'         => new sfWidgetFormDateTime(),
      'admin_id'           => new sfWidgetFormPropelChoice(array('model' => 'sfGuardUser', 'add_empty' => false)),
      'active'             => new sfWidgetFormInputCheckbox(),
      'participation_list' => new sfWidgetFormPropelChoice(array('multiple' => true, 'model' => 'sfGuardUser')),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorChoice(array('choices' => array($this->getObject()->getId()), 'empty_value' => $this->getObject()->getId(), 'required' => false)),
      'name'               => new sfValidatorString(array('max_length' => 255)),
      'place'              => new sfValidatorString(array('max_length' => 255)),
      'date'               => new sfValidatorDateTime(array('required' => false)),
      'key'                => new sfValidatorString(array('max_length' => 255)),
      'event_type_id'      => new sfValidatorPropelChoice(array('model' => 'EventType', 'column' => 'id', 'required' => false)),
      'created_at'         => new sfValidatorDateTime(array('required' => false)),
      'admin_id'           => new sfValidatorPropelChoice(array('model' => 'sfGuardUser', 'column' => 'id')),
      'active'             => new sfValidatorBoolean(array('required' => false)),
      'participation_list' => new sfValidatorPropelChoice(array('multiple' => true, 'model' => 'sfGuardUser', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorPropelUnique(array('model' => 'Event', 'column' => array('key')))
    );

    $this->widgetSchema->setNameFormat('event[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Event';
  }


  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['participation_list']))
    {
      $values = array();
      foreach ($this->object->getParticipations() as $obj)
      {
        $values[] = $obj->getUserId();
      }

      $this->setDefault('participation_list', $values);
    }

  }

  protected function doSave($con = null)
  {
    parent::doSave($con);

    $this->saveParticipationList($con);
  }

  public function saveParticipationList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['participation_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (null === $con)
    {
      $con = $this->getConnection();
    }

    $c = new Criteria();
    $c->add(ParticipationPeer::EVENT_ID, $this->object->getPrimaryKey());
    ParticipationPeer::doDelete($c, $con);

    $values = $this->getValue('participation_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new Participation();
        $obj->setEventId($this->object->getPrimaryKey());
        $obj->setUserId($value);
        $obj->save();
      }
    }
  }

}
