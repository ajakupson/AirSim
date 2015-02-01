<?php
/**
 * Created by Andrei Jakupson
 * Date: 24.01.15
 * Time: 17:47
 */

namespace AirSim\Bundle\CoreBundle\Tools;


class Functions
{

    public static function setString($stringValue)
    {
        if($stringValue != null && trim($stringValue) != "")
        {
            return $stringValue;
        }
        else
        {
            return null;
        }
    }

    public static function setStringLike($stringValue)
    {
        if($stringValue != null && trim($stringValue) != "")
        {
            return "%".strtoupper($stringValue)."%";
        }
        else
        {
            return null;
        }
    }
}