<?php

/**
 * Unique transaction reference number
 */
if (!function_exists('generate_ref_number')) {

    function generate_ref_number()
    {
        return 'TR-' . time() . rand(00000, 55555);
    }
}
