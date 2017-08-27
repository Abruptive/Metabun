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
    'id'          => 'text',
    'title'       => 'Text',
    'type'        => 'text',
    'description' => 'This is an example text setting.',
    'default'     => 'Default Value'
  ),
  array(
    'id'          => 'textarea',
    'title'       => 'Textarea',
    'type'        => 'textarea',
    'description' => 'This is an example textarea setting.',
    'default'     => 'Default Value'
  ),
  array(
    'id'          => 'toggle',
    'title'       => 'Toggle',
    'type'        => 'toggle',
    'description' => 'This is an example toggle setting.',
  ),
  array(
    'id'          => 'select',
    'title'       => 'Select',
    'type'        => 'select',
    'description' => 'This is an example select setting.',
    'options'     => array(
      array(
        'id'    => 'option_1',
        'title' => 'Option 1'
      ),
      array(
        'id'    => 'option_2',
        'title' => 'Option 2'
      ),
      array(
        'id'    => 'option_3',
        'title' => 'Option 3'
      )
    ),
    'default' => 'option_2'
  ),
  array(
    'id'          => 'checkbox',
    'title'       => 'Checkboxes',
    'type'        => 'checkbox',
    'description' => 'This is an example checkboxes setting.',
    'options'     => array(
      array(
        'id'    => 'option_1',
        'title' => 'Option 1'
      ),
      array(
        'id'    => 'option_2',
        'title' => 'Option 2'
      ),
      array(
        'id'    => 'option_3',
        'title' => 'Option 3'
      )
    ),
    'default' => 'option_2'
  ),
  array(
    'id'          => 'radio',
    'title'       => 'Radio',
    'type'        => 'radio',
    'description' => 'This is an example radio setting.',
    'options'     => array(
      array(
        'id'    => 'option_1',
        'title' => 'Option 1'
      ),
      array(
        'id'    => 'option_2',
        'title' => 'Option 2'
      ),
    ),
    'default' => 'option_2'
  )
```

3. Create the new class instance.
```
<?php new ALXWP_Meta( $type, $boxes ); ?>
```

## Supported Field Types

`text`, `number`, `phone`, `url`, `email`, `password`, `textarea`, `readonly`, `select`, `radio`, `checkbox`, `toggle`
