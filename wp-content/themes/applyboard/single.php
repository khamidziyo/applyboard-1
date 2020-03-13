<?php get_header();?>
	
<div class="container">
<div>


<div class="blog-list">
<?php if(have_posts()): while(have_posts()): the_post(); ?>

<h3><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>

<?php the_content();?>




</div>


<?php endwhile; else: ?>
<h2 class="text-center">Not Found</h2>
<p>Sorry, you are looking for something that's not here!</p>
<?php endif; ?>
</div>
</div>
 	
  
<?php get_footer();?>