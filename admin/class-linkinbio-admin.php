<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://yame.be
 * @since      1.0.0
 *
 * @package    Linkinbio
 * @subpackage Linkinbio/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Linkinbio
 * @subpackage Linkinbio/admin
 * @author     Yame <yannick@yame.be>
 */
class Linkinbio_Admin {

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

    private $option_name = 'linkinbio';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;


	}

	/**
	 * Register the stylesheets for the admin area.
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
        if( isset($_GET['page']) && $_GET['page'] == 'linkinbio' ) {
            wp_enqueue_style($this->plugin_name . '-css-compiled', plugin_dir_url(__FILE__) . 'css/linkinbio-admin.compiled.css', array(), $this->version, 'all');
        }

	}

	/**
	 * Register the JavaScript for the admin area.
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
        wp_enqueue_script( $this->plugin_name . '-vue', plugin_dir_url( __FILE__ ) . '../node_modules/vue/dist/vue.js', array(), $this->version, true);
        wp_enqueue_script( $this->plugin_name . '-axios', plugin_dir_url( __FILE__ ) . '../node_modules/axios/dist/axios.js', array(), $this->version, true);
        wp_enqueue_script( $this->plugin_name . '-sortable', plugin_dir_url( __FILE__ ) . 'js/vendor/sortable.min.js', array(), $this->version, true);
        wp_enqueue_script( $this->plugin_name . '-vuedraggable',  plugin_dir_url( __FILE__ ) . 'js/vendor/vuedraggable.min.js', array(), $this->version, true);


        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/linkinbio-admin.js', array( 'jquery' ), $this->version . time(), true );


	}

    /**
     * Add an options page under the Settings submenu
     *
     * @since  1.0.0
     */
    public function add_options_page() {

        $this->plugin_screen_hook_suffix = add_options_page(
            __( 'LinkInBio Settings', 'yame-linkinbio' ),
            __( 'LinkInBio', 'yame-linkinbio' ),
            'manage_options',
            $this->plugin_name,
            array( $this, 'display_options_page' )
        );

    }

    public function register_setting(){
        // Add a General section
        add_settings_section(
            $this->option_name . '_general',
            __( 'General', 'yame-linkinbio' ),
            array( $this, $this->option_name . '_general_cb' ),
            $this->plugin_name
        );

        add_settings_field(
            $this->option_name . '_position',
            __( 'Text position', 'yame-linkinbio' ),
            array( $this, $this->option_name . '_position_cb' ),
            $this->plugin_name,
            $this->option_name . '_general',
            array( 'label_for' => $this->option_name . '_position' )
        );

    }

    /**
     * Render the text for the general section
     *
     * @since  1.0.0
     */
    public function linkinbio_general_cb() {
        echo '<p>' . __( 'Please change the settings accordingly.', 'outdated-notice' ) . '</p>';
    }

    /*
     * **
     * Render the radio input field for position option
     *
     * @since  1.0.0
     */
    public function linkinbio_position_cb() {
        ?>
        <fieldset>
            <label>
                <input type="radio" name="<?php echo $this->option_name . '_position' ?>" id="<?php echo $this->option_name . '_position' ?>" value="before">
                <?php _e( 'Before the content', 'outdated-notice' ); ?>
            </label>
            <br>
            <label>
                <input type="radio" name="<?php echo $this->option_name . '_position' ?>" value="after">
                <?php _e( 'After the content', 'outdated-notice' ); ?>
            </label>
        </fieldset>
        <?php
    }

    /**
     * Render the options page for plugin
     *
     * @since  1.0.0
     */
    public function display_options_page() {

        include_once 'partials/linkinbio-admin-display.php';
    }

    /**
     * Register all our REST routes (crud)
     */
    public function register_rest_routes(){

        register_rest_route('linkinbio/v1', '/insta/', [
            'methods' => 'GET',
            'callback' => [$this, 'fetchInstagramMedias']
        ]);

        register_rest_route('linkinbio/v1', '/links', [
            'methods' => 'GET',
            'callback' => [$this, 'fetchLinks']
        ]);

        register_rest_route('linkinbio/v1', '/links', [
            'methods' => 'POST',
            'callback' => [$this, 'updateLinks']
        ]);

    }

    /**
     * @return mixed
     * @throws \InstagramScraper\Exception\InstagramException
     * @throws \InstagramScraper\Exception\InstagramNotFoundException
     */
    public function fetchInstagramMedias(){

        $username = (get_option($this->plugin_name.'_username', 'yame.be'));
        $description = (get_option($this->plugin_name.'_description'));

        $medias = LinkInBio_Instagram::getMedias($username);

            foreach( $medias as $media ){
                $media->getImageThumbnailUrl();
            }

        return $this->response( [
            'instagram_posts' => $this->mediasToArray( $medias ),
            'username' => $username,
            'description' => $description
        ] );

    }

    public function fetchLinks(){

        $data = get_option( $this->plugin_name . '_links', [] );

        return $this->response( $data );

    }

    public function updateLinks( $request ){

        $parameters = $request->get_params();

        update_option($this->plugin_name . '_links', $parameters['links'] );
        update_option( $this->plugin_name . '_username', sanitize_user( $parameters['username'] ) );
        update_option( $this->plugin_name . '_description', sanitize_textarea_field( $parameters['description'] ) );

        return $this->response( ['ok'] );

    }

    protected function mediasToArray( $medias ){
        $array = [];

        foreach( $medias as $media ){
            $array[] = [
                'instagram_url' => $media->getLink(),
                'image' => $media->getImageThumbnailUrl(),
            ];
        }

        return $array;
    }

    protected function response( $data ){

        // Create the response object
        $response = new WP_REST_Response( $data );

        // Add a custom status code
        $response->set_status( 200 );

        // Add a custom header
        $response->header( 'Content-type', 'application/json' );

        return $response;
    }


    public function page_settings_link( $links ){
        $links[] = '<a href="' .
            admin_url( 'options-general.php?page=linkinbio' ) .
            '">' . __('Configure') . '</a>';
        return $links;
    }

}
