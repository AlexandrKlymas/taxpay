<?php

namespace EvolutionCMS\Main\Services\CarInsurance;

class SessionHelper
{
    protected $sessionVar = 'osago_car_info';

    public function merge(array $array)
    {
        $_SESSION[$this->sessionVar] = array_merge_recursive($_SESSION[$this->sessionVar],$array);
    }
    public function set(array $array)
    {
        $_SESSION[$this->sessionVar][array_key_first($array)] = $array[array_key_first($array)];
    }
    public function get(string $key=null)
    {
        if(!empty($key)){
            return $_SESSION[$this->sessionVar][$key]??'';
        }

        return $_SESSION[$this->sessionVar]??[];
    }
    public function has(string $key): bool
    {
        return !empty($_SESSION[$this->sessionVar][$key]);
    }
    public function clear()
    {
        unset($_SESSION[$this->sessionVar]);
    }
}