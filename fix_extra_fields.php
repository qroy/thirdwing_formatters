<?php
/**
 * Force CCK to recognize the new extra field
 * 
 * Place this in your Drupal root and visit it once:
 * http://www.thirdwing.nl/fix_extra_fields.php
 */

// Bootstrap Drupal
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

// Must be admin to run this
global $user;
if ($user->uid != 1) {
  echo "You must be logged in as admin (user 1) to run this script.";
  exit;
}

echo "<h1>Forcing CCK to Recognize Extra Fields</h1>";

// Clear all caches
echo "<p>Clearing all caches...</p>";
drupal_flush_all_caches();
echo "<p style='color: green;'>✓ All caches cleared</p>";

// Clear CCK cache specifically
if (function_exists('content_clear_type_cache')) {
  echo "<p>Clearing CCK content type cache...</p>";
  content_clear_type_cache();
  echo "<p style='color: green;'>✓ CCK cache cleared</p>";
}

// Clear menu cache
echo "<p>Rebuilding menu...</p>";
menu_rebuild();
echo "<p style='color: green;'>✓ Menu rebuilt</p>";

// Force reload of content type
echo "<p>Reloading repertoire content type...</p>";
$type = content_types('repertoire');
echo "<pre>";
print_r($type['extra']);
echo "</pre>";

// Try to trigger hook_content_extra_fields directly
echo "<p>Getting extra fields for repertoire...</p>";
$extra_fields = content_extra_fields('repertoire');
echo "<pre>";
print_r($extra_fields);
echo "</pre>";

echo "<hr>";
echo "<h2>Result</h2>";
if (isset($extra_fields['media_counts'])) {
  echo "<p style='color: green; font-size: 18px;'><strong>✓ SUCCESS!</strong> The 'media_counts' field is now registered.</p>";
  echo "<p>Now visit: <a href='/admin/content/node-type/repertoire/display'>admin/content/node-type/repertoire/display</a></p>";
  echo "<p>You should see 'Media Counts' in the list of fields.</p>";
} else {
  echo "<p style='color: red;'><strong>✗ ISSUE:</strong> The field is not showing up in the content type.</p>";
  echo "<p>This might be a CCK version issue. Let's try a different approach...</p>";
}

echo "<hr>";
echo "<h2>Alternative: Check Display Fields Page Directly</h2>";
echo "<p>Visit these URLs:</p>";
echo "<ul>";
echo "<li><a href='/admin/content/node-type/repertoire/fields'>Manage Fields</a></li>";
echo "<li><a href='/admin/content/node-type/repertoire/display'>Display Fields</a></li>";
echo "<li><a href='/admin/build/ds/layout/repertoire'>Display Suite Layout</a> (if DS is enabled)</li>";
echo "</ul>";

echo "<hr>";
echo "<p><strong>If the field still doesn't show:</strong></p>";
echo "<ol>";
echo "<li>Delete this file (fix_extra_fields.php) for security</li>";
echo "<li>Go to admin/content/node-type/repertoire/edit</li>";
echo "<li>Click 'Save content type' (without making any changes)</li>";
echo "<li>Clear cache again at admin/settings/performance</li>";
echo "<li>Check admin/content/node-type/repertoire/display again</li>";
echo "</ol>";

echo "<hr>";
echo "<p style='background: #ffffcc; padding: 10px; border: 1px solid #ccc;'>";
echo "<strong>IMPORTANT:</strong> Delete this file (fix_extra_fields.php) after running it once!";
echo "</p>";
