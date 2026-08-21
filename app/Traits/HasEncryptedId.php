<?php

namespace App\Traits;

use Illuminate\Support\Facades\Crypt;

trait HasEncryptedId
{
    /**
     * Get the encrypted ID for secure URLs.
     */
    public function getEncryptedIdAttribute(): string
    {
        return Crypt::encryptString((string) $this->getKey());
    }

    /**
     * Safely decrypt encrypted ID or return original if unencrypted/numeric.
     */
    public static function decryptId($encryptedId)
    {
        if (empty($encryptedId)) {
            return null;
        }

        try {
            return Crypt::decryptString($encryptedId);
        } catch (\Exception $e) {
            return $encryptedId;
        }
    }

    /**
     * Eloquent Query Scope to find by encrypted ID or fail.
     * Works with Model::with(...)->findByEncryptedIdOrFail($id)
     */
    public function scopeFindByEncryptedIdOrFail($query, $encryptedId)
    {
        $realId = static::decryptId($encryptedId);
        $keyName = $this->getKeyName();
        return $query->where($this->getTable() . '.' . $keyName, $realId)->firstOrFail();
    }

    /**
     * Eloquent Query Scope to find by encrypted ID or null.
     */
    public function scopeFindByEncryptedId($query, $encryptedId)
    {
        $realId = static::decryptId($encryptedId);
        $keyName = $this->getKeyName();
        return $query->where($this->getTable() . '.' . $keyName, $realId)->first();
    }

    /**
     * Static finder by encrypted ID or fail.
     */
    public static function findByEncryptedIdOrFail($encryptedId)
    {
        $realId = static::decryptId($encryptedId);
        return static::findOrFail($realId);
    }
}
