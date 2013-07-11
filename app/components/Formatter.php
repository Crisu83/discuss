<?php

class Formatter extends CFormatter
{
    /**
     * Displays the time difference (e.g. # hours ago).
     * @param string $value a timestamp.
     * @return string the time difference.
     */
    public function formatTimeAgo($value)
    {
        if (empty($value))
            return '';

        $now = new DateTime(sqlDateTime());
        $then = new DateTime(sqlDateTime(strtotime($value)));
        $diff = $now->diff($then);

        $days = $diff->format('%d');
        $hours = $diff->format('%h');
        $minutes = $diff->format('%i');

        if ($days > 1)
            $timeAgo = dateFormatter()->formatDateTime($value, 'medium', 'short');
        else if ($days == 1)
            $timeAgo = t('format', 'Eilen');
        else if ($hours > 0)
            $timeAgo = t('format', '{n} tunti sitten|{n} tuntia sitten', $hours);
        else if ($minutes > 0)
            $timeAgo = t('format', '{n} minuutti sitten|{n} minuuttia sitten', $minutes);
        else
            $timeAgo = t('format', 'juuri nyt');

        return $timeAgo;
    }
}