<?php
	/* Template Name: EXAMPLET */

	get_header();
	the_post();
?>

<section class="section-main" role="main">

	<div class="grid infinite-scroll"
		data-posts-per-page="3"
		data-cleaner="clean_data"
		data-taxonomy-term="<?php echo $vars->term_id ?>"
		data-taxonomy-name="<?php echo $vars->taxonomy ?>"
	>

	<?php
		// InfiniteScroll::inst() will load the already declared instance rather than build a new one
		InfiniteScroll::inst()
			// this will load _ (underscore) from a CDN, i only do this because browserify kill it
			->load_underscore()
			// this will echo the template on the page
			->get_template('post-template');
	?>

</section>

<?php get_footer(); ?>