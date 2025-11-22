<?php
/**
 * Diagnostic script to test thirdwing_formatters hooks
 * 
 * Place this in your Drupal root and visit it in your browser:
 * http://www.thirdwing.nl/test_formatters.php
 */

// Bootstrap Drupal
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

echo "<h1>Thirdwing Formatters Diagnostic</h1>";

// Check if module is enabled
$modules = module_list();
if (in_array('thirdwing_formatters', $modules)) {
  echo "<p style='color: green;'>✓ Module is enabled</p>";
} else {
  echo "<p style='color: red;'>✗ Module is NOT enabled</p>";
  exit;
}

// Check hook_content_extra_fields
echo "<h2>Testing hook_content_extra_fields()</h2>";
$extra_fields = module_invoke('thirdwing_formatters', 'content_extra_fields', 'repertoire');
echo "<pre>";
print_r($extra_fields);
echo "</pre>";

// Check all extra fields registered for repertoire
echo "<h2>All Extra Fields for Repertoire</h2>";
$all_extra = content_extra_fields('repertoire');
echo "<pre>";
print_r($all_extra);
echo "</pre>";

// Check hook_ds_fields (if DS is enabled)
if (module_exists('ds')) {
  echo "<h2>Testing hook_ds_fields()</h2>";
  $ds_fields = module_invoke('thirdwing_formatters', 'ds_fields', 'repertoire', 'full');
  echo "<pre>";
  print_r($ds_fields);
  echo "</pre>";
  
  // Get all DS fields
  echo "<h2>All DS Fields for Repertoire</h2>";
  $all_ds_fields = ds_fields('repertoire');
  echo "<pre>";
  print_r($all_ds_fields);
  echo "</pre>";
} else {
  echo "<p>Display Suite module is not enabled</p>";
}

// Test the media counts function directly
echo "<h2>Testing Media Counts Function</h2>";
echo "<p>Testing with a sample repertoire node (if nid=1 exists)...</p>";

$test_node = node_load(1);
if ($test_node && $test_node->type == 'repertoire') {
  $counts = thirdwing_formatters_get_media_counts_for_display($test_node->nid);
  echo "<p>Node {$test_node->nid}: {$test_node->title}</p>";
  echo "<pre>";
  print_r($counts);
  echo "</pre>";
  
  echo "<h3>Rendered Output:</h3>";
  echo theme('thirdwing_formatters_media_counts_display', $counts, $test_node->nid);
} else {
  echo "<p>No repertoire node found with nid=1. Please check your first repertoire node.</p>";
}

echo "<h2>Content Type Info</h2>";
$type_info = content_types('repertoire');
echo "<pre>";
print_r($type_info);
echo "</pre>";

echo "<hr><p><em>If you see the 'media_counts' field in the 'All Extra Fields' section, then the hook is working correctly and the issue might be with cache.</em></p>";
echo "<p><strong>Next step:</strong> Clear all caches at admin/settings/performance</p>";
