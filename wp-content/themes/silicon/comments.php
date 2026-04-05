<?php
/**
 * The template for displaying comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package silicon
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<section id="comments" class="comments-area mt-5 pt-2" aria-label="<?php esc_attr_e( 'Post Comments', 'silicon' ); ?>">

	<?php if ( have_comments() ) : ?>
	<div class="mb-4 pt-lg-4 pb-lg-3">
		<h2 class="comments-title text-center text-sm-start">
			<?php
				// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
				printf(
					/* translators: %s - number of comments */
					esc_html( _nx( '%s comment', '%s comments', get_comments_number(), 'number of comments', 'silicon' ) ),
					number_format_i18n( get_comments_number() )
				);
				// phpcs:enable
			?>
		</h2>

		<div class="comment-list">
			<?php
				wp_list_comments(
					array(
						'style'      => 'div',
						'short_ping' => true,
						'callback'   => 'silicon_comment',
					)
				);
			?>
		</div><!-- .comment-list -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through. ?>
		<nav id="comment-nav-below" class="comment-navigation pt-lg-3 d-flex" role="navigation" aria-label="<?php esc_attr_e( 'Comment Navigation Below', 'silicon' ); ?>">
			<span class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'silicon' ); ?></span>
			<div class="nav-previous"><?php previous_comments_link( esc_html__( '&larr; Older Comments', 'silicon' ) ); ?></div>
			<div class="nav-next ms-auto"><?php next_comments_link( esc_html__( 'Newer Comments &rarr;', 'silicon' ) ); ?></div>
		</nav><!-- #comment-nav-below -->
		<?php endif; // Check for comment navigation. ?>

	</div>
		<?php

	endif;

	if ( ! comments_open() && 0 !== intval( get_comments_number() ) && post_type_supports( get_post_type(), 'comments' ) ) :
		?>
		<p class="no-comments alert alert-warning"><?php esc_html_e( 'Comments are closed.', 'silicon' ); ?></p>
		<?php
	endif;

	$required_indicator = ' <span class="required" aria-hidden="true">*</span>';
	$required_attribute = ' required';

	$args = apply_filters(
		'silicon_comment_form_args',
		array(
			'after'              => '</div></div></div>',
			'title_reply_before' => '<div class="pb-2 pb-lg-5"><div class="card p-md-5 p-4 border-0 bg-secondary"><div class="card-body w-100 mx-auto px-0" style="max-width: 746px;"><h2 id="reply-title" class="mb-4 pb-3 comment-reply-title">',
			'title_reply_after'  => '</h2>',
			'class_form'         => 'comment-form row gy-4',
			'class_container'    => 'comment-respond mb-4 mb-md-5',
			'class_submit'       => 'submit btn btn-lg btn-primary w-sm-auto w-100 mt-2',
			'comment_field'      => sprintf(
				'<p class="comment-form-comment">%s %s</p>',
				sprintf(
					'<label class="form-label fs-base" for="comment">%s%s</label>',
					_x( 'Comment', 'noun', 'silicon' ),
					$required_indicator
				),
				'<textarea class="form-control form-control-lg" id="comment" name="comment" cols="45" rows="8" maxlength="65525"' . $required_attribute . '></textarea>'
			),
		)
	);

	comment_form( $args );

	?>

</section><!-- #comments -->
