<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Class WFFN_DB_Optin
 */
if ( ! class_exists( 'WFFN_DB_Optin' ) ) {
	class WFFN_DB_Optin {
		/**
		 * @var $ins
		 */
		public static $ins;

		/**
		 * @var $wp_db
		 */
		public $wp_db;

		/**
		 * @var $contact_tbl
		 */
		public $optin_tbl;

		/**
		 * WFFN_DB_Optin constructor.
		 */
		public function __construct() {
			global $wpdb;
			$this->wp_db     = $wpdb;
			$this->optin_tbl = $this->wp_db->prefix . 'bwf_optin_entries';
		}

		/**
		 * @return WFFN_DB_Optin
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}

		/**
		 * Inserting a new row in bwf_optins table
		 *
		 * @param $optin
		 *
		 * @return int
		 * @SuppressWarnings(PHPMD.DevelopmentCodeFragment)
		 */
		public function insert_optin( $optin ) {
			$optin_data = array(
				'step_id'   => $optin['step_id'],
				'funnel_id' => $optin['funnel_id'],
				'cid'       => $optin['cid'],
				'opid'      => $optin['opid'],
				'email'     => $optin['email'],
				'data'      => wp_json_encode( $optin['data'] ),
				'date'      => current_time( 'mysql' ),
			);

			$inserted = $this->wp_db->insert( $this->optin_tbl, $optin_data );

			$lastId = 0;
			if ( $inserted ) {
				$lastId = $this->wp_db->insert_id;
			}
			if ( ! empty( $this->wp_db->last_error ) ) {
				WFFN_Core()->logger->log( 'Get last error in insert_contact: ' . print_r( $this->wp_db->last_error, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
			}

			return $lastId;
		}

		/**
		 * Updating an optin
		 *
		 * @param $optin
		 *
		 * @SuppressWarnings(PHPMD.DevelopmentCodeFragment)
		 */
		public function update_optin( $optin ) {
			$update_data = array();
			foreach ( is_array( $optin ) ? $optin : array() as $key => $value ) {
				$update_data[ $key ] = $value;
			}

			$this->wp_db->update( $this->optin_tbl, $update_data, array( 'id' => $optin['id'] ) );

			if ( ! empty( $this->wp_db->last_error ) ) {
				WFFN_Core()->logger->log( "Get last error in update_optin for optin id: $optin[id]" . print_r( $this->wp_db->last_error, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
			}
		}

		/**
		 * Deleting an optin
		 *
		 * @param $optin_id
		 *
		 * @SuppressWarnings(PHPMD.DevelopmentCodeFragment)
		 */
		public function delete_optin( $optin_id ) {
			$this->wp_db->delete( $this->optin_tbl, array( 'id' => $optin_id ) );

			if ( ! empty( $this->wp_db->last_error ) ) {
				WFFN_Core()->logger->log( "Get last error in " . __FUNCTION__ . " for optin id: $optin_id" . print_r( $this->wp_db->last_error, true ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
			}
		}

		/**
		 * Getting an optin
		 *
		 * @SuppressWarnings(PHPMD.DevelopmentCodeFragment)
		 *
		 * @param $args
		 *
		 * @return array|object|null
		 */
		public function get_optins( $args ) {
			$search    = $args['s'];
			$limit     = $args['limit'];
			$page      = $args['page_no'];
			$orderby   = $args['orderby'];
			$order     = $args['order'];
			$funnel_id = $args['funnel_id'];
			$optin_id  = $args['optin_id'];

			$offset = intval( $limit ) * intval( $page - 1 );

			$optin_sql = "SELECT * FROM " . $this->optin_tbl . "  AS optin WHERE optin.funnel_id=$funnel_id";

			if ( ! empty( $optin_id ) && $optin_id > 0 ) {
				$optin_sql .= " AND optin.step_id=$optin_id";
			}

			if ( ! empty( $search ) ) {
				$optin_sql .= " AND optin.data LIKE '%$search%'";
			}
			if ( ! empty( $orderby ) ) {
				$optin_sql .= " ORDER BY optin.$orderby $order";
			}
			$optin_sql .= " LIMIT $offset, $limit";

			return $this->wp_db->get_results( $optin_sql, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		}

		/**
		 * Get contact for given opid if it exists
		 */
		public function get_contact_by_opid( $opid ) {
			$sql = "SELECT * FROM `$this->optin_tbl` WHERE `opid` = '$opid' ";

			$contact = $this->wp_db->get_row( $sql ); //WPCS: unprepared SQL ok

			return $contact;
		}

		/**
		 * Get contact for given id if it exists
		 */
		public function get_contact( $id ) {
			$sql = "SELECT * FROM `$this->optin_tbl` WHERE `id` = '$id' ";

			$contact = $this->wp_db->get_row( $sql ); //WPCS: unprepared SQL ok

			return $contact;
		}

		/**
		 * Get contact for given funnel id if it exists
		 */
		public function get_contact_by_funnels( $funnel_id ) {
			$sql = "SELECT * FROM `$this->optin_tbl` WHERE `funnel_id` = '$funnel_id' ORDER BY `$this->optin_tbl`.`id` ASC";

			$contact = $this->wp_db->get_results( $sql, ARRAY_A ); //WPCS: unprepared SQL ok

			return $contact;
		}


	}

	WFFN_DB_Optin::get_instance();
}