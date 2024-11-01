<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://yame.be
 * @since      1.0.0
 *
 * @package    Linkinbio
 * @subpackage Linkinbio/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Linkinbio
 * @subpackage Linkinbio/public
 * @author     Yame <yannick@yame.be>
 */
class Linkinbio_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Linkinbio_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Linkinbio_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/linkinbio-public.css', array(), $this->version . time(), 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Linkinbio_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Linkinbio_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/linkinbio-public.js', array( 'jquery' ), $this->version, false );

	}

	public function linkinbio_create_shortcode(){

	    $links = get_option( $this->plugin_name . '_links' );
	    $username = get_option( $this->plugin_name . '_username', 'yame.be' );
	    $description = get_option( $this->plugin_name . '_description' );

	    ob_start();
	    ?>

        <div class="linkinbio-user">
            <img class="linkinbio-avatar" style="background-image:url(<?php echo LinkInBio_Instagram::getAvatarUrl($username); ?>);">
            <?php if( $description ): ?>
                <p class="linkinbio-description"><?php echo $description; ?></p>
            <?php endif; ?>
        </div>

        <?php
        $html = ob_get_clean();

        ob_start();
	    echo '<div class="linkinbio">';
	    $html .= ob_get_clean();

	    ob_start();
        foreach( $links as $media ){

            if( empty($media['url']) or empty($media['title']) ){
                continue;
            }

            ?>
            <div class="linkinbio--item">
                <div>
                    <a class="linkinbio--item--link" href="<?php echo $media['url']; ?>" target="_blank">
                        <div class="linkinbio--item--picture" style="background-image:url(<?php echo $media['image'] ; ?>)">
                        </div>
                    </a>
                    <div class="linkinbio--item--title">
                        <a href="<?php echo $media['url']; ?>" target="blank"><?php echo $media['title']; ?></a>
                    </div>
                </div>
            </div>
            <?php

        }

        echo '</div>';
        $html .= ob_get_clean();

        return $html;

    }

}
