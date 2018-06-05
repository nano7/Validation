<?php namespace Nano7\Validation\Json;

use Nano7\Foundation\Support\Str;
use Nano7\Foundation\Support\Filesystem;
use Nano7\Validation\Json\Checks\AnyCheck;
use Nano7\Validation\Json\Checks\ArrayCheck;
use Nano7\Validation\Json\Checks\NullCheck;
use Nano7\Validation\Json\Checks\NumberCheck;
use Nano7\Validation\Json\Checks\ObjectCheck;
use Nano7\Validation\Json\Checks\StringCheck;
use Nano7\Validation\Json\Checks\BooleanCheck;
use Nano7\Validation\Json\Checks\StringFormatCheck;

class ValidatorJson
{
    use AnyCheck;
    use NullCheck;
    use ArrayCheck;
    use StringCheck;
    use ObjectCheck;
    use NumberCheck;
    use BooleanCheck;
    use StringFormatCheck;

    /**
     * @var Filesystem
     */
    protected $files;

    /**
     * @var string
     */
    protected $schemaPath;

    /**
     * @var array
     */
    protected $schemas = [];

    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @param string $schemaPath
     */
    public function __construct($schemaPath)
    {
        $this->files = new Filesystem();
        $this->schemaPath = $schemaPath;
    }

    /**
     * Validate schema object
     *
     * @param mixed $entity
     * @param string $schemaName
     *
     * @return bool
     */
    public function validate($entity, $schemaName)
    {
        $this->errors = [];

        $this->validateTypes($entity, $this->loadSchema($schemaName), 'root');

        return (count($this->errors) == 0);
    }

    /**
     * @param null $key
     * @return array
     */
    public function getErros($key = null)
    {
        if (is_null($key)) {
            return $this->errors;
        }

        return array_key_exists($key, $this->errors) ? $this->errors[$key] : null;
    }

    /**
     * Validar lista de tipos.
     *
     * @param $entity
     * @param $schema
     * @param $entityName
     */
    protected function validateTypes($entity, $schema, $entityName)
    {
        $types = isset($schema->type) ? $schema->type : 'any';
        $types = is_array($types) ? $types : [$types];

        foreach ($types as $type) {
            $this->validateType($type, $entity, $schema, $entityName);
        }
    }

    /**
     * Validar tipo.
     *
     * @param $type
     * @param $entity
     * @param $schema
     * @param $entityName
     * @return bool
     */
    protected function validateType($type, $entity, $schema, $entityName)
    {
        $method = sprintf('checkType%s', Str::studly($type));
        if (! method_exists($this, $method)) {
            $this->error($entityName, "Property type invalid or not found [$type]");
            return false;
        }

        return call_user_func_array([$this, $method], [$entity, $schema, $entityName]);
    }

    /**
     * Validate object properties
     *
     * @param object $entity
     * @param object $schema
     * @param string $entityName
     *
     * @return bool
     */
    protected function validateProperties($entity, $schema, $entityName)
    {
        $properties = get_object_vars($entity);

        if (!isset($schema->properties)) {
            return true;
        }

        // Check defined properties
        foreach($schema->properties as $propertyName => $property) {
            if (array_key_exists($propertyName, $properties)) {
                // Check type
                $path = $entityName . '.' . $propertyName;
                $this->validateTypes($entity->{$propertyName}, $property, $path);
            } else {
                // Check required
                if (isset($property->required) && $property->required) {
                    $this->error($entityName, "Missing required property [$propertyName]");
                }
            }
        }

        // Check additional properties
        if (isset($schema->additionalProperties) && !$schema->additionalProperties) {
            $extra = array_diff(array_keys((array)$entity), array_keys((array)$schema->properties));
            if (count($extra)) {
                $this->error($entityName, sprintf('Additional properties [%s] not allowed for property', implode(',', $extra)));
            }
        }

        return true;
    }

    /**
     * Add message error in list.
     *
     * @param $entityName
     * @param $message
     * @return bool
     */
    public function error($entityName, $message)
    {
        $this->errors[$entityName][] = $message;

        return false;
    }

    /**
     * Carregar schema by name.
     *
     * @param $schemaName
     * @return \stdClass
     * @throws SchemaException
     */
    protected function loadSchema($schemaName)
    {
        // Verificar se o schema jah foi carregado
        $schema_key = strtolower($schemaName);
        if (array_key_exists($schema_key, $this->schemas)) {
            return $this->schemas[$schema_key];
        }

        // Verificar se arquivo do schema existe
        $file_schema = $this->files->combine($this->schemaPath, $schemaName . '.json');
        if (! $this->files->exists($file_schema)) {
            throw new SchemaException("Json schema file [$file_schema] not found.");
        }

        $schema = json_decode($this->files->get($file_schema));
        if (is_null($schema)) {
            throw new SchemaException("Json schema file [$file_schema] unable to parse JSON data. Sintax with error.");
        }

        return $this->schemas[$schema_key] = $schema;
    }
}