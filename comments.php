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
/**
 * Custom comment callback for wp_list_comments
 */
function vvfs_comment_template( $comment, $args, $depth ) {
    $tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
    ?>
    <<?php echo esc_attr( $tag ); ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( 'vvfs-comment' ); ?>>
      <div class="flex items-start gap-4">
        <?php echo get_avatar( $comment, $args['avatar_size'], '', '', array( 'class' => 'rounded-full border-2 border-rose-500/30 flex-shrink-0' ) ); ?>
        <div class="flex-1">
          <div class="flex items-center gap-3 mb-1">
            <span class="comment-author font-bold text-white font-serif"><?php comment_author(); ?></span>
            <span class="comment-meta text-zinc-500 text-xs">
              <i class="fa-regular fa-clock mr-1"></i><?php comment_date(); ?>
            </span>
          </div>
          <div class="comment-content text-zinc-300 text-sm leading-relaxed mt-2">
            <?php comment_text(); ?>
          </div>
          <?php
          comment_reply_link( array_merge( $args, array(
              'depth'     => $depth,
              'max_depth' => $args['max_depth'],
              'before'    => '<div class="mt-2">',
              'after'     => '</div>',
          ) ) );
          ?>
        </div>
      </div>
    <?php
}
