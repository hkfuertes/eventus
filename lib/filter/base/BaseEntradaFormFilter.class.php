<?php

/**
 * Entrada filter form base class.
 *
 * @package    PhpProject1
 * @subpackage filter
 * @author     Your name here
 */
abstract class BaseEntradaFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'evento_id' => new sfWidgetFormPropelChoice(array('model' => 'Evento', 'add_empty' => true)),
      'hora'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'acto'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'evento_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Evento', 'column' => 'id')),
      'hora'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'acto'      => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('entrada_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Entrada';
  }

  public function getFields()
  {
    return array(
      'id'        => 'Number',
      'evento_id' => 'ForeignKey',
      'hora'      => 'Date',
      'acto'      => 'Text',
    );
  }
}
