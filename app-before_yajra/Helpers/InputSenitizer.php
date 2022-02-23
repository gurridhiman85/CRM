<?php
namespace App\Helpers;
class InputSenitizer
{
    public static function SanitizeNumber($number) {
        return filter_var($number, FILTER_SANITIZE_NUMBER_INT);
    }

    public static function SanitizeDecimal($decimal) {
        return filter_var($decimal, FILTER_SANITIZE_NUMBER_FLOAT);
    }

    public static function SanitizeString($string) {
        $string = strip_tags($string);
        $string = addslashes($string);
        return filter_var($string, FILTER_SANITIZE_STRING);
    }

    public static function SanitizeHTML($string) {
        $string = strip_tags($string, '<a><strong><em><hr><br><p><u><ul><ol><li><dl><dt><dd><table><thead><tr><th><tbody><td><tfoot>');
        $string = addslashes($string);
        return filter_var($string, FILTER_SANITIZE_STRING);
    }

    public static function SanitizeURL($url) {
        return filter_var($url, FILTER_SANITIZE_URL);
    }

    public static function SanitizeSlug($string) {
        $string = str_slug($string);
        return filter_var($string, FILTER_SANITIZE_URL);
    }

    public static function SanitizeEmail($string) {
        return filter_var($string, FILTER_SANITIZE_EMAIL);
    }
}
?>