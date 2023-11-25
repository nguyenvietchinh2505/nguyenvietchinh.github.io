<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Class WFACP_Contacts_Analytics
 */
if ( ! class_exists( 'WFACP_Contacts_Analytics' ) ) {
	class WFACP_Contacts_Analytics {

		/**
		 * instance of class
		 * @var null
		 */
		private static $ins = null;
		/**
		 * WPDB instance
		 *
		 * @since 2.0
		 *
		 * @var $wp_db
		 */
		protected $wp_db;

		/**
		 * WFACP_Contacts_Analytics constructor.
		 */
		public function __construct() {
			global $wpdb;
			$this->wp_db = $wpdb;
		}

		/**
		 * @return WFACP_Contacts_Analytics|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self();
			}

			return self::$ins;
		}

		/**
		 * @param $funnel_id
		 * @param string $search
		 *
		 * @return array|object|null
		 */
		public function get_contacts( $funnel_id, $search = '' ) {

			if ( ! empty( $search ) ) {
				$query = "SELECT contact.id as cid, contact.f_name, contact.l_name, contact.email, aero.date, aero.total_revenue, aero.wfacp_id FROM " . $this->wp_db->prefix . 'bwf_contact' . " AS contact JOIN " . $this->wp_db->prefix . 'wfacp_stats' . " AS aero ON contact.id=aero.cid WHERE aero.fid=$funnel_id";

				$query .= " AND (contact.f_name LIKE '%$search%' OR contact.email LIKE '%$search%') group by contact.id";

			} else {
				$query = "SELECT aero.cid FROM " . $this->wp_db->prefix . 'wfacp_stats' . " AS aero WHERE aero.fid=$funnel_id";
			}

			return $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		}

		public function get_contacts_by_funnel( $funnel_id ) {
			return "SELECT DISTINCT cid as id FROM " . $this->wp_db->prefix . "wfacp_stats WHERE fid = " . $funnel_id . "";

		}

		public function get_contacts_by_funnel_and_checkout_id( $funnel_id, $checkout_ids ) {
			return "SELECT cid,wfacp_id FROM " . $this->wp_db->prefix . "wfacp_stats WHERE fid = " . $funnel_id . " AND wfacp_id IN " . $funnel_id . "";

		}

		/**
		 * @param $funnel_id
		 * @param string $search
		 * @param string $offset
		 * @param string $limit
		 * @param string $orderby
		 * @param string $order
		 *
		 * @return array|object|null
		 */
		public function get_contacts_data( $funnel_id, $filtered_ids = [] ) {
			$get_total_possible_contacts_str = implode( ',', $filtered_ids );
			$query                           = "SELECT aero.cid as cid, DATE_FORMAT(aero.date, '%Y-%m-%dT%TZ') as 'date', aero.total_revenue, aero.total_revenue AS aero_revenue, aero.wfacp_id FROM " . $this->wp_db->prefix . 'wfacp_stats' . " AS aero WHERE aero.fid=$funnel_id AND `cid` IN (" . $get_total_possible_contacts_str . ")";

			$data     = $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			return $data;

		}

		/**
		 * @param $funnel_id
		 * @param $cid
		 *
		 * @return array|object|null
		 */
		public function get_all_contacts_records( $funnel_id, $cid ) {
			$query = "SELECT aero.wfacp_id as 'object_id', p.post_title as 'object_name',aero.total_revenue as 'total_revenue',DATE_FORMAT(aero.date, '%Y-%m-%dT%TZ') as 'date', 'checkout' as 'type' FROM " . $this->wp_db->prefix . 'wfacp_stats' . " AS aero LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON aero.wfacp_id  = p.id WHERE aero.fid=$funnel_id AND aero.cid=$cid order by aero.date asc";

			$data     = $this->wp_db->get_results( $query ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			return $data;
		}

		/**
		 * @param $funnel_id
		 * @param $start_date
		 * @param $end_date
		 * @param $interval_query
		 * @param $group_by
		 *
		 * @return string
		 */
		public function get_contacts_by_funnel_id( $funnel_id, $start_date, $end_date, $interval_query = '', $group_by = '' ) {
			return "SELECT DISTINCT cid as contacts " . $interval_query . " FROM `" . $this->wp_db->prefix . "wfacp_stats` WHERE `date` >= '" . $start_date . "' AND `date` < '" . $end_date . "' AND fid = " . $funnel_id . " " . $group_by;
		}

		/**
		 * @param $funnel_id
		 * @param $start_date
		 * @param $end_date
		 *
		 * @return array|object|null
		 */
		public function get_records_by_date_range( $funnel_id, $start_date, $end_date ) {

			$query = "SELECT aero.wfacp_id as 'object_id', p.post_title as 'object_name',SUM(aero.total_revenue) as 'total_revenue',COUNT(aero.ID) as cn,aero.date as 'date', 'checkout' as 'type' FROM " . $this->wp_db->prefix . 'wfacp_stats' . " AS aero LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON aero.wfacp_id  = p.id WHERE aero.fid=$funnel_id AND date BETWEEN '" . $start_date . "' AND '" . $end_date . "' GROUP by aero.wfacp_id ASC";

			return $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		}

		/**
		 * @param $funnel_id
		 * @param $interval_query
		 * @param $start_date
		 * @param $end_date
		 * @param $group_by
		 * @param $limit
		 *
		 * @return array|object|null
		 */
		public function get_total_orders_by_interval( $funnel_id, $interval_query, $start_date, $end_date, $group_by, $limit ) {
			$funnel_param = ( $funnel_id !== '' && $funnel_id > 0 ) ? " AND fid = " . $funnel_id . " " : '';

			$query = "SELECT  COUNT(ID) as total_orders " . $interval_query . "  FROM `" . $this->wp_db->prefix . "wfacp_stats` WHERE 1=1 AND `date` >= '" . $start_date . "' AND `date` < '" . $end_date . "' " . $funnel_param . $group_by . " ORDER BY id ASC " . $limit . "";

			return $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		}

		/**
		 * @param $funnel_id
		 * @param $interval_query
		 * @param $start_date
		 * @param $end_date
		 * @param $group_by
		 *
		 * @return array|object|null
		 */
		public function get_total_revenue_by_interval( $funnel_id, $interval_query, $start_date, $end_date, $group_by ) {

			$query = "SELECT  SUM(total_revenue) as sum_aero " . $interval_query . "  FROM `" . $this->wp_db->prefix . "wfacp_stats` WHERE 1=1 AND `date` >= '" . $start_date . "' AND `date` < '" . $end_date . "' AND fid = " . $funnel_id . " " . $group_by . " ORDER BY id ASC";

			return $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		}


		/**
		 * @param $start_date
		 * @param $end_date
		 *
		 * @return array|object|null
		 */
		public function get_total_orders( $funnel_id, $start_date, $end_date ) {
			$funnel_id = ( $funnel_id !== '' ) ? " AND fid = " . $funnel_id . " " : '';
			$query     = "SELECT  COUNT(ID) as total_orders FROM `" . $this->wp_db->prefix . "wfacp_stats` WHERE 1=1 AND `date` >= '" . $start_date . "' AND `date` < '" . $end_date . "' " . $funnel_id . " ORDER BY id ASC";

			$data     = $this->wp_db->get_row( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			return $data;
		}

		/**
		 * @param $start_date
		 * @param $end_date
		 *
		 * @return array
		 */
		public function get_total_cids( $start_date, $end_date ) {
			$query = "SELECT DISTINCT cid FROM `" . $this->wp_db->prefix . "wfacp_stats` WHERE 1=1 AND `date` >= '" . $start_date . "' AND `date` < '" . $end_date . "' ORDER BY cid ASC";

			$data     = $this->wp_db->get_col( $query ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			return $data;
		}

		/**
		 * @param $start_date
		 * @param $end_date
		 *
		 * @return array|object|null
		 */
		public function get_total_revenue( $funnel_id, $start_date, $end_date ) {
			$funnel_id = ( $funnel_id !== '' ) ? " AND fid = " . $funnel_id . " " : '';
			$query     = "SELECT SUM(total_revenue) as sum_aero FROM `" . $this->wp_db->prefix . "wfacp_stats` WHERE 1=1 AND `date` >= '" . $start_date . "' AND `date` < '" . $end_date . "' " . $funnel_id . " ORDER BY id DESC";

			$data     = $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			return $data;

		}

		/**
		 * @param $start_date
		 * @param $end_date
		 * @param string $limit
		 *
		 * @return array
		 */
		public function get_top_checkouts( $start_date, $end_date, $limit = '' ) {
			$limit = ( $limit !== '' ) ? " LIMIT " . $limit : '';
			$query = "SELECT wfacp_id,sum(total_revenue) as revenue,COUNT(aero.id) as conversion,p.post_title as title FROM `" . $this->wp_db->prefix . "wfacp_stats` as aero LEFT JOIN " . $this->wp_db->prefix . "posts as p ON p.id = aero.wfacp_id WHERE date >= '" . $start_date . "' AND date < '" . $end_date . "' GROUP BY wfacp_id ORDER BY sum(total_revenue) DESC" . $limit;

			$data     = $this->wp_db->get_results( $query ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			return $data;
		}

		/**
		 * @param $aero_id
		 * @param $start_date
		 * @param $end_date
		 *
		 * @return array|object|null
		 */
		public function get_aero_data_by_id( $aero_id, $start_date = '', $end_date = '' ) {
			$range = '';
			if ( $start_date !== '' && $end_date !== '' ) {
				$range = "WHERE date >= '" . $start_date . "' AND date < '" . $end_date . "' AND ";
			}
			$query = "SELECT * from `" . $this->wp_db->prefix . "wfacp_stats` WHERE " . $range . " wfacp_id = " . $aero_id;

			return $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
		}

		public function get_contacts_by_ids( $ids, $fid, $start_date = '' ) {
			$get_total_possible_contacts_str = implode( ',', $ids );
			$start_date = $start_date !== '' ? " AND date >= '".$start_date."' ": '';

			$query =  "SELECT DISTINCT cid FROM " . $this->wp_db->prefix . "wfacp_stats WHERE wfacp_id IN (" . $get_total_possible_contacts_str.") AND fid = $fid " . $start_date;
			return $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		}

		/**
		 * @param $limit
		 * @param string $order
		 * @param string $order_by
		 *
		 * @return string
		 */
		public function get_timeline_data_query( $limit, $order = "DESC", $order_by = 'date' ) {
			$limit = ( $limit !== '' ) ? " LIMIT " . $limit : '';

			return "SELECT stats . wfacp_id as id, stats . cid as 'cid', stats . order_id as 'order_id', CONVERT( stats . total_revenue USING utf8) as 'total_revenue', 'aero' as 'type', posts . post_title as 'post_title', stats . date as date FROM " . $this->wp_db->prefix . "wfacp_stats as stats LEFT JOIN " . $this->wp_db->prefix . "posts as posts ON stats . wfacp_id = posts . ID ORDER BY " . $order_by . " " . $order . " " . $limit;
		}

		/**
		 * @param $start_date
		 * @param $end_date
		 * @param $limit
		 *
		 * @return array|object|null
		 */
		public function get_top_funnels( $start_date, $end_date, $limit ) {
			$limit = ( $limit !== '' ) ? " LIMIT " . $limit : '';
			$query = "SELECT fid, SUM( total_revenue ) as total FROM " . $this->wp_db->prefix . "wfacp_stats WHERE fid != 0 and date >= '" . $start_date . "' and date < '" . $end_date . "' group by fid ORDER BY total DESC " . $limit;

			$data     = $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			return $data;
		}


		/**
		 * @param $funnel_id
		 * @param array $cids
		 *
		 * @return bool|false|int
		 */
		public function delete_contact( $funnel_id, $cids = array() ) {
			$cid_count                = count( $cids );
			$stringPlaceholders       = array_fill( 0, $cid_count, '%s' );
			$placeholdersForFavFruits = implode( ',', $stringPlaceholders );

			$query = "DELETE FROM " . $this->wp_db->prefix . "wfacp_stats WHERE cid IN( " . $placeholdersForFavFruits . " ) and fid = " . $funnel_id;

			$data     = $this->wp_db->query( $this->wp_db->prepare( $query, $cids ) ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			return $data;
		}


		/**
		 * @param $contact_id
		 * @param string $search
		 *
		 * @return array|object|null
		 */
		public function get_contacts_crm( $contact_id, $search = '' ) {

			if ( ! empty( $search ) ) {
				$query = "SELECT p . post_title as title, aero . fid as fid, aero . wfacp_id FROM " . $this->wp_db->prefix . 'bwf_contact' . " as contact JOIN " . $this->wp_db->prefix . 'wfacp_stats' . " as aero ON contact . id = aero . cid LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON aero . fid = p . id WHERE aero . cid = $contact_id";

				$query .= " and ( contact . f_name LIKE '%$search%' or contact . email LIKE '%$search%') group by contact . id";

			} else {
				$query = "SELECT p . post_title as title, aero . fid FROM " . $this->wp_db->prefix . 'wfacp_stats' . " as aero LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON aero . fid = p . id WHERE aero . cid = $contact_id";
			}

			return $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		}


		/**
		 * @param $contact_id
		 * @param string $search
		 * @param string $offset
		 * @param string $limit
		 * @param string $orderby
		 * @param string $order
		 *
		 * @return array|object|null
		 */
		public function get_contacts_data_crm( $contact_id, $search = '', $offset = '', $limit = '', $orderby = '', $order = '' ) {
			$query = "SELECT p . post_title as title, aero . fid as fid, aero . wfacp_id FROM " . $this->wp_db->prefix . 'bwf_contact' . " as contact JOIN " . $this->wp_db->prefix . 'wfacp_stats' . " as aero ON contact . id = aero . cid LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON aero . fid = p . id WHERE aero . cid = $contact_id";

			if ( ! empty( $search ) ) {
				$query .= " and ( contact . f_name LIKE '%$search%' or contact . email LIKE '%$search%')";
			}
			if ( ! empty( $orderby ) ) {
				$query .= " ORDER BY $orderby $order";
			}
			if ( ! empty( $orderby ) ) {
				$query .= " LIMIT $offset, $limit";
			}

			return $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		}

		/**
		 * @param $cid
		 *
		 * @return array|object|null
		 */
		public function get_all_contacts_records_crm( $cid ) {
			$query = "SELECT p . post_title as 'name', aero . fid as 'funnel', aero . total_revenue as 'amount', aero . order_id as 'order', DATE_FORMAT( aero . date, '%Y-%m-%dT%TZ' ) as 'date' FROM " . $this->wp_db->prefix . 'wfacp_stats' . " as aero LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON aero . wfacp_id = p . id WHERE aero . cid = $cid order by aero . fid asc";

			return $this->wp_db->get_results( $query ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		}


		/**
		 * @param array $cids
		 *
		 * @return bool|false|int
		 */
		public function delete_contact_crm( $cids = array() ) {

			$cid_count                = count( $cids );
			$stringPlaceholders       = array_fill( 0, $cid_count, '%s' );
			$placeholdersForFavFruits = implode( ',', $stringPlaceholders );

			$query = "DELETE FROM " . $this->wp_db->prefix . "wfacp_stats WHERE cid IN( " . $placeholdersForFavFruits . " )";

			return $this->wp_db->query( $this->wp_db->prepare( $query, $cids ) );

		}

		/**
		 * @param $funnel_id
		 */
		public function reset_analytics( $funnel_id ) {
			$query = "DELETE FROM " . $this->wp_db->prefix . "wfacp_stats WHERE fid = " . $funnel_id;
			$this->wp_db->query( $query );
		}
	}
}