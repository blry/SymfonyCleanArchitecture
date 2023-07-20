<?php

declare(strict_types=1);

namespace App\Domain\Common\Service;

/**
 * @author Alexandru Sterpu <alexander.sterpu@gmail.com>
 */
class NumberShortener
{
    const BASE = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    public static function to10($num) {
        $limit = strlen($num);
        $res = strpos(self::BASE, $num[0]);

        for($i=1; $i < $limit; $i++) {
            $res = 62 * $res + strpos(self::BASE, $num[$i]);
        }

        return $res;
    }

    public static function toBase62($num): string {
        $res = '';
        $q = $num;
        do {
            $res = self::BASE[$q % 62] . $res;
        } while ($q = floor($q / 62));

        return $res;
    }
}
