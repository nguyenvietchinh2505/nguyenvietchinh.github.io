<?php

/**
 * Class WFFN_Wizard
 * Class controls rendering and behaviour of wizard for the Funnel
 */
class WFFN_Wizard {

	public static $is_wizard_done;
	public static $step;
	public static $suffix;
	public static $steps;
	public static $license_state = null;
	public static $key = '';
	public static $licence_key;
	public static $installed_plugins = null;

	public static function init() {
		add_action( 'current_screen', array( __CLASS__, 'setup_wizard' ) );
		add_action( 'wp_ajax_wffn_wiz_activate_plugin', array( __CLASS__, 'activate_plugin' ) );
		add_action( 'wp_ajax_wffn_wizard_optin', array( __CLASS__, 'wffn_wizard_optin' ) );
		add_action( 'wp_ajax_wffn_wizard_tracking', array( __CLASS__, 'wffn_wizard_tracking' ) );
		add_action( 'admin_notices', array( __CLASS__, 'show_setup_wizard' ) );
		add_action( 'admin_init', array( __CLASS__, 'hide_notices' ) );
		self::$is_wizard_done = get_option( '_wffn_onboarding_completed', false );
		add_action( 'admin_init', [ __CLASS__, 'force_reload_the_wizard' ], 0 );
		add_filter( 'woocommerce_enable_setup_wizard', [ __CLASS__, 'stop_wc_wizard' ] );
		add_action( 'wp_loaded', array( __CLASS__, 'unhook_connector_call' ), 0 );
	}

	public static function steps() {
		$cookie = 'wffn_wizard_steps';

		/** Triggers just for a user */
		if ( isset( $_COOKIE[ $cookie ] ) && ! empty( $_COOKIE[ $cookie ] ) ) {
			$val         = stripslashes_deep( $_COOKIE[ $cookie ] );
			self::$steps = json_decode( $val, true );

			if ( is_array( self::$steps ) ) {
				return self::$steps;
			}
		}

		self::$steps = array(
			'welcome'          => array(
				'name' => __( 'Welcome', 'funnel-builder' ),
				'view' => array( __CLASS__, 'wffn_setup_introduction' ),
			),
			'install_wc'       => self::get_wc_step(),
			'install_autonami' => self::get_autonami_step(),
			'optin'            => self::get_optin_step(),
			'thank_you'        => array(
				'name' => __( 'Thank You', 'funnel-builder' ),
				'view' => array( __CLASS__, 'wffn_setup_ready' ),
			),
		);
		if ( true === wffn_is_wc_active() ) {
			unset( self::$steps['install_wc'] );
		}
		if ( true === wffn_fk_automations_active() ) {
			unset( self::$steps['install_autonami'] );
		}
		$opted = get_option( 'bwf_is_opted_email', 'no' );
		if ( 'yes' === strval( $opted ) ) {
			unset( self::$steps['optin'] );
		}

		self::$steps = apply_filters( 'wffn_wizard_steps', self::$steps );

		$secure = is_ssl();
		if ( ! headers_sent() ) {
			setcookie( $cookie, json_encode( self::$steps ), time() + HOUR_IN_SECONDS, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, $secure ); //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.cookies_setcookie
		}

		return self::$steps;
	}

	public static function get_optin_step() {
		$optin    = get_option( 'bwf_is_opted_email', false );
		$is_opted = ( $optin === 'yes' ) ? 'disable' : '';

		return array(
			'name'   => __( 'Optin', 'funnel-builder' ),
			'view'   => array( __CLASS__, 'wffn_setup_optin' ),
			'status' => $is_opted,
		);
	}

	public static function get_wc_step() {
		$plugin_slug = 'woocommerce';
		$plugin_init = 'woocommerce/woocommerce.php';
		$status      = self::get_plugin_status( $plugin_init );

		return array(
			'name'   => __( 'Install Checkout', 'funnel-builder' ),
			'view'   => array( __CLASS__, 'wffn_setup_wc' ),
			'status' => ( 'disable' === $status || 'activated' === $status ) ? 'disable' : $status,
			'slug'   => $plugin_slug,
			'init'   => $plugin_init
		);
	}

	public static function get_autonami_step() {
		$plugin_slug = 'wp-marketing-automations';
		$plugin_init = 'wp-marketing-automations/wp-marketing-automations.php';
		$status      = self::get_plugin_status( $plugin_init );

		return array(
			'name'   => __( 'Install Autonami', 'funnel-builder' ),
			'view'   => array( __CLASS__, 'wffn_setup_autonami' ),
			'status' => ( 'disable' === $status || 'activated' === $status ) ? 'disable' : $status,
			'slug'   => $plugin_slug,
			'init'   => $plugin_init
		);
	}

