# PostTypeHandler
Helper class to quickly manage PostType and Taxonomy declarations

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

You can also set the taxonomies for the PostType.

```php
$post_type_handler = new PostType( 'Books' );

// add multiple taxonomies
$post_type_handler->set_taxonomies( [ 'custom-taxonomy', 'post_tag' ] );

// or add a single taxonomy
$post_type_handler->set_taxonomies( 'custom-taxonomy' );

$post_type_handler->register();
```

## Hooks

| Hook type | Hook name                         | Params         | Description                          |
| --------- | --------------------------------- | -------------- | ------------------------------------ |
| Filter    | gt_post_type_{$post_type}_labels  | array $labels  | Custom the labels for the post type  |
| Filter    | gt_post_type_{$post_type}_options | array $options | Custom the options for the post type |


## TODOS