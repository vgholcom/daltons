<?php
/**
 * Template Name: Store Page Template
 * @package WordPress
 * @subpackage daltons
 */
get_header();
$categories = get_terms( 'inventorycategory');
foreach ($categories as $category) : 
	$link = get_term_link( $category );?>
	<h1><a href="<?php echo $link; ?>"><?php echo $category->name; ?></a></h1><?php
endforeach;?>