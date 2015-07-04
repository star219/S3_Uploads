<?php

class Test_S3_Uploads extends WP_UnitTestCase {

	protected $s3 = null;

	public function setUp() {

		// start the tests with nothing added
		S3_Uploads::get_instance()->tear_down();
	}

	public function tearDown() {
		// reenable for other tests
		S3_Uploads::get_instance()->setup();
	}

	/**
	 * Test s3 uploads sets up all the necessary hooks
	 */
	public function test_setup() {

		S3_Uploads::get_instance()->setup();

		$this->assertEquals( 10, has_action( 'upload_dir', array( S3_Uploads::get_instance(), 'filter_upload_dir' ) ) );
		$this->assertEquals( 9, has_action( 'wp_image_editors', array( S3_Uploads::get_instance(), 'filter_editors' ) ) );
		$this->assertEquals( 10, has_action( 'wp_handle_sideload_prefilter', array( S3_Uploads::get_instance(), 'filter_sideload_move_temp_file_to_s3' ) ) );

		$this->assertTrue( in_array( 's3', stream_get_wrappers() ) );
		S3_Uploads::get_instance()->tear_down();
	}

	/**
	 * Test s3 uploads sets up all the necessary hooks
	 */
	public function test_tear_down() {

		S3_Uploads::get_instance()->setup();
		S3_Uploads::get_instance()->tear_down();

		$this->assertFalse( has_action( 'upload_dir', array( S3_Uploads::get_instance(), 'filter_upload_dir' ) ) );
		$this->assertFalse( has_action( 'wp_image_editors', array( S3_Uploads::get_instance(), 'filter_editors' ) ) );
		$this->assertFalse( has_action( 'wp_handle_sideload_prefilter', array( S3_Uploads::get_instance(), 'filter_sideload_move_temp_file_to_s3' ) ) );

		$this->assertFalse( in_array( 's3', stream_get_wrappers() ) );
	}
}
