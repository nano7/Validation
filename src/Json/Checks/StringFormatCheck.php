<?php namespace Nano7\Validation\Json\Checks;

use Nano7\Foundation\Support\Str;

trait StringFormatCheck
{
    /**
     * @param $formatName
     * @param $callback
     * @return void
     */
    public static function format($formatName, $callback)
    {
        static::$formats[$formatName] = $callback;
    }

    /**
     * Check string format.
     *
     * @param $entity
     * @param $schema
     * @param $entityName
     * @return bool
     */
    protected function checkFormat($entity, $schema, $entityName)
    {
        // Verificar se foi definido o check format.
        if (!isset($schema->format)) {
            return true;
        }

        $format = $schema->format;

        // Vareificar se format foi implementado por metodo
        $method = sprintf('format%s', Str::studly($format));
        if (method_exists($this, $method)) {
            // Validar format by method
            if (! call_user_func_array([$this, $method], [$entity])) {
                return $this->error($entityName, "Value must match format [$schema->format]");
            }

            return true;
        }

        // Verificar se format foi implementado por extend
        if (array_key_exists($format, static::$formats)) {
            if (! call_user_func_array(static::$formats[$format], [$entity])) {
                return $this->error($entityName, "Value must match format [$schema->format]");
            }
        }


        return $this->error($entityName, "Format [$schema->format] not implemented");
    }

    /**
     * Format: datetime.
     * @param $entity
     * @return bool
     */
    public function formatDateTime($entity)
    {
        return preg_match('#^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}Z$#', $entity);
    }

    /**
     * Format: date.
     * @param $entity
     * @return bool
     */
    public function formatDate($entity)
    {
        return preg_match('#^\d{4}-\d{2}-\d{2}$#', $entity);
    }

    /**
     * Format: time.
     * @param $entity
     * @return bool
     */
    public function formatTime($entity)
    {
        return preg_match('#^\d{2}:\d{2}:\d{2}$#', $entity);
    }

    /**
     * Format: utc-millisec.
     * @param $entity
     * @return bool
     */
    public function formatUtcMillisec($entity)
    {
        return ($entity >= 0);
    }

    /**
     * Format: style.
     * @param $entity
     * @return bool
     */
    public function formatStyle($entity)
    {
        return preg_match('#(\.*?)[ ]?:[ ]?(.*?)#', $entity);
    }

    /**
     * Format: phone.
     * @param $entity
     * @return bool
     */
    public function formatPhone($entity)
    {
        return preg_match('#^[0-9\-+ \(\)]*$#', $entity);
    }

    /**
     * Format: URI.
     * @param $entity
     * @return bool
     */
    public function formatUri($entity)
    {
        return preg_match('#^[A-Za-z0-9:/;,\-_\?&\.%\+\|\#=]*$#', $entity);
    }

    /**
     * Format: email.
     * @param $entity
     * @return bool
     */
    public function formatEmail($entity)
    {
        return filter_var($entity, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Format: IP.
     * @param $entity
     * @return bool
     */
    public function formatIp($entity)
    {
        return filter_var($entity, FILTER_VALIDATE_IP) !== false;
    }

    /**
     * Format: Uri Level (folder, subdomain).
     * @param $entity
     * @return bool
     */
    public function formatUrilevel($entity)
    {
        return preg_match('/^[a-zA-Z0-9_-]+$/', $entity);
    }

    /**
     * Format: Language.
     * @param $entity
     * @return bool
     */
    public function formatLanguage($entity)
    {
        return preg_match('/^[a-z]{2}(?:_[a-zA-Z]{2})?$/', $entity);
    }
}