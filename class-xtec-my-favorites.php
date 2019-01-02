<?php
/**
 * Undocumented file.
 *
 * @package category
 */

/**
 * Undocumented class
 */
class XTEC_My_Favorites extends WP_Widget {

	/**
	 * Class constructor
	 */
	public function __construct() {

		$widget_ops = array(
			'classname'   => 'XTEC_my_favorites',
			'description' => 'Llista els blocs favorits de l\'usuari',
		);

		parent::__construct( 'xtec_my_favorites', 'My Favorites', $widget_ops );

	}

	/**
	 * Undocumented function
	 *
	 * @param array $args No comment yet.
	 * @param array $instance No comment yet.
	 * @return void
	 */
	public function widget( $args, $instance ) {

		echo $args['before_widget'];

		if ( is_user_logged_in() ) {
			/** @todo Do it in the class not in the external plugin. */
			$blogs = xtec_favorites_get_user_preferred_blogs();
		}

		if ( count( $blogs ) > 0 ) {
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'];
				echo esc_html( apply_filters( 'widget_title', $instance['title'] ) );
				echo $args['after_title'];
			}
			echo '<ul>';
			foreach ( $blogs as $blog ) {
				// If blog's titles is empty, compose title from url (Ex: http://agora/blocs/elspinguins/ --> elspinguins).
				$titol_blog = trim( get_blog_option( $blog, 'blogname' ) );
				$url_blog   = trim( $url_blog, '/' );

				if ( empty( $titol_blog ) ) {
					$url_blog   = get_blog_option( $blog, 'siteurl' );
					$titol_blog = 'Bloc ' . substr( $url_blog, strrpos( $url_blog, '/' ) + 1 );
				} else {
					$titol_blog = stripslashes( get_blog_option( $blog, 'blogname' ) );
					?>
					<li>
						<a href='<?php echo esc_url( get_blogaddress_by_id( $blog ) ); ?>' target="_blank" title="Entra al bloc">
							<?php echo esc_html( $titol_blog ); ?>
						</a>
						&nbsp;
						<a href="index.php?action=delete_favorite&blog_id=<?php echo esc_html( $blog ); ?>" title="Esborra">
							<?php if ( 'old' === $instance['style'] ) : ?>
							<img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '/images/delete.gif' ); ?>" alt="Esborra" />
							<?php elseif ( 'new' === $instance['style'] ) : ?>
							<i class="fas fa-trash my-favorites-image-color"></i>
							<?php endif; ?>
						</a>
					</li>
					<?php
				}
			}
			echo '</ul>';
		} elseif ( 'on' === $instance['show-message'] ) {
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'];
				echo esc_html( apply_filters( 'widget_title', $instance['title'] ) );
				echo $args['after_title'];
			}
			echo esc_html( $instance['message'] );
		}

		echo $args['after_widget'];

	}

	/**
	 * Undocumented function
	 *
	 * @param array $instance No comment yet.
	 *
	 * @return void
	 */
	public function form( $instance ) {

		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'El meus preferits', 'text_domain' );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php esc_attr_e( 'Title:', 'text_domain' ); ?>
			</label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label>
				<?php esc_attr_e( 'Style:', 'text_domain' ); ?>
			</label>
			<br />
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'style-old' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'style' ) ); ?>" type="radio" value="<?php echo esc_attr( 'old' ); ?> " <?php checked( $instance['style'], 'old' ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'style-old' ) ); ?>">
				<?php esc_attr_e( 'Old', 'text_domain' ); ?>
			</label>
			<br />
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'style-new' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'style' ) ); ?>" type="radio" value="<?php echo esc_attr( 'new' ); ?> " <?php checked( $instance['style'], 'new' ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'style-new' ) ); ?>">
				<?php esc_attr_e( 'New', 'text_domain' ); ?>
			</label>
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $instance['show-message'], 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show-message' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show-message' ) ); ?>" />
			<label for="<?php echo esc_attr( $this->get_field_id( 'show-message' ) ); ?>">
				<?php esc_attr_e( 'Show message', 'text_domain' ); ?>
			</label>
			<br/>
			<label for="<?php echo esc_attr( $this->get_field_id( 'message' ) ); ?>">
				<?php echo esc_html_e( 'Message:' ); ?>
			</label>
			<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'message' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'message' ) ); ?>"><?php echo esc_attr( $instance['message'] ); ?></textarea>
		</p>
		<?php

	}

	/**
	 * Save options.
	 *
	 * @param array $new_instance No comment yet.
	 * @param array $old_instance No comment yet.
	 *
	 * @return array $instance
	 */
	public function update( $new_instance, $old_instance ) {

		$instance['title']        = ( ! empty( $new_instance['title'] ) ) ? wp_strip_all_tags( $new_instance['title'] ) : 'El meus preferits';
		$instance['style']        = ( ! empty( $new_instance['style'] ) ) ? wp_strip_all_tags( $new_instance['style'] ) : 'old';
		$instance['show-message'] = $new_instance['show-message'];
		$instance['message']      = wp_strip_all_tags( $new_instance['message'] );

		return $instance;

	}

	/**
	 * Undocumented function
	 *
	 * @param integer $blog_id No comment yet.
	 * @return void
	 */
	public function xg_delete_favorite( $blog_id ) {

		global $wpdb;

		$current_user = wp_get_current_user();

		$wpdb->delete(
			$wpdb->base_prefix . 'user_blogs',
			array(
				'blogId' => $blog_id,
				'userId' => $current_user->ID,
			),
			array( '%d', '%s' )
		);

	}

}
