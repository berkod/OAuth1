<?php

/**
 * Unit tests covering WP_JSON_Server functionality.
 *
 * @todo Do we bother testing serve_request() or leave that for client tests?
 *       It might be nice to at least test JSONP support here.
 *
 * @group json_api
 *
 * @package WordPress
 * @subpackage JSON API
 */
class WP_Test_OAuth1 extends WP_UnitTestCase {

	/**
	 * Create WP_JSON_Server class instance for use with tests.
	 *
	 * @todo Use core method for fetching filtered WP_JSON_Server class when
	 *       it's available. Ideally, we shouldn't be filtering ourselves here.
	 */
	function setUp() {
		global $wp_oauth1, $_POST;

		parent::setUp();

		include_once( plugin_dir_path( dirname( __FILE__ ) ) . 'lib/class-wp-json-authentication-oauth1.php' );

    $wp_oauth1 = new WP_JSON_Authentication_OAuth1();
    $_POST = array();
	}

	/**
	 * The server should be able to authenticate users using basic auth.
	 */
	function test_invalid_xauth_pass() {
		global $wp_oauth1;
        $user_creds = array(
        			'user_login' => 'basic_auth',
        			'user_pass' => 'basic_auth'
        		) ;
		$user_id = $this->factory->user->create( $user_creds );
        $_POST["x_auth_username"] = $user_creds['user_login'];
        $_POST["x_auth_password"] = $user_creds['user_pass'].'SALT';
		$result = $wp_oauth1->handle_xauth_request();
		$this->assertTrue( $result instanceof WP_Error   );
		$this->assertTrue( $result->get_error_code() == 'incorrect_password' );


	}

}
