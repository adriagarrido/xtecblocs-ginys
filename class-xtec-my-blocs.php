<?php
/**
 * Undocumented file.
 *
 * @package category
 */

/**
 * Undocumented class
 */
class XTEC_My_Blocs extends WP_Widget {

	/**
	 * Class constructor
	 */
	public function __construct() {

		$widget_ops = array(
			'classname'   => 'XTEC_my_blocs',
			'description' => 'Llista els blocs de l\'usuari',
		);

		parent::__construct( 'xtec_my_blocs', 'My Blocs', $widget_ops );

	}

	/**
	 * Output the widget content on the front-end
	 *
	 * @param [type] $args No comment yet.
	 * @param [type] $instance No comment yet.
	 * @return void
	 */
	public function widget( $args, $instance ) {

		global $current_user;

		echo $args['before_widget'];

		if ( is_user_logged_in() ) {
			$blogs = get_blogs_of_user( $current_user->ID );
		}

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'];
			echo esc_html( apply_filters( 'widget_title', $instance['title'] ) );
			echo $args['after_title'];
		}

		if ( ! empty( $blogs ) ) {
			echo '<ul>';
			foreach ( $blogs as $blog ) {
				$value = 'wp_' . $blog->userblog_id . '_user_level';
				$level = $current_user->$value;
				switch ( $level ) {
					case '':
						$image = 'subscrip';
						$text  = 'Entra';
						break;
					case 10:
						$image = 'admin';
						$text  = 'Administra';
						break;
					default:
						$image = 'edit';
						$text  = 'Escriu';
				}
				$number = xtec_descriptors_count_bloc_descriptors( $blog->userblog_id );
				?>
				<li>
					<a href="<?php echo esc_attr( 'http://' . $blog->domain . $blog->path ); ?>" target="_blank" title="Entra al bloc"><?php echo esc_html( stripslashes( $blog->blogname ) ); ?></a>
					&nbsp;
					<?php if ( 'old' === $instance['style'] ) : ?>
						<a href="<?php echo esc_attr( 'http://' . $blog->domain . $blog->path . 'wp-admin/' ); ?>" target="_blank" title="<?php echo esc_attr( $text ); ?>">
							<img src="<?php echo esc_attr( plugin_dir_url( __FILE__ ) . 'images/' . $image . '.gif' ); ?>" alt="<?php echo esc_attr( $text ); ?>" class="myicon" />
						</a>
						<?php if ( 'admin' === $image && 1 !== $blog->userblog_id ) : ?>
						<a href="<?php echo esc_attr( 'http://' . $blog->domain . $blog->path . 'wp-admin/ms-delete-site.php' ); ?>" target="_blank" title="Elimina el bloc">
							<img src="<?php echo esc_attr( plugin_dir_url( __FILE__ ) . 'images/delete.gif' ); ?>" alt="Elimina el Bloc" class="myicon" />
						</a>
						<?php endif; ?>
					<?php else : ?>
						<a href="<?php echo esc_attr( 'http://' . $blog->domain . $blog->path . 'wp-admin/' ); ?>" target="_blank" title="<?php echo esc_attr( $text ); ?>" >
							<?php
							if ( 'subscrip' === $image ) {
								$image = 'user';
							}
							if ( 'admin' === $image ) {
								$image = 'tools';
							}
							if ( 'edit' === $image ) {
								$image = 'edit';
							}
							?>
							<i class="fas fa-<?php echo esc_attr( $image ); ?> my-blocs-image-color"></i>
						</a>
						<?php if ( 'tools' === $image && 1 !== $blog->userblog_id ) : ?>
						<a href="<?php echo esc_attr( 'http://' . $blog->domain . $blog->path . 'wp-admin/ms-delete-site.php' ); ?>" target="_blank" title="Elimina el bloc" >
							<i class="fas fa-trash my-blocs-image-color"></i>
						</a>
						<?php endif; ?>
					<?php endif; ?>
				</li>
				<?php
			}
			echo '</ul>';
		} else {
			echo esc_html__( 'No posts selected!', 'text_domain' );
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

		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Els meus blocs', 'text_domain' );
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

		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? wp_strip_all_tags( $new_instance['title'] ) : 'Els meus blocs';
		$instance['style'] = ( ! empty( $new_instance['style'] ) ) ? wp_strip_all_tags( $new_instance['style'] ) : 'old';

		return $instance;

	}

}
