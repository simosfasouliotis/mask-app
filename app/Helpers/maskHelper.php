<?php

if (!function_exists('maskEmail')) {
    function maskEmail(string $email): string
    {
        $email = mb_strtolower($email);
        $at_index = strpos($email, '@');
        if ($at_index === false) {
            return $email;
        }

        $first_letter = $email[0];
        $last_letter = $email[$at_index - 1];

        return $first_letter . str_repeat('*', 5) . $last_letter . substr($email, $at_index);
    }
}

if (!function_exists('maskTelephone')) {
    function maskTelephone(string $telephone): string
    {
        return "***-***-" . substr($telephone ,-4);
    }
}
