<?php namespace Nano7\Validation\Json\Checks;

trait ArrayCheck
{
    /**
     * Check type array.
     *
     * @param $entity
     * @param $schema
     * @param $entityName
     * @return bool
     */
    protected function checkTypeArray($entity, $schema, $entityName)
    {
        // Verificar se eh um array
        if (! is_array($entity)) {
            return $this->error($entityName, "Expected to be an array");
        }

        $this->checkMinItems($entity, $schema, $entityName);
        $this->checkMaxItems($entity, $schema, $entityName);
        $this->checkUniqueItems($entity, $schema, $entityName);
        $this->checkEnum($entity, $schema, $entityName);
        $this->checkItems($entity, $schema, $entityName);
        //$this->checkDisallow($entity, $schema, $entityName);

        return true;
    }

    /**
     * @param $entity
     * @param $schema
     * @param $entityName
     * @return bool
     */
    protected function checkMinItems($entity, $schema, $entityName)
    {
        // Verificar se check minItems foi definido
        if (! (isset($schema->minItems) && $schema->minItems)) {
            return true;
        }

        // Verificar a qtdade de itens do array
        if (count($entity) < $schema->minItems) {
            return $this->error($entityName, "Not enough array items, minimum is [$schema->minItems]");
        }

        return true;
    }

    /**
     * @param $entity
     * @param $schema
     * @param $entityName
     * @return bool
     */
    protected function checkMaxItems($entity, $schema, $entityName)
    {
        // Verificar se check maxItems foi definido
        if (! (isset($schema->maxItems) && $schema->maxItems)) {
            return true;
        }

        // Verificar a qtdade de itens do array
        if (count($entity) > $schema->maxItems) {
            return $this->error($entityName, "Too many array items, maximum is [$schema->minItems]");
        }

        return true;
    }

    /**
     * @param $entity
     * @param $schema
     * @param $entityName
     * @return bool
     */
    protected function checkUniqueItems($entity, $schema, $entityName)
    {
        // Verificar se check uniqueItems foi definido
        if (! (isset($schema->uniqueItems) && $schema->uniqueItems)) {
            return true;
        }

        // Verificar se neao ah itens repetidos
        if (count(array_unique($entity)) != count($entity)) {
            return $this->error($entityName, "All items in array must be unique");
        }

        return true;
    }

    /**
     * @param $entity
     * @param $schema
     * @param $entityName
     * @return bool
     */
    protected function checkEnum($entity, $schema, $entityName)
    {
        // Verificar se check enum foi definido
        if (! (isset($schema->enum) && $schema->enum)) {
            return true;
        }

        // Verificar se enum foi definido como um array de opcoes
        if (! is_array($schema->enum)) {
            return $this->error($entityName, "Enum property must be an array");
        }

        $str_enums = implode(',', $schema->enum);

        // Se entity for array verificar todos os itens na lista de enums
        if (is_array($entity)) {
            foreach ($entity as $val) {
                if (!in_array($val, $schema->enum)) {
                    return $this->error($entityName, "Invalid value(s), allowable values are [$str_enums]");
                }
            }
        }

        // Caso seja um valor unico validar valor com a lista de enums
        if (!in_array($entity, $schema->enum)) {
            return $this->error($entityName, "Invalid value(s), allowable values are [$str_enums]");
        }

        return true;
    }

    /**
     * @param $entity
     * @param $schema
     * @param $entityName
     * @return bool
     */
    protected function checkItems($entity, $schema, $entityName)
    {
        // Verificar se check items foi definido
        if (! (isset($schema->items) && $schema->items)) {
            return true;
        }

        // Verificar items for do tipo object
        if (is_object($schema->items)) {
            foreach($entity as $index => $node) {
                $nodeEntityName = $entityName . '[' . $index . ']';
                if (! $this->validateTypes($node, $schema->items, $nodeEntityName)) {
                    return false;
                }
            }

            return true;
        }

        // Verificar items for do tipo object
        if (is_array($schema->items)) {
            foreach($entity as $index => $node) {
                $nodeEntityName = $entityName . '[' . $index . ']';

                // Check if the item passes any of the item validations
                $nodeValid = true;
                foreach($schema->items as $item) {
                    if (! $this->validateTypes($node, $item, $nodeEntityName)) {
                        $nodeValid = false;
                        break;
                    }
                }

                // If item did not pass any item validations
                if (!$nodeValid) {
                    $allowedTypes = array_map(function($item){
                        return $item->type == 'object' ? 'object (schema)' : $item->type;
                    }, $schema->items);
                    $allowedTypes = implode(', ' , $allowedTypes);

                    return $this->error($nodeEntityName, "Invalid value, must be one of the following types: [$allowedTypes]");
                }
            }
        }

        return $this->error($entityName, "Invalid items value");
    }
}