<?php

namespace App\Services;

use App\Models\ActivityLog;

class ActivityLogService
{
    public static function store(
        string $module,
        string $action,
        $recordId,
        $oldData = null,
        $newData = null
    ) {
        $loginUser = loginAccount();
        ActivityLog::create([
            'user_id' => $loginUser['account_id'],
            'module_name' => $module,
            'action' => $action,
            'record_id' => $recordId,
            'old_data' => $oldData,
            'new_data' => $newData,
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
