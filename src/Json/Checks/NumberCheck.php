<?php namespace Nano7\Validation\Json\Checks;

trait NumberCheck
{
    /**
     * Check type integer.
     *
     * @param $entity
     * @param $schema
     * @param $entityName
     * @return bool
     */
    protected function checkTypeInteger($entity, $schema, $entityName)
    {
        // Verificar se eh um int
        if (! is_int($entity)) {
            return $this->error($entityName, "Expected to be an integer");
        }

        $this->checkMinimum($entity, $schema, $entityName);
        $this->checkMaximum($entity, $schema, $entityName);
        $this->checkExclusiveMinimum($entity, $schema, $entityName);
        $this->checkExclusiveMaximum($entity, $schema, $entityName);
        $this->checkFormat($entity, $schema, $entityName);  // Usa o definido no string format
        $this->checkEnum($entity, $schema, $entityName);  // Usa o definido no Array
        //$this->checkDisallow($entity, $schema, $entityName);
        $this->checkDivisibleBy($entity, $schema, $entityName);

        return true;
    }

    /**
     * Check type number.
     *
     * @param $entity
     * @param $schema
     * @param $entityName
     * @return bool
     */
    protected function checkTypeNumber($entity, $schema, $entityName)
    {
        // Verificar se eh um number
        if (! is_numeric($entity)) {
            return $this->error($entityName, "Expected to be an number");
        }

        $this->checkMinimum($entity, $schema, $entityName);
        $this->checkMaximum($entity, $schema, $entityName);
        $this->checkExclusiveMinimum($entity, $schema, $entityName);
        $this->checkExclusiveMaximum($entity, $schema, $entityName);
        $this->checkFormat($entity, $schema, $entityName);  // Usa o definido no string format
        $this->checkEnum($entity, $schema, $entityName);  // Usa o definido no Array
        //$this->checkDisallow($entity, $schema, $entityName);
        $this->checkDivisibleBy($entity, $schema, $entityName);

        return true;
    }

    /**
     * @param $entity
     * @param $schema
     * @param $entityName
     * @return bool
     */
    protected function checkMinimum($entity, $schema, $entityName)
    {
        // Verificar se check minimum foi definido
        if (! (isset($schema->minimum) && $schema->minimum)) {
            return true;
        }

        // Verificar valor
        if ($entity < $schema->minimum) {
            return $this->error($entityName, "Invalid value, minimum is [$schema->minimum]");
        }

        return true;
    }

    /**
     * @param $entity
     * @param $schema
     * @param $entityName
     * @return bool
     */
    protected function checkMaximum($entity, $schema, $entityName)
    {
        // Verificar se check maximum foi definido
        if (! (isset($schema->maximum) && $schema->maximum)) {
            return true;
        }

        // Verificar valor
        if ($entity > $schema->maximum) {
            return $this->error($entityName, "Invalid value, maximum is [$schema->maximum]");
        }

        return true;
    }

    /**
     * @param $entity
     * @param $schema
     * @param $entityName
     * @return bool
     */
    protected function checkExclusiveMinimum($entity, $schema, $entityName)
    {
        // Verificar se check minimum e exclusiveMinimum foi definido
        if (! (isset($schema->minimum) && isset($schema->exclusiveMinimum) && $schema->exclusiveMinimum)) {
            return true;
        }

        // Verificar valor
        if ($entity == $schema->minimum) {
            return $this->error($entityName, "Invalid value, must be greater than [$schema->minimum]");
        }

        return true;
    }

    /**
     * @param $entity
     * @param $schema
     * @param $entityName
     * @return bool
     */
    protected function checkExclusiveMaximum($entity, $schema, $entityName)
    {
        // Verificar se check maximum e exclusiveMinimum foi definido
        if (! (isset($schema->maximum) && isset($schema->exclusiveMaximum) && $schema->exclusiveMaximum)) {
            return true;
        }

        // Verificar valor
        if ($entity == $schema->maximum) {
            return $this->error($entityName, "Invalid value, must be less than [$schema->maximum]");
        }

        return true;
    }


    /**
     * @param $entity
     * @param $schema
     * @param $entityName
     * @return bool
     */
    protected function checkDivisibleBy($entity, $schema, $entityName)
    {
        // Verificar se check divisibleBy foi definido
        if (! (isset($schema->divisibleBy) && $schema->divisibleBy)) {
            return true;
        }

        // Verificar valor
        if ($entity % $schema->divisibleBy != 0) {
            return $this->error($entityName, "Invalid value, must be divisible by [$schema->divisibleBy]");
        }

        return true;
    }
}