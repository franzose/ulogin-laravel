<?php namespace Franzose\UloginLaravel\Validators;

use Config;
use Validator;

class UloginResponseValidator {

    /**
     * Data to validate.
     *
     * @var array
     */
    protected $data;

    /**
     * Validation rules.
     *
     * @var array
     */
    protected $rules;

    /**
     * Validation error messages.
     *
     * @var $errors
     */
    protected $errors;

    public function __construct()
    {
        $table = Config::get('ulogin-laravel::config.db_table');

        $this->rules = array(
            'network'   => 'required|max:255',
            'identity'  => 'required|max:255|unique:' . $table,
            'email'     => 'required|unique:' . $table . '|unique:users',
        );

        $this->messages = array(
            'email.unique' => trans('ulogin-laravel::ulogin-laravel.email_already_registered'),
        );
    }

    /**
     * Set data for further validation.
     *
     * @param array $data
     * @return $this
     */
    public function with(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Indicates if validation is passing.
     *
     * @return bool
     */
    public function passes()
    {
        $validator = Validator::make($this->data, $this->rules, $this->messages);
        $this->errors = $validator->errors();

        return $validator->passes();
    }

    /**
     * Get validation errors.
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
} 