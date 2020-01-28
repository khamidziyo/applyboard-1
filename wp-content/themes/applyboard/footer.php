<?php wp_footer();?>
<section class="footer" id="footer-section">
				<div class="container">
					<div class="row">
						<div class="col-sm-12 col-md-12 col-lg-12">
							<?php if(is_active_sidebar('footer_1')): dynamic_sidebar('footer_1'); endif; ?>
							<?php if(is_active_sidebar('footer_2')): dynamic_sidebar('footer_2'); endif; ?>
						</div>
					</div>
				</div>
			</section>
			<section class="Features" id="main-section-Features">
			<?php if(is_active_sidebar('footer_5')): dynamic_sidebar('footer_5'); endif; ?>
			</section>
			</div>
		</div>
	</body>
	</html>