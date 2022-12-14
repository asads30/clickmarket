<?php
/**
 * The template for displaying the footer.
 *
 * @since   1.0.0
 * @package Kalles
 */
?>
			<?php the4_kalles_footer(); ?>
		</div><!-- #the4-wrapper -->
		<a id="the4-backtop" class="pf br__50"><span class="tc bgp br__50 db cw"><i class="pr pe-7s-angle-up"></i></span></a>

		<?php if ( cs_get_option( 'preloader' ) ) : ?>
			<div class="preloader pf">
				<?php if ( cs_get_option( 'preloader-type' ) == 'css' ) : ?>
					<div class="progress pa">
						<div class="indeterminate"></div>
					</div>
				<?php elseif ( cs_get_option( 'preloader-img' ) ) : ?>
					<?php $img = cs_get_option( 'preloader-img' ); ?>
					<?php $img = wp_get_attachment_image_src( $img['id'], 'full', true ); ?>

					<img class="pr" src="<?php echo esc_url( $img[0] ); ?>" width="<?php echo esc_attr( $img[1] ); ?>" height="<?php echo esc_attr( $img[2] ); ?>" alt="<?php get_bloginfo( 'name' ); ?>" />
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<!-- Glicon Symbols -->
        <?php get_template_part('views/common/gliconsymbols'); ?>
		<div class="mask-overlay ntpf t__0 r__0 l__0 b__0 op__0 pe_none"></div>
		<!-- Yandex.Metrika counter -->
		<script type="text/javascript" >
			(function (d, w, c) {
				(w[c] = w[c] || []).push(function() {
					try {
						w.yaCounter86507788 = new Ya.Metrika({
							id:86507788,
							clickmap:true,
							trackLinks:true,
							accurateTrackBounce:true,
							webvisor:true,
							ecommerce:"dataLayer"
						});
					} catch(e) { }
				});

				var n = d.getElementsByTagName("script")[0],
					s = d.createElement("script"),
					f = function () { n.parentNode.insertBefore(s, n); };
				s.type = "text/javascript";
				s.async = true;
				s.src = "https://mc.yandex.ru/metrika/watch.js";

				if (w.opera == "[object Opera]") {
					d.addEventListener("DOMContentLoaded", f, false);
				} else { f(); }
			})(document, window, "yandex_metrika_callbacks");
		</script>
		<noscript><div><img src="https://mc.yandex.ru/watch/86507788" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
		<!-- /Yandex.Metrika counter -->
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=G-8FLJ8RF34K"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());

		  gtag('config', 'G-8FLJ8RF34K');
		</script>
		<?php wp_footer(); ?>
	</body>
</html>