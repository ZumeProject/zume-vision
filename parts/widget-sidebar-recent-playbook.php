<h3><a href="/playbook">Newest Plays</a></h3>
<ul>
    <?php
    $recent_posts = wp_get_recent_posts( [
        'post_type' => 'playbook',
        'numberposts'  => 4,
        'post_status' => 'publish'
    ] );
    foreach ( $recent_posts as $recent ) {
        printf( '<li><a href="%1$s">%2$s</a></li>',
            esc_url( get_permalink( $recent['ID'] ) ),
            apply_filters( 'the_title', $recent['post_title'], $recent['ID'] )
        );
    }
    ?>
</ul>
