<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Timetable extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_unit_id',
        'day',
        'start_time',
        'end_time',
        'room',
        'type',
        'attendance_code',
        'is_cancelled',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_cancelled' => 'boolean',
    ];

    public function courseUnit(): BelongsTo
    {
        return $this->belongsTo(CourseUnit::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(ClassNotification::class);
    }

    public function cancel()
    {
        $this->is_cancelled = true;
        $this->save();

        // Notify all enrolled students in this course
        $enrolledStudents = Enrollment::where('course_unit_id', $this->course_unit_id)
            ->where('status', 'active')
            ->pluck('user_id');

        foreach ($enrolledStudents as $studentId) {
            ClassNotification::create([
                'user_id' => $studentId,
                'timetable_id' => $this->id,
                'type' => 'class_cancelled',
                'message' => "The class {$this->courseUnit->name} on {$this->day} {$this->start_time->format('H:i')} - {$this->end_time->format('H:i')} has been cancelled.",
            ]);
        }
    }
}
