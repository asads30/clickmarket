<?php 

class Product_Share_Front{

	protected static $_instance = null;

	public $display_position;

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function __construct(){

    	// Asset Load

    	add_action( 'wp_enqueue_scripts', array( $this, 'front_asset' ) );

    	// Display Conditions of Social Icons

    	$this->display_position = Product_Share::get_options()->display_position;

    	if( 'after_product_price' === $this->display_position ){
			add_action( 'woocommerce_single_product_summary', array( $this, 'display_share_link' ), 11 );
		}
		elseif( 'after_product_title' === $this->display_position ){
			add_action( 'woocommerce_single_product_summary', array( $this, 'display_share_link' ), 6 );
		}
		else{
			add_action( 'woocommerce_share', array( $this, 'display_share_link' ) );
		}
    }

    public function front_asset(){

    	// @Note: Checking if `SCRIPT_DEBUG` is defined and `true`
        $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

    	if( is_product() || is_shop() ){

    		wp_enqueue_style('psfw-public', plugins_url('public/css/public'. $suffix .'.css', PRODUCT_SHARE_PLUGIN_FILE), array(), product_share()->version(), 'all');
    		wp_style_add_data( 'psfw-public', 'rtl', 'replace' );

    		wp_enqueue_style('psfw-fontawesome-6.1.1', plugins_url('fonts/fontawesome/css/all.css', PRODUCT_SHARE_PLUGIN_FILE), array(), product_share()->version(), 'all');

    		wp_enqueue_script('psfw-public', plugins_url('public/js/public.min.js', PRODUCT_SHARE_PLUGIN_FILE), array('jquery'), product_share()->version(), true);

    		wp_localize_script( 'psfw-public', 'public_js_object',
		        array( 
		            'copy_to_clipboard_text' => apply_filters( 'psfw_copy_to_clipboard_text', __('Copy to Clipboard', 'product-share') ),
		            'copied_to_clipboard_text' => apply_filters( 'psfw_copied_to_clipboard_text', __('Copied to Clipboard', 'product-share') ),
		        )
		    );

    	}

    	
    }

    public function display_share_link(){

        $selected_labels = Product_Share::get_options()->selected_lables;
        $icon_appearance = Product_Share::get_options()->icon_appearance;
        $button_shape = Product_Share::get_options()->button_shape;
        $display_position = Product_Share::get_options()->display_position;
        $icon_title = Product_Share::get_options()->icon_title;

        if( 'hide_icon' === $display_position ){
        	return;
        }

        // Preparing Icon Title 
        $title = sprintf(
        	'<span class="psfw-icon-title">%s</span>',
        	apply_filters('psfw_icon_title', __('Share On:', 'product-share'))
        );

        echo sprintf(
        	'<div class="psfw-social-wrap">
        	%s<ul class="psfw-social-icons %s %s %s">',
        	( $icon_title === 'yes' ) ? $title : "",
        	esc_attr( $button_shape ),
        	esc_attr( $icon_appearance ),
        	apply_filters('psfw_ul_class', '')
        );

        foreach ($selected_labels as $key => $label) {

        	if( $key == 'whatsapp' ){
    			$text = 'WhatsApp';
    		}

    		elseif( $key == 'linkedin' ){
    			$text = 'LinkedIn';
    		}

    		else{
    			$text = ucfirst($key);
    		}

        	if( 'only_icon' == $icon_appearance ){
	    		$btn_format = '<i class="fa-brands fa-'.$key.'"></i>';
	    	}
	    	elseif( 'only_text' == $icon_appearance ){
	    		
	    		$btn_format = $text;
	    		
	    	}
	    	else{
	    		
	    		$btn_format = '<i class="fa-brands fa-'.$key.'"></i> '.$text;
	    	}

        	switch ($key) {

        		case "facebook":
			    	 $this->get_facebook($icon_appearance, $btn_format);
			    break;

			    case "twitter":
			    	 $this->get_twitter($icon_appearance, $btn_format);
			    break;

			    case "linkedin":
			    	 $this->get_linkedin($icon_appearance, $btn_format);
			    break;

			    case "viber":
			    	 $this->get_viber($icon_appearance, $btn_format);
			    break;

			    case "telegram":
			    	 $this->get_telegram($icon_appearance, $btn_format);
			    break;

			    case "whatsapp":
			    	 $this->get_whatsapp($icon_appearance, $btn_format);
			    break;

			    default:
			    	echo  "";
        	}

        	
        }

        // Clipboard Button
        if( 'yes' === Product_Share::get_options()->copy_to_clipboard ){
        	$this->get_copy_to_clipboard($icon_appearance);
        }

        echo '</ul></div>';

    }

    public function get_facebook($icon_appearance, $btn_format){

    	echo sprintf(
    		'<li><a href="%s%s" target="_blank">%s</a></li>',
    		'https://www.facebook.com/sharer/sharer.php?u=',
    		get_permalink( get_the_ID() ),
    		$btn_format
    	);
    }


    public function get_twitter($icon_appearance, $btn_format){

    	echo sprintf(
    		'<li><a href="%s%s" target="_blank">%s</a></li>',
    		'https://twitter.com/intent/tweet?url=',
    		get_permalink( get_the_ID() ),
    		$btn_format
    	);
    }

    public function get_linkedin($icon_appearance, $btn_format){
    	echo sprintf(
    		'<li><a href="%s%s" target="_blank">%s</a></li>',
    		'https://www.linkedin.com/shareArticle?mini=true&url=',
    		get_permalink( get_the_ID() ),
    		$btn_format
    	);
    }

    public function get_viber($icon_appearance, $btn_format){
    	echo sprintf(
    		'<li><a href="%s%s" target="_blank">%s</a></li>',
    		'viber://forward?text=',
    		get_permalink( get_the_ID() ),
    		$btn_format
    	);
    }

    public function get_telegram($icon_appearance, $btn_format){
    	echo sprintf(
    		'<li><a href="%s%s" target="_blank">%s</a></li>',
    		'https://t.me/share/url?url=',
    		get_permalink( get_the_ID() ),
    		$btn_format
    	);
    }

    public function get_whatsapp($icon_appearance, $btn_format){
    	echo sprintf(
    		'<li><a href="%s%s" target="_blank">%s</a></li>',
    		'https://api.whatsapp.com/send?text=',
    		get_permalink( get_the_ID() ),
    		$btn_format
    	);
    }

    public function get_copy_to_clipboard($icon_appearance){

    	if( 'only_icon' == $icon_appearance ){
    		$btn_format = '<i class="psfw-clipboard fa-solid fa-clipboard"></i>';
    	}
    	elseif( 'only_text' == $icon_appearance ){
    		
    		$btn_format = '<span class="psfw-clipboard-text">'.apply_filters( 'psfw_copy_to_clipboard_text', __('Copy to Clipboard', 'product-share') ).'</span>';
    		
    	}
    	else{
    		
    		$btn_format = '<i class="psfw-clipboard fa-solid fa-clipboard"></i> <span class="psfw-clipboard-text">'.apply_filters( 'psfw_copy_to_clipboard_text', __('Copy to Clipboard', 'product-share') ).'</span>';
    	}

    	echo sprintf(
    		'<li><a id="psfw-copy-link" data-url="%s" href="#">%s</a></li>',
    		get_permalink( get_the_ID() ),
    		$btn_format
    	);
    }


}