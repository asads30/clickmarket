<section class="general" id="psfw-general-section">

    <h3>Icon Settings</h3>
    <table class="widefat wpx-table">
        <tr class="alternate" valign="top">

            <td class="row-title" scope="row">
                <label for="tablecell">
                    <?php echo esc_attr__('Icons to Display', 'product-share'); ?>
                </label>   
            </td>
            <td>
                <ul id="sortable" style="margin-top: 5px;" class="checklist sortable">
                    <?php

                    $selected_labels = Product_Share::get_options()->selected_lables;

                    foreach ( $selected_labels as $key => $label){ 
                    ?>
                        <li id="list_item_<?php echo esc_attr( $key ); ?>" class='ui-state-default'>
                            <label>
                                <input type='hidden' value='<?php echo esc_attr( $label ); ?>' name='product_share_option[buttons][<?php echo esc_attr( $key ); ?>]'>
                                <span>
                                    <i class="fa-brands fa-<?php echo esc_attr( $key ); ?>"></i> <?php echo esc_attr( $label ); ?>
                                </span>
                            </label>
                        </li>
                    <?php
                    }
                    ?>


                    <?php

                        $labels = Product_Share::get_options()->labels;
                    ?>
                </ul>

                <button class="more-icons"><i class="fas fa-plus"></i> <?php echo esc_attr__('More Icons', 'product-share'); ?></button>

                <div class="all-icons">

                    <ul id="base-list" class="sortable-list">
                    
                        <li id="facebook">
                            <label>
                                <input type="checkbox" name="product_share_option[all_buttons][facebook]" value="Facebook" <?php checked( in_array( 'Facebook',$labels), 1 ); ?> />
                                <span>
                                    <i class="fa-brands fa-facebook"></i> Facebook
                                </span>
                            </label>
                        </li>
                    

                    
                        <li id="twitter">
                            <label>
                                <input type="checkbox" name="product_share_option[all_buttons][twitter]" value="Twitter" <?php checked(in_array( 'Twitter',$labels), 1 ); ?> />
                                <span>
                                    <i class="fa-brands fa-twitter"></i> Twitter
                                </span>
                            </label>
                        </li>
                    
                   
                        <li id="linkedin">
                            <label>
                                <input type="checkbox" name="product_share_option[all_buttons][linkedin]" value="LinkedIn" <?php checked(in_array( 'LinkedIn', $labels), 1 ); ?> />
                                <span>
                                    <i class="fa-brands fa-linkedin"></i> LinkedIn
                                </span>
                            </label>
                        </li>
                    
                        <li id="viber">
                            <label>
                                <input type="checkbox" name="product_share_option[all_buttons][viber]" value="Viber" <?php checked(in_array( 'Viber', $labels), 1 ); ?> />
                                <span>
                                    <i class="fa-brands fa-viber"></i> Viber
                                </span>
                            </label>
                        </li>
                    
                        <li id="telegram">
                            <label>
                                <input type="checkbox" name="product_share_option[all_buttons][telegram]" value="Telegram" <?php checked(in_array( 'Telegram', $labels), 1 ); ?> />
                                <span>
                                    <i class="fa-brands fa-telegram"></i> Telegram
                                </span>
                            </label>
                        </li>
                    
                        <li id="whatsapp">
                            <label>
                                <input type="checkbox" name="product_share_option[all_buttons][whatsapp]" value="WhatsApp" <?php checked(in_array( 'WhatsApp', $labels), 1 ); ?> />
                                <span>
                                    <i class="fa-brands fa-whatsapp"></i> WhatsApp
                                </span>
                            </label>
                        </li>
                    </ul>

                </div>

            </td>
        </tr>

        <?php 

            // Icon Appearance
            WPXtension_Setting_Fields::select(
                $options = array(
                    'tr_class' => 'alternate',
                    'label' => esc_attr__('Icon Appearance', 'product-share'),
                    'value' => Product_Share::get_options()->icon_appearance,
                    'name' => 'product_share_option[icon_appearance]',
                    'option' => apply_filters('psfw_icon_appearance_option', array(
                        'option_1' => array(
                            'name' => 'Only Icon',
                            'value' => 'only_icon',
                            'need_pro' => false,
                        ),
                        'option_2' => array(
                            'name' => 'Only Text',
                            'value' => 'only_text',
                            'need_pro' => false,
                        ),
                        'option_3' => array(
                            'name' => 'Icon with text',
                            'value' => 'icon_with_text',
                            'need_pro' => false,
                        ),
                    )),
                    'note' => '',
                    'need_pro' => false,
                ),
            ); 


            // Social Button Shape
            WPXtension_Setting_Fields::select(
                $options = array(
                    'tr_class' => '',
                    'label' => esc_attr__('Social Button Shape', 'product-share'),
                    'value' => Product_Share::get_options()->button_shape,
                    'name' => 'product_share_option[button_shape]',
                    'option' => apply_filters('psfw_button_shape_option', array(
                        'option_1' => array(
                            'name' => 'Round',
                            'value' => 'round',
                            'need_pro' => false,
                        ),
                        'option_2' => array(
                            'name' => 'Square',
                            'value' => 'square',
                            'need_pro' => false,
                        ),
                    )),
                    'note' => '',
                    'need_pro' => false,
                ),
            ); 

            // Copy to clipboard
            WPXtension_Setting_Fields::checkbox(
                $options = array(
                    'tr_class' => 'alternate',
                    'label' => esc_attr__('Enable "Copy to Clipboard"', 'product-share'),
                    'value' => Product_Share::get_options()->copy_to_clipboard,
                    'name' => 'product_share_option[copy_to_clipboard]',
                    'default_value' => 'yes',
                    'checkbox_label' => esc_attr__('Display "Copy to Clipboard" button to copy product link.', 'product-share'),
                    'note' => esc_attr__('Note: To get it to work, your site should have a secure connection. For Example: https://example.com', 'product-share'),
                    'need_pro' => false,
                ),
            ); 

        ?>
    </table>

    <h3>General Settings</h3>
    <table class="widefat wpx-table">

        <?php 

            // Where to Display
            WPXtension_Setting_Fields::select(
                $options = array(
                    'tr_class' => 'alternate',
                    'label' => esc_attr__('Where to Display', 'product-share'),
                    'value' => Product_Share::get_options()->display_position,
                    'name' => 'product_share_option[display_position]',
                    'option' => apply_filters('psfw_display_position_option', array(
                        'option_1' => array(
                            'name' => 'Always show with category name',
                            'value' => 'with_category',
                            'need_pro' => false,
                        ),
                        'option_2' => array(
                            'name' => 'Display after product tilte',
                            'value' => 'after_product_title',
                            'need_pro' => false,
                        ),
                        'option_3' => array(
                            'name' => 'Display after product price',
                            'value' => 'after_product_price',
                            'need_pro' => false,
                        ),
                        'option_4' => array(
                            'name' => 'Hide Icon',
                            'value' => 'hide_icon',
                            'need_pro' => false,
                        ),
                    )),
                    'note' => '',
                    'need_pro' => false,
                ),
            );

            // Enable Icon Title
            WPXtension_Setting_Fields::checkbox(
                $options = array(
                    'tr_class' => 'new',
                    'label' => esc_attr__('Enable Icon Title', 'product-share'),
                    'value' => Product_Share::get_options()->icon_title,
                    'name' => 'product_share_option[icon_title]',
                    'default_value' => 'yes',
                    'checkbox_label' => esc_attr__('Display title before social icons.', 'product-share'),
                    'note' => '',
                    'need_pro' => false,
                    'tag' => esc_attr__('New', 'product-share'),
                ),
            ); 


        ?>

    </table>

    <h3>Floating Social Icons Settings</h3>
    <table class="widefat wpx-table">
        <?php 

            // Enable Floating Icon
            WPXtension_Setting_Fields::checkbox(
                $options = array(
                    'tr_class' => 'alternate',
                    'label' => esc_attr__('Enable Floating Icon', 'product-share'),
                    'value' => Product_Share::get_options()->float_icon,
                    'name' => 'product_share_option[float_icon]',
                    'default_value' => 'yes',
                    'checkbox_label' => esc_attr__('Enable Foating Social Icon on Single Product Page.', 'product-share'),
                    'note' => '',
                    'need_pro' => true,
                    'pro_exists' => Product_Share::check_plugin_state('product-share-pro'),
                ),
            ); 

            // Floating Icon Position
            WPXtension_Setting_Fields::select(
                $options = array(
                    'tr_class' => '',
                    'label' => esc_attr__('Position', 'product-share'),
                    'value' => Product_Share::get_options()->float_icon_position,
                    'name' => 'product_share_option[float_icon_position]',
                    'option' => apply_filters('psfw_float_icon_position_option', array(
                        'option_1' => array(
                            'name' => 'Right Side',
                            'value' => 'right_side',
                            'need_pro' => true,
                        ),
                        'option_2' => array(
                            'name' => 'Left Side',
                            'value' => 'left_side',
                            'need_pro' => true,
                        ),
                    )),
                    'note' => '',
                    'need_pro' => true,
                    'pro_exists' => Product_Share::check_plugin_state('product-share-pro'),
                ),
            ); 

        ?>
    </table>
</section>