<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class SuynlLessonCompletion extends Model
{
    protected $fillable = [
        'church_attender_id',
        'lesson_number',
        'completion_date'
    ];

    protected $casts = [
        'completion_date' => 'date',
    ];

    public function churchAttender()
    {
        return $this->belongsTo(ChurchAttender::class);
    }

    /**
     * Create or update a lesson completion record
     * Prevents duplicate key constraint violations
     */
    public static function createOrUpdateCompletion($churchAttenderId, $lessonNumber, $completionDate)
    {
        return static::updateOrCreate(
            [
                'church_attender_id' => $churchAttenderId,
                'lesson_number' => $lessonNumber,
            ],
            [
                'completion_date' => $completionDate,
            ]
        );
    }

    /**
     * Override save method to handle duplicates gracefully
     */
    public function save(array $options = [])
    {
        try {
            return parent::save($options);
        } catch (QueryException $e) {
            // Check if it's a duplicate key error
            if ($e->errorInfo[1] == 1062) { // MySQL duplicate entry error
                // Try to update existing record instead
                $existing = static::where([
                    'church_attender_id' => $this->church_attender_id,
                    'lesson_number' => $this->lesson_number,
                ])->first();
                
                if ($existing) {
                    $existing->completion_date = $this->completion_date;
                    return $existing->save($options);
                }
            }
            
            // Re-throw the exception if it's not a duplicate key error
            throw $e;
        }
    }
}
