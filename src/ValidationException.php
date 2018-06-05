<?php namespace Nano7\Validation;

class ValidationException extends \Exception
{
    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @param string $message
     * @param null $code
     * @param \Exception|null $previous
     * @param array $errors
     */
    public function __construct($message, $code = null, $previous = null, array $errors = [])
    {
        parent::__construct($message, $code, $previous);

        $this->errors;
    }

    /**
     * @param null $key
     * @return array|null
     */
    public function getErrors($key = null)
    {
        if (is_null($key)) {
            return $this->errors;
        }

        return array_key_exists($key, $this->errors) ? $this->errors[$key] : null;
    }
}