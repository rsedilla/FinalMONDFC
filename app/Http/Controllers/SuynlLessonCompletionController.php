<?php

namespace App\Http\Controllers;

use App\Models\SuynlLessonCompletion;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SuynlLessonCompletionController extends Controller
{
    /**
     * Store or update a lesson completion record
     * Prevents duplicate key constraint violations
     */
    public function storeOrUpdate(Request $request)
    {
        $validated = $request->validate([
            'church_attender_id' => 'required|exists:church_attenders,id',
            'lesson_number' => 'required|integer|min:1|max:10',
            'completion_date' => 'required|date',
        ]);

        try {
            $completion = SuynlLessonCompletion::updateOrCreate(
                [
                    'church_attender_id' => $validated['church_attender_id'],
                    'lesson_number' => $validated['lesson_number'],
                ],
                [
                    'completion_date' => $validated['completion_date'],
                ]
            );

            return response()->json([
                'success' => true,
                'data' => $completion,
                'message' => 'Lesson completion saved successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save lesson completion: ' . $e->getMessage(),
            ], 500);
        }
    }
}
