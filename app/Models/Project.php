<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Project extends Model
{
    use LogsActivity; // <--- Add Trait
    protected $table = 'projects';

    protected $fillable = [
        'department_id',
        'title',
        'description',
        'status',
        'start_date',
        'end_date',
        'created_by'
    ];

    const STATUS_PLANNING = 'planning';
    const STATUS_ACTIVE = 'active';
    const STATUS_ON_HOLD = 'on_hold';
    const STATUS_COMPLETED = 'completed';
    const STATUS_ARCHIVED = 'archived';

     

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'status', 'start_date', 'department_id']) // What to track
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Project has been {$eventName}");
    }
    protected static function booted()
    {
        static::saving(function ($project) {
            // حساب النسبة لحظياً للتأكد من الحالة
            $total = $project->tasks()->count();
            if ($total > 0) {
                $completed = $project->tasks()->where('status', 'completed')->count();
                $percentage = ($completed / $total) * 100;

                if ($percentage >= 100) {
                    $project->status = self::STATUS_COMPLETED;
                } else {
                    // إذا كانت النسبة أقل من 100 وكان المشروع مكتمل، نعيده لـ Active
                    if ($project->status === self::STATUS_COMPLETED) {
                        $project->status = self::STATUS_ACTIVE;
                    }
                }
            }
        });
    }

    // تعديل دالة التحديث لتعمل بدون عمود في الداتابيز
    public function updateProgress()
    {
        // بمجرد مناداة save، سيتم تفعيل الـ booted static::saving أعلاه وتحديث الحالة
        $this->save(); 
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function members()
    {
        return $this->belongsToMany(User::class)
        ->whereHas('roles', fn ($q) => $q->where('name', 'Member'));
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
