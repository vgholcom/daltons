<?php
/**
 * Template Name: Store Page Template
 * @package WordPress
 * @subpackage daltons
 */
get_header(); ?>
<article class="container"><?php 
	if ( have_posts() ) : while ( have_posts() ) : the_post();?>
		<h2><?php the_title(); ?></h2><?php 
		the_content(); 
	endwhile; else: ?>
		<p><?php _e('Sorry, no posts matched your criteria.'); ?></p><?php 
	endif;

	$categories = get_terms( 'inventorycategory');
	foreach ($categories as $category) : 
		$link = get_term_link( $category );?>
		<h1><a href="<?php echo $link; ?>"><?php echo $category->name; ?></a></h1><?php
	endforeach; ?>
</article><?php 
get_footer(); ?>