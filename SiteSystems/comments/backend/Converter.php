<?php

function objectToarray($data)
{
    $array = (array)$data;

    foreach($array as $key => &$field)
        if (is_object($field))
            $field = $this->objectToarray($field);
    
    return $array;
}