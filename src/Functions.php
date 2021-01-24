<?php

namespace app;

class Functions
{
    public static $pathToPublic = __DIR__ . '/public/';

    public static function getIcon(string $icon, bool $inline = false)
    {
        echo '<span class="iconify"  data-icon="' . $icon . '" data-inline="' . $inline . '"></span>';
    }

    public static function getMonthLabel(int $number)
    {

        if ($number < 1 || $number > 11) {
            return null;
        }

        $months = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mai', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        return $months[$number];
    }
}
