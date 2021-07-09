<?php

namespace kirillemko\yci\models\request;

use yii\base\Model;

function isAssoc(array $arr)
{
    if (array() === $arr) return false;
    return array_keys($arr) !== range(0, count($arr) - 1);
}

class RequestModel extends Model
{
    /** @var  Model */
    private $model;

    public function __construct($config = [], $autoLoad=true)
    {
        parent::__construct($config);
        if( $autoLoad ){
            $this->load();
        }
    }


    public function getModel()
    {
        return $this->model;
    }

    public function initModel(Model &$model)
    {
        $fields = $this->getModelFields()['model'];

        foreach ($fields as $field) {
            $model->{$field} = $this->model->{$field};
        }
    }

    protected function models()
    {
        return [];
    }

    protected function modelFields()
    {
        return [];
    }

    private function getModelFields()
    {
        $model_fields = $this->modelFields();

        if (empty($model_fields)) {
            return [];
        }

        if (!isAssoc($model_fields)) {
            $model_fields = ['model' => $model_fields];
        }

        return $model_fields;
    }

    public function load($data=null, $formName = null, $isJson=true)
    {
        $ci = &get_instance();
        if( $isJson ){
            $data = json_decode($ci->security->xss_clean($ci->input->raw_input_stream), true);
        } else {
            $data = $_REQUEST;
        }
        $models = $this->models();

        if (!is_array($models)) {
            $this->model = \Yii::createObject($models);
        } else {
            foreach ($models as $model_field => $model_class) {
                $this->{$model_field} = \Yii::createObject($model_class);
            }
        }

        foreach ($this->getModelFields() as $model_field => $model_fields) {
            foreach ($model_fields as $field) {
                $this->{$model_field}->{$field} = isset($data[$field]) ? $data[$field] : '';
            }
        }

        $result = parent::load($data, '');

        $this->validate();

        return $result;
    }


    public function validate($attributeNames = null, $clearErrors = true)
    {
        $result = parent::validate($attributeNames, $clearErrors);

        foreach ($this->getModelFields() as $model_field => $model_fields) {

            /** @var Model $model */
            $model = $this->{$model_field};

            if (!$model->validate($model_fields)) {

                foreach ($model->getErrors() as $error_field => $field_errors) {
                    foreach ($field_errors as $error) {
                        $this->addError($error_field, $error);
                    }
                }

                $result = false;
            }
        }

        if (!$result) {
            $errors = $this->getErrors();

            $ex = new InvalidRequestException();

            foreach ($errors as $field => $field_errors) {
                foreach ($field_errors as $error) {
                    $ex->addError(new RequestError($field, $error));
                }
            }

            throw $ex;
        }
    }
}