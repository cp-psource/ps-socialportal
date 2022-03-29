<?php global $current_blog ?>
<form action="<?php echo esc_url( trailingslashit( $current_blog->path . global_site_search_get_search_base() ) ) ?>">
	<table border="0" cellpadding="2" cellspacing="2" style="width:100%">
		<tr>
			<td style="width:75%">
				<input type="text" name="phrase" style="width:100%" value="<?php echo esc_attr( stripslashes( global_site_search_get_phrase() ) ) ?>">
			</td>
			<td style="text-align:right;width:25%">
				<input type="submit" value="<?php _e( 'Durchsuche das Netzwerk', 'globalsitesearch' ) ?>">
			</td>
		</tr>
	</table>
</form>