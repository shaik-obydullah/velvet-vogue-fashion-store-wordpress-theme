<?php
/**
 * Comments Template
 *
 * @package Velvet_Vogue_Fashion_Store
 */

if ( post_password_required() ) return;
?>

<div id="comments" class="vvfs-comments-area">

  <?php if ( have_comments() ) : ?>
    <h2 class="text-xl font-bold font-serif text-white mb-6">
      <?php
      $comment_count = get_comments_number();
      printf(
          esc_html( _n( '%s Comment', '%s Comments', $comment_count, 'velvet-vogue-fashion-store' ) ),
          esc_html( $comment_count )
      );
      ?>
    </h2>

    <ol class="comment-list">
      <?php
      wp_list_comments( array(
          'style'       => 'ol',
          'short_ping'  => true,
          'avatar_size' => 50,
          'callback'    => 'vvfs_comment_template',
      ) );
      ?>
    </ol>

    <?php the_comments_navigation(); ?>

  <?php endif; ?>

  <?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
    <p class="no-comments text-zinc-400 font-light"><?php esc_html_e( 'Comments are closed.', 'velvet-vogue-fashion-store' ); ?></p>
  <?php endif; ?>

  <?php comment_form( array(
      'title_reply'          => esc_html__( 'Leave a Comment', 'velvet-vogue-fashion-store' ),
      'title_reply_before'   => '<div id="respond" class="vvfs-comment-respond mt-10"><h3 class="text-xl font-bold font-serif text-white mb-6">',
      'title_reply_after'    => '</h3>',
      'comment_notes_after'  => '</div>',
      'cancel_reply_before'  => ' <small>',
      'cancel_reply_after'   => '</small>',
      'cancel_reply_link'    => esc_html__( 'Cancel reply', 'velvet-vogue-fashion-store' ),
      'label_submit'         => esc_html__( 'Post Comment', 'velvet-vogue-fashion-store' ),
      'submit_button'        => '<button type="submit" name="%1$s" id="%2$s" class="btn-cart px-6 py-3 text-sm">%4$s</button>',
      'submit_field'         => '<p class="form-submit">%1$s %3$s</p>',
  ) ); ?>

</div>

<?php
