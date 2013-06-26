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

        $years = $diff->format('%y');
        $months = $diff->format('%m');
        $days = $diff->format('%d');
        $hours = $diff->format('%h');
        $minutes = $diff->format('%i');

        if ($years > 0)
            $timeAgo = t('format', '{n} year ago|{n} years ago', $years);
        else if ($months > 0)
            $timeAgo = t('format', '{n} month ago|{n} months ago', $months);
        else if ($days > 0)
            $timeAgo = t('format', '{n} day ago|{n} days ago', $days);
        else if ($hours > 0)
            $timeAgo = t('format', '{n} hour ago|{n} hours ago', $hours);
        else if ($minutes > 0)
            $timeAgo = t('format', '{n} minute ago|{n} minutes ago', $minutes);
        else
            $timeAgo = t('format', 'just now');

        return $timeAgo;
    }
}