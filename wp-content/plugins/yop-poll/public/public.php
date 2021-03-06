<?php
class YOP_Poll_Public {
	public function __construct() {
		add_filter( 'script_loader_tag', array( $this, 'clean_recaptcha_url' ), 10, 2 );
		add_action( 'yop_poll_hourly_event', array( $this, 'cron' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'load_dependencies' ) );
		add_action( 'init', array( $this, 'load_translation' ) );
		add_action( 'init', array( $this, 'create_shortcodes' ) );
	}
	public function clean_recaptcha_url( $tag, $handle ) {
		if ( 'yop-reCaptcha' !== $handle )
        	return $tag;
		return str_replace( "&#038;", "&", str_replace( ' src', ' async defer src', $tag ) );
	}
	public function load_dependencies() {
		$this->load_styles();
		$this->load_scripts();
	}
	public function load_styles() {
		wp_enqueue_style( 'yop-public', YOP_POLL_URL . 'public/assets/css/yop-poll-public.css' );
	}
	public function load_scripts() {
		$plugin_settings = get_option( 'yop_poll_settings' );
		if ( false !== $plugin_settings ) {
			$plugin_settings_decoded = unserialize( $plugin_settings);
		}
		wp_enqueue_script( 'yop-public', YOP_POLL_URL . 'public/assets/js/yop-poll-public.min.js', array( 'jquery' ) );
        /* add reCaptcha if enabled */
        if (
            ( 'yes' === $plugin_settings_decoded['integrations']['reCaptcha']['enabled'] ) &&
            ( '' !== $plugin_settings_decoded['integrations']['reCaptcha']['site-key'] ) &&
            ( '' !== $plugin_settings_decoded['integrations']['reCaptcha']['secret-key'] )
        ) {
            $args = array(
                'render' => 'explicit',
                'onload' => 'YOPPollOnLoadRecaptcha'
            );
            wp_register_script( 'yop-reCaptcha', add_query_arg( $args, 'https://www.google.com/recaptcha/api.js' ), '', null );
            wp_enqueue_script( 'yop-reCaptcha' );
        }
        $captcha_accessibility_description = str_replace( '[STRONG]', '<strong>', esc_html( $plugin_settings_decoded['messages']['captcha']['accessibility-description'] ) );
        $captcha_accessibility_description = str_replace( '[/STRONG]', '</strong>', $captcha_accessibility_description );
        $captcha_explanation = str_replace( '[STRONG]', '<strong>', esc_html( $plugin_settings_decoded['messages']['captcha']['explanation'] ) );
        $captcha_explanation = str_replace( '[/STRONG]', '</strong>', $captcha_explanation );
        /* done adding reCaptcha */
		wp_localize_script( 'yop-public', 'objectL10n', array(
			'yopPollParams' => array(
				'urlParams' => array(
					'ajax' => admin_url( 'admin-ajax.php' ),
					'wpLogin' => wp_login_url( admin_url( 'admin-ajax.php?action=yop_poll_record_wordpress_vote' ) )
				),
				'apiParams' => array(
					'reCaptcha' => array(
						'siteKey' => ( isset( $plugin_settings_decoded['integrations'] ) && isset( $plugin_settings_decoded['integrations']['reCaptcha'] ) && isset( $plugin_settings_decoded['integrations']['reCaptcha']['site-key'] ) ) ? $plugin_settings_decoded['integrations']['reCaptcha']['site-key'] : ''
					)
				),
                'captchaParams' => array(
                    'imgPath' => YOP_POLL_URL . 'public/assets/img/',
                    'url' => YOP_POLL_URL . 'app.php',
                    'accessibilityAlt' => esc_html( $plugin_settings_decoded['messages']['captcha']['accessibility-alt'] ),
                    'accessibilityTitle' => esc_html( $plugin_settings_decoded['messages']['captcha']['accessibility-title'] ),
                    'accessibilityDescription' => $captcha_accessibility_description,
                    'explanation' => $captcha_explanation,
                    'refreshAlt' => esc_html( $plugin_settings_decoded['messages']['captcha']['refresh-alt'] ),
                    'refreshTitle' => esc_html( $plugin_settings_decoded['messages']['captcha']['refresh-title'] )
                ),
                'voteParams' => array(
                    'invalidPoll' => esc_html( $plugin_settings_decoded['messages']['voting']['invalid-poll'] ),
                    'noAnswersSelected' => esc_html( $plugin_settings_decoded['messages']['voting']['no-answers-selected'] ),
                    'minAnswersRequired' => esc_html( $plugin_settings_decoded['messages']['voting']['min-answers-required'] ),
                    'maxAnswersRequired' => esc_html( $plugin_settings_decoded['messages']['voting']['max-answers-required'] ),
                    'noAnswerForOther' => esc_html( $plugin_settings_decoded['messages']['voting']['no-answer-for-other'] ),
                    'noValueForCustomField' => esc_html( $plugin_settings_decoded['messages']['voting']['no-value-for-custom-field'] ),
                    'consentNotChecked' => esc_html( $plugin_settings_decoded['messages']['voting']['consent-not-checked'] ),
                    'noCaptchaSelected' => esc_html( $plugin_settings_decoded['messages']['voting']['no-captcha-selected'] ),
                    'thankYou' => esc_html( $plugin_settings_decoded['messages']['voting']['thank-you'] )
                ),
                'resultsParams'=> array(
                    'singleVote' => esc_html( $plugin_settings_decoded['messages']['results']['single-vote'] ),
                    'multipleVotes' => esc_html( $plugin_settings_decoded['messages']['results']['multiple-votes'] )
                )
			)
		));
	}
	public function load_translation() {
		load_plugin_textdomain( 'yop-poll', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
	public function create_shortcodes()  {
        add_shortcode( 'yop_poll', array( $this, 'parse_regular_shortcode' ) );
        add_shortcode( 'yop_poll_archive', array( $this, 'parse_archive_shortcode' ) );
	}
	public function parse_regular_shortcode( $atts ) {
		$params = shortcode_atts(
			array(
                'id'      => - 1,
                'results' => 0,
                'tid'   => '',
                'show_results' => ''
            ),
			$atts, 'yop_poll' );
		return $this->generate_poll( $params );
	}
    public function parse_archive_shortcode( $atts ) {
	    $content = '';
        $sql = 'SELECT `id` FROM ' . $GLOBALS['wpdb']->yop_poll_polls;
        $polls = $GLOBALS['wpdb']->get_results( $sql, ARRAY_A );
        if ( count( $polls ) > 0 ) {
            foreach ( $polls as $p ) {
	            $params = shortcode_atts(
		            array(
			            'id'      => $p['id'],
			            'results' => 0,
			            'tid'   => '',
			            'show_results' => ''
		            ),
		            $atts, 'yop_poll' );
                $content .= $this->generate_poll( $params );
            }
        }
        return $content;
    }
	public function generate_poll( $params ){
		if ( isset( $params['id'] ) && ( '' !== $params['id'] ) ) {
			$poll = '';
			$poll_ready_for_output = '';
			switch ( $params['id'] ) {
				case '-1': {
					$poll_id = YOP_Poll_Polls::get_current_active();
					break;
				}
				case '-2': {
					$poll_id = YOP_Poll_Polls::get_latest();
					break;
				}
				case '-3': {
					$poll_id = YOP_Poll_Polls::get_random();
					break;
				}
				default: {
					$poll_id = $params['id'];
					break;
				}
			}
			if ( isset( $poll_id ) ) {
				$poll = YOP_Poll_Polls::get_poll( $poll_id );
			}
			if ( false !== $poll ) {
				switch ( $poll->template_base ) {
					case 'basic': {
						$poll_ready_for_output = YOP_Poll_Basic::create_poll_view( $poll, $params );
						break;
					}
					case 'basic-pretty': {
						$poll_ready_for_output = YOP_Poll_Basic::create_poll_view( $poll, $params );
						break;
					}
				}
				$content_for_output = "<div class='bootstrap-yop'>
							{$poll_ready_for_output}
						</div>";
			} else {
				$content_for_output = '';
			}
			return $content_for_output;
		}
	}
	public function cron() {
		$polls = YOP_Poll_Polls::get_polls_for_cron();
		foreach ( $polls as $poll ) {
			if ( 'yes' === $poll['resetPollStatsAutomatically'] ) {
				if ( strtotime( $poll['resetPollStatsOn'] ) <= time() ) {
					YOP_Poll_Polls::reset_stats_for_poll( $poll['id'] );
					switch ( $poll['resetPollStatsEveryPeriod'] ) {
						case 'hours': {
							$unit_multiplier = 60 *60;
							break;
						}
						case 'days': {
							$unit_multiplier = 60 * 60 * 24;
							break;
						}
					}
					$next_reset_date = strtotime( $poll['resetPollStatsOn'] ) + intval( $poll['resetPollStatsEvery'] ) * $unit_multiplier;
					YOP_Poll_Polls::update_meta_data( $poll['id'], 'poll', 'resetPollStatsOn', date( 'Y-m-d H:i', $next_reset_date ) );
				}
			}
		}
	}
}
