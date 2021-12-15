<?php

namespace GoApptiv\TextLocal\Services;

class Crypto
{
    /**
     * Encrypt the string
     *
     * @param string $string
     * @return string
     */
    public static function encrypt($string)
    {
        $ciphering = "AES-128-CTR";
        $options = 0;
        $iv = env("TEXTLOCAL_CRYPTO_IV");
        $key = env("TEXTLOCAL_CRYPTO_KEY");

        return openssl_encrypt(
            $string,
            $ciphering,
            $key,
            $options,
            $iv
        );
    }

    /**
     * Encrypt the string
     *
     * @param string $string
     * @return string
     */
    public static function decrypt($encryption)
    {
        $ciphering = "AES-128-CTR";
        $options = 0;
        $iv = env("TEXTLOCAL_CRYPTO_IV");
        $key = env("TEXTLOCAL_CRYPTO_KEY");

        return openssl_decrypt(
            $encryption,
            $ciphering,
            $key,
            $options,
            $iv
        );
    }
}
