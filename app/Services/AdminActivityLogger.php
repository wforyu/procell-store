<?php

namespace App\Services;

use App\Models\AdminActivityLog;
use Illuminate\Database\Eloquent\Model;

class AdminActivityLogger
{
    public static function log(
        string $action,
        ?Model $model = null,
        ?string $description = null,
        ?array $oldValues = null,
        ?array $newValues = null,
    ): AdminActivityLog {
        return AdminActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->getKey(),
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public static function created(Model $model, ?string $description = null): AdminActivityLog
    {
        return static::log('created', $model, $description ?? static::describe($model, 'dibuat'));
    }

    public static function updated(Model $model, ?string $description = null): AdminActivityLog
    {
        $changes = $model->getChanges();
        $original = [];
        foreach (array_keys($changes) as $key) {
            $original[$key] = $model->getOriginal($key);
        }

        return static::log('updated', $model, $description ?? static::describe($model, 'diperbarui'), $original, $changes);
    }

    public static function deleted(Model $model, ?string $description = null): AdminActivityLog
    {
        return static::log('deleted', $model, $description ?? static::describe($model, 'dihapus'));
    }

    protected static function describe(Model $model, string $action): string
    {
        $name = method_exists($model, 'getNameAttribute') ? $model->name : (method_exists($model, 'getTitleAttribute') ? $model->title : class_basename($model).' #'.$model->getKey());

        return auth()->user()?->name.' '.$action.' '.$name;
    }
}
