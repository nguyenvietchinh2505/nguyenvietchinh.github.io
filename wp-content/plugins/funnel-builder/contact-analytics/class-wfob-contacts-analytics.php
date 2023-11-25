<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Class WFOB_Contacts_Analytics
 */
if ( ! class_exists( 'WFOB_Contacts_Analytics' ) ) {

	class WFOB_Contacts_Analytics {

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
		 * WFOB_Contacts_Analytics constructor.
		 */
		public function __construct() {
			global $wpdb;
			$this->wp_db = $wpdb;
		}

		/**
		 * @return WFOB_Contacts_Analytics|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self();
			}

			return self::$ins;
		}

		/**
		 * @param $funnel_id
		 * @param $cid
		 *
		 * @return array|object|null
		 */
		public function get_contacts_data( $funnel_id, $cid ) {
			$cid   = is_array( $cid ) ? implode( ',', $cid ) : $cid;
			$query = "SELECT bump.cid, DATE_FORMAT(bump.date, '%Y-%m-%dT%TZ') as 'date', bump.total as total_revenue, bump.total as bump_revenue, bump.bid, bump.converted as converted FROM " . $this->wp_db->prefix . 'wfob_stats' . " AS bump WHERE bump.fid=$funnel_id AND bump.cid IN (" . $cid . ")";

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

			$query = "SELECT bump.bid as 'object_id',bump.total as 'total_revenue',p.post_title as 'object_name', bump.converted as 'is_converted',DATE_FORMAT(bump.date, '%Y-%m-%dT%TZ') as 'date','bump' as 'type' FROM " . $this->wp_db->prefix . 'wfob_stats' . " AS bump LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON bump.bid  = p.id WHERE bump.fid=$funnel_id AND bump.cid=$cid order by bump.date asc";

			$data     = $this->wp_db->get_results( $query ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			return $data;

		}


		public function get_records_by_date_range( $funnel_id, $start_date, $end_date ) {

			$query = "SELECT bump.bid as 'object_id',COUNT(CASE WHEN converted = 1 THEN 1 END) AS `converted`, p.post_title as 'object_name',SUM(bump.total) as 'total_revenue',COUNT(bump.ID) as viewed, 'bump' as 'type' FROM " . $this->wp_db->prefix . 'wfob_stats' . " AS bump LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON bump.bid  = p.id WHERE bump.fid =$funnel_id AND date BETWEEN '" . $start_date . "' AND '" . $end_date . "' GROUP by bump.bid ASC";

			return $this->wp_db->get_results( $query ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		}


		/**
		 * @param $funnel_id
		 * @param $start_date
		 * @param $end_date
		 *
		 * @return array|object|null
		 */
		public function get_records_data( $funnel_id, $start_date, $end_date ) {

			$query = "SELECT COUNT(CASE WHEN action_type_id = 4 THEN 1 END) AS `converted`, COUNT(CASE WHEN action_type_id = 2 THEN 1 END) AS `viewed`, object_id  as 'offer', action_type_id,SUM(value) as revenue FROM " . $this->wp_db->prefix . 'wfocu_event' . "  as events INNER JOIN " . $this->wp_db->prefix . 'wfocu_event_meta' . " AS events_meta__funnel_id ON ( events.ID = events_meta__funnel_id.event_id ) AND ( ( events_meta__funnel_id.meta_key   = '_funnel_id' AND events_meta__funnel_id.meta_value = $funnel_id )) AND (events.action_type_id = '2' OR events.action_type_id = '4' ) AND events.timestamp BETWEEN '" . $start_date . "' AND '" . $end_date . "'  GROUP BY events.object_id";


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

			$query = "SELECT  SUM(total) as sum_bump " . $interval_query . "  FROM `" . $this->wp_db->prefix . "wfob_stats` WHERE 1=1 AND `date` >= '" . $start_date . "' AND `date` < '" . $end_date . "' AND fid = " . $funnel_id . " " . $group_by . " ORDER BY id ASC";

			return $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		}

		/**
		 * @param $start_date
		 * @param $end_date
		 *
		 * @return array|object|null
		 */
		public function get_total_revenue( $funnel_id, $start_date, $end_date ) {

			$funnel_id = ( $funnel_id !== '' ) ? " AND fid = " . $funnel_id . " " : '';

			$query = "SELECT  SUM(total) as sum_bump FROM `" . $this->wp_db->prefix . "wfob_stats` WHERE 1=1 AND `date` >= '" . $start_date . "' AND `date` < '" . $end_date . "' " . $funnel_id . " ORDER BY id DESC";

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
		public function get_top_bumps( $start_date, $end_date, $limit = '' ) {

			$limit = ( $limit !== '' ) ? " LIMIT " . $limit : '';

			$query = "SELECT bid, sum(total) as revenue,COUNT(bmp.id) as conversion,p.post_title as title FROM `" . $this->wp_db->prefix . "wfob_stats` as bmp LEFT JOIN " . $this->wp_db->prefix . "posts as p ON p.id = bmp.bid WHERE date >= '" . $start_date . "' AND date < '" . $end_date . "' AND bmp.converted = 1 GROUP BY bid ORDER BY sum(total) DESC" . $limit;

			$data     = $this->wp_db->get_results( $query ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			return $data;

		}

		/**
		 * @param $bump_id
		 * @param $start_date
		 * @param $end_date
		 *
		 * @return array|object|null
		 */
		public function get_bump_data_by_id( $bump_id, $start_date, $end_date ) {
			$range = '';

			if ( $start_date !== '' && $end_date !== '' ) {
				$range = " date >= '" . $start_date . "' AND date < '" . $end_date . "' AND ";
			}

			$query = "SELECT ";
			$query .= "COUNT(bid) as viewed, ";
			$query .= "SUM(total) as total_revenue, ";
			$query .= "SUM(IF(converted=1, 1, 0)) as converted, ";
			$query .= "CONCAT(CAST( ROUND(SUM(total) / COUNT(bid), 2) as decimal(5,2)), '%') as avg_revenue, ";
			$query .= "CONCAT(CAST( ROUND(SUM(IF(converted=1, 1, 0)) * 100.0 / COUNT(bid), 2) as decimal(5,2)), '%') as conversion ";

			$query .= "FROM " . $this->wp_db->prefix . "wfob_stats WHERE " . $range . " bid = " . $bump_id . "";

			return $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		}

		/**
		 * @param $limit
		 * @param string $order
		 * @param string $order_by
		 *
		 * @return string
		 */
		public function get_timeline_data_query( $limit, $order = 'DESC', $order_by = 'date' ) {

			$limit = ( $limit !== '' ) ? " LIMIT " . $limit : '';

			return "SELECT stats.bid as id, stats.cid as 'cid', oid as 'order_id', CONVERT( stats.total USING utf8) as 'total_revenue', 'bump' as 'type', posts.post_title as 'post_title', stats.date as date FROM " . $this->wp_db->prefix . "wfob_stats AS stats LEFT JOIN " . $this->wp_db->prefix . "posts AS posts ON stats.bid=posts.ID WHERE stats.converted = 1 ORDER BY " . $order_by . " " . $order . " " . $limit;

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

			$query = "SELECT fid, SUM(total) as total FROM " . $this->wp_db->prefix . "wfob_stats WHERE converted = 1 AND fid != 0 AND date >= '" . $start_date . "' AND date < '" . $end_date . "' GROUP BY fid ORDER BY total DESC " . $limit;

			$data     = $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			return $data;
		}

		/**
		 * @param $funnel_id
		 * @param $cids
		 *
		 * @return bool|false|int
		 */
		public function delete_contact( $funnel_id, $cids ) {

			$cid_count                = count( $cids );
			$stringPlaceholders       = array_fill( 0, $cid_count, '%s' );
			$placeholdersForFavFruits = implode( ',', $stringPlaceholders );

			$query = "DELETE FROM " . $this->wp_db->prefix . "wfob_stats WHERE cid IN (" . $placeholdersForFavFruits . ") AND fid=" . $funnel_id;

			$data     = $this->wp_db->query( $this->wp_db->prepare( $query, $cids ) ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			return $data;

		}


		/**
		 * @param $contact_id
		 * @param $fid
		 *
		 * @return array|object|null
		 */
		public function get_contacts_data_crm( $contact_id, $fid ) {

			$fid = is_array( $fid ) ? implode( ',', $fid ) : $fid;

			$query = "SELECT p.post_title as title, bump.fid as fid, bump.bid, bump.converted as converted FROM " . $this->wp_db->prefix . 'wfob_stats' . " AS bump LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON bump.fid  = p.id WHERE bump.cid=$contact_id AND bump.fid IN (" . $fid . ")";

			return $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		}

		/**
		 * @param $cid
		 *
		 * @return array|object|null
		 */
		public function get_all_contacts_records_crm( $cid ) {

			$query = "SELECT p.post_title as 'name', bump.fid as 'funnel', bump.total as 'amount', bump.oid as 'order', DATE_FORMAT(bump.date, '%Y-%m-%dT%TZ') as 'date' FROM " . $this->wp_db->prefix . 'wfob_stats' . " AS bump LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON bump.bid = p.id WHERE bump.cid=$cid order by bump.fid asc";

			return $this->wp_db->get_results( $query ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		}

		/**
		 * @param $cids
		 *
		 * @return bool|false|int
		 */
		public function delete_contact_crm( $cids ) {

			$cid_count                = count( $cids );
			$stringPlaceholders       = array_fill( 0, $cid_count, '%s' );
			$placeholdersForFavFruits = implode( ',', $stringPlaceholders );

			$query = "DELETE FROM " . $this->wp_db->prefix . "wfob_stats WHERE cid IN (" . $placeholdersForFavFruits . ")";

			return $this->wp_db->query( $this->wp_db->prepare( $query, $cids ) );
		}

		/**
		 * @param $funnel_id
		 */
		public function reset_analytics( $funnel_id ) {
			$query = "DELETE FROM " . $this->wp_db->prefix . "wfob_stats WHERE fid=" . $funnel_id;
			$this->wp_db->query( $query );
		}
	}
}