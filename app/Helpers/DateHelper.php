<?php

namespace App\Helpers;

class DateHelper
{
    /**
     * Chuyển đổi tên ngày trong tuần theo ngôn ngữ
     */
    public static function translateDays($days, $locale = null)
    {
        if (! $locale) {
            $locale = app()->getLocale();
        }

        $dayTranslations = [
            'vi' => [
                'Monday' => 'Thứ 2',
                'Tuesday' => 'Thứ 3',
                'Wednesday' => 'Thứ 4',
                'Thursday' => 'Thứ 5',
                'Friday' => 'Thứ 6',
                'Saturday' => 'Thứ 7',
                'Sunday' => 'Chủ nhật',
            ],
            'en' => [
                'Monday' => 'Monday',
                'Tuesday' => 'Tuesday',
                'Wednesday' => 'Wednesday',
                'Thursday' => 'Thursday',
                'Friday' => 'Friday',
                'Saturday' => 'Saturday',
                'Sunday' => 'Sunday',
            ],
            'zh' => [
                'Monday' => '星期一',
                'Tuesday' => '星期二',
                'Wednesday' => '星期三',
                'Thursday' => '星期四',
                'Friday' => '星期五',
                'Saturday' => '星期六',
                'Sunday' => '星期日',
            ],
        ];

        if (! isset($dayTranslations[$locale])) {
            $locale = 'en';
        }

        $translatedDays = [];
        foreach ($days as $day) {
            $translatedDays[] = $dayTranslations[$locale][$day] ?? $day;
        }

        return $translatedDays;
    }

    /**
     * Chuyển đổi tên ngày trong tuần từ string sang array và translate
     */
    public static function translateDaysString($daysString, $locale = null)
    {
        if (! $daysString) {
            return [];
        }

        $days = is_array($daysString) ? $daysString : explode(', ', $daysString);

        return self::translateDays($days, $locale);
    }
}
