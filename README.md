# ALXWP: Meta Boxes

The `ALXWP_Meta` class allows plugin developers to easily register custom meta boxes for their custom post types. As a lightweight dependency-free module, the class is a great choice for building fast and scalable plugins.

## Getting Started

1. Include `class-alxwp-meta.php` within your plugin;
2. Create a new instance of the `ALXWP_Meta` class with the proper parameters (as seen below).
3. That's it, enjoy your new meta boxes!

## Usage

1. Declaring the post type: 
```
$type = 'post';
```

2. Declaring the arguments: 
```
$boxes = array(
  array(
    'id'     => 'example',
    'title'  => __( 'Example', 'plugin' ),
    'fields' => array(
      array(
        'id'      => 'text',
        'name'    => __( 'Text', 'plugin' ),
        'type'    => 'text'
      ),
      array(
        'id'      => 'readonly',
        'name'    => __( 'Read-only', 'plugin' ),
        'type'    => 'readonly'
      ),
      array(
        'id'      => 'select',
        'name'    => __( 'Select', 'plugin' ),
        'type'    => 'select',
        'options' => array(
          array(
            'id'   => 'option_1',
            'name' => 'Option 1'
          ),
          array(
            'id'   => 'option_2',
            'name' => 'Option 2'
          )
        )
      ),
    ),
    'context'  => 'normal',
    'priority' => 'default',
  )
)
```

3. Create the new class instance.
```
<?php new ALXWP_Meta( $type, $boxes ); ?>
```

## Supported Field Types

`text`, `readonly`, `select`
