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

			// Add the meta boxes to the post admin page.
			add_action( 'add_meta_boxes_' . $post_type, array( $this, 'add_meta_boxes' ) );
			add_action( 'save_post_' . $post_type, array( $this, 'save_meta_boxes' ) );

		}

		/**
		 * Field
		 *
		 * @param     object    $field
		 * @param     string    $value
		 * @return    string    The field.
		 */
		public function field( $field, $value ) {

			switch( $field['type'] ) {

				case 'text':
					return $this->text( $field, $value );
				break;

				case 'readonly':
					return $this->readonly( $field, $value );
				break;

				case 'select':
					return $this->select( $field, $value );
				break;

				default:
					return $this->text( $field, $value );
				break;
				
			}

		}

		/**
		 * Field: Single Line Text
		 *
		 * @param     object    $field
		 * @param     string    $value
		 * @return    string    The single line text field.
		 */
		public function text( $field, $value ) {

			return '<input type="text" name="' . $field['id'] . '" id="' . $field['id'] . '" class="widefat" value="' . esc_attr( $value ) . '">';

		}

		/**
		 * Field: Read-only
		 *
		 * @param     object    $field
		 * @param     string    $value
		 * @return    string    The read-only field.
		 */
		public function readonly( $field, $value ) {

			return esc_attr( $value );

		}

		/**
		 * Field: Select Box
		 *
		 * @param     object    $field
		 * @param     string    $value
		 * @return    string    The select field.
		 */
		public function select( $field, $value ) {
			
			$markup = '<select name="' . $field['id'] . '">';
			foreach( $field['options'] as $option ) {
				$markup .= '<option value="' . $option['id'] . '"' . selected( $value, $option['id'], false ) . '>' . $option['name'] . '</option>';
			}
			$markup .= '</select>';
			return $markup;

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
					$meta_box['screen'], 
					$post_type, 
					$meta_box['priority'] 
				);
			}

		}

		/**
		 * Generate the markup for a meta box.
		 *
		 * @param     int    $post    The post id to register the meta box for.
		 * @return    void
		 */
		public function meta_box( $post ) {
			
			// Retrieve the current values.
			$meta_box = $this->meta_boxes[ $this->counter ];

			// Make sure the form request comes from WordPress
			wp_nonce_field( basename( __FILE__ ), $meta_box['id'] . '_meta_box_nonce' );

			// Get the current data from the database.
			foreach( $meta_box['fields'] as $field ) { 

				// Attempt to get the current field value.
				$value = get_post_meta( $post->ID, '_' . $meta_box['id'] . '_' . $field['id'], true ); 
				
				// If none is set, fallback to default.
				if( empty( $value ) && isset( $field['default'] ) ) {
					update_post_meta( $post->ID, '_' . $meta_box['id'] . '_' . $field['id'], $field['default'] );
					$value = get_post_meta( $post->ID, '_' . $meta_box['id'] . '_' . $field['id'], true );
				} ?>

				<div id="<?php echo $meta_box['id'] ?>_<?php echo $field['id'] ?>" class="field">
					<p class="label">
						<label for="<?php echo $field['id'] ?>">
							<strong><?php echo $field['name'] ?></strong>
						</label>
					</p>	
					<?php echo $this->field( $field, $value ); ?>
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

			// Check if there's a valid nonce and update the values.
			foreach( $this->meta_boxes as $meta_box ) {
				if ( !isset( $_POST[ $meta_box['id'] . '_meta_box_nonce' ] ) || !wp_verify_nonce( $_POST[ $meta_box['id'] . '_meta_box_nonce' ], basename( __FILE__ ) ) ){
					return;
				}
				foreach( $meta_box['fields'] as $field ) {
					if ( isset( $_REQUEST[ $field['id'] ] ) ) {	
						update_post_meta( $post_id, '_' . $meta_box['id'] . '_' . $field['id'], sanitize_text_field( $_POST[ $field['id'] ] ) );
					}
				}
			}

		}

	}

}
