<?php
/**
 * WicketPixie
 * (c) 2006-2011 Eddie Ringle <eddie@eringle.net>
 * Provided by Chris Pirillo <chris@pirillo.com>
 *
 * Licensed under the New BSD License.
 */
?>
                <div id="content">
                <?php wipi_before_content(); ?>
	                <?php
	                query_posts('posts_per_page=1&order=DSC');
	                $counter = 0;
	                if (have_posts()) : while(have_posts() && $counter++ == 0) : the_post();
	                ?>
	                <div class="post" id="post-<?php the_ID(); ?>">
	                    <?php wipi_before_post(); ?>
		                <?php
	                    if (get_the_title()) {
	                    ?>
		                <h2 class="post-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
		                <?php
		                } else {
		                    $date = get_the_date();
		                ?>
		                <h2 class="post-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php echo $date; ?> at <?php the_time(); ?></a></h2>
		                <?php
	                    }
		                ?>
		                <div class="meta">
			                <?php the_date(); ?> at <?php the_time(); ?> | <?php comments_popup_link(__('0 Comments'), __('1 Comment'), __('% Comments')); ?>
		                </div>
		                <div class="post-aside">
	                        <?php wipi_post_aside(); ?>
	                    </div>
		                <div class="post-content">
			                <?php the_content(__('Read on...')); ?>
			                <?php wp_link_pages(); ?>
		                </div>
		                <?php wipi_after_post(); ?>
		                <?php wipi_post_meta(); ?>
	                </div>
	                <?php
	                endwhile; endif;
	                ?>
	            <?php wipi_after_content(); ?>
                </div>
                <?php include_once 'sidebar.php'; ?>

