<?php
class BlindSetting
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
	
    {	
		add_submenu_page('myplugin', 'Settings', 'Settings', 'manage_options', 'icon_shotcode',   array( $this, 'create_admin_page' ) ,'dashicons-clipboard' );	

    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'option_blindmatrix_settings' );
		
        ?>
        <div class="wrap">
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'blindmatrix-settings-group' );
                do_settings_sections( 'blindmatrix_settings_page ' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'blindmatrix-settings-group', // Option group
            'option_blindmatrix_settings', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'Seasonal Image', // Title
            array( $this, 'print_section_info' ), // Callback
            'blindmatrix_settings_page ' // Page
        );  

        add_settings_field(
            'seasonal_image_check', // ID
            'Enable/Disable', // Title 
            array( $this, 'check_seasonal_image_callback' ), // Callback
            'blindmatrix_settings_page ', // Page
            'setting_section_id' // Section           
        );   
		
		add_settings_field(
            'seasonal_image_img', // ID
            'Add/Update Image', // Title 
            array( $this, 'seasonal_image_callback' ), // Callback
            'blindmatrix_settings_page ', // Page
            'setting_section_id' // Section           
        ); 
		add_settings_field(
            'menu_type', // ID
            'Select Menu Type', // Title 
            array( $this, 'menu_type_callback' ), // Callback
            'blindmatrix_settings_page ', // Page
            'setting_section_id' // Section           
        ); 
		
		 add_settings_section(
            'setting_page_section_id', // ID
            'Page Slug', // Title
            array( $this, 'print_page_section_info' ), // Callback
            'blindmatrix_settings_page ' // Page
        );  
		add_settings_field(
            'product_page', // ID
            'Product page', // Title 
            array( $this, 'product_page_callback' ), // Callback
            'blindmatrix_settings_page ', // Page
            'setting_page_section_id' // Section           
        ); 
		add_settings_field(
            'product_category_page', // ID
            'Product Category Page', // Title 
            array( $this, 'product_category_page_callback' ), // Callback
            'blindmatrix_settings_page ', // Page
            'setting_page_section_id' // Section           
        ); 
		add_settings_field(
            'productview_page', // ID
            'Product View Page', // Title 
            array( $this, 'productview_page_callback' ), // Callback
            'blindmatrix_settings_page ', // Page
            'setting_page_section_id' // Section           
        ); 
		add_settings_field(
            'shutters_page', // ID
            'Shutters Page', // Title 
            array( $this, 'shutters_page_callback' ), // Callback
            'blindmatrix_settings_page ', // Page
            'setting_page_section_id' // Section           
        ); 
		add_settings_field(
            'shutters_type_page', // ID
            'Shutters Type Page', // Title 
            array( $this, 'shutters_type_page_callback' ), // Callback
            'blindmatrix_settings_page ', // Page
            'setting_page_section_id' // Section           
        ); 
		add_settings_field(
            'shutter_visualizer_page', // ID
            'Shutter Visualizer Page', // Title 
            array( $this, 'shutter_visualizer_page_callback' ), // Callback
            'blindmatrix_settings_page ', // Page
            'setting_page_section_id' // Section           
        ); 

    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['seasonal_image_check'] ) ){
            $new_input['seasonal_image_check'] = sanitize_text_field( $input['seasonal_image_check'] );
		}else{
			$new_input['seasonal_image_check'] ='';
		}
		if( isset( $input['seasonal_image_img'] ) ){
            $new_input['seasonal_image_img'] = sanitize_text_field( $input['seasonal_image_img'] );
		}
		if( isset( $input['menu_type'] ) ){
			$new_input['menu_type'] = sanitize_text_field( $input['menu_type'] );
		}
		if( isset( $input['product_page'] ) ){
			$new_input['product_page'] = sanitize_text_field( $input['product_page'] );
		}
		if( isset( $input['product_category_page'] ) ){
			$new_input['product_category_page'] = sanitize_text_field( $input['product_category_page'] );
		}
		if( isset( $input['productview_page'] ) ){
			$new_input['productview_page'] = sanitize_text_field( $input['productview_page'] );
		}
		if( isset( $input['shutters_page'] ) ){
			$new_input['shutters_page'] = sanitize_text_field( $input['shutters_page'] );
		}
		if( isset( $input['shutters_type_page'] ) ){
			$new_input['shutters_type_page'] = sanitize_text_field( $input['shutters_type_page'] );
		}
		if( isset( $input['shutter_visualizer_page'] ) ){
			$new_input['shutter_visualizer_page'] = sanitize_text_field( $input['shutter_visualizer_page'] );
		}
		
		return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Click here if you need the view the Seasonal Image near your product';
    }
	public function print_page_section_info(){
		print '';
	}
    /** 
     * Get the settings option array and print one of its values
     */
    public function check_seasonal_image_callback()
    {
        printf(
            '<input %s type="checkbox" id="seasonal_image_check" name="option_blindmatrix_settings[seasonal_image_check]" value="checked" />',
            isset( $this->options['seasonal_image_check'] ) ? esc_attr( $this->options['seasonal_image_check']) : ''
        );
      
    }
	public function seasonal_image_callback(){
		$image_id = isset( $this->options['seasonal_image_img'] ) ? esc_attr( $this->options['seasonal_image_img']) : '';
		if( $image = wp_get_attachment_image_src( $image_id ,'full' ) ) {
		 
			echo '<a href="#" class="seasonal_image_upl"><img style="max-width:300px;max-height:200px;" src="' . $image[0] . '" /></a>
				  <a href="#" class="seasonal_image_rmv">Remove image</a>
				  <input type="hidden" id="seasonal_image_img" name="option_blindmatrix_settings[seasonal_image_img]" value="' . $image_id . '">';
		 
		} else {
		 
			echo '<a href="#" class="seasonal_image_upl">Upload image</a>
				  <a href="#" class="seasonal_image_rmv" style="display:none">Remove image</a>
				  <input type="hidden"id="seasonal_image_img"  name="option_blindmatrix_settings[seasonal_image_img]" value="">';
		 
		}
	}
	public function menu_type_callback(){
		  ob_start();
		  ?>
            <select name="option_blindmatrix_settings[menu_type]" />
				  <option value="type1" <?php if ($this->options['menu_type'] == 'type1') echo ' selected="selected"'; ?>>Type 1</option>
				  <option value="type2"  <?php if ($this->options['menu_type'] == 'type2') echo ' selected="selected"'; ?>>Type 2</option>
			</select>
			<?php
			$out2 = ob_get_contents();
			ob_end_clean();
        echo($out2);
	}
	public function product_page_callback(){
		  printf(
            '<input  type="input" id="product_page" name="option_blindmatrix_settings[product_page]" value="%s" />',
            isset( $this->options['product_page'] ) ? esc_attr( $this->options['product_page']) : ''
        );
	}
	public function product_category_page_callback(){
		  printf(
			'<input  type="input" id="product_category_page" name="option_blindmatrix_settings[product_category_page]" value="%s"  />',
			isset( $this->options['product_category_page'] ) ? esc_attr( $this->options['product_category_page']) : ''
		);
	}
	public function productview_page_callback(){
		  printf(
			'<input  type="input" id="productview_page" name="option_blindmatrix_settings[productview_page]" value="%s"  />',
			isset( $this->options['productview_page'] ) ? esc_attr( $this->options['productview_page']) : ''
		);
	}
	public function shutters_page_callback(){
		  printf(
			'<input  type="input" id="shutters_page" name="option_blindmatrix_settings[shutters_page]" value="%s"   />',
			isset( $this->options['shutters_page'] ) ? esc_attr( $this->options['shutters_page']) : ''
		);
	}
	public function shutters_type_page_callback(){
		  printf(
			'<input  type="input" id="shutters_type_page" name="option_blindmatrix_settings[shutters_type_page]" value="%s"  />',
			isset( $this->options['shutters_type_page'] ) ? esc_attr( $this->options['shutters_type_page']) : ''
		);
	}
	public function shutter_visualizer_page_callback(){
		  printf(
			'<input  type="input" id="shutter_visualizer_page" name="option_blindmatrix_settings[shutter_visualizer_page]" value="%s"  />',
			isset( $this->options['shutter_visualizer_page'] ) ? esc_attr( $this->options['shutter_visualizer_page']) : ''
		);
	}

}

if( is_admin() )
    $my_settings_page = new BlindSetting();