<?php

/**
 * This meta box is for custom post type `seminar`
 * Create a custom post type `seminar` or create a new one
 * Replace `seminar` with your custom post type
 */

// Seminar Data Meta box
function seminar_info_meta_box() {
	add_meta_box('seminar_info', 'Seminar Information', 'seminar_info_meta_box_callback');
}
add_action( 'add_meta_boxes_seminar', 'seminar_info_meta_box' );

function seminar_info_meta_box_callback( $post ) {
	wp_nonce_field('_seminar_info_meta_box_nonce','_seminar_info_meta_box_nonce');
	$seminarDate = get_post_meta($post->ID, '_seminar_date', true);
	$seminarTime = get_post_meta($post->ID, '_seminar_time', true);
	$seminarType = get_post_meta($post->ID, '_seminar_type', true);
	?>
	<div style="display: flex; align-items: center; justify-content: space-between;">
		<div style="width: 30%;">
			<p>Seminar Date</p>
			<input type='date' name='_seminar_date' value="<?php echo $seminarDate; ?>" style="width: 100%;" />
		</div>
		<div style="width: 30%;">
			<p>Seminar Time</p>
			<input type='text' name='_seminar_time' placeholder="10AM - 12PM" value="<?php echo $seminarTime; ?>" style="width: 100%;" />
		</div>
		<div style="width: 30%;">
			<p>Seminar Type</p>
			<div class="seminar_type_meta_box">
				<select name="_seminar_type" id="_seminar_type" style="width: 100%;">
					<option value="online" <?php selected( $seminarType, 'online' )?>>Online</option>
					<option value="on-site" <?php selected( $seminarType, 'on-site' )?>>On-Site</option>
				</select>
			</div>
		</div>
	</div>
	<?php
}

function save_seminar_info_meta_box_data( $post_id ) {
	// Check if our nonce is set.
	if ( ! isset( $_POST['_seminar_info_meta_box_nonce'] ) ) {
		return;
	}
	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['_seminar_info_meta_box_nonce'], '_seminar_info_meta_box_nonce' ) ) {
		return;
	}
	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Check the user's permissions.
	if ( ! current_user_can( 'edit_post', $post_id )) {
		return $post_id;
	}
	// Make sure that it is set.
	if ( ! isset( $_POST['_seminar_date'] ) || ! isset( $_POST['_seminar_time'] ) || ! isset( $_POST['_seminar_type'] ) ) {
		return;
	}
	/* OK, it's safe for us to save the data now. */
	// Sanitize user input.
	$seminarDate = sanitize_text_field( $_POST['_seminar_date'] );
	$seminarTime = sanitize_text_field( $_POST['_seminar_time'] );
	$seminarType = sanitize_text_field( $_POST['_seminar_type'] );

	// Update the meta field in the database.
	update_post_meta( $post_id, '_seminar_date', $seminarDate );
	update_post_meta( $post_id, '_seminar_time', $seminarTime );
	update_post_meta( $post_id, '_seminar_type', $seminarType );
}
add_action( 'save_post_seminar', 'save_seminar_info_meta_box_data' );