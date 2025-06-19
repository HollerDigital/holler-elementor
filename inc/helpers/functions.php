<?php

/**
 * Check if memory usage is approaching the limit
 * 
 * @param float $threshold Percentage threshold (0.8 = 80%)
 * @return bool True if memory usage is above threshold
 */
function holler_check_memory_usage($threshold = 0.8) {
    $memory_limit = ini_get('memory_limit');
    if ($memory_limit == -1) {
        return false; // No limit set
    }
    
    // Convert memory limit to bytes
    $memory_limit_bytes = wp_convert_hr_to_bytes($memory_limit);
    
    // Get current memory usage
    $current_usage = memory_get_usage(true);
    
    // Calculate percentage used
    $percent_used = $current_usage / $memory_limit_bytes;
    
    return ($percent_used >= $threshold);
}

/**
 * Safely execute a callback function with memory monitoring
 *
 * @param callable $callback Function to call
 * @param array $args Arguments to pass to the callback
 * @param string $fallback_message Message to display if memory limit is reached
 * @return mixed Result of callback or fallback message
 */
function holler_memory_safe_render($callback, $args = [], $fallback_message = '') {
    // Set a memory checkpoint
    $memory_before = memory_get_usage(true);
    
    // Check if we're in an AJAX request
    $is_ajax = defined('DOING_AJAX') && DOING_AJAX;
    
    // Try to execute the callback
    try {
        // If memory is already high, use fallback
        if (holler_check_memory_usage(0.9)) {
            if ($is_ajax) {
                // For AJAX requests, return a simpler response to avoid potential issues
                return '<div class="holler-memory-limit">Memory limit reached</div>';
            }
            return $fallback_message ?: 'Unable to display content due to memory constraints.';
        }
        
        // Set a reasonable time limit for the operation
        // This helps prevent timeouts in AJAX requests
        $original_time_limit = ini_get('max_execution_time');
        if ($original_time_limit > 0 && $original_time_limit < 30) {
            // Only increase the time limit if it's set and less than 30 seconds
            @set_time_limit(30);
        }
        
        // Call the function
        $result = call_user_func_array($callback, $args);
        
        // Check if memory usage spiked dramatically
        $memory_after = memory_get_usage(true);
        $memory_used = $memory_after - $memory_before;
        
        // If function used excessive memory, log it for debugging
        if ($memory_used > 5 * 1024 * 1024) { // 5MB threshold
            // error_log("High memory usage detected in Holler Elementor: {$memory_used} bytes");
        }
        
        return $result;
    } catch (\Exception $e) {
        // Log the error
        // error_log("Holler Elementor error: " . $e->getMessage());
        
        if ($is_ajax) {
            // For AJAX requests, return a simpler response
            return '<div class="holler-error">Error processing request</div>';
        }
        
        return $fallback_message ?: 'An error occurred while processing this content.';
    } catch (\Error $e) {
        // Catch PHP 7+ errors
        // error_log("Holler Elementor fatal error: " . $e->getMessage());
        
        if ($is_ajax) {
            return '<div class="holler-error">Error processing request</div>';
        }
        
        return $fallback_message ?: 'An error occurred while processing this content.';
    }
}

/**
 * Create a URL-friendly slug from a string
 * 
 * @param string $text The text to convert to a slug
 * @param bool $force_regenerate Whether to force regeneration of the slug (bypass cache)
 * @return string The slugified text
 */
function slugify($text, $force_regenerate = false) {
  // Static cache to avoid processing the same string multiple times
  static $cache = array();
  
  // If the text is empty, return a default value
  if (empty($text)) {
    return 'n-a';
  }
  
  // Create a cache key from the input text
  $cache_key = md5($text);
  
  // Return cached result if available and not forcing regeneration
  if (!$force_regenerate && isset($cache[$cache_key])) {
    return $cache[$cache_key];
  }
  
  // WordPress has a built-in function for this that's more efficient
  if (function_exists('sanitize_title')) {
    $slug = sanitize_title($text);
    $cache[$cache_key] = $slug;
    return $slug;
  }
  
  // Fallback to manual implementation if WordPress functions aren't available
  // Convert to ASCII
  $text = remove_accents($text);
  
  // Replace non-alphanumeric characters with hyphens
  $text = preg_replace('/[^a-z0-9]+/i', '-', $text);
  
  // Remove leading/trailing hyphens
  $text = trim($text, '-');
  
  // Convert to lowercase
  $text = strtolower($text);
  
  // Store in cache
  $cache[$cache_key] = $text;
  
  return $text;
}

