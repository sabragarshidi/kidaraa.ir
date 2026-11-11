<?php
if (post_password_required()) return;

/** Comment renderer (demo: .media layout; avatar 120px, reply link). */
if (!function_exists('kidara_comment')) {
    function kidara_comment($comment, $args, $depth)
    {
        ?>
        <li id="comment-<?php comment_ID(); ?>" <?php comment_class('list-none'); ?>>
            <div id="div-comment-<?php comment_ID(); ?>" class="flex gap-5 pb-8.75 max-[575px]:gap-3.5">
                <div class="shrink-0"><?= get_avatar($comment, 120, '', '', ['class' => 'block size-30 rounded-full bg-[#ccc] max-[575px]:size-15']) ?></div>
                <div class="min-w-0 flex-1">
                    <h4 class="m-0 text-base leading-[1.8] text-heading"><?= esc_html(get_comment_author($comment)) ?></h4>
                    <div class="mb-1 text-sm text-body">
                        <a href="<?= esc_url(get_comment_link($comment)) ?>" class="text-body"><time datetime="<?= esc_attr(get_comment_date('c', $comment)) ?>"><?= esc_html(get_comment_date('F j, Y', $comment) . ' at ' . get_comment_time('g:i a')) ?></time></a>
                        <?= get_comment_reply_link(array_merge($args, ['depth' => $depth, 'before' => '<span class="mx-1">/</span>', 'reply_text' => 'Reply'])) ?>
                    </div>
                    <?php if ($comment->comment_approved === '0') : ?><p class="italic">Your comment is awaiting moderation.</p><?php endif; ?>
                    <div class="text-sm [&>p]:mb-2"><?php comment_text(); ?></div>
                </div>
            </div>
        <?php
    }
}
$n = get_comments_number();
$field = 'h-[47px] w-full rounded-btn border border-border bg-white px-6.25 py-0 text-base text-heading outline-none transition-colors focus:border-accent';
?>
<div id="comments" class="mt-15 border-t border-border pt-10">
    <?php if (have_comments()) : ?>
        <h3 class="m-0 mb-7.5 text-center text-[30px] leading-[1.2]"><?= esc_html(sprintf(_n('%s Comment', '%s Comments', $n), number_format_i18n($n))) ?></h3>
        <ol class="kd-comment-list m-0 list-none p-0">
            <?php wp_list_comments(['callback' => 'kidara_comment', 'style' => 'ol', 'avatar_size' => 120]); ?>
        </ol>
        <?php the_comments_pagination(); ?>
    <?php endif; ?>

    <div class="mt-10">
        <?php
        comment_form([
            'title_reply'          => 'Leave a Reply',
            'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title m-0 mb-1.5 text-center text-[30px] leading-[1.2]">',
            'title_reply_after'    => '</h3>',
            'cancel_reply_before'  => ' <small class="text-sm">',
            'comment_notes_before' => '<p class="mb-7.5 text-center text-xs">Your email address will not be published. Required fields are marked <span class="text-[#d33]">*</span></p>',
            'comment_notes_after'  => '',
            'logged_in_as'         => '',
            'fields'               => [
                'author' => '<div class="kd-field"><input id="author" name="author" type="text" required placeholder="Name *" class="' . $field . '"></div>',
                'email'  => '<div class="kd-field"><input id="email" name="email" type="email" required placeholder="Email *" class="' . $field . '"></div>',
                'url'    => '<div class="kd-field kd-field-full"><input id="url" name="url" type="text" placeholder="Website" class="' . $field . '"></div>',
                'cookies' => '',
            ],
            'comment_field'        => '<div class="kd-field kd-field-full"><textarea id="comment" name="comment" rows="12" required placeholder="Your Comment Here..." class="block h-87 w-full resize-y rounded-btn border border-border bg-white px-6.25 py-2.5 text-base text-heading outline-none transition-colors focus:border-accent"></textarea></div>',
            'class_form'           => 'kd-comment-form',
            'submit_button'        => '<button name="%1$s" type="submit" id="%2$s" class="%3$s cursor-pointer rounded-btn border-0 bg-[#d33] px-10 py-2.5 text-sm font-bold text-white transition-colors hover:bg-accent-dark">%4$s</button>',
            'submit_field'         => '<div class="kd-field kd-field-full text-center">%1$s %2$s</div>',
            'label_submit'         => 'Post Comment',
            'format'               => 'html5',
        ]);
        ?>
    </div>
</div>
