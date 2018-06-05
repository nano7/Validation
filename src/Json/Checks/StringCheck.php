<?php namespace Nano7\Validation\Json\Checks;

trait StringCheck
{
    /**
     * Check type string.
     *
     * @param $entity
     * @param $schema
     * @param $entityName
     * @return bool
     */
    protected function checkTypeString($entity, $schema, $entityName)
    {
        // Verificar se eh um objeto
        if (! is_string($entity)) {
            $this->error($entityName, "Expected to be an string");
            return false;
        }

        $this->checkPattern($entity, $schema, $entityName);
        $this->checkMinLength($entity, $schema, $entityName);
        $this->checkMaxLength($entity, $schema, $entityName);
        $this->checkFormat($entity, $schema, $entityName); // Usa o definido no string format
        $this->checkEnum($entity, $schema, $entityName); // Usa o definido no Array
        //$this->checkDisallow($entity, $schema, $entityName);

        return true;
    }

    /**
     * @param $entity
     * @param $schema
     * @param $entityName
     * @return bool
     */
    protected function checkPattern($entity, $schema, $entityName)
    {
        // Verificar se check pattern foi definido
        if (! (isset($schema->pattern) && $schema->pattern)) {
            return true;
        }

        // Validar pattern
        if (! preg_match($schema->pattern, $entity)) {
            return $this->error($entityName, "String does not match pattern [$schema->pattern]");
        }

        return true;
    }

    /**
     * @param $entity
     * @param $schema
     * @param $entityName
     * @return bool
     */
    protected function checkMinLength($entity, $schema, $entityName)
    {
        // Verificar se check minLength foi definido
        if (! (isset($schema->minLength) && $schema->minLength)) {
            return true;
        }

        // Validar strlen
        if (strlen($entity) < $schema->minLength) {
            return $this->error($entityName, "String too short, minimum length is [$schema->minLength]");
        }

        return true;
    }

    /**
     * @param $entity
     * @param $schema
     * @param $entityName
     * @return bool
     */
    protected function checkMaxLength($entity, $schema, $entityName)
    {
        // Verificar se check maxLength foi definido
        if (! (isset($schema->maxLength) && $schema->maxLength)) {
            return true;
        }

        // Validar strlen
        if (strlen($entity) > $schema->maxLength) {
            return $this->error($entityName, "String too long, maximum length is [$schema->maxLength]");
        }

        return true;
    }
}