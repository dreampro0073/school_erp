<?php

namespace App\Models;

use Illuminate\Support\Facades\Schema;

class ModelHelper
{
    public static function resolveTable(array $candidates): ?string
    {
        foreach ($candidates as $table) {
            if (Schema::hasTable($table)) {
                return $table;
            }
        }

        return null;
    }

    public static function resolveColumn(string $table, array $candidates): ?string
    {
        if (!Schema::hasTable($table)) {
            return null;
        }

        foreach ($candidates as $column) {
            if (Schema::hasColumn($table, $column)) {
                return $column;
            }
        }

        return null;
    }

    public static function resolveFirstExistingColumn(string $table, array $candidates): ?string
    {
        return self::resolveColumn($table, $candidates);
    }

    public static function applyClientScope($query, string $table, int $clientId): void
    {
        if (Schema::hasColumn($table, 'client_id')) {
            $query->where('client_id', $clientId);
        }
    }

    public static function applyTimestamps(string $table, array $payload, bool $isCreate): array
    {
        if (Schema::hasColumn($table, 'updated_at')) {
            $payload['updated_at'] = now();
        }
        if ($isCreate && Schema::hasColumn($table, 'created_at')) {
            $payload['created_at'] = now();
        }

        return $payload;
    }
}
