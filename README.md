# Thirdwing Custom Formatters Module

This module provides custom CCK field formatters for the thirdwing.nl Drupal 6 site.

## Installation

1. Copy this module to your `sites/all/modules/custom/` directory
2. Enable the module at `admin/build/modules`
3. Clear the cache at `admin/settings/performance`

## Migrating Custom Formatters

To migrate your existing custom formatters from the custom_formatters module:

### Step 1: Export Your Formatters

1. Go to `admin/build/formatters` in your Drupal site
2. For each custom formatter, click "Export" to see the code
3. Note the formatter's machine name, label, and field types

### Step 2: Add to This Module

For each formatter, you need to:

1. **Register the formatter** in `hook_field_formatter_info()`:
```php
'your_formatter_name' => array(
  'label' => t('Your Formatter Label'),
  'field types' => array('text', 'number_integer', 'link', etc),
  'multiple values' => CONTENT_HANDLE_CORE,
),
```

2. **Register a theme function** in `hook_theme()`:
```php
'thirdwing_formatters_your_formatter_name' => array(
  'arguments' => array('element' => NULL),
),
```

3. **Create the theme function** to handle the formatting:
```php
function theme_thirdwing_formatters_your_formatter_name($element) {
  // Your formatting logic here
  return $output;
}
```

### Step 3: Common Formatter Patterns

**For text fields:**
```php
function theme_thirdwing_formatters_my_text_formatter($element) {
  $value = $element['#item']['safe'];
  // Or use: $element['#item']['value'] for unfiltered
  
  return '<div class="custom-format">' . $value . '</div>';
}
```

**For link fields:**
```php
function theme_thirdwing_formatters_my_link_formatter($element) {
  $url = $element['#item']['url'];
  $title = $element['#item']['title'];
  
  return l($title ? $title : $url, $url);
}
```

**For image fields:**
```php
function theme_thirdwing_formatters_my_image_formatter($element) {
  $filepath = $element['#item']['filepath'];
  
  return theme('image', $filepath, '', '', array('class' => 'my-class'));
}
```

**For node reference fields:**
```php
function theme_thirdwing_formatters_my_nodereference_formatter($element) {
  $nid = $element['#item']['nid'];
  
  if ($node = node_load($nid)) {
    return l($node->title, 'node/' . $nid);
  }
  return '';
}
```

### Step 4: Access Field Context

The `$element` array contains:
- `$element['#item']` - The field item data
- `$element['#field_name']` - The field's machine name
- `$element['#type_name']` - The content type
- `$element['#formatter']` - The formatter being used
- `$element['#node']` - The full node object (if available)

### Step 5: Testing

1. Go to `admin/content/node-type/[your-content-type]/display`
2. Select your custom formatter from the dropdown for each field
3. View a node to test the output

## Notes

- Make sure to clear the cache after adding new formatters
- Use `check_plain()` or `filter_xss()` to sanitize user input
- Use `t()` for translatable strings
- Follow Drupal coding standards

## Support

For issues specific to this module, contact the Thirdwing development team.
