<?php
class TC_Nav_Menu_Widget extends WP_Widget {

	public function __construct() {
		$widget_ops = array( 'description' => __('Add a custom menu to your sidebar.') );
		parent::__construct( 'nav_menu', __('Custom Menu'), $widget_ops );
	}

	public function widget($args, $instance) {
		// Get menu
		$nav_menu = ! empty( $instance['nav_menu'] ) ? wp_get_nav_menu_object( $instance['nav_menu'] ) : false;
		if ( !$nav_menu )
			return;
			
		$nav_menu_id=$nav_menu->term_id;
		$current_user_id = get_current_user_id();
		$post_type = get_post_type( get_the_ID()); 
		$portfolio_menu_id =368;
               
		/** This filter is documented in wp-includes/default-widgets.php */
		$instance['title'] = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
                echo $args['before_widget'];

		if ($nav_menu_id == $portfolio_menu_id){
		
			if (($current_user_id > 0 && $post_type <> 'course_unit' && $post_type <> 'topic' && $post_type <> 'forum') || ( is_home() && $current_user_id > 0 )){
			 if ( !empty($instance['title']) ){
			 echo $args['before_title'] . $instance['title'] . $args['after_title'];
			 }

			wp_nav_menu( array( 'fallback_cb' => '', 'menu' => $nav_menu ) );
			}
			else{
			
			}
		}		
		else{
		    if ( !empty($instance['title']) ){
			echo $args['before_title'] . $instance['title'] . $args['after_title'];
                    }
		 wp_nav_menu( array( 'fallback_cb' => '', 'menu' => $nav_menu ) );
                }
		
		
		echo $args['after_widget'];
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		if ( ! empty( $new_instance['title'] ) ) {
			$instance['title'] = strip_tags( stripslashes($new_instance['title']) );
		}
		if ( ! empty( $new_instance['nav_menu'] ) ) {
			$instance['nav_menu'] = (int) $new_instance['nav_menu'];
		}
		return $instance;
	}

	public function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		$nav_menu = isset( $instance['nav_menu'] ) ? $instance['nav_menu'] : '';

		// Get menus
		$menus = wp_get_nav_menus( array( 'orderby' => 'name' ) );

		// If no menus exists, direct the user to go and create some.
		if ( !$menus ) {
			echo '<p>'. sprintf( __('No menus have been created yet. <a href="%s">Create some</a>.'), admin_url('nav-menus.php') ) .'</p>';
			return;
		}
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('nav_menu'); ?>"><?php _e('Select a menu:'); ?></label>
			<select id="<?php echo $this->get_field_id('nav_menu'); ?>" name="<?php echo $this->get_field_name('nav_menu'); ?>">
				<option value="0"><?php _e( '&mdash; Select &mdash;' ) ?></option>
		<?php
			foreach ( $menus as $menu ) {
				echo '<option value="' . $menu->term_id . '"'
					. selected( $nav_menu, $menu->term_id, false )
					. '>'. esc_html( $menu->name ) . '</option>';
			}
		?>
			</select>
		</p>
		<?php
	}
}
?>