<?php
/**
 * Template Name: Store Page Template
 * @package WordPress
 * @subpackage daltons
 */
get_header(); ?>
<div class="container"><?php 
	if ( have_posts() ) : while ( have_posts() ) : the_post();
		the_content(); 
	endwhile; else: ?>
		<p><?php _e('Sorry, no posts matched your criteria.'); ?></p><?php 
	endif;

	$categories = get_terms( 'inventorycategory');
	$count = count($categories); $I = 0; ?>
	<div class="row"><?php
		foreach ($categories as $category) : 
			if ($category->parent == 0) {
				$parentID = $category->term_id;?>
				<h2><?php echo $category->name; ?></h2><?php
				$child_cat = get_term_children( $parentID,'designercategory' ); 
				if (!empty($child_cat)) :
					foreach ($child_cat as $child) { $I++;
						$t_id = $category->term_id;
				    	$cat_meta = get_option( "category_$t_id");
				    	$img_src = wp_get_attachment_image_src( $cat_meta['img'], 'full' );
						$link = get_term_link( $category );?>
						<a class="col-md-3 inventory" href="<?php echo $link; ?>">
							<img id="inventory_image" class="img-responsive" src="<?php if($cat_meta['img'] != '') echo $img_src[0]; ?>"/>
							<h3><?php echo $category->name; ?></h3>
						</a><?php
						if ($I % 3 == 0 && $I != $count) {?>
							</div><?php 
						}
					}
				endif;
			}
		endforeach; 
		if ($I % 3 != 0 ) {?>
			</div><?php
		}?>
</div><?php 
get_footer(); ?>