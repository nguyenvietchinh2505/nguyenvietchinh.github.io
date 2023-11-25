<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Class WFOCU_Contacts_Analytics
 */
if ( ! class_exists( 'WFOCU_Contacts_Analytics' ) ) {

	class WFOCU_Contacts_Analytics {

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
		 * WFOCU_Contacts_Analytics constructor.
		 */
		public function __construct() {
			global $wpdb;
			$this->wp_db = $wpdb;
		}

		/**
		 * @return WFOCU_Contacts_Analytics|null
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
			$query = "SELECT session.cid, DATE_FORMAT(session.timestamp, '%Y-%m-%dT%TZ') as 'date', (CASE WHEN action_type_id = 4 THEN `value`  else '' END) AS `total_revenue`, session.id as session_id, event.action_type_id FROM " . $this->wp_db->prefix . 'wfocu_session' . " AS session LEFT JOIN " . $this->wp_db->prefix . 'wfocu_event' . " AS event ON session.id=event.sess_id WHERE session.fid=$funnel_id AND session.cid IN (" . $cid . ")";

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

			$query = "SELECT event.object_id,event.action_type_id,event.value,DATE_FORMAT(event.timestamp, '%Y-%m-%dT%TZ') as 'date',p.post_title as 'object_name','upsell' as 'type' FROM " . $this->wp_db->prefix . 'wfocu_event' . " as event LEFT JOIN " . $this->wp_db->prefix . 'wfocu_session' . " as session ON event.sess_id = session.id LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON event.object_id  = p.id WHERE (event.action_type_id = 4 OR event.action_type_id = 6 OR event.action_type_id = 7 OR event.action_type_id = 9) AND session.fid=$funnel_id AND session.cid=$cid order by session.timestamp asc";

			$data     = $this->wp_db->get_results( $query ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			return $data;

		}


		public function get_records_by_date_range( $funnel_id, $start_date, $end_date ) {

			$query = "SELECT DISTINCT(event.object_id),p.post_title as 'name' FROM " . $this->wp_db->prefix . 'wfocu_event' . " as event LEFT JOIN " . $this->wp_db->prefix . 'wfocu_session' . " as session ON event.sess_id = session.id LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON event.object_id  = p.id WHERE (event.action_type_id = 1) AND session.fid=$funnel_id AND event.timestamp BETWEEN '" . $start_date . "' AND '" . $end_date . "' order by event.object_id asc";

			return $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

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

			$query = "SELECT SUM(total) as sum_upsells" . $interval_query . "  FROM `" . $this->wp_db->prefix . "wfocu_session` WHERE 1=1 AND `timestamp` >= '" . $start_date . "' AND `timestamp` < '" . $end_date . "' AND fid = " . $funnel_id . " " . $group_by . " ORDER BY id ASC";

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

			$query = "SELECT SUM(ev.value) as sum_upsells FROM `" . $this->wp_db->prefix . "wfocu_event` as ev LEFT JOIN `" . $this->wp_db->prefix . "wfocu_session` as sess on sess.id = ev.sess_id WHERE ev.action_type_id = 4 AND sess.fid != 0 AND sess.total > 0 AND ev.timestamp >= '" . $start_date . "' AND ev.timestamp < '" . $end_date . "' ORDER BY sess.id DESC";
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
		public function get_top_upsells( $start_date, $end_date, $limit = '' ) {

			$limit = ( $limit !== '' ) ? " LIMIT " . $limit : '';

			$query = "SELECT object_id,sum(value) as revenue,COUNT(ev.id) as conversion,p.post_title as title FROM `" . $this->wp_db->prefix . "wfocu_event` as ev LEFT JOIN " . $this->wp_db->prefix . "posts as p ON p.id = ev.object_id WHERE timestamp >= '" . $start_date . "' AND timestamp < '" . $end_date . "' AND action_type_id=4 GROUP BY object_id ORDER BY sum(value) DESC " . $limit;

			$data     = $this->wp_db->get_results( $query ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			return $data;
		}

		/**
		 * @param $upsell_id
		 * @param $start_date
		 * @param $end_date
		 *
		 * @return array|object|null
		 */
		public function get_upsell_data_by_id( $upsell_id, $start_date, $end_date ) {
			$range = '';

			if ( $start_date !== '' && $end_date !== '' ) {
				$range = " timestamp >= '" . $start_date . "' AND timestamp < '" . $end_date . "' AND ";
			}

			$query = "SELECT ";
			$query .= "SUM(IF(action_type_id=2, 1, 0)) as offers_viewed, ";
			$query .= "SUM(IF(action_type_id=4, 1, 0)) as offers_accepted, ";
			$query .= "SUM(IF(action_type_id=6, 1, 0)) as offers_rejected, ";
			$query .= "SUM(IF(action_type_id=9, 1, 0)) as offers_failed, ";
			$query .= "SUM(IF(action_type_id=7, 1, 0)) as offers_expired, ";
			$query .= "SUM(IF(action_type_id=4, value, 0)) as upsells, ";
			$query .= "SUM(IF(action_type_id=7, 1, 0)) + SUM(IF(action_type_id=9, 1, 0)) as offers_pending, ";
			$query .= "CONCAT(CAST( ROUND(SUM(IF(action_type_id=4, 1, 0)) * 100.0/ SUM(IF(action_type_id=2, 1, 0)) + SUM(IF(action_type_id=7, 1, 0)) + SUM(IF(action_type_id=9, 1, 0)), 2) as decimal(5,2)), '%') as conversations ";

			$query .= "FROM " . $this->wp_db->prefix . "wfocu_event WHERE " . $range . " object_id = " . $upsell_id . "";

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

			return "SELECT stats.object_id as id, sess.cid as 'cid', sess.order_id as 'order_id', CONVERT( stats.value USING utf8) as 'total_revenue', 'upsell' as 'type', posts.post_title as 'post_title', stats.timestamp as date FROM " . $this->wp_db->prefix . "wfocu_event AS stats LEFT JOIN " . $this->wp_db->prefix . "wfocu_session AS sess ON stats.sess_id=sess.id LEFT JOIN " . $this->wp_db->prefix . "posts AS posts ON stats.object_id=posts.ID where ( stats.action_type_id = 4)
AND sess.cid != NULL ORDER BY " . $order_by . " " . $order . " " . $limit;

		}

		/**
		 * @param $offer_id
		 *
		 * Get offers contact ids
		 * @return array|false[]|object|null
		 */
		public function get_cid_by_offer_id( $offer_id, $start_date='' ) {
			$offer_id = is_array( $offer_id ) ? implode( ',', $offer_id ) : $offer_id;
			$start_date = $start_date !== '' ? " AND session.timestamp >= '".$start_date."' ": '';

			$query = "SELECT session.cid as 'cid' FROM " . $this->wp_db->prefix . "wfocu_session AS session INNER JOIN " . $this->wp_db->prefix . "wfocu_event AS event ON session.id=event.sess_id WHERE  event.action_type_id=4 AND event.object_id IN ( ". $offer_id .") ".$start_date." GROUP BY session.cid ORDER BY session.cid ASC";
			$data     = $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			return $data;
		}

		/**
		 * @param $upsell_ids
		 *
		 * Get all accepted offer ids
		 * @return array|false[]|object|null
		 */
		public function get_accepted_offer_ids_by_upsell( $upsell_ids, $start_date='' ) {
			$upsell_ids = is_array( $upsell_ids ) ? implode( ',', $upsell_ids ) : $upsell_ids;
			$start_date = $start_date !== '' ? " AND events.timestamp >= '".$start_date."' ": '';

			$query ="SELECT events.object_id as 'offer' FROM " . $this->wp_db->prefix . "wfocu_event as events INNER JOIN " . $this->wp_db->prefix . "wfocu_event_meta AS event_meta ON(events.ID=event_meta.event_id) AND ((event_meta.meta_key='_funnel_id' AND event_meta.meta_value IN(".$upsell_ids."))) AND ( events.action_type_id='4' ) ".$start_date." GROUP BY events.object_id ORDER BY events.object_id";
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
		 * @param $limit
		 *
		 * @return array|object|null
		 */
		public function get_top_funnels( $start_date, $end_date, $limit ) {

			$limit = ( $limit !== '' ) ? " LIMIT " . $limit : '';

			$query = "SELECT sess.fid as fid, SUM(ev.value) as total FROM " . $this->wp_db->prefix . "wfocu_event as ev LEFT JOIN " . $this->wp_db->prefix . "wfocu_session as sess on sess.id = ev.sess_id WHERE ev.action_type_id = 4 AND sess.fid != 0 AND sess.total > 0 AND ev.timestamp >= '" . $start_date . "' AND ev.timestamp < '" . $end_date . "' GROUP BY fid ORDER BY total DESC " . $limit;

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


			if ( ! class_exists( 'WFOCU_Core' ) ) {
				return;
			}

			$query    = "SELECT id  FROM " . $this->wp_db->prefix . "wfocu_session WHERE cid IN (" . $placeholdersForFavFruits . ") AND fid=" . $funnel_id;
			$sess_ids = $this->wp_db->get_results( $this->wp_db->prepare( $query, $cids ), ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

			$db_error = WFFN_Common::maybe_wpdb_error( $this->wp_db );
			if ( true === $db_error['db_error'] ) {
				return $db_error;
			}

			if ( is_array( $sess_ids ) && count( $sess_ids ) > 0 ) {
				foreach ( $sess_ids as $sess_id ) {
					WFOCU_Core()->session_db->delete( $sess_id['id'] );
				}
			}

			return true;
		}

		/**
		 * @param $contact_id
		 * @param $fid
		 *
		 * @return array|object|null
		 */
		public function get_contacts_data_crm( $contact_id, $fid ) {

			$fid = is_array( $fid ) ? implode( ',', $fid ) : $fid;

			$query = "SELECT p.post_title as title, session.fid as fid, (CASE WHEN action_type_id = 4 THEN `value` END) AS `total_revenue`, session.id as session_id, event.action_type_id FROM " . $this->wp_db->prefix . 'wfocu_session' . " AS session LEFT JOIN " . $this->wp_db->prefix . 'wfocu_event' . " AS event ON session.id=event.sess_id LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON session.fid = p.id WHERE session.cid=$contact_id AND session.fid IN (" . $fid . ")";

			return $this->wp_db->get_results( $query, ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

		}

		/**
		 * @param $cid
		 *
		 * @return array|object|null
		 */
		public function get_all_contacts_records_crm( $cid ) {

			$query = "SELECT event.object_id,event.action_type_id,event.value,DATE_FORMAT(event.timestamp, '%Y-%m-%dT%TZ') as 'date',p.post_title as 'object_name','upsell' as 'type' FROM " . $this->wp_db->prefix . 'wfocu_event' . " as event LEFT JOIN " . $this->wp_db->prefix . 'wfocu_session' . " as session ON event.sess_id = session.id LEFT JOIN " . $this->wp_db->prefix . 'posts' . " as p ON event.object_id  = p.id WHERE (event.action_type_id = 4 OR event.action_type_id = 6 OR event.action_type_id = 7 OR event.action_type_id = 9 OR event.action_type_id = 10) AND session.cid=$cid order by session.timestamp asc";

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

			$query = "DELETE FROM " . $this->wp_db->prefix . "wfocu_session WHERE cid IN (" . $placeholdersForFavFruits . ") AND";

			return $this->wp_db->query( $this->wp_db->prepare( $query, $cids ) );
		}

		/**
		 * @param $funnel_id
		 */
		public function reset_analytics( $funnel_id ) {

			if ( ! class_exists( 'WFOCU_Core' ) ) {
				return;
			}
			$query = "SELECT id  FROM " . $this->wp_db->prefix . "wfocu_session WHERE fid =" . $funnel_id;
			$sess_ids = $this->wp_db->get_results( $query, ARRAY_A );
			if ( is_array( $sess_ids ) && count( $sess_ids ) > 0 ) {
				foreach ( $sess_ids as $sess_id ) {
					WFOCU_Core()->session_db->delete( $sess_id['id'] );
				}
			}
			$query = "DELETE FROM " . $this->wp_db->prefix . "wfocu_session WHERE fid=" . $funnel_id;
			$this->wp_db->query( $query );

			$all_upsell_funnel_ids = [];
			$funnel = new WFFN_Funnel($funnel_id);
			$rest_API = WFFN_REST_API_EndPoint::get_instance();
			if ( $funnel instanceof WFFN_Funnel && 0 < $funnel->get_id() ) {
				$get_steps = $funnel->get_steps();
				$get_steps = $rest_API::get_instance()->maybe_add_ab_variants( $get_steps );
				foreach ( $get_steps as $step ) {
					if ( $step['type'] === 'wc_upsells' ) {
						array_push($all_upsell_funnel_ids, $step['id']);
					}
				}
			}


			if ( count( $all_upsell_funnel_ids ) > 0 ) {
					$get_all_upsell_events_left = $this->wp_db->get_results("DELETE eventss FROM " . $this->wp_db->prefix . "wfocu_event as eventss
INNER JOIN " . $this->wp_db->prefix . "wfocu_event_meta AS events_meta__funnel_id ON ( eventss.ID = events_meta__funnel_id.event_id ) 
 

			                        AND
( ( events_meta__funnel_id.meta_key   = '_funnel_id' AND events_meta__funnel_id.meta_value IN (".implode(',',$all_upsell_funnel_ids).") ))");

			}


		}
	}
}