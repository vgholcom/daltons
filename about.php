<?php
/**
 * Template Name: About Page Template
 * @package WordPress
 * @subpackage daltons
 */
get_header(); 
if ( have_posts() ) {
	while ( have_posts() ) { ?>
		<div class="row">
			<div class="col-md-5"><?php
				the_title();
				the_content(); ?>
			</div>
			<div class="col-md-8">
				<div class="row">
				</div>
				<div class="row">
					<div class="col-md-6">
					</div>
					<div class="col-md-6">
						<div class="row">
						</div>
					</div>
				</div>
			</div>
		</div><?php
	} 
}?>

