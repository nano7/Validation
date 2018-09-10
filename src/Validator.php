<?php namespace Nano7\Validation;

use Nano7\Support\Arr;
use Nano7\Validation\Json\ValidatorJson;

class Validator
{
    /**
     * @var ValidatorJson
     */
    protected $json;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->json = new ValidatorJson('', ['array_equal_object' => true]);
    }

    /**
     * @param $data
     * @param $rules
     * @return bool
     */
    public function validate($data, $rules)
    {
        $this->json->registerVirtualSchema('default', $this->makeSchema($rules));

        return $this->json->validate($data, 'default', '');
    }

    /**
     * @param null $key
     * @return array
     */
    public function getErros($key = null)
    {
        return $this->json->getErros($key);
    }

    /**
     * @param $rules
     * @return \stdClass
     */
    protected function makeSchema($rules)
    {
        $schema = new \stdClass();
        $schema->type = 'object';

        $properties = [];
        foreach ($rules as $name => $rule) {
            $properties[$name] = $this->makeSchemaRule($rule);
        }

        $schema->properties = (object) $properties;

        return $schema;
    }

    /**
     * @param $rule
     * @return object
     */
    protected function makeSchemaRule($rule)
    {
        // Tratar regras
        $list = explode('|', $rule);
        $rules = [];
        foreach ($list as $item) {
            list($rule, $params) = explode(':', $item);
            $rules[$rule] = explode(',', $params);
        }

        $attrs = [];
        $names = array_keys($rules);

        $attrs['type'] = Arr::first($names, function($value, $key) {
            return in_array($value, ['number','integer','array','boolean', 'object']);
        }, 'string');

        $attrs['required'] = in_array('required', $names);

        return (object) $attrs;
    }
}