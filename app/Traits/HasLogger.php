<?php

namespace App\Traits;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait HasLogger
{
    public function log($activity, $data = "{}", $id = null)
    {
        try {
            $user = User::find($id) ?: Auth::user();

            $id = $user?->id ?? null;
            $username = $user?->username ?? 'sample_admin';
            $roleId = $user?->role?->id ?? 0;

            return AuditLog::create([
                'initiator_id' => $id,
                'initiator_username' => $username,
                'initiator_role' => $roleId,
                'action' => $activity,
                'details' => $this->parseData($data),
                'ip_address' => request()->ip(),
            ]);
        } catch (\Throwable $e) {
            \Log::error("User log failed: {$e->getMessage()}");
            abort(500, "Logging activity failed.");
        }
    }

    public function parseData($data): false|string
    {
        return is_array($data) ? json_encode(
            $data,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
        ) : (string)$data;
    }
}
