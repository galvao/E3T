<?php

declare(strict_types = 1);

namespace E3T;

use \E3T\Enumeration\{
    FontWeight,
    BgColor,
    FgColor,
};

class Text
{
    public static function out(string $output,
        FontWeight $weight = FontWeight::Regular,
        BgColor $bg = BgColor::Black,
        FgColor $fg = FgColor::White) {
        return sprintf("\e[%d;%d;%dm%s\e[0m", $weight->value, $fg->value, $bg->value, $output);
    }
}
