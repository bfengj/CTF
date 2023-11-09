<?php
namespace UserMeta;

/**
 * Non WP helpers functions
 */

/**
 * Exception handling with showing-logging message and trace
 *
 * Example echoException(function($arg1){}, [$arg1]);
 *
 * @author Khaled Hossain
 * @since 1.4
 * @param callable $callableTry
 * @param array $args
 */
function echoThrowable(callable $callableTry, array $args = [])
{
    $showThrowable = function ($t) {
        echo adminNotice('<strong>' . $t->getMessage() . '</strong> ' . $t->getTraceAsString());
        error_log('UserMetaError: ' . $t->getMessage() . $t->getTraceAsString());
    };
    try {
        call_user_func_array($callableTry, $args);
    } catch (\Throwable $t) {
        $showThrowable($t);
    } catch (\Exception $t) {
        $showThrowable($t);
    }
}

/**
 * Siteurl without protocol and trailing slash
 * This function has been used by user-meta-site too.
 *
 * @author Khaled Hossain
 * @since 1.3
 * @uses user-meta-site plugin
 *      
 * @param string $url
 */
function cleanSiteUrl($url)
{
    $url = str_replace([
        'http://',
        'https://'
    ], [
        '',
        ''
    ], $url);

    return trim(trim($url), '/');
}

/**
 * Validate timestamp within certain time windows
 * This function has been used by user-meta-site too.
 *
 * @uses user-meta-site plugin
 *      
 * @param int $timestamp
 * @param int $span
 * @return boolean
 */
function validateTimeStamp($timestamp, $span = 86400)
{
    $min = time() - $span;
    $max = time() + $span;
    if (($timestamp >= $min) && ($timestamp <= $max))
        return true;

    return false;
}

/**
 * Return true if the $needle in $haystack has a value and an array
 *
 * @since 1.4
 * @param string|int $needle
 * @param array $haystack
 * @return boolean
 */
function isValuedArray($needle, $haystack)
{
    return ! empty($haystack[$needle]) && is_array($haystack[$needle]);
}

/**
 * Convert nnumeric array to associative array
 * key will used as value
 *
 * @since 1.5
 * @param array $numericArray
 * @return array [key => key]
 */
function numericToAssociativeArray(array $numericArray)
{
    return array_combine($numericArray, $numericArray);
}

/**
 * Dumping data.
 *
 * @since 1.2
 * @param mixed $data
 *            Data to dump
 * @param bool $dump
 *            true for using var_dump
 */
function dump($data, $dump = false)
{
    echo '<pre>';
    if (is_array($data) or is_object($data)) {
        if ($dump) {
            var_dump($data);
        } else {
            print_r($data);
        }
    } else {
        var_dump($data);
    }
    echo '</pre>';
}