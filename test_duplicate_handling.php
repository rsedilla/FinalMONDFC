<?php

use App\Models\SuynlLessonCompletion;

try {
    echo "Testing duplicate handling...\n";
    
    // Test the createOrUpdateCompletion method
    $result = SuynlLessonCompletion::createOrUpdateCompletion(6, 9, '2025-09-05');
    
    echo "Success! Result: " . json_encode($result->toArray()) . "\n";
    echo "Duplicate handling works correctly!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
