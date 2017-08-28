<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * ALXWP: Meta Boxes
 * 
 * This class adds custom meta boxes for post types.
 *
 * @version    1.0.0
 * @link       https://github.com/AlexandruDoda/ALXWP-Meta
 * @author     Alexandru Doda <https://alexandru.co>
 */

if( ! class_exists( 'ALXWP_Meta' ) ) {
	
	class ALXWP_Meta {

		/**
		 * The meta boxes to be registered.
		 *
		 * @var    object    $meta_boxes
		 */
		private $meta_boxes;

		/**
		 * The post type to have meta boxes registered for.
		 *
		 * @var    object    $post_type
		 */
		private $post_type;

		/**
		 * A field container used as a temporary variable.
		 *
		 * @var    object    $field
		 */
		private $field;

		/**
		 * Counter that keeps track of the meta boxes.
		 *
		 * @var    int    $counter
		 */
		private $counter = 0;

		/**
		 * Construct the class.
		 */
		public function __construct( $post_type, $meta_boxes ) {

			// Initialize the meta boxes.
			$this->meta_boxes = $meta_boxes;

			// Initialize the post type.
			$this->post_type = $post_type;

			// Enqueue the scripts and stylesheets.
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			// Add the meta boxes to the post admin page.
			add_action( 'add_meta_boxes_' . $post_type, array( $this, 'add_meta_boxes' ) );
			add_action( 'save_post_' . $post_type, array( $this, 'save_meta_boxes' ) );

		}

		/**
		 * Enqueue the admin stylesheets.
		 *
		 * @link https://developer.wordpress.org/reference/functions/wp_enqueue_style
		 */
		public function enqueue_styles() {
			
			// // Enqueue and localize the admin plugin stylesheet.
			wp_enqueue_style( 'alxwp-meta', plugin_dir_url( __FILE__ ) . 'assets/css/alxwp-meta.css', array(), '1.0.0', 'all' );

		}

		/**
		 * Enqueue the admin scripts.
		 * 
		 * @link https://developer.wordpress.org/reference/functions/wp_enqueue_script
		 */
		public function enqueue_scripts() {

			// // Enqueue and localize the admin plugin script.
			wp_enqueue_script( 'alxwp-meta', plugin_dir_url( __FILE__ ) . 'assets/js/alxwp-meta.js', array( 'jquery' ), '1.0.0', true );

		}

		/**
		 * Register the plugin meta boxes.
		 *
		 * @link https://codex.wordpress.org/Plugin_API/Action_Reference/add_meta_boxes
		 */
		public function add_meta_boxes() {
		
			foreach( $this->meta_boxes as $meta_box ) {
				add_meta_box( 
					$meta_box['id'] . '_meta_box', 
					$meta_box['title'], 
					array( $this, 'meta_box' ), 
					$this->post_type, 
					$meta_box['context'], 
					$meta_box['priority'] 
				);
			}

		}

		/**
		 * Generate the markup for a meta box.
		 *
		 * @param     int    $post    The post id to register the meta box for.
		 */
		public function meta_box( $post ) {

			// Retrieve the current values.
			$meta_box = $this->meta_boxes[ $this->counter ];
			
			// Make sure the form request comes from WordPress.
			wp_nonce_field( basename( __FILE__ ), $meta_box['id'] . '_meta_box_nonce' );

			// Get the current data from the database.
			foreach( $meta_box['fields'] as $field ) { 

				// Extend the field ID with the meta box.
				$field['id'] = $meta_box['id'] . '_' . $field['id'];
				
				// Retrieve the current field value.
				$field['value'] = get_post_meta( $post->ID, '_' . $field['id'], true );

				// If none is set, fallback to default.
				if( empty( $field['value'] ) && isset( $field['default'] ) && !in_array( $field['type'], array( 'checkbox', 'toggle' ) ) ) {
					update_post_meta( $post->ID, '_' . $field['id'], $field['default'] );
					$field['value'] = get_post_meta( $post->ID, '_' . $field['id'], true );
				}
				
				// Format the field for checkboxes and toggles.
				if( $field['type'] == 'checkbox' || $field['type'] == 'toggle' || $field['type'] == 'repeater' ) {
					$field['value'] = ( $field['value'] ) ? $field['value'] : array();
				} 
				
				// Store the field to the class instance.
				$this->field = $field; ?>

				<div id="<?php echo $meta_box['id'] ?>_<?php echo $field['id'] ?>" class="field">

					<p class="label">
						<label for="<?php echo $field['id'] ?>">
							<strong><?php echo $field['title'] ?></strong>
						</label>
					</p>

					<span class="value">
						<?php echo $this->field(); ?>
					</span>

				</div>

			<?php }

			// Increment the class meta counter.
			$this->counter += 1;

		}

		/**
		 * Store custom field meta box data
		 *
		 * @link     https://codex.wordpress.org/Plugin_API/Action_Reference/save_post
		 * @param    int    $post_id    The post ID.
		*/
		public function save_meta_boxes( $post_id ) {
			
			// Do not run if it's an autosave.
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
				return;
			}
			
			// Check if the user is allowed to edit the post.
			if ( ! current_user_can( 'edit_post', $post_id ) ){
				return;
			}

			// Process each meta box.
			foreach( $this->meta_boxes as $meta_box ) {

				// Verify the meta box nonce.
				if ( !isset( $_POST[ $meta_box['id'] . '_meta_box_nonce' ] ) || !wp_verify_nonce( $_POST[ $meta_box['id'] . '_meta_box_nonce' ], basename( __FILE__ ) ) ){
					return;
				}

				// Save the data based on field type.
				foreach( $meta_box['fields'] as $field ) {

					// Extend the field ID with the meta box.
					$field['id'] = $meta_box['id'] . '_' . $field['id'];

					// Check if the meta is set and process it.
					if( isset( $_POST[ $field['id'] ] ) ) {
							
						if( $field['type'] == 'checkbox' || $field['type'] == 'toggle' || $field['type'] == 'repeater' ) {
							update_post_meta( $post_id, '_' . $field['id'], array_map( 'sanitize_text_field', (array) $_POST[ $field['id'] ] ) );
						} else {
							update_post_meta( $post_id, '_' . $field['id'], sanitize_text_field( $_POST[ $field['id'] ] ) );
						}

					} else {
						
						delete_post_meta( $post_id, '_' . $field['id'] );

					}

				}

			}

		}

		/**
		 * Field
		 *
		 * @return    string    The field.
		 */
		private function field() {
			
			switch( $this->field['type'] ) {

				case 'textarea':
					return $this->textarea();
				break;

				case 'readonly':
					return $this->readonly();
				break;

				case 'select':
					return $this->select();
				break;

				case 'toggle':
					return $this->toggle();
				break;

				case 'checkbox':
					return $this->checkbox();
				break;

				case 'radio':
					return $this->radio();
				break;

				case 'repeater':
					return $this->repeater();
				break;

				default:
					return $this->text();
				break;
				
			}

		}

		/**
		 * Field: Single Line Text
		 * 
		 * This field is used for text, phone, email and url inputs.
		 *
		 * @return    string    The single line text field.
		 */
		private function text() {

			// Fetch the field from the class instance.
			$field = $this->field;

			// Format the field type if necessary.
			if( $field['type'] == 'phone' ) {
				$field['type'] = 'tel';
			}

			// Return the input.
			return '<input type="' . $field['type'] . '" name="' . $field['id'] . '" id="' . $field['id'] . '" class="widefat" value="' . $field['value'] . '">';

		}

		/**
		 * Field: Multi Line Text
		 *
		 * @return    string    The multi line text field.
		 */
		private function textarea() {

			// Fetch the field from the class instance.
			$field = $this->field;
			
			// Return the input.
			return '<textarea name="' . $field['id'] . '" id="' . $field['id'] . '" class="widefat">' . esc_attr( $field['value'] ) . '</textarea>';

		}

		/**
		 * Field: Read-only
		 *
		 * @return    string    The read-only field.
		 */
		private function readonly() {

			// Fetch the field from the class instance.
			$field = $this->field;

			// Return the input.
			return esc_attr( $field['value'] );

		}

		/**
		 * Field: Select Box
		 *
		 * @return    string    The select field.
		 */
		private function select() {

			// Fetch the field from the class instance.
			$field = $this->field;
			
			// Build the input.
			$markup = '<select name="' . $field['id'] . '" id="' . $field['id'] . '">';
			foreach( $field['options'] as $option ) {
				$markup .= '<option value="' . $option['id'] . '"' . selected( $field['value'], $option['id'], false ) . '>' . $option['title'] . '</option>';
			}
			$markup .= '</select>';

			// Return the input.
			return $markup;

		}

		/**
		 * Field: Toggle
		 *
		 * @return    string    The toggle field.
		 */
		private function toggle() {

			// Fetch the field from the class instance.
			$field = $this->field;

			// Build the input.
			$markup  = '<input type="checkbox" name="' . $field['id'] . '[]" id="' . $field['id'] . '" value="' . $field['id'] . '"' . checked( ( in_array( $field['id'], $field['value'] ) ) ? $field['id'] : '', $field['id'], false ) . '>';
			$markup .= '<label for="' . $field['id'] . '">' . $field['description'] . '</label>';

			// Return the input.
			return $markup;

		}

		/**
		 * Field: Checkbox
		 *
		 * @return    string    The checkbox field.
		 */
		private function checkbox() {

			// Fetch the field from the class instance.
			$field = $this->field;

			// Build the markup.
			$markup = '';
			foreach ( $field['options'] as $option ) {
				$markup .= '<p>';
					$markup .= '<label>';
						$markup .= '<input type="checkbox" name="' . $field['id'] . '[]" id="' . $field['id'] . '[' . $option['id'] . ']' . '" value="' . $option['id'] . '"' . checked( ( in_array( $option['id'], $field['value'] ) ) ? $option['id'] : '', $option['id'], false ) . '>';
						$markup .= $option['title'];
					$markup .= '</label>';
				$markup .= '</p>';
			}

			// Return the input.
			return $markup;

		}

		/**
		 * Field: Radio
		 *
		 * @return    string    The radio field.
		 */
		private function radio() {

			// Fetch the field from the class instance.
			$field = $this->field;

			// Build the markup.
			$markup = '';
			foreach( $field['options'] as $option ) {
				$markup .= '<p>';
					$markup .= '<label>';
						$markup .= '<input type="radio" name="' . $field['id'] . '" id="' . $field['id'] . '[' . $option['id'] . ']' . '" value="' . $option['id'] . '"' . checked( $field['value'], $option['id'], false ) . '>';
						$markup .= $option['title'];
					$markup .= '</label>';
				$markup .= '</p>';
			}

			// Return the input.
			return $markup;

		}

		/**
		 * Field: Repeater
		 *
		 * @return    string    The repeater field.
		 */
		private function repeater() {

			// Fetch the field from the class instance.
			$field = $this->field;

			return '
			<table class="repeater wp-list-table">
				<tbody>
					<tr class="repeater-template hidden">
						<td>
							<input type="text" data-name="' . $field['id'] . '[]" class="regular-text" value="">
						</td>
						<td>
							<button class="button" data-repeater="remove" tabindex="-1">
								' . __( 'Remove', 'plugin' ) . '
							</button>
						</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="2">
							<button class="button" data-repeater="add">
								' . __( 'Add Item', 'plugin' ) . '
							</button>
						</td>
					</tr>
					<input type="hidden" class="repeater-data" value=' . "'" . json_encode( $field['value'] ) . "'" . '>
				</tfoot>
			</table>';

		}

	}

}
