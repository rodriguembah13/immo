<?php

namespace App\Utils;

class DateTime
{
    const FORMAT_DEFAULT = 'Y-m-d H:i:s';

    /**
     * @author Nadeen Nilanka <ntwobike@gmail.com>
     *
     * @param string $date
     * @param string $format
     */
    public static function getDateTimeString($date = 'now', $format = self::FORMAT_DEFAULT): string
    {
        $date = new \DateTime($date, new \DateTimeZone('UTC'));

        return $date->format($format);
    }

    /**
     * @author Nadeen Nilanka <ntwobike@gmail.com>
     *
     * @param string $date
     */
    public static function getDateTime($date = 'now'): \DateTime
    {
        return new \DateTime($date, new \DateTimeZone('UTC'));
    }
}
