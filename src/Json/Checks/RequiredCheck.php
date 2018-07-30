<?php namespace Nano7\Validation\Json\Checks;

trait RequiredCheck
{
    /**
     * Check required.
     *
     * @param $entity
     * @param $schema
     * @param $ruleValue
     * @return bool
     */
    protected function checkTypeRequired($entity, $schema, $ruleValue)
    {
        return ($ruleValue === true);
    }

    /**
     * Check required_with.
     *
     * O campo sob validação deve estar presente apenas se algum dos outros campos
     * especificados estiver presente.
     *
     * @param $entity
     * @param $schema
     * @param $ruleValue
     * @return bool
     */
    protected function checkTypeRequiredWith($entity, $schema, $ruleValue)
    {
        $ruleValue = (array) $ruleValue;

        foreach ($ruleValue as $attr) {
            if (isset($entity->{$attr})) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check required_with_all.
     *
     * O campo sob validação deve estar presente apenas se todos os outros campos
     * especificados estiverem presentes.
     *
     * @param $entity
     * @param $schema
     * @param $ruleValue
     * @return bool
     */
    protected function checkTypeRequiredWithAll($entity, $schema, $ruleValue)
    {
        $ruleValue = (array) $ruleValue;

        foreach ($ruleValue as $attr) {
            if (! isset($entity->{$attr})) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check required_without.
     *
     * O campo sob validação deve estar presente apenas quando algum dos outros campos
     * especificados não estiver presente.
     *
     * @param $entity
     * @param $schema
     * @param $ruleValue
     * @return bool
     */
    protected function checkTypeRequiredWithout($entity, $schema, $ruleValue)
    {
        $ruleValue = (array) $ruleValue;

        foreach ($ruleValue as $attr) {
            if (! isset($entity->{$attr})) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check required_without_all.
     *
     * O campo sob validação deve estar presente apenas quando todos os outros campos
     * especificados não estiverem presentes.
     *
     * @param $entity
     * @param $schema
     * @param $ruleValue
     * @return bool
     */
    protected function checkTypeRequiredWithoutAll($entity, $schema, $ruleValue)
    {
        $ruleValue = (array) $ruleValue;

        foreach ($ruleValue as $attr) {
            if (isset($entity->{$attr})) {
                return false;
            }
        }

        return true;
    }
}