<?php
/**
 * Form Field Date block.
 *
 * @package ive
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class ive_Form_Field_Date_Block
 */
class ive_Form_Field_Date_Block {
    /**
     * ive_Form_Field_Date_Block constructor.
     */
    public function __construct() {
        add_action( 'init', array( $this, 'init' ) );
    }

    /**
     * Init.
     */
    public function init() {
        if ( ! function_exists( 'register_block_type' ) ) {
            return;
        }

        register_block_type(
            'ive/form-field-date',
            array(
                'parent'          => array( 'ive/form' ),
                'render_callback' => array( $this, 'block_render' ),
                'attributes'      => ive_Form_Field_Attributes::get_block_attributes(
                    array(
                        'label' => array(
                            'default' => esc_html__( 'Date', '@@text_domain' ),
                        ),
                        'uniqueID' => array(
                            'type' => 'string',
                            'default' => ''
                        ),
                        'min' => array(
                            'type' => 'string',
                        ),
                        'max' => array(
                            'type' => 'string',
                        ),
                        'frameNormalBorderStyle' => array(
                            'type' => 'array',
                            'default' => array( 'none', 'none', 'none' )
                        ),
                        'frameNormalBorderColor' => array(
                            'type' => 'array',
                            'default' => array( '', '', '' )
                        ),
                        'frameNormalBorderWidth' => array(
                            'type' => 'array',
                            'default' => array( 0, 0, 0 )
                        ),
                        'frameNormalboxshadcolor' => array(
                            'type' => 'array',
                            'default' => array( '', '', '' )
                        ),
                        'frameNormalboxshadx' => array(
                            'type' => 'array',
                            'default' => array( 0, 0, 0 )
                        ),
                        'frameNormalboxshady' => array(
                            'type' => 'array',
                            'default' => array( 0, 0, 0 )
                        ),
                        'frameNormalboxshadblur' => array(
                            'type' => 'array',
                            'default' => array( 0, 0, 0 )
                        ),
                        'frameNormalboxshadspread' => array(
                            'type' => 'array',
                            'default' => array( 0, 0, 0 )
                        ),
                        'frameNormalBorderRadius' => array(
                            'type' => 'array',
                            'default' => array(
                              array( 0, 0, 0, 0 ),
                              array( 0, 0, 0, 0 ),
                              array( 0, 0, 0, 0 )
                            ),
                        ),
                        'frameNormalHovBorderStyle' => array(
                            'type' => 'string',
                            'default' => array( 'none', 'none', 'none' )
                        ),
                        'frameNormalHovBorderColor' => array(
                            'type' => 'array',
                            'default' => array( '', '', '' )
                        ),
                        'frameNormalHovBorderWidth' => array(
                            'type' => 'array',
                            'default' => array( 0, 0, 0 )
                        ),
                        'frameNormalHovboxshadcolor' => array(
                            'type' => 'array',
                            'default' => array( '', '', '' )
                        ),
                        'frameNormalHovboxshadx' => array(
                            'type' => 'array',
                            'default' => array( 0, 0, 0 )
                        ),
                        'frameNormalHovboxshady' => array(
                            'type' => 'array',
                            'default' => array( 0, 0, 0 )
                        ),
                        'frameNormalHovboxshadblur' => array(
                            'type' => 'array',
                            'default' => array( 0, 0, 0 )
                        ),
                        'frameNormalHovboxshadspread' => array(
                            'type' => 'array',
                            'default' => array( 0, 0, 0 )
                        ),
                        'frameNormalHovBorderRadius' => array(
                            'type' => 'array',
                            'default' => array(
                              array( 0, 0, 0, 0 ),
                              array( 0, 0, 0, 0 ),
                              array( 0, 0, 0, 0 )
                            ),
                        ),
                        'spacingMargin' => array(
                            'type' => 'array',
                            'default' => array(
                              array( 0, 0, 0, 0 ),
                              array( 0, 0, 0, 0 ),
                              array( 0, 0, 0, 0 )
                            ),
                        ),
                        'spacingPadding' => array(
                            'type' => 'array',
                            'default' => array(
                              array( 0, 0, 0, 0 ),
                              array( 0, 0, 0, 0 ),
                              array( 0, 0, 0, 0 )
                            ),
                        ),
                        'displayFields' => array(
                            'type' => 'array',
                            'default' => array( 'true', 'true', 'true' )
                        ),
                        'animationStyle' => array(
                            'type' => 'string',
                            'default' => 'none'
                        ),
                        'animationType' => array(
                            'type' => 'string',
                            'default' => 'center'
                        ),
                    )
                ),
            )
        );
    }

    /**
     * Register gutenberg block output
     *
     * @param array $attributes - block attributes.
     *
     * @return string
     */
    public function block_render( $attributes ) {
        ob_start();
        $uniqueID = $attributes['uniqueID'];

        $class = 'ive-form-field ive-form-field-date';

        if ( isset( $attributes['className'] ) ) {
            $class .= ' ' . $attributes['className'];
        }
        $class .= ' form_date'.$uniqueID;
        $class .= ' '.$attributes['animationStyle'].'-'.$attributes['animationType'];

        ?>

        <div class="<?php echo esc_attr( $class ); ?>">
            <?php ive_Form_Field_Label::get( $attributes ); ?>

            <input type="date" <?php ive_Form_Field_Attributes::get( $attributes ); ?> />

            <?php ive_Form_Field_Description::get( $attributes ); ?>
        </div>

        <?php

        return ob_get_clean();
    }
}
new ive_Form_Field_Date_Block();
