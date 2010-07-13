<div class="wrap">
	<h2>Comment Image Uploader Plugin Options</h2>

	<?php if ( $updated ): ?>
		<div id="message" class="updated fade below-h2">
			<p><strong>Success!</strong> The changes have been made.</p>
		</div>
	<?php endif; ?>

	<form id="customForm" action="<?php echo $_SERVER['SCRIPT_NAME']; ?>?page=comment-image-uploader/comment-image-uploader.php" method="post" enctype="multipart/form-data">
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row" class="required"><label for="option-tmaxwidth">Thumbnail Max Width:</label></th>
				<td>
					<input id="option-tmaxwidth" type="text" class="small-text input-required" name="option[tmaxwidth]" value="<?php echo $values['tmaxwidth']; ?>" />px
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" class="required"><label for="option-tmaxheight">Thumbnail Max Height:</label></th>
				<td>
					<input id="option-tmaxheight" type="text" class="small-text input-required" name="option[tmaxheight]" value="<?php echo $values['tmaxheight']; ?>" />px
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" class="required"><label for="option-lmaxwidth">Large Max Width:</label></th>
				<td>
					<input id="option-lmaxwidth" type="text" class="small-text input-required" name="option[lmaxwidth]" value="<?php echo $values['lmaxwidth']; ?>" />px
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" class="required"><label for="option-lmaxheight">Large Max Height:</label></th>
				<td>
					<input id="option-lmaxheight" type="text" class="small-text input-required" name="option[lmaxheight]" value="<?php echo $values['lmaxheight']; ?>" />px
				</td>
			</tr>
			<tr valign="top">
				<th scope="row" class="required">Lightbox Effect:</th>
				<td>
					<label>
						<input type="radio" name="option[lightbox]" value="1" <?php if ( $values['lightbox'] ) echo 'checked="checked"'; ?> />
						Enabled
					</label>
					<label>
						<input type="radio" name="option[lightbox]" value="0" <?php if ( !$values['lightbox'] ) echo 'checked="checked"'; ?> />
						Disabled
					</label>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"></th>
				<td>
					<input type="submit" name="Submit" class="button-primary" value="Save Changes" />
				</td>
			</tr>
		</tbody>
	</table>
	<?php if (function_exists('wp_nonce_field')) wp_nonce_field('commentImageUploader'); ?>
	</form>

</div>
