![Cover](http://alexandru.co/wp-content/uploads/metabun/github-cover.png)

## What is _Metabun_?

_Meta boxes_ are draggable interface elements shown on the post editing screen, allowing the user to add additional information to the post. Learn more about Custom Meta Boxes by visiting the official [Plugin Handbook](https://developer.wordpress.org/plugins/metadata/custom-meta-boxes/). _Metabun_ is a lightweight utility *(only 20KB)* that allows WordPress plugin developers to easily register custom meta boxes.

## Getting Started
1. Download the [latest release](https://github.com/AlexandruDoda/Metabun/releases)
2. Copy the `metabun` folder to your plugin
3. Include the `class-metabun.php` file from the copied folder
4. Create a new instance of the `Metabun` class

## Usage
### Creating a new instance of the class.
* After including the plugin, all you need to do is create a new instance as follows: 
```
new Metabun( $type, $meta );
```
* As you can see, 2 parameters are used: `$type` and `$meta`.

### Declaring the parameters.

* `$type` _(string)_: The custom post type to register the meta boxes for.
* `$meta` _(array)_: An array defining the registered meta boxes.

### Example

```
<?php

$type = 'post';

$meta = array(
  array(
    'id'     => 'meta',
    'title'  => 'Meta Box',
    'fields' => array(
      array(
        'id'          => 'example_text',
        'title'       => 'Text Field',
        'type'        => 'text',
        'description' => 'This is an example text field.',
        'default'     => 'Default Value'
      ),
      // Insert additional field arrays here.
    ),
    'context'  => 'normal',
    'priority' => 'default'
  )
);

new Metabun( $type, $meta ); 

?>
```

### Supported Field Types

For a complete list of supported field types, please [visit the documentation wiki](https://github.com/AlexandruDoda/Metabun/wiki).