	/**
	 * Show the setup wizard
	 */
	public static function setup_wizard() {
		if ( empty( $_GET['page'] ) || 'woofunnels' !== $_GET['page'] ) {   // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}
		if ( empty( $_GET['tab'] ) || WFFN_SLUG . '-wizard' !== $_GET['tab'] ) {  // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}
		self::steps();
		ob_end_clean();

		self::$step = isset( $_GET['step'] ) ? sanitize_key( $_GET['step'] ) : current( array_keys( self::$steps ) );  // phpcs:ignore WordPress.Security.NonceVerification.Recommended

		//enqueue style for admin notices
		wp_enqueue_style( 'wp-admin' );
		wp_enqueue_style( 'install' );
		wp_enqueue_style( 'dashicons' );
		wp_enqueue_script( 'updates' );
		$wffn_wiz = array(
			'installing'                => __( 'Installing...', 'funnel-builder' ),
			'activating'                => __( 'Activating...', 'funnel-builder' ),
			'activated'                 => __( 'Activated', 'funnel-builder' ),
			'activate_btn'              => __( 'Activate', 'funnel-builder' ),
			'ajaxurl'                   => admin_url( 'admin-ajax.php' ),
			'nonce_activate_plugin'     => wp_create_nonce( 'wffn_wiz_activate_plugin' ),
			'nonce_wiz_optin_choice'    => wp_create_nonce( 'wffn_wiz_optin_choice' ),
			'nonce_wiz_tracking_option' => wp_create_nonce( 'wffn_wiz_tracking_option' ),
		)
		?>
        <script>
            window.wffn_wiz =<?php echo wp_json_encode( apply_filters( 'wffn_wiz_localize_data', $wffn_wiz ) ); ?>;
            window.pagenow = 'woofunnels';
        </script>
		<?php

		ob_start();
		wp_print_admin_notice_templates();

		self::setup_wizard_header();
		self::setup_wizard_steps();
		$show_content = true;
		echo '<div class="wffn-setup-content">';

		if ( $show_content ) {
			self::setup_wizard_content();
		}
		echo '</div>';
		self::setup_wizard_footer();
		exit;
	}

public static function setup_wizard_header() { ?>
    <!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
    <head>
        <meta name="viewport" content="width=device-width"/>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<?php wp_site_icon(); ?>
        <title><?php esc_html_e( 'Plugin &rsaquo; Setup Wizard', 'funnel-builder' ); ?></title>
		<?php
		do_action( 'admin_print_styles' );
		do_action( 'admin_print_scripts' );
		?>
    </head>
	<?php self::setup_css(); ?>
    <body class="wffn-setup wp-core-ui">
    <div id="wc-logo"><img alt="Woofunnels Logo" height="40px;" src="<?php echo esc_url( plugin_dir_url( WooFunnel_Loader::$ultimate_path ) . 'woofunnels/assets/img/funnelkit-logo.svg' ); ?>"/></div>
	<?php
	}

	/**
	 * Output the steps
	 */
	public static function setup_wizard_steps() {
		$output_steps = self::$steps;

		?>
        <ol class="wffn-setup-steps">
			<?php foreach ( $output_steps as $step_key => $step ) : ?>
                <li class="
                    <?php
				if ( $step_key === self::$step ) {
					echo 'active';
				} elseif ( array_search( self::$step, array_keys( self::$steps ), true ) > array_search( $step_key, array_keys( self::$steps ), true ) ) {
					echo 'done';

				} ?>
                    ">
					<?php echo esc_html( $step['name'] ); ?>
                </li>
			<?php endforeach; ?>
        </ol>
		<?php
	}

	/**
	 * Setup Wizard Footer
	 */
	public static function setup_wizard_footer() {
	$wizard_back_link = admin_url();
	$wizard_link_text = __( 'Return to the WordPress Dashboard', 'funnel-builder' );

	if ( isset( $_GET['source'] ) && 'funnel' == $_GET['source'] ) {
		$wizard_back_link = admin_url( 'admin.php?page=bwf' );
		$wizard_link_text = __( 'Return to the Funnel Dashboard', 'funnel-builder' );

	}

	?>
    <a class="wc-return-to-dashboard" href="<?php echo esc_url( $wizard_back_link ); ?>"><?php echo esc_html( $wizard_link_text ); ?></a>
	<?php
	self::add_onboarding_js();
	?>
    </body>
	<?php

	@do_action( 'admin_footer' ); //phpcs:ignore Generic.PHP.NoSilencedErrors.Forbidden
	do_action( 'admin_print_footer_scripts' );
	?>
    </html>
	<?php
}


	public static function wffn_setup_introduction() {
		?>
        <h1><?php esc_html_e( 'You\'re on a roll! Follow the steps to get started!', 'funnel-builder' ); ?></h1>
        <p><?php esc_html_e( 'The Funnel Builder is already installed and activated on your site. Keep moving forward to start building your first sales funnel.', 'funnel-builder' ); ?></p>
        <div class="wffn_tracking_option">
            <div class="wffn_wizard_setup_message">
                <h4 class="wffn-setup-message-title no_topmrg"><?php esc_html_e( "Want to help us improve the user experience?", 'funnel-builder' ); ?> </h4>
                <p><?php esc_html_e( 'Help us make the overall user experience better. By checking the box, you\'ll allow us to track your interactions with our interface. Stay assured no sensitive information will be tracked or stored by us', 'funnel-builder' ); ?></p>
                <input type="checkbox" name="wffn_usage_tracking_option" id="wffn_usage_tracking_option" value="yes" checked>
                <label for="wffn_usage_tracking_option"><?php esc_html_e( 'Yes, I want to help improve the user experience', 'funnel-builder' ); ?></label>
            </div>
            <div class="wffn-setup-actions step" id="wffn_tracking_option" data-next-link="<?php echo esc_url( self::get_next_step_link() ) ?>">
                <a class="button-primary button button-large button-next" href="javascript:void(0);"><?php esc_html_e( 'Let\'s Go »', 'funnel-builder' ); ?></a>
            </div>
        </div>
		<?php
		$admin_id       = get_current_user_id();
		$wffn_activated = get_user_meta( $admin_id, '_wffn_activated_plugins', true ); //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.user_meta_get_user_meta
		if ( ! empty( $wffn_activated ) && is_array( $wffn_activated ) && count( $wffn_activated ) > 0 ) {
			delete_user_meta( $admin_id, '_wffn_activated_plugins' ); //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.user_meta_delete_user_meta
			echo '<META HTTP-EQUIV="REFRESH" CONTENT="0">';
		}
	}

	public static function wffn_wizard_tracking() {
		check_ajax_referer( 'wffn_wiz_tracking_option', '_nonce' );
		$choice = isset( $_POST['choice'] ) ? sanitize_text_field( $_POST['choice'] ) : '';

		if ( 'yes' === $choice ) {
			WooFunnels_optIn_Manager::Allow_optin();
		} else {
			WooFunnels_optIn_Manager::block_optin();
		}

		wp_send_json_success( array(
			'success' => true,
		) );
	}

	public static function wffn_setup_wc( $step ) {
		?>
        <h1><?php esc_html_e( 'Install Checkout', 'funnel-builder' ); ?></h1>
        <p><?php esc_html_e( "The Funnel Builder needs WooCommerce to run the checkout. You can still create Optin funnels without WooCommerce.", 'funnel-builder' ); ?></p>
        <p><?php esc_html_e( "It only takes a minute to install it.", 'funnel-builder' ); ?></p>
        <p class="wffn-setup-actions step">
            <a class="button-primary button button-large button-next" id="wffn_install_plugin" data-next-link="<?php echo esc_url( self::get_next_step_link() ) ?>&wffn_need_reload=true" data-init="<?php echo esc_attr( $step['init'] ) ?>" data-status="<?php echo esc_attr( $step['status'] ); ?>" data-slug="<?php echo esc_attr( $step['slug'] ); ?>" href="javascript:void(0);"><?php echo ( 'activate' === $step['status'] ) ? esc_html__( 'Activate Now', 'funnel-builder' ) : esc_html__( 'Install Now', 'funnel-builder' ) ?></a> <?php //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <a href="<?php echo esc_url( self::get_next_step_link() ); ?>"
               class="button-secondary button button-large button-next skip-button"><?php esc_html_e( 'Skip', 'funnel-builder' ); ?>
            </a>
        </p>
        <div style="display:none;" class="wffn-plugin-installation-failed notice notice-error">
            <p class="" style="margin-bottom: 0px;"> <?php esc_html_e( 'Error: Unable to install the plugin. Don\'t worry, you can skip the step.', 'funnel-builder' ); ?>
            </p>
        </div>
		<?php
	}

	public static function wffn_setup_autonami( $step ) {
		?>
        <h1><?php esc_html_e( 'Activate Automations', 'funnel-builder' ); ?></h1>
        <p><?php esc_html_e( "Go beyond funnels to engage with your customers using email and SMS based campaigns.", 'funnel-builder' ); ?></p>
        <p><?php esc_html_e( "It only takes a minute to install it.", 'funnel-builder' ); ?></p>
        <p class="wffn-setup-actions step">
            <a class="button-primary button button-large button-next" id="wffn_install_plugin" data-next-link="<?php echo esc_url( self::get_next_step_link() ) ?>" data-init="<?php echo esc_attr( $step['init'] ); ?>" data-status="<?php echo esc_attr( $step['status'] ); ?>" data-slug="<?php echo esc_attr( $step['slug'] ); ?>" href="javascript:void(0);"><?php echo ( 'activate' === $step['status'] ) ? esc_html__( 'Activate Now', 'funnel-builder' ) : esc_html__( 'Install Now', 'funnel-builder' ) ?></a> <?php //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <a href="<?php echo esc_url( self::get_next_step_link() ); ?>"
               class="button-secondary button button-large button-next  skip-button"><?php esc_html_e( 'Skip', 'funnel-builder' ); ?>
            </a>
        </p>
        <div style="display:none;" class="wffn-plugin-installation-failed notice notice-error">
            <p class="" style="margin-bottom: 0px;"> <?php esc_html_e( 'Error: Unable to install the plugin. Don\'t worry, you can skip the step.', 'funnel-builder' ); ?>
            </p>
        </div>
		<?php
	}

	public static function wffn_setup_optin() {
		?>
        <h1><?php esc_html_e( 'You\'re Almost Done!', 'funnel-builder' ); ?></h1>
        <p><?php esc_html_e( 'We send out helpful video tutorials and guides that help you build converting funnels.', 'funnel-builder' ); ?><br/> Stay in loop</p>
        <p>
            <input type="email" id="wffn_optin_email" placeholder="<?php esc_html_e( 'Enter your email to subscribe', 'funnel-builder' ); ?>" class="wffn-optin-email">
            <span class="wffn_invalid_email wffn-hide"><?php esc_html_e( 'Invalid Email. Please enter a valid email.', 'funnel-builder' ); ?></span>
        </p>
        <p class="wffn-setup-actions step" id="wffn_option_choice" data-next-link="<?php echo esc_url( self::get_next_step_link() ) ?>">
            <a class="button-primary button button-large button-next" data-choice="yes" href="javascript:void(0);"><?php esc_html_e( 'Allow', 'funnel-builder' ); ?></a>
            <a class="button-secondary button button-large skip-button" data-choice="no" href="javascript:void(0);"><?php esc_html_e( 'Skip', 'funnel-builder' ); ?></a>
        </p>
		<?php
	}

	public static function wffn_setup_ready() {
		?>
        <h1><?php esc_html_e( 'Awesome! Let\'s create your first funnel. ', 'funnel-builder' ); ?></h1>
        <p><?php esc_html_e( 'You\'re on your way to building your very first funnel with FunnelKit.', 'funnel-builder' ); ?></p>
        <p><?php esc_html_e( 'Here a few handy links to get help along the way!', 'funnel-builder' ); ?>
        <ul>
            <li>
                <a target="_blank" href="https://funnelkit.com/documentation/?utm_source=WordPress&utm_medium=Wizard+Doc&utm_campaign=Lite+Plugin"><?php esc_html_e( 'Visit Documentation', 'funnel-builder' ); ?></a>
            </li>
            <li>
                <a target="_blank" href="https://funnelkit.com/support/?utm_source=WordPress&utm_medium=Wizard+Support&utm_campaign=Lite+Plugin"><?php esc_html_e( 'Raise a support ticket', 'funnel-builder' ); ?></a>
            </li>
        </ul>
        <p class="wffn-setup-actions step">
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=bwf&path=/funnels' ) ); ?>" class="button-primary button button-large button-next"><?php esc_html_e( 'Create a Funnel', 'funnel-builder' ); ?></a>
        </p>
		<?php
		$admin_id       = get_current_user_id();
		$wffn_activated = get_user_meta( $admin_id, '_wffn_activated_plugins', true ); //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.user_meta_get_user_meta
		if ( ! empty( $wffn_activated ) && is_array( $wffn_activated ) && count( $wffn_activated ) > 0 ) {
			delete_user_meta( $admin_id, '_wffn_activated_plugins' ); //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.user_meta_delete_user_meta
		}
		update_option( '_wffn_onboarding_completed', true );

		/** On-boarding was complete */
		$secure = is_ssl();
		if ( ! headers_sent() ) {
			setcookie( 'wffn_wizard_steps', '', time() + HOUR_IN_SECONDS, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, $secure ); //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.cookies_setcookie
		}
	}


	public static function get_next_step_link() {
		$keys = array_keys( self::$steps );

		return add_query_arg( 'step', $keys[ array_search( self::$step, array_keys( self::$steps ), true ) + 1 ], remove_query_arg( 'translation_updated' ) );
	}

	public static function get_prev_step_link() {
		$keys = array_keys( self::$steps );

		return add_query_arg( 'step', $keys[ array_search( self::$step, array_keys( self::$steps ), true ) - 1 ], remove_query_arg( 'translation_updated' ) );
	}

	public static function setup_css() {
		?>
        <style>
            :root {
                --wffn-primary: #0073aa;
                --wffn-primary-alter: #135e96;
                --wffn-tertiary: #EBF2F6;
                --wffn-text: #353030;
                --wffn-desc: #82838E;
                --wffn-border: 1px solid rgb(222, 223, 234);
                --wffn-border-color: rgb(222, 223, 234);
                --wffn-border-radius: 6px;
                --wffn-light-bg: #e5e5e5;
                --wffn-error: #D72D1D;
            }

            html {
                background: #fff;
            }

            body {
                margin: 100px auto 100px auto;
                box-shadow: none;
                background: #fff;
                padding: 0;
                max-width: 800px;
                width: 100%;
                border: none;
                font-size: 13px;
                line-height: 28px;
                border-radius: 10px;
                color: var(--wffn-text);
            }

            p, li, dd, dt {
                padding-bottom: 2px;
                font-size: 15px;
                line-height: 1.5;
            }

            .qm-no-js {
                display: none !important;
            }

            #wc-logo {
                margin: 0;
                text-align: center;
                border: 1px solid var(--wffn-border-color);
                padding: 25px 0 0;
                border-bottom: 0;
                border-radius: 10px 10px 0 0;
            }

            #wc-logo img {
                max-width: 50%
            }

            .wffn-setup-steps {
                padding: 30px 0 24px;
                margin: 0;
                list-style: none;
                overflow: hidden;
                color: #ccc;
                width: 100%;
                display: -webkit-inline-flex;
                display: -ms-inline-flexbox;
                display: inline-flex;
                border: 1px solid var(--wffn-border-color);
                border-top: 0;
                box-sizing: border-box;
                border-bottom: 0;
            }

            .wffn-setup-steps li {
                width: 50%;
                float: left;
                padding: 0 0 .8em;
                margin: 0;
                text-align: center;
                position: relative;
                border-bottom: 4px solid #ccc;
                line-height: 1.4em
            }

            .wffn-setup-steps li:before {
                content: "";
                border: 4px solid #ccc;
                border-radius: 100%;
                width: 4px;
                height: 4px;
                position: absolute;
                bottom: 0;
                left: 50%;
                margin-left: -6px;
                margin-bottom: -8px;
                background: #fff
            }

            .wffn-setup-steps li a {
                text-decoration: none;
            }

            .wffn-setup-steps li.active {
                border-color: var(--wffn-primary-alter);;
                color: var(--wffn-primary-alter);;
            }

            .wffn-setup-steps li.active a {
                color: var(--wffn-primary-alter);;
            }

            .wffn-setup-steps li.active:before {
                border-color: var(--wffn-primary-alter);
            }

            .wffn-setup-steps li.done {
                border-color: var(--wffn-primary);
                color: var(--wffn-primary);
            }

            .wffn-setup-steps li.done a {
                color: var(--wffn-primary)
            }

            .wffn-setup-steps li.done:before {
                border-color: var(--wffn-primary);
                background: var(--wffn-primary);
            }

            .wffn-setup-content {
                padding: 10px 24px;
                background: #fff;
                overflow: hidden;
                zoom: 1;
                border: 1px solid var(--wffn-border-color);
                border-top: 0;
                border-radius: 0 0 10px 10px;
            }

            .wffn-setup-content h1, .wffn-setup-content h2, .wffn-setup-content h3, .wffn-setup-content table {
                margin: 0 0 24px;
                border: 0;
                padding: 0;
                color: var(--wffn-text);
                clear: none
            }

            .wffn-setup-content ul {
                list-style: disc
            }

            .wffn-setup-content h1 {
                line-height: 30px;
                font-weight: 500;
            }

            .wffn-setup-content p {
                font-size: 16px;
                line-height: 28px;
                color: var(--wffn-text);
                margin: 0 0 10px;
            }

            .wffn-setup-content a {
                color: var(--wffn-primary)
            }

            .wffn-setup-content a:focus, .wffn-setup-content a:hover {
                color: var(--wffn-primary-alter);
            }

            .wffn_wizard_setup_message {
                background: var(--wffn-tertiary);
                padding: 25px;
                border-radius: 10px;
            }

            .wffn_wizard_setup_message input[type="checkbox"] {
                margin-top: 2px
            }

            .wffn_wizard_setup_message .spinner {
                margin-top: 0
            }

            .wffn-setup-content .wffn_wizard_setup_message .wffn-setup-message-title {
                font-size: 16px;
                line-height: 28px;
                color: var(--wffn-text);
                font-weight: 600;
                margin: 10px auto;
            }

            .wffn-setup-content .no_topmrg {
                margin-top: 0 !important;
            }

            .wffn-setup-content .no_botmrg {
                margin-bootom: 0 !important;
            }

            .wffn-setup-content .wffn-setup-actions.step {
                margin: 20px 0 24px;
            }

            .wffn-setup .wffn-setup-actions {
                overflow: hidden
            }

            .wffn-setup .wffn-setup-actions .button {
                float: right;
                font-size: 16px;
                line-height: 22px;
                opacity: 1;
                height: auto;
                padding: 0 16px;
                -webkit-box-shadow: none;
                -moz-box-shadow: none;
                box-shadow: none;
                transform: translateY(0px);
                min-width: 84px;
                border-radius: 6px;
                font-size: 13px;
                font-size: 0.8125rem;
                border: 1px solid var(--wffn-primary);
                min-height: 36px;
                display: inline-flex;
                text-align: center;
                align-items: center;
                justify-content: center;
                gap: 5px;
            }

            .wffn-setup .wffn-setup-actions .button.skip-button {
                margin-right: 10px;
                background: #fff;
            }


            .wffn-setup .wffn-setup-actions .button-primary {
                margin: 0;
                float: right;
                opacity: 1;
                background: var(--wffn-primary);
            }

            .wffn-setup .wffn-setup-actions .button-primary:hover, .wffn-setup .wffn-setup-actions .button-primary:focus {
                background: var(--wffn-primary-alter);
            }

            .wffn-setup .wffn-setup-actions .button-primary.button-prev {
                float: left;
            }

            .wc-return-to-dashboard {
                color: #ccc;
                margin: 1em 0;
                display: block;
                text-align: center;
                box-shadow: none !important;
                outline: none !important;
            }


            li[data-slug="woocommerce"] > span,
            tr[data-content="attachment"] {
                display: none !important;
            }

            .wp-core-ui .woocommerce-button {
                background-color: #bb77ae !important;
                border-color: #A36597 !important;
                -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, .25), 0 1px 0 #A36597 !important;
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, .25), 0 1px 0 #A36597 !important;
                text-shadow: 0 -1px 1px #A36597, 1px 0 1px #A36597, 0 1px 1px #A36597, -1px 0 1px #A36597 !important;
                opacity: 1;
            }

            .wffn-setup-content p.no_border {
                border-bottom: none;
                margin-bottom: 0px;
            }

            .wffn-setup-content p.success {
                color: #7eb62e !important;
            }

            .wffn-setup-content p.error {
                color: red !important;
            }

            .wffn-setup-content table {
                margin: 0;
            }


            .wffn-setup-content .form-table th {
                width: 35%;
                vertical-align: top;
                font-weight: 400
            }

            .wffn-setup-content .form-table td {
                vertical-align: top
            }

            .wffn-setup-content .form-table td input, .wffn-setup-content .form-table td select {
                width: 100%;
                box-sizing: border-box
            }

            .wffn-setup-content .form-table td input[size] {
                width: auto
            }

            .wffn-setup-content .form-table td .description {
                line-height: 1.5em;
                display: block;
                margin-top: .25em;
                color: #999;
                font-style: italic
            }

            .wffn-setup-content .form-table td .input-checkbox, .wffn-setup-content .form-table td .input-radio {
                width: auto;
                box-sizing: inherit;
                padding: inherit;
                margin: 0 .5em 0 0;
                box-shadow: none
            }

            .wffn-setup-content .form-table .section_title td {
                padding: 0
            }

            .wffn-setup-content .form-table .section_title td h2, .wffn-setup-content .form-table .section_title td p {
                margin: 12px 0 0
            }

            .wffn-setup-content .form-table td, .wffn-setup-content .form-table th {
                padding: 12px 0;
                margin: 0;
                border: 0
            }

            .wffn-setup-content .form-table td:first-child, .wffn-setup-content .form-table th:first-child {
                padding-right: 1em
            }

            .wffn-setup-content .form-table table.tax-rates {
                width: 100%;
                font-size: .92em
            }

            .wffn-setup-content .form-table table.tax-rates th {
                padding: 0;
                text-align: center;
                width: auto;
                vertical-align: middle
            }

            .wffn-setup-content .form-table table.tax-rates td {
                border: 1px solid #eee;
                padding: 6px;
                text-align: center;
                vertical-align: middle
            }

            .wffn-setup-content .form-table table.tax-rates td input {
                outline: 0;
                border: 0;
                padding: 0;
                box-shadow: none;
                text-align: center
            }

            .wffn-setup-content .form-table table.tax-rates td.sort {
                cursor: move;
                color: #ccc
            }

            .wffn-setup-content .form-table table.tax-rates td.sort:before {
                content: "\f333";
                font-family: dashicons
            }

            .wffn-setup-content .form-table table.tax-rates .add {
                padding: 1em 0 0 1em;
                line-height: 1em;
                font-size: 1em;
                width: 0;
                margin: 6px 0 0;
                height: 0;
                overflow: hidden;
                position: relative;
                display: inline-block
            }

            .wffn-setup-content .form-table table.tax-rates .add:before {
                content: "\f502";
                font-family: dashicons;
                position: absolute;
                left: 0;
                top: 0
            }

            .wffn-setup-content .form-table table.tax-rates .remove {
                padding: 1em 0 0 1em;
                line-height: 1em;
                font-size: 1em;
                width: 0;
                margin: 0;
                height: 0;
                overflow: hidden;
                position: relative;
                display: inline-block
            }

            .wffn-setup-content .form-table table.tax-rates .remove:before {
                content: "\f182";
                font-family: dashicons;
                position: absolute;
                left: 0;
                top: 0
            }

            .wffn-setup-content .wffn-setup-plugins {
                width: 100%;
                border-top: 1px solid #eee
            }

            .wffn-setup-content .wffn-setup-plugins thead th {
                display: none
            }

            .wffn-setup-content .wffn-setup-plugins .plugin-name {
                width: 30%;
                font-weight: 700
            }

            .wffn-setup-content .wffn-setup-plugins td, .wffn-setup-content .wffn-setup-plugins th {
                padding: 14px 0;
                border-bottom: 1px solid #eee
            }

            .wffn-setup-content .wffn-setup-plugins td:first-child, .wffn-setup-content .wffn-setup-plugins th:first-child {
                padding-right: 9px
            }

            .wffn-setup-content .wffn-setup-plugins th {
                padding-top: 0
            }

            .wffn-setup-content .wffn-setup-plugins .page-options p {
                color: #777;
                margin: 6px 0 0 24px;
                line-height: 1.75em
            }

            .wffn-setup-content .wffn-setup-plugins .page-options p input {
                vertical-align: middle;
                margin: 1px 0 0;
                height: 1.75em;
                width: 1.75em;
                line-height: 1.75em
            }

            .wffn-setup-content .wffn-setup-plugins .page-options p label {
                line-height: 1
            }

            @media screen and (max-width: 782px) {
                .wffn-setup-content .form-table tbody th {
                    width: auto
                }
            }

            .wffn-setup-content .twitter-share-button {
                float: right
            }

            .wffn-setup-content .wffn-setup-next-steps {
                overflow: hidden;
                margin: 0 0 24px
            }

            .wffn-setup-content .wffn-setup-next-steps h2 {
                margin-bottom: 12px
            }

            .wffn-setup-content .wffn-setup-next-steps .wffn-setup-next-steps-first {
                float: left;
                width: 50%;
                box-sizing: border-box
            }

            .wffn-setup-content .wffn-setup-next-steps .wffn-setup-next-steps-last {
                float: right;
                width: 50%;
                box-sizing: border-box
            }

            .wffn-setup-content .wffn-setup-next-steps ul {
                padding: 0 2em 0 0;
                list-style: none;
                margin: 0 0 -.75em
            }

            .wffn-setup-content .wffn-setup-next-steps ul li a {
                display: block;
                padding: 0 0 .75em
            }

            .wffn-setup-content .wffn-setup-next-steps ul .setup-product a {
                text-align: center;
                font-size: 1em;
                padding: 1em;
                line-height: 1.75em;
                height: auto;
                margin: 0 0 .75em;
                opacity: 1;
            }

            .wffn-setup-content .wffn-setup-next-steps ul .setup-product a.button-primary {
                background-color: var(--wffn-primary);
                border-color: var(--wffn-primary);
                -webkit-box-shadow: inset 0 1px 0 rgba(255, 255, 255, .2), 0 1px 0 rgba(0, 0, 0, .15);
                box-shadow: inset 0 1px 0 rgba(255, 255, 255, .2), 0 1px 0 rgba(0, 0, 0, .15)
            }

            .wffn-setup-content .wffn-setup-next-steps ul li a:before {
                color: #82878c;
                font: 400 20px/1 dashicons;
                speak: none;
                display: inline-block;
                padding: 0 10px 0 0;
                top: 1px;
                position: relative;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
                text-decoration: none !important;
                vertical-align: top
            }

            .wffn-setup-content .wffn-setup-next-steps ul .documentation a:before {
                content: "\f331"
            }

            .wffn-setup-content .wffn-setup-next-steps ul .howto a:before {
                content: "\f223"
            }

            .wffn-setup-content .wffn-setup-next-steps ul .rating a:before {
                content: "\f155"
            }

            .wffn-setup-content .wffn-setup-next-steps ul .support a:before {
                content: "\f307"
            }

            .wffn-setup-content .updated, .wffn-setup-content .woocommerce-language-pack, .wffn-setup-content .woocommerce-tracker {
                padding: 24px 24px 0;
                margin: 0 0 24px;
                overflow: hidden;
                background: #f5f5f5
            }

            .wffn-setup-content .updated p, .wffn-setup-content .woocommerce-language-pack p, .wffn-setup-content .woocommerce-tracker p {
                padding: 0;
                margin: 0 0 12px
            }

            .wffn-setup-content .updated p:last-child, .wffn-setup-content .woocommerce-language-pack p:last-child, .wffn-setup-content .woocommerce-tracker p:last-child {
                margin: 0 0 24px
            }

            .dtbaker_loading_button_current {
                color: #CCC !important;
                text-align: center;

            }

            .wffn-wizard-plugins li {
                position: relative;
            }

            .wffn-wizard-plugins li span {
                padding: 0 0 0 10px;
                font-size: 0.9em;
                color: var(--wffn-primary);
                display: inline-block;
                position: relative;

            }

            .wffn-wizard-plugins.installing li .spinner {
                visibility: visible;
            }

            .wffn-wizard-plugins li .spinner {
                display: inline-block;
                position: absolute;

            }

            .wffn-setup-pages {
                width: 100%;
            }

            .wffn-setup-pages .check {
                width: 35px;
            }

            .wffn-setup-pages .item {
                width: 90px;
            }

            .wffn-setup-pages td,
            .wffn-setup-pages th {
                padding: 5px;
            }

            .wffn-setup-pages .status {
                display: none;
            }

            .wffn-setup-pages.installing .status {
                display: table-cell;
            }

            .wffn-setup-pages.installing .status span {
                display: inline-block;
                position: relative;
            }

            .wffn-setup-pages.installing .description {
                display: none;
            }

            .wffn-setup-pages.installing .spinner {
                visibility: visible;
            }

            .wffn-setup-pages .spinner {
                display: inline-block;
                position: absolute;

            }

            .theme-presets {
                background-color: rgba(0, 0, 0, .03);
                padding: 10px 20px;
                margin-left: -25px;
                margin-right: -25px;
                margin-bottom: 20px;
            }

            .theme-presets ul {
                list-style: none;
                margin: 0px 0 15px 0;
                padding: 0;
                overflow-x: auto;
                display: block;
                white-space: nowrap;
            }

            .theme-presets ul li {
                list-style: none;
                display: inline-block;
                padding: 6px;
                margin: 0;
                vertical-align: bottom;
            }

            .theme-presets ul li.current {
                background: #000;
                border-radius: 5px;
            }

            .theme-presets ul li a {
                float: left;
                line-height: 0;
            }

            .theme-presets ul li a img {
                width: 160px;
                height: auto;
            }

            .wffn_invalid_license {
                font-style: italic;
                color: #dc3232;
            }

            .button-primary .wffn_install.spinner {
                visibility: visible;
                float: left;
            }

            .wffn-setup-content .wffn-optin-email {
                width: 100%;
                height: 3.5em;
            }

            .wffn-hide {
                display: none;
                visibility: hidden;
            }

            .wffn_invalid_email, .wffn-wiz-error {
                color: #dc3232;
                font-size: 1em;
                font-style: italic;
                line-height: 1.75em;
            }


            .wffn-setup-content .wffn-builder {
                width: 50%;
                height: 3.2em;
                position: relative;
                left: 23px;
            }

            .wffn-setup .et-core-modal-overlay.et-core-form {
                display: none;
            }


            .wffn-setup-content .button-primary .wffn_install.spinner {
                background: none;
                opacity: 1;
                margin: 10px 5px 0px 0px;
                -webkit-animation: rotating 2s linear infinite;
                -moz-animation: rotating 2s linear infinite;
                -ms-animation: rotating 2s linear infinite;
                -o-animation: rotating 2s linear infinite;
                animation: rotating 2s linear infinite;
                margin: 5px 0 0;
            }

            @-webkit-keyframes rotating /* Safari and Chrome */
            {
                from {
                    -webkit-transform: rotate(0deg);
                    -o-transform: rotate(0deg);
                    transform: rotate(0deg);
                }
                to {
                    -webkit-transform: rotate(360deg);
                    -o-transform: rotate(360deg);
                    transform: rotate(360deg);
                }
            }

            @keyframes rotating {
                from {
                    -ms-transform: rotate(0deg);
                    -moz-transform: rotate(0deg);
                    -webkit-transform: rotate(0deg);
                    -o-transform: rotate(0deg);
                    transform: rotate(0deg);
                }
                to {
                    -ms-transform: rotate(360deg);
                    -moz-transform: rotate(360deg);
                    -webkit-transform: rotate(360deg);
                    -o-transform: rotate(360deg);
                    transform: rotate(360deg);
                }
            }

            .rotating {
                -webkit-animation: rotating 2s linear infinite;
                -moz-animation: rotating 2s linear infinite;
                -ms-animation: rotating 2s linear infinite;
                -o-animation: rotating 2s linear infinite;
                animation: rotating 2s linear infinite;
            }

            .wffn-setup-content .button-primary .wffn_install.spinner:after {
                font: normal 20px/1 'dashicons';
                content: "\f463";
                color: #fff;
            }
        </style>
		<?php
	}

	/**
	 * Output the content for the current step
	 */
	public static function setup_wizard_content() {
		isset( self::$steps[ self::$step ] ) ? call_user_func( self::$steps[ self::$step ]['view'], self::$steps[ self::$step ] ) : false;
	}

	public static function set_license_state( $state = false ) {
		self::$license_state = $state;
	}

	public static function set_license_key( $key = false ) {
		self::$key = $key;
	}

	public static function add_onboarding_js() {
		?>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                /* global pagenow */
                jQuery('#wffn_install_plugin').on('click', function () {
                    let plugin_slug, plugin_status, plugin_init, next_link;
                    plugin_slug = jQuery(this).attr('data-slug');
                    plugin_status = jQuery(this).attr('data-status');
                    plugin_init = jQuery(this).attr('data-init');
                    next_link = jQuery(this).attr('data-next-link');


                    if ('disable' === plugin_status) {
                        jQuery(this).html('<span class="wffn_install spinner"></span>' + wffn_wiz.activating);
                        window.location = next_link;
                    } else if ('install' === plugin_status) {
                        jQuery(this).html('<span class="wffn_install spinner"></span>' + wffn_wiz.installing);
                        // Add each plugin activate request in Ajax queue.
                        // @see wp-admin/js/updates.js
                        window.wp.updates.queue.push({
                                action: 'install-plugin', // Required action.
                                data: {
                                    slug: plugin_slug
                                }
                            }
                        );
                        window.wp.updates.queueChecker();
                    } else if ('activate' === plugin_status) {
                        activate_plugin(plugin_init, next_link);
                    }
                    jQuery(document).on(
                        'wp-plugin-install-success',
                        function (event, response) {
                            activate_plugin(plugin_init, next_link);
                        }
                    );


                });
                jQuery(document).on(
                    'wp-plugin-install-error',
                    function (event, response) {
                        jQuery('.wffn-plugin-installation-failed').show();
                        jQuery('#wffn_install_plugin').html(wffn_wiz.activate_btn);
                    }
                );

                function activate_plugin(plugin_init, nextlink) {
                    jQuery('#wffn_install_plugin').html('<span class="wffn_install spinner"></span>' + wffn_wiz.activating);
                    jQuery.ajax(
                        {
                            url: wffn_wiz.ajaxurl,
                            type: 'POST',
                            data: {
                                action: 'wffn_wiz_activate_plugin',
                                plugin_init: plugin_init,
                                _nonce: wffn_wiz.nonce_activate_plugin
                            },
                        }
                    )
                        .done(
                            function () {
                                jQuery('#wffn_install_plugin').html(wffn_wiz.activated);
                                window.location = nextlink;
                            }
                        );
                }

                jQuery('#wffn_option_choice a').on('click', function () {
                    let choice = jQuery(this).attr('data-choice');
                    let optin_email = '';
                    let nextlink = jQuery('#wffn_option_choice').attr('data-next-link');

                    jQuery('.wffn-setup-content').find('.wffn-wiz-error').remove();

                    if ('no' === choice) {
                        return window.location = nextlink;
                    }

                    if ('yes' === choice) {
                        optin_email = jQuery('#wffn_optin_email').val();
                        if ('' === optin_email || !validateEmail(optin_email)) {
                            jQuery('.wffn_invalid_email').removeClass('wffn-hide');
                            return;
                        }
                    }

                    jQuery(this).append('<span class="wffn_install spinner"></span>');
                    jQuery('.wffn_invalid_email').addClass('wffn-hide');

                    jQuery.ajax(
                        {
                            url: wffn_wiz.ajaxurl,
                            type: 'POST',
                            data: {
                                action: 'wffn_wizard_optin',
                                choice: choice,
                                optin_email: optin_email,
                                _nonce: wffn_wiz.nonce_wiz_optin_choice,
                            },
                        }
                    )
                        .done(
                            function (result) {
                                if (false === result.success) {
                                    jQuery('.wffn-setup-content').find('.wffn_install').remove();
                                    jQuery('#wffn_optin_email').after('<span class="wffn-wiz-error">' + result.message + '</span>');
                                } else {
                                    return window.location = nextlink;
                                }
                            }
                        );
                });

                jQuery('#wffn_tracking_option a').on('click', function () {

                    jQuery(this).append('<span class="wffn_install spinner"></span>');
                    let nextlink = jQuery('#wffn_tracking_option').attr('data-next-link');
                    let choice = 'yes';
                    if (jQuery('#wffn_usage_tracking_option').prop('checked') !== true) {
                        choice = 'no';
                    }

                    jQuery.ajax(
                        {
                            url: wffn_wiz.ajaxurl,
                            type: 'POST',
                            data: {
                                action: 'wffn_wizard_tracking',
                                choice: choice,
                                _nonce: wffn_wiz.nonce_wiz_tracking_option,
                            },
                        }
                    )
                        .done(
                            function () {
                                window.location = nextlink;
                            }
                        );
                });

                function validateEmail(email) {
                    let emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                    return emailReg.test(email);
                }
            });
        </script>
		<?php
	}

	public static function get_plugin_status( $plugin_init_file ) {
		if ( null === self::$installed_plugins ) {
			self::$installed_plugins = get_plugins();
		}

		if ( ! isset( self::$installed_plugins[ $plugin_init_file ] ) ) {
			return 'install';
		} elseif ( ! is_plugin_active( $plugin_init_file ) ) {
			return 'activate';
		}

		$admin_id       = get_current_user_id();
		$wffn_activated = get_user_meta( $admin_id, '_wffn_activated_plugins', true ); //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.user_meta_get_user_meta
		$wffn_activated = is_array( $wffn_activated ) ? $wffn_activated : [];

		return in_array( $plugin_init_file, $wffn_activated, true ) ? 'activated' : 'disable';
	}

	/**
	 * Ajax action to activate plugin
	 */
	public static function activate_plugin() {
		check_ajax_referer( 'wffn_wiz_activate_plugin', '_nonce' );

		$plugin_init = isset( $_POST['plugin_init'] ) ? sanitize_text_field( $_POST['plugin_init'] ) : '';

		$activate = activate_plugin( $plugin_init, '', false, true );

		if ( is_wp_error( $activate ) ) {
			wp_send_json_error( array(
				'success' => false,
				'message' => $activate->get_error_message(),
				'init'    => $plugin_init,
			) );
		}

		$admin_id       = get_current_user_id();
		$wffn_activated = get_user_meta( $admin_id, '_wffn_activated_plugins', true ); //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.user_meta_get_user_meta
		$wffn_activated = is_array( $wffn_activated ) ? $wffn_activated : [];

		array_push( $wffn_activated, $plugin_init );
		update_user_meta( $admin_id, '_wffn_activated_plugins', $wffn_activated ); //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.user_meta_update_user_meta

		wp_send_json_success( array(
			'success' => true,
			'message' => __( 'Plugin Successfully Activated', 'funnel-builder' ),
			'init'    => $plugin_init,
		) );
	}

	public static function wffn_wizard_optin() {
		check_ajax_referer( 'wffn_wiz_optin_choice', '_nonce' );

		$result = array(
			'success' => false,
			'message' => __( 'Something wrong try again', 'funnel-builder' ),
		);

		$posted_content = isset( $_POST ) ? wffn_clean( $_POST ) : [];
		$email_id       = isset( $posted_content['optin_email'] ) ? $posted_content['optin_email'] : '';

		if ( $email_id !== '' ) {

			$api_params = array(
				'action' => 'woofunnelsapi_email_optin',
				'data'   => array( 'email' => $email_id, 'site' => home_url() ),
			);

			$request_args = WooFunnels_API::get_request_args( array(
				'timeout'   => 30, //phpcs:ignore WordPressVIPMinimum.Performance.RemoteRequestTimeout.timeout_timeout
				'sslverify' => WooFunnels_API::$is_ssl,
				'body'      => urlencode_deep( $api_params ),
			) );

			$request = wp_remote_post( WooFunnels_API::get_api_url( WooFunnels_API::$woofunnels_api_url ), $request_args );

			if ( ! is_wp_error( $request ) ) {
				update_option( 'bwf_is_opted_email', 'yes', true );

				$result = array(
					'success' => true,
				);
			}
		}

		wp_send_json( $result );
	}

	/**
	 * Show action links on the plugin screen.
	 *
	 * @return void
	 * @since 1.0.0
	 */

	public static function show_setup_wizard() {

		$screen    = get_current_screen();
		$screen_id = $screen ? $screen->id : '';

		$allowed_screens = array(
			'woofunnels_page_bwf_funnels',
			'dashboard',
			'plugins',
		);
		if ( ! in_array( $screen_id, $allowed_screens, true ) ) {
			return;
		}

		$status = get_option( '_wffn_onboarding_completed', false );

		if ( false === $status ) { ?>
            <div class="notice notice-info bwf-notice">
                <p><b><?php esc_html_e( 'Thank you for activating the Funnel Builder!', 'funnel-builder' ); ?></b></p>
                <p><?php esc_html_e( 'Start a quick tour to get familiar with the interface.', 'funnel-builder' ); ?></p>
                <p>
                    <a href="<?php echo esc_url( WFFN_Core()->admin->wizard_url() ); ?>" class="button button-primary"> <?php esc_html_e( 'Start Wizard', 'funnel-builder' ); ?></a>
                    <a class="button-secondary" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'wffn-hide-notice', 'install' ), 'wffn_hide_notices_nonce', '_wffn_notice_nonce' ) ); ?>"><?php esc_html_e( 'Skip', 'funnel-builder' ); ?></a>
                </p>
            </div>
			<?php
		}
	}

	/**
	 * Hide a notice if the GET variable is set.
	 */

	public static function hide_notices() {

		if ( ! isset( $_GET['wffn-hide-notice'] ) ) {
			return;
		}

		$hide_notice  = filter_input( INPUT_GET, 'wffn-hide-notice', FILTER_UNSAFE_RAW );
		$notice_nonce = filter_input( INPUT_GET, '_wffn_notice_nonce', FILTER_UNSAFE_RAW );

		if ( $hide_notice && $notice_nonce && wp_verify_nonce( sanitize_text_field( wp_unslash( $notice_nonce ) ), 'wffn_hide_notices_nonce' ) ) {
			update_option( '_wffn_onboarding_completed', true );
			wp_redirect( wp_get_referer() );
			exit;
		}
	}

	public static function force_reload_the_wizard() {
		if ( isset( $_GET['wffn_need_reload'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			unset( $_GET['wffn_need_reload'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$url = add_query_arg( $_GET, admin_url( 'admin.php' ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			wp_safe_redirect( $url );
			exit;
		}
	}

	public static function stop_wc_wizard( $status ) {
		if ( isset( $_GET['tab'] ) && 'wffn-wizard' == $_GET['tab'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$status = false;
		}

		return $status;
	}

	public static function unhook_connector_call() {
		if ( ! isset( $_GET['page'] ) || 'woofunnels' !== $_GET['page'] ) {   // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}
		if ( ! isset( $_GET['tab'] ) || WFFN_SLUG . '-wizard' !== $_GET['tab'] ) {  // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}
		if ( ! class_exists( 'WFCO_Common' ) ) {
			return;
		}
		remove_action( 'wp_loaded', array( 'WFCO_Common', 'get_connectors_data' ) );
	}


}

WFFN_Wizard::init();
