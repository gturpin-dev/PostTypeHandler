# PostTypeHandler
Class helpers to handle fast PostType and Taxonomies declarations

## Usage
Below is a basic example of setting up a simple PostType.

```php
// don't forget to import your autoloader
require_once __DIR__ . '/vendor/autoload.php';

use PostTypeHandler\PostType;

$post_type_handler = new PostType( 'Book' );
$post_type_handler->register();
```

You can add custom $options and $labels to the PostType declaration.

```php
$labels = [
	'add_new'   => __( 'my add_new', 'context' ),
	'all_items' => __( 'my all_items', 'context' ),
];

$options = [
	'public'    => true,
	'menu_icon' => 'dashicons-admin-post',
	'rewrite'   => [
		'slug' => 'my-post-type',
	],
];

$post_type_handler = new PostType( 'Books', $options, $labels );
$post_type_handler->register();
```

## Hooks

| Hook type | hook_name                         | params         |
| --------- | --------------------------------- | -------------- |
| Filter    | gt_post_type_{$post_type}_labels  | array $labels  |
| Filter    | gt_post_type_{$post_type}_options | array $options |


## TODOS