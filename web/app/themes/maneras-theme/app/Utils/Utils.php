<?php
namespace App\Utils;

use DateTime;
use App\Constants;

class Utils
{
    public static function formatDateAndTime(string $dateTime) : string
    {
        if (empty($dateTime) || $dateTime === '0000-00-00 00:00:00' || $dateTime === '0000-00-00') {
            return Constants::TEXT_UNKNOWN_DATA;
        }

        $hasTime = strlen($dateTime) > 10;
        $format = $hasTime ? 'Y-m-d H:i:s' : 'Y-m-d';
        $dateTimeObject = DateTime::createFromFormat($format, $dateTime);

        if ($dateTimeObject === false) {
            return Constants::TEXT_UNKNOWN_DATA;
        }

        $errors = DateTime::getLastErrors();
        if ($errors && ($errors['warning_count'] > 0 || $errors['error_count'] > 0)) {
            return Constants::TEXT_UNKNOWN_DATA;
        }

        $dateTimeFormatted = strftime('%e de %B %H:%Mh', $dateTimeObject->getTimestamp());

        return $dateTimeFormatted;
    }

    /**
     * Format a string to title case
     * Credits: https://www.php.net/manual/en/function.ucwords.php#112795
     */
    public static function formatTitleCase(string $string, array $delimiters = array(" ", "-", ".", "'"), array $exceptions = array("de", "del", "I", "II", "III", "IV", "V", "VI")) : string
    {
        /*
         * Exceptions in lower case are words you don't want converted
         * Exceptions all in upper case are any words you don't want converted to title case
         *   but should be converted to upper case, e.g.:
         *   king henry viii or king henry Viii should be King Henry VIII
         */
        $string = mb_convert_case($string, MB_CASE_TITLE, "UTF-8");
        foreach ($delimiters as $dlnr => $delimiter) {
            $words = explode($delimiter, $string);
            $newwords = array();
            foreach ($words as $wordnr => $word) {
                if (in_array(mb_strtoupper($word, "UTF-8"), $exceptions)) {
                    // check exceptions list for any words that should be in upper case
                    $word = mb_strtoupper($word, "UTF-8");
                } elseif (in_array(mb_strtolower($word, "UTF-8"), $exceptions)) {
                    // check exceptions list for any words that should be in upper case
                    $word = mb_strtolower($word, "UTF-8");
                } elseif (!in_array($word, $exceptions)) {
                    // convert to uppercase (non-utf8 only)
                    $word = ucfirst($word);
                }
                array_push($newwords, $word);
            }
            $string = join($delimiter, $newwords);
        }
        return $string;
    }
}
