<?php

namespace Josefo727\FilamentGeneralSettings\Services;

use Illuminate\Support\Facades\Crypt;

class EncryptionService
{
    /**
     * Encrypt a value.
     */
    public function encrypt(string $value): string
    {
        return Crypt::encryptString($value);
    }

    /**
     * Decrypt a value.
     */
    public function decrypt(string $value): string
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            // If decryption fails, return the original value
            return $value;
        }
    }
}
