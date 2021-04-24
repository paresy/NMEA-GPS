<?php

namespace BultonFr\NMEA\Utils;

/**
 * Tools to convert NMEA coordinates
 * 
 * @package BultonFr\NMEA
 * @author Vermeulen Maxime <bulton.fr@gmail.com>
 */
class Coordinates
{
    /**
     * Convert coordinate to degree format
     * 
     * @param string $data Data readed by parser
     * @param string $direction (default null) Direction of the coordinate
     * @param string $isLongitude (default false) If is for longitude
     * @param boolean $toString (default false) Return format
     * 
     * @return \stdClass|string Change with $toString parameter value
     */
    public static function convertGPSDataToDegree(
        $data,
        $direction = null,
        $isLongitude = false,
        $toString = false
    ) {
        $dotPosition  = strpos($data, '.');
        $degreeEndPos = ($isLongitude === false) ? 2 : 3;
        
        $obj = (object) [
            'degree' => (int) substr($data, 0, $degreeEndPos),
            'minute' => (int) substr($data, $degreeEndPos, $dotPosition),
            'second' => (int) substr($data, $dotPosition+1)
        ];
        
        if ($toString === false) {
            return $obj;
        }
        
        return $obj->degree.'° '.$obj->minute.'\' '.$obj->second.'" '.$direction;
    }
    
    /**
     * Convert coordinate to decimal format
     * 
     * @param string $data Data readed by parser
     * @param string $isLongitude (default false) If is for longitude
     * 
     * @return float
     */
    public static function convertGPSDataToDec($data, $isLongitude = false)
    {
        $obj = static::convertGPSDataToDegree($data, null, $isLongitude);
        
        /**
         * DD = d + (min/60) + (sec/3600)
         * @link http://www.latlong.net/degrees-minutes-seconds-to-decimal-degrees
         */
        return ($obj->degree) + ($obj->minute / 60) + ($obj->second / 3600);
    }
}
