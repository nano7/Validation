<?php

if (! function_exists('cookie')) {
    /**
     * @param null $key
     * @param null $default
     * @return \Nano7\Http\CookieManager|string|mixed
     */
    function cookie($key = null, $default = null)
    {
        $cookie = app('cookie');

        if (is_null($key)) {
            return $cookie;
        }

        return $cookie->get($key, $default);
    }
}