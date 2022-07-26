<?php

/**
 *   _   __ __ _____ _____ ___  ____  _____
 *  | | / // // ___//_  _//   ||  __||_   _|
 *  | |/ // /(__  )  / / / /| || |     | |
 *  |___//_//____/  /_/ /_/ |_||_|     |_|
 * @link https://vistart.me/
 * @copyright Copyright (c) 2016 - 2022 vistart
 * @license https://vistart.me/license/
 */

namespace rhosocial\base\helpers;

use DateTime;
use DateTimeZone;

/**
 * Class BaseTimezone
 * @package rhosocial\base\helpers
 * @version 1.0
 * @author vistart <i@vistart.me>
 */
class BaseTimezone
{
    /**
     * Generate Time Zone List.
     * @param int $region
     * @return array
     */
    public static function generateList($region = DateTimeZone::ALL)
    {
        $regions = array(
            DateTimeZone::AFRICA,
            DateTimeZone::AMERICA,
            DateTimeZone::ANTARCTICA,
            DateTimeZone::ASIA,
            DateTimeZone::ATLANTIC,
            DateTimeZone::AUSTRALIA,
            DateTimeZone::EUROPE,
            DateTimeZone::INDIAN,
            DateTimeZone::PACIFIC,
        );
        if ($region !== DateTimeZone::ALL && !in_array($region, $regions)) {
            $region = DateTimeZone::ALL;
        }
        $timezones = array();
        if ($region == DateTimeZone::ALL) {
            foreach ($regions as $r) {
                $timezones = array_merge($timezones, DateTimeZone::listIdentifiers($r));
            }
        } else {
            $timezones = DateTimeZone::listIdentifiers($region);
        }

        $timezone_offsets = array();
        foreach ($timezones as $timezone) {
            $tz = new DateTimeZone($timezone);
            $timezone_offsets[$timezone] = $tz->getOffset(new DateTime);
        }
        asort($timezone_offsets);

        $timezone_list = array();
        foreach ($timezone_offsets as $timezone => $offset) {
            $offset_prefix = $offset < 0 ? '-' : '+';
            $offset_formatted = gmdate('H:i', abs($offset));
            $pretty_offset = "UTC${offset_prefix}${offset_formatted}";
            $t = new DateTimeZone($timezone);
            $c = new DateTime(null, $t);
            $timezone_list[$timezone] = $pretty_offset . " - " . $timezone;
        }
        return $timezone_list;
    }
}
