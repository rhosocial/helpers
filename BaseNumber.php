<?php

/**
 *   _   __ __ _____ _____ ___  ____  _____
 *  | | / // // ___//_  _//   ||  __||_   _|
 *  | |/ // /(__  )  / / / /| || |     | |
 *  |___//_//____/  /_/ /_/ |_||_|     |_|
 * @link https://vistart.me/
 * @copyright Copyright (c) 2016 vistart
 * @license https://vistart.me/license/
 */

namespace rhosocial\base\helpers;

/**
 * Generate Random Number with the specified length, GUID.
 *
 * @author vistart <i@vistart.me>
 */
class BaseNumber
{
    const GUID_REGEX = "/^[a-fA-F0-9]{8}-([a-fA-F0-9]{4}-){3}[a-fA-Z0-9]{12}$/";

    /**
     * Generate the guid with parameters.
     * @param boolean $need_braces Determines whether it needs both sides of braces.
     * @param boolean $lowercase Determines whether it needs to be lower case.
     * @param string If this parameter is 128-bit hexidecimal, then it will return the readable one.
     * @return string the Guid generated with parameters.
     */
    public static function guid($need_braces = false, $lowercase = false, $existed = null)
    {
        if (!$existed) {
            $existed = static::guid_bin();
        }
        $charid = '';
        for ($i = 0; $i < strlen($existed); $i++) {
            $charid .= bin2hex($existed[$i]);
        }
        $hyphen = chr(45);
        $guid = chr(123)
                .substr($charid, 0, 8) . $hyphen
                .substr($charid, 8, 4) . $hyphen
                .substr($charid, 12, 4) . $hyphen
                .substr($charid, 16, 4) . $hyphen
                .substr($charid, 20, 12) . chr(125);
        if ($need_braces === false)
        {
            $guid = (($need_braces == true) ? $guid : trim($guid, '{}'));
        }
        return ($lowercase === true ? strtolower($guid) : $guid);
    }

    /**
     * Generate raw GUID binary.
     * @param string If this parameter is readable GUID value, the corresponding
     * binary will be given. The brace is accepted.
     * @return string raw output of GUID.
     */
    public static function guid_bin($existed = null)
    {
        if (is_string($existed)) {
            $guid = trim($existed, '{}');
            if (preg_match(static::GUID_REGEX, $guid)) {
                return hex2bin(str_replace([' ', '-'], '', $guid));
            }
        }
        mt_srand(microtime(true) * 1000);
        $uniqid = uniqid(rand(), true);
        return md5($uniqid, true);
    }
    
    /**
     * Divide composited guid binary into chunk.
     * @param string $guids
     * @return array
     */
    public static function divide_guid_bin($guids)
    {
        if (!is_string($guids) || strlen($guids) == 0 || strlen($guids) % 16 > 0) {
            return [];
        }
        return str_split($guids, 16);
    }
    
    /**
     * Composited guid binary chunk into one string.
     * @param array $guids
     * @return string
     */
    public static function composite_guid($guids)
    {
        if (!is_array($guids) || empty($guids)) {
            return '';
        }
        if (is_string($guids[0]) && strlen($guids[0]) == 16) {
            return implode('', $guids);
        }
        $validGuids = static::unsetInvalidGUIDs($guids);
        $composited = '';
        foreach ($validGuids as $guid) {
            $composited .= static::guid_bin($guid);
        }
        return $composited;
    }
    
    /**
     * Unset invalid GUID element of an array.
     * @param string[] $guids original array.
     * @return string[] GUID array without invalid ones.
     */
    public static function unsetInvalidGUIDs($guids)
    {
        foreach ($guids as $key => $guid)
        {
            if (!preg_match(self::GUID_REGEX, $guid)){
                unset($guids[$key]);
            }
        }
        return $guids;
    }
    
    /**
     * Generate the random number.
     * @param string $prefix
     * @param integer $length
     * @return string Random number string.
     */
    public static function randomNumber($prefix = '4', $length = 8)
    {
        if (function_exists('random_int')) {
            $length = $length - strlen($prefix);
            $result = $prefix;
            while ($length > 0) {
                $result .= static::randomNumberUsingRandomInt('', $length > 9 ? 9 : $length);
                $length -= 9;
            }
            return $result;
        }
        $key = "";
        $pattern = '1234567890';
        for ($i = 0; $i < $length - strlen($prefix); $i++)
        {
            $key .= $pattern[mt_rand(0, 9)];
        }
        return $prefix . $key;
    }

    /**
     * Generate the random number using `random_int` method.
     * This method is only available for PHP 7 and above.
     * @param string $prefix
     * @param int $length
     * @return string
     */
    public static function randomNumberUsingRandomInt($prefix = '4', $length = 8)
    {
        $max = (int)str_pad("9", $length - strlen($prefix), "9");
        return $prefix . (string)str_pad(random_int(0, $max), $length - strlen($prefix), "0", STR_PAD_LEFT);
    }
}
