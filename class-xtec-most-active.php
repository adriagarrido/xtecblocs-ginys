<?php
/**
 * Undocumented file.
 *
 * @package category
 */

/**
 * Undocumented class
 */
class XTEC_Most_Active extends WP_Widget {

	/**
	 * Class constructor
	 */
	public function __construct() {

		$widget_ops = array(
			'classname'   => 'XTEC_most_active',
			'description' => 'Llista els blocs mes actius',
		);

		parent::__construct( 'xtec_most_active', 'Most Active', $widget_ops );

	}

	/**
	 * Undocumented function
	 *
	 * @param [type] $args No comment yet.
	 * @param [type] $instance No comment yet.
	 * @return void
	 */
	public function widget( $args, $instance ) {

		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'];
			echo esc_html( apply_filters( 'widget_title', $instance['title'] ) );
			echo $args['after_title'];
		}

		$most_active = xtec_lastest_posts_most_active_blogs();

		if ( count( $most_active ) > 0 ) {
			echo '<ul>';
			foreach ( $most_active as $active ) {
				$titol_blog = trim( stripslashes( $active['blog_title'] ) );
				$url_blog   = trim( stripslashes( $active['blog_url'] ) );
				if ( empty( $titol_blog ) ) {
					$titol_blog = 'Blog ' . substr( $url_blog, strrpos( $url_blog, '/' ) + 1 );
				}
				?>
				<li>
					<a href='<?php echo esc_attr( $active['blog_url'] ); ?>' target="_blank" title="Entra al blog">
						<?php echo esc_html( $titol_blog ); ?>
					</a>
					&nbsp;
					<?php if ( is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( 'index.php?action=add_favorite&blog_id=' . $active['blogId'] . '&xg_key=' . wp_create_nonce( 'xg_key' ) ); ?>" title="Preferit">
						<?php if ( 'old' === $instance['style'] ) : ?>
						<img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '/images/myblogs.gif' ); ?>" alt="Preferit" />
						<?php elseif ( 'new' === $instance['style'] ) : ?>
						<i class="fas fa-heart"></i>
						<?php endif; ?>
					</a>
					<?php endif; ?>
				</li>
				<?php
			}
			echo '</ul>';
		}

		echo $args['after_widget'];

	}

	/**
	 * Output the option form field in admin Widgets screen.
	 *
	 * @param [type] $instance No comment yet.
	 * @return void
	 */
	public function form( $instance ) {

		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Els blocs més actius', 'text_domain' );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php esc_attr_e( 'Title:', 'text_domain' ); ?>
			</label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label>
				<?php esc_attr_e( 'Style: ', 'text_domain' ); ?>
			</label>
			<br />
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'style-old' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'style' ) ); ?>" type="radio" value="<?php echo esc_attr( 'old' ); ?> " <?php echo esc_attr( ( 'old' === $instance['style'] ) ? 'checked' : '' ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id( 'style-old' ) ); ?>">
				<?php esc_attr_e( 'Old', 'text_domain' ); ?>
			</label>
			<br />
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'style-new' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'style' ) ); ?>" type="radio" value="<?php echo esc_attr( 'new' ); ?> " <?php echo esc_attr( ( 'new' === $instance['style'] ) ? 'checked' : '' ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id( 'style-new' ) ); ?>">
				<?php esc_attr_e( 'New', 'text_domain' ); ?>
			</label>
		</p>
		<?php

	}

	/**
	 * Save options.
	 *
	 * @param [type] $new_instance No comment yet.
	 * @param [type] $old_instance No comment yet.
	 * @return array $instance
	 */
	public function update( $new_instance, $old_instance ) {

		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? wp_strip_all_tags( $new_instance['title'] ) : 'Els blocs més actius';
		$instance['style'] = ( ! empty( $new_instance['style'] ) ) ? wp_strip_all_tags( $new_instance['style'] ) : 'old';

		return $instance;

	}

}
