## Synopsis

This is an infinite scroll setup for wordpress. A few assumptions have been made. Most notably is that you are using an AMD JS framework, and that you wish to load underscore conditionally from a CDN.

## Code Example

```
<?php $vars = get_queried_object(); ?>
<div class="grid infinite-scroll"
		data-posts-per-page="3"
		data-cleaner="clean_data"
		data-taxonomy-term="<?php echo $vars->term_id ?>"
		data-taxonomy-name="<?php echo $vars->taxonomy ?>">
```

## Motivation

This is a common concept in web design these days, and writing a unique solution for each occurance can become tedious. This is NOT a full featured library, and it is NOT a wordpress plugin. This is just a starting point, to get the heavy lifting out of the way.

## Installation

- Drop the infinite-scroll.php file anywhere in your code, and include it in your functions.php file. Don't forget to fill out the settings in the __construct function
- Include the infinite-scroll.js file where needed
- Drop the html in your template
- Drop the temmplates directory in the root of your theme folder, or whereever you with to use it, just be sure the path is in the infinite-scroll.php class

## License

Just use it. If you like it cool, if not ... thats cool too.
Feel free to make pull requests