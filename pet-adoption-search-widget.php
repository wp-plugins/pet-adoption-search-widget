<?php

/*
Plugin Name: Pet Adoption Search Widget
Plugin URI:
Description: Display an Adopt-a-Pet.com pet adoption search form in a widget
Version: 1.0
Author: Chris Hardie
Author URI: http://www.chrishardie.com/
License: GPL2
*/

/*

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

defined('ABSPATH') or die("Please don't try to run this file directly.");

class Pet_Adoption_Search_Widget extends WP_Widget {
	/**
	 * Register the widget with WordPress
	 */
	function __construct() {
		parent::__construct(
			'pet_adoption_search_widget', // base id
			__( 'Pet Adoption Search Widget', 'pet_adoption_search_widget_domain' ), // name
			array( 'description' => __( 'A widget to display a pet adoption search form for Adopt-a-Pet.com.', 'pet_adoption_search_widget_domain' ) )
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	function widget( $args, $instance ) {
		extract( $args, EXTR_SKIP );
		$title = apply_filters( 'widget_title', $instance['title'] );

		// Output the widget content
		echo $before_widget;

		// Title
		if ( !empty( $title ) ) {
			echo $before_title . $title . $after_title;
		}

		// Adapted from http://www.adoptapet.com/public/searchtools/
		// In future versions we could allow some customization to match the theme.
		echo '<div class="pet_adoption_search_widget_main" style="text-align: center;">
			<iframe width="150" height="240" frameborder="0" marginwidth="0" marginheight="0" scrolling="0"
			        src="http://searchtools.adoptapet.com/public/searchtools/display/150x240"></iframe>';

		// Show credit link
		if ( $instance['show_credit_p'] ) {

			echo '<div class="pet_adoption_search_widget_credit" style="font-size: x-small;">Pet adoption and rescue <br/>powered by
				<a href="http://www.adoptapet.com/" title="Pet adoption and rescue powered by Adopt-a-Pet.com">Adopt-a-Pet.com</a>
			</div>';

		}

		echo '</div>';

		echo $after_widget;
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance          = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['show_credit_p'] = !empty($new_instance['show_credit_p']) ? 1 : 0;

		return $instance;

	}

	/**
	 * Back-end form to manage a widget's options in wp-admin
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

		if ( isset( $instance['title'] ) ) {
			$title = $instance['title'];
		} else {
			$title = __( 'Search for an Adoptable Pet', 'pet_adoption_search_widget_domain' );
		}
		// Should we show the credit link to Adopt-a-Pet.com? (Must be opt-in per WordPress.org guidelines.)
		$show_credit_p = isset( $instance['show_credit_p'] ) ? (bool) $instance['show_credit_p'] : false;

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
			       name="<?php echo $this->get_field_name( 'title' ); ?>" type="text"
			       value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked( $show_credit_p ); ?> id="<?php echo $this->get_field_id( 'show_credit_p' ); ?>" name="<?php echo $this->get_field_name( 'show_credit_p' ); ?>" />
			<label for="<?php echo $this->get_field_id( 'show_credit_p' ); ?>"><?php _e( 'Include credit link to Adopt-a-Pet.com?' ); ?></label>
		</p>
	<?php

	}

}

class JCH_PetAdoptionSearchWidget {
	function __construct() {
		add_action( 'init', array( $this, 'init' ), 1 );
	}

	public function init() {
		register_widget( 'Pet_Adoption_Search_Widget' );
	}


}

$jch_pet_adoption_search_widget = new JCH_PetAdoptionSearchWidget();

?>