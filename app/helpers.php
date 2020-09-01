<?php

if (!function_exists('format_invoice_number')) {
    function format_invoice_number($prefix, $number)
    {
        return $prefix . sprintf('%06d', $number);
    }
}
