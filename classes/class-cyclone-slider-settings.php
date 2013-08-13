<?php
if(!class_exists('Cyclone_Slider_Settings') and class_exists('Codefleet_Settings_Sub_Page')):
	/**
	* Class for plugin settings
	*/
	class Cyclone_Slider_Settings extends Codefleet_Settings_Sub_Page {
		
		/**
		* Render settings page. This function should echo the HTML form of the settings page.
		*/
		public function render_settings_page($post){
			?>
			<div class="wrap">
				<?php echo get_screen_icon('options-general'); ?>
				<h2><?php echo $this->page_title; ?></h2>
				<div class="intro">
					<p><?php _e('Play with these settings if Cyclone Slider 2 is not working or if you want to optimize it.', 'cycloneslider'); ?></p>
				</div>
				<?php settings_errors();
				$settings_data = $this->get_settings_data();
				//Cyclone_Slider_Data::debug($settings_data);
				?>
				
				<form method="post" action="options.php">
					<?php
					echo $this->settings_fields( $this->option_group );
					?>
					<table class="form-table">
						<tr>
							<th><label for="cs2-settings-load_scripts_in"><?php _e('Load scripts in:', 'cycloneslider'); ?></label></th>
							<td>
								<select name="<?php echo esc_attr( $this->option_name."[load_scripts_in]" ); ?>" id="cs2-settings-load_scripts_in">
									<option value="header" <?php echo ($settings_data['load_scripts_in']=='header') ? 'selected="selected"' : ''; ?>><?php _e('Header', 'cycloneslider'); ?></option>
									<option value="footer" <?php echo ($settings_data['load_scripts_in']=='footer') ? 'selected="selected"' : ''; ?>><?php _e('Footer', 'cycloneslider'); ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<th><label for=""><?php _e('Load these scripts:', 'cycloneslider'); ?></label></th>
							<td>
								<label for="cs2-settings-load_cycle2">
									<input type="hidden" name="<?php echo esc_attr( $this->option_name."[load_cycle2]" ); ?>" value="0">
									<input type="checkbox" id="cs2-settings-load_cycle2" name="<?php echo esc_attr( $this->option_name."[load_cycle2]" ); ?>" value="1" <?php echo ($settings_data['load_cycle2']==1) ? 'checked="checked"' : ''; ?> />
									<span><em><?php _e('Cycle 2. This is the core script needed by the plugin.', 'cycloneslider'); ?></em></span>
								</label> <br />
								<label for="cs2-settings-load_cycle2_carousel">
									<input type="hidden" name="<?php echo esc_attr( $this->option_name."[load_cycle2_carousel]" ); ?>" value="0">
									<input type="checkbox" id="cs2-settings-load_cycle2_carousel" name="<?php echo esc_attr( $this->option_name."[load_cycle2_carousel]" ); ?>" value="1" <?php echo ($settings_data['load_cycle2_carousel']==1) ? 'checked="checked"' : ''; ?> />
									<span><em><?php _e('Cycle 2 - Carousel. Used by these templates: Galleria, Lea, Dos.', 'cycloneslider'); ?></em></span>
								</label> <br />
								<label for="cs2-settings-load_cycle2_tile">
									<input type="hidden" name="<?php echo esc_attr( $this->option_name."[load_cycle2_tile]" ); ?>" value="0">
									<input type="checkbox" id="cs2-settings-load_cycle2_tile" name="<?php echo esc_attr( $this->option_name."[load_cycle2_tile]" ); ?>" value="1" <?php echo ($settings_data['load_cycle2_tile']==1) ? 'checked="checked"' : ''; ?> />
									<span><em><?php _e('Cycle 2 - Tile. Used for tile transition effects.', 'cycloneslider'); ?></em></span>
								</label> <br />
								<label for="cs2-settings-load_cycle2_video">
									<input type="hidden" name="<?php echo esc_attr( $this->option_name."[load_cycle2_video]" ); ?>" value="0">
									<input type="checkbox" id="cs2-settings-load_cycle2_video" name="<?php echo esc_attr( $this->option_name."[load_cycle2_video]" ); ?>" value="1" <?php echo ($settings_data['load_cycle2_video']==1) ? 'checked="checked"' : ''; ?> />
									<span><em><?php _e('Cycle 2 - Video. Used by YouTube template.', 'cycloneslider'); ?></em></span>
								</label> <br />
							</td>
						</tr>
						<tr>
							<th><label for="cs2-settings-script_priority"><?php _e('Scripts loading priority:', 'cycloneslider'); ?></label></th>
							<td>
								<input type="number" id="<?php echo esc_attr( 'script_priority' ); ?>" name="<?php echo esc_attr( $this->option_name."[script_priority]" ); ?>" value="<?php echo esc_attr( $this->get_data('script_priority') ); ?>" />
								<em><?php _e('Make this value bigger to load scripts last.', 'cycloneslider'); ?></em>
							</td>
						</tr>
					</table>
					<br /><br />
					<?php submit_button( __('Save Options', 'cycloneslider'), 'primary', 'submit', false) ?>
					<?php submit_button( __('Restore Defaults', 'cycloneslider'), 'secondary', 'reset', false) ?>
				</form>
				
			</div><?php
		}

		/**
		* Validate data from HTML form
		*/
		public function validate_options( $input ) {
			$input = wp_parse_args($input, $this->get_settings_data());
			if( isset($_POST['reset']) ){
				$input = $this->get_default_settings_data();
				add_settings_error( $this->menu_slug, 'restore_defaults', __( 'Default options restored.', 'cycloneslider'), 'updated fade' );
			} else {
				
			}
			return $input;
		}
		
		/**
		* Apply default values
		*/
		public function get_default_settings_data() {
			$defaults = array();
			$defaults['load_scripts_in'] = 'footer';
			$defaults['load_cycle2'] = 1;
			$defaults['load_cycle2_carousel'] = 1;
			$defaults['load_cycle2_tile'] = 1;
			$defaults['load_cycle2_video'] = 1;
			$defaults['script_priority'] = 100;
			return $defaults;
		}
		
		
	} // end class
	
endif;