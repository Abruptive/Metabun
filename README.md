# ALXWP: Meta Boxes

The `ALXWP_Meta` class allows plugin developers to easily register custom meta boxes for their custom post types. As a lightweight dependency-free module, the class is a great choice for building fast and scalable plugins.

*Important:* This project is still in early development and major structural changes might happen until the official release.

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
$meta = array(
  array(
    'id'     => 'meta',
    'title'  => 'Meta Box',
    'fields' => array(
      array(
        'id'          => 'text',
        'title'       => 'Text',
        'type'        => 'text',
        'description' => 'This is an example text setting.',
        'default'     => 'Default Value'
      ),
      array(
        'id'          => 'number',
        'title'       => 'Number',
        'type'        => 'number',
        'description' => 'This is an example number setting.',
        'default'     => 100
      ),
      array(
        'id'          => 'email',
        'title'       => 'Email',
        'type'        => 'email',
        'description' => 'This is an example email setting.',
        'default'     => 'hello@example.com'
      ),
      array(
        'id'          => 'phone',
        'title'       => 'Phone',
        'type'        => 'phone',
        'description' => 'This is an example phone setting.',
        'default'     => '1-800-000-000'
      ),
      array(
        'id'          => 'url',
        'title'       => 'URL Address',
        'type'        => 'url',
        'description' => 'This is an example URL setting.',
        'default'     => 'https://example.com'
      ),
      array(
        'id'          => 'textarea',
        'title'       => 'Textarea',
        'type'        => 'textarea',
        'description' => 'This is an example textarea setting.',
        'default'     => 'In magna augue, imperdiet et tempor nec, tincidunt ut nisi. Fusce condimentum massa nec dui facilisis, et auctor dolor molestie. Aenean eget fringilla libero.'
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
            'title' => 'Option 1',
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
            'title' => 'Option 1',
          ),
          array(
            'id'    => 'option_2',
            'title' => 'Option 2'
          ),
          array(
            'id'    => 'option_3',
            'title' => 'Option 3'
          )
        )
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
      ),
      array(
        'id'          => 'repeater',
        'title'       => 'Repeater',
        'type'        => 'repeater',
        'description' => 'This is an example repeater setting.'
      ),
      array(
        'id'          => 'post',
        'title'       => 'Post',
        'type'        => 'post',
        'description' => 'This is an example post setting.',
        'post_type'   => 'item'
      )
    ),
    'context'  => 'normal',
    'priority' => 'default'
  )
)
```

3. Create the new class instance.
```
<?php new ALXWP_Meta( $type, $meta ); ?>
```

## Supported Field Types

`text`, `number`, `phone`, `url`, `email`, `password`, `textarea`, `readonly`, `select`, `radio`, `checkbox`, `toggle`, `repeater`, `post`
