<?php
/**
 * Debug display fields page
 * 
 * Visit: http://www.thirdwing.nl/debug_display.php
 */

require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

global $user;
if ($user->uid != 1) {
  echo "You must be logged in as admin (user 1) to run this script.";
  exit;
}

echo "<h1>Debug Display Fields</h1>";

// Get the content type
$type = content_types('repertoire');

echo "<h2>Content Type Extra Fields</h2>";
echo "<pre>";
print_r($type['extra']);
echo "</pre>";

echo "<h2>Content Fields</h2>";
echo "<pre>";
print_r($type['fields']);
echo "</pre>";

// Check what content_fields returns
echo "<h2>All Content Fields for Repertoire</h2>";
$fields = content_fields(NULL, 'repertoire');
echo "<pre>";
print_r($fields);
echo "</pre>";

// Try to see what the display page would see
echo "<h2>Build Modes</h2>";
$build_modes = content_build_modes();
echo "<pre>";
print_r($build_modes);
echo "</pre>";

// Check if there's a display settings variable
echo "<h2>Display Settings (from variables)</h2>";
$display_settings = variable_get('content_extra_weights_repertoire', array());
echo "<pre>";
print_r($display_settings);
echo "</pre>";

// Let's manually check what should be on the display page
echo "<h2>What Display Page Should Show</h2>";
echo "<p>Regular CCK fields:</p><ul>";
foreach ($type['fields'] as $field_name => $field) {
  echo "<li>{$field['widget']['label']} ({$field_name})</li>";
}
echo "</ul>";

echo "<p>Extra fields that should appear:</p><ul>";
foreach ($type['extra'] as $extra_name => $extra) {
  if (!empty($extra['visible'])) {
    echo "<li style='color: green;'><strong>{$extra['label']} ({$extra_name})</strong> - visible = TRUE</li>";
  } else {
    echo "<li style='color: red;'>{$extra['label']} ({$extra_name}) - visible = FALSE or not set</li>";
  }
}
echo "</ul>";

echo "<hr>";
echo "<h2>Testing Direct Access to Display Page Data</h2>";

// Simulate what the display page does
$content = content_types('repertoire');
$extra = $content['extra'];
$fields = $content['fields'];

echo "<p>Fields that should appear on display page:</p>";
echo "<ul>";

// CCK fields
foreach ($fields as $field_name => $field) {
  echo "<li>CCK Field: {$field['widget']['label']}</li>";
}

// Extra fields
foreach ($extra as $extra_name => $extra_field) {
  $visible = isset($extra_field['visible']) ? $extra_field['visible'] : FALSE;
  $color = $visible ? 'green' : 'orange';
  echo "<li style='color: {$color};'>Extra Field: {$extra_field['label']} (visible={$visible})</li>";
}

echo "</ul>";

echo "<hr>";
echo "<h2>Check Display Settings Storage</h2>";

// Different places where display settings might be stored
$possible_vars = array(
  'content_extra_weights_repertoire',
  'ds_repertoire',
  'ds_layout_settings_repertoire',
  'content_display_repertoire_full',
);

foreach ($possible_vars as $var) {
  $value = variable_get($var, NULL);
  if ($value !== NULL) {
    echo "<h3>{$var}</h3>";
    echo "<pre>";
    print_r($value);
    echo "</pre>";
  }
}

echo "<hr>";
echo "<p><strong>Next step:</strong> Visit <a href='/admin/content/node-type/repertoire/display'>the display page</a> and check if 'Media Counts' appears.</p>";
echo "<p>If not, try saving a field's display settings and check again.</p>";
