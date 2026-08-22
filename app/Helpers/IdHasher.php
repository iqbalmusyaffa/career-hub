<?php

namespace App\Helpers;

class IdHasher
{
    private static function getKey(): string
    {
        return substr(hash('sha256', config('app.key') . '_id_hasher_salt'), 0, 16);
    }

    public static function encode($id): string
    {
        if (!$id) return '';
        $idStr = (string)$id;
        $checksum = substr(hash('sha256', $idStr . config('app.key')), 0, 4);
        $payload = $idStr . ':' . $checksum;
        $encrypted = openssl_encrypt($payload, 'AES-128-ECB', self::getKey(), OPENSSL_RAW_DATA);
        return rtrim(strtr(base64_encode($encrypted), '+/', '-_'), '=');
    }

    public static function decode(string|int|null $hash): ?int
    {
        if ($hash === null || $hash === '') {
            return null;
        }

        if (is_numeric($hash)) {
            return (int) $hash;
        }

        try {
            $b64 = strtr((string)$hash, '-_', '+/');
            $pad = strlen($b64) % 4;
            if ($pad) {
                $b64 .= str_repeat('=', 4 - $pad);
            }
            $data = base64_decode($b64);
            if (!$data) return null;
            $decrypted = openssl_decrypt($data, 'AES-128-ECB', self::getKey(), OPENSSL_RAW_DATA);
            if (!$decrypted || !str_contains($decrypted, ':')) return null;
            list($idStr, $check) = explode(':', $decrypted, 2);
            $expectedCheck = substr(hash('sha256', $idStr . config('app.key')), 0, 4);
            if ($check !== $expectedCheck) return null;
            return (int)$idStr;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
