<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Funnel entity class
 * Class WFFN_Funnel
 */
if ( ! class_exists( 'WFFN_Funnel' ) ) {
	class WFFN_Funnel {
		private static $ins = null;

		/**
		 * @var $id
		 */
		public $id = 0;
		public $title = '';
		public $desc = '';
		public $date_added = null;
		public $steps = [];
		public $slug = '';

		/**
		 * WFFN_Funnel constructor..
		 * @since  1.0.0
		 */
		public function __construct( $id = 0 ) {
			$this->id   = $id;
			$this->slug = WFFN_Common::get_funnel_slug();

			if ( $this->id > 0 ) {
				$data = WFFN_Core()->get_dB()->get( $this->id );

				if ( ! empty( $data ) && is_array( $data ) ) {
					foreach ( $data as $col => $value ) {
						if ( 'steps' === $col ) {

							$stepsdb = json_decode( $value, true );
							if ( ! isset( $stepsdb[0]['id'] ) ) {
								$this->steps = [];
								continue;
							}
							$this->steps = $stepsdb;
						} else {
							$this->$col = $value;
						}
					}
				} else {
					$this->id = 0;
				}
			}
		}

		/**
		 * @return WFFN_Funnel|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}

		public function get_funnel_data() {
			return array(
				'type'        => $this->slug,
				'title'       => $this->get_title(),
				'is_funnel'   => true,
				'popup_title' => __( 'Add Funnel', 'funnel-builder' ),
			);
		}

		public function get_title() {
			return $this->title;
		}

		public function set_title( $title ) {
			$this->title = $title;
		}

		public function set_global() {
			$this->is_global = 1;
		}

		public function get_delete_data() {
			return array(
				'type'      => $this->slug,
				'is_funnel' => true,
				'title'     => __( 'Are you sure you want to delete this funnel?', 'funnel-builder' ),
				'subtitle'  => __( 'All the steps in this funnel will also be deleted.', 'funnel-builder' ),
			);
		}

		public function get_duplicate_data() {
			return array(
				'type'     => $this->slug,
				'title'    => __( 'Are you sure you want to duplicate this funnel?', 'woofunnel-flex-funnels' ),
				'subtitle' => __( 'All the steps in this funnel will also be duplicated.', 'woofunnel-flex-funnels' ),
			);
		}

		public function get_popup_data() {
			return array(
				'title'   => __( 'Great! Funnel has been successfully added', 'funnel-builder' ),
				'deleted' => __( 'Funnel has been successfully deleted.', 'funnel-builder' ),
			);
		}

		public function get_loader_popup() {
			return array(
				'title'    => __( 'Adding the funnel', 'funnel-builder' ),
				'deleting' => __( 'Deleting the funnel and all its steps', 'funnel-builder' ),
			);
		}

		/**
		 * @param $funnel_data
		 *
		 * @return mixed
		 */
		public function add_funnel( $funnel_data ) {
			if ( isset( $funnel_data['title'] ) ) {
				$this->set_title( $funnel_data['title'] );
			}
			if ( isset( $funnel_data['desc'] ) ) {
				$this->set_desc( $funnel_data['desc'] );
			}

			$this->set_date_added( current_time( 'mysql' ) );

			return $this->save( array() );
		}

		/**
		 * Create/update Funnel using given or set funnel data
		 *
		 * @param $data
		 *
		 * @return mixed
		 */
		public function save( $data = array() ) {
			if ( count( $data ) > 0 ) {
				foreach ( $data as $col => $value ) {
					$this->$col = $value;
				}
			}
			$funnel_data               = array();
			$funnel_data['title']      = $this->get_title();
			$funnel_data['desc']       = $this->get_desc();
			$funnel_data['date_added'] = $this->get_date_added();
			$funnel_data['steps']      = wp_json_encode( $this->get_steps() );

			$funnel_id = $this->get_id();

			if ( $funnel_id > 0 ) {
				$updated = WFFN_Core()->get_dB()->update( $funnel_data, array( 'id' => $funnel_id ) );
				WFFN_Core()->get_dB()->clear_cache();
				WFFN_Core()->logger->log( 'Funnel ID #' . $funnel_id . ': ' . print_r( wp_debug_backtrace_summary(), true ), 'wffn', true );//phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_wp_debug_backtrace_summary,WordPress.PHP.DevelopmentFunctions.error_log_print_r

				return false === $updated ? $updated : $funnel_id;
			}

			WFFN_Core()->get_dB()->insert( $funnel_data );
			$funnel_id = WFFN_Core()->get_dB()->insert_id();
			WFFN_Core()->get_dB()->update_meta( $funnel_id, '_version', WFFN_VERSION );

			WFFN_Core()->get_dB()->clear_cache();

			return $funnel_id;
		}

		public function get_desc() {
			return $this->desc;
		}

		public function set_desc( $desc ) {
			return $this->desc = $desc;
		}

		public function get_date_added() {
			return $this->date_added;
		}

		public function set_date_added( $date_added ) {
			return $this->date_added = $date_added;
		}

		public function get_steps( $populated = false ) {
			return ( true === $populated ) ? $this->prepare_steps( $this->steps ) : $this->steps;
		}

		public function get_step_count() {
			return count( $this->steps );
		}

		public function set_steps( $steps ) {
			$this->steps = $steps;
		}

		/**
		 * @param $steps
		 *
		 * @return array
		 */
		public function prepare_steps( $steps ) {
			$get_all_registered_steps = WFFN_Core()->steps->get_supported_steps();
			$result_steps             = array();
			foreach ( $steps as $key => &$step ) {
				/**
				 * IF we do not found the current step in our registered steps, then remove this step
				 */
				if ( ! in_array( $step['type'], array_keys( $get_all_registered_steps ), true ) ) {
					unset( $steps[ $key ] );
					continue;
				}
				/**
				 * if admin side actions then we need to initiate all data
				 */
				$get_object     = WFFN_Core()->steps->get_integration_object( $step['type'] );
				$result_steps[] = $get_object->populate_data_properties( $step, $this->get_id() );
			}

			return $result_steps;
		}

		public function get_id() {
			return $this->id;
		}

		public function set_id( $id ) {
			$this->id = empty( $id ) ? $this->id : $id;
		}


		/**
		 * @return bool
		 */
		public function delete() {
			$funnel_id = $this->get_id();
			$deleted   = false;
			if ( $funnel_id > 0 ) {
				$funnel_steps = $this->get_steps();
				foreach ( $funnel_steps as $funnel_step ) {
					$type    = isset( $funnel_step['type'] ) ? $funnel_step['type'] : '';
					$step_id = isset( $funnel_step['id'] ) ? $funnel_step['id'] : 0;
					if ( ! empty( $type ) ) {
						$get_step = WFFN_Core()->steps->get_integration_object( $type );
						if ( 0 < $step_id && $get_step instanceof WFFN_Step ) {
							$get_step->delete_step( $funnel_id, $step_id );
						} elseif ( 0 <= $step_id && ! get_post( $step_id ) instanceof WP_Post ) {
							$this->delete_step( $funnel_id, $step_id );
						}
					}
				}
				if ( 0 === count( $this->get_steps() ) ) {
					$deleted = true;
					WFFN_Core()->get_dB()->delete( $funnel_id );
					WFFN_Core()->get_dB()->clear_cache();
				} else {
					WFFN_Core()->logger->log( 'Something wrong funnel not deleted #' . $funnel_id, 'wffn', true ); // phpcs:ignore

				}
			}

			return $deleted;
		}

		/**
		 * @param $type
		 * @param $step_id
		 * @param $original_id
		 *
		 * @return false|int|mixed
		 */
		public function add_step( $type, $step_id, $original_id ) {
			$steps          = $this->get_steps();
			$store_checkout = ( 'wc_checkout' === $type ) && ( absint( $this->get_id() ) === WFFN_Common::get_store_checkout_id() ) ? true : false;
			if ( $original_id > 0 ) {
				$position = array_search( absint( $original_id ), array_map( 'intval', wp_list_pluck( $steps, 'id' ) ), true );
				if ( false !== $position ) {
					array_splice( $steps, ( $position + 1 ), 0, array( array( 'type' => $type, 'id' => $step_id ) ) );
				} else {
					if ( true === $store_checkout ) {
						array_unshift( $steps, array(
							'type' => $type,
							'id'   => $step_id,
						) );
					} else {
						array_push( $steps, array(
							'type' => $type,
							'id'   => $step_id,
						) );
					}
				}
			} else {
				if ( true === $store_checkout ) {
					array_unshift( $steps, array(
						'type' => $type,
						'id'   => $step_id,
					) );
				} else {
					array_push( $steps, array(
						'type' => $type,
						'id'   => $step_id,
					) );
				}
			}

			$this->set_steps( $steps );

			return $this->save( array() );
		}

		/**
		 * @param $step_id
		 * @param $substep_id
		 * @param $type
		 *
		 * @return mixed
		 */
		public function add_substep( $step_id, $substep_id, $type ) {
			$steps = $this->get_steps();

			$search    = array_search( absint( $step_id ), array_map( 'absint', wp_list_pluck( $steps, 'id' ) ), true );
			$sub_steps = ( isset( $steps[ $search ] ) && isset( $steps[ $search ]['substeps'] ) ) ? $steps[ $search ]['substeps'] : $steps[ $search ]['substeps'] = array();


			$sub_step = isset( $sub_steps[ $type ] ) ? $sub_steps[ $type ] : array();

			array_push( $sub_step, $substep_id );

			$steps[ $search ]['substeps'][ $type ] = $sub_step;

			$this->set_steps( $steps );

			return array( 'funnel_id' => $this->save( array() ), 'type' => $type );

		}

		/**
		 * @param $funnel_id
		 * @param $step_id
		 * @param $type
		 *
		 * @return mixed
		 */
		public function delete_step( $funnel_id, $step_id ) {
			$steps  = $this->get_steps();
			$search = array_search( intval( $step_id ), array_map( 'intval', wp_list_pluck( $steps, 'id' ) ), true );
			if ( $search === false ) {
				return;
			}
			unset( $steps[ $search ] );
			$steps = array_values( $steps );
			$this->set_steps( $steps );

			return $this->save( array() );

		}

		/**
		 * @param $funnel_id
		 * @param $step_id
		 * @param $substep_id
		 * @param $substep
		 *
		 * @return mixed
		 */
		public function delete_substep( $funnel_id, $step_id, $substep_id, $substep ) {
			$steps       = $this->get_steps();
			$search      = array_search( intval( $step_id ), array_map( 'intval', wp_list_pluck( $steps, 'id' ) ), true );
			$substep_ids = array_map( 'intval', $steps[ $search ]['substeps'][ $substep ] );

			$substep_search = array_search( intval( $substep_id ), $substep_ids, true );
			unset( $substep_ids[ $substep_search ] );
			$substep_ids = array_values( $substep_ids );
			if ( ! empty( $substep_ids ) ) {
				$steps[ $search ]['substeps'][ $substep ] = $substep_ids;
			} else {
				unset( $steps[ $search ]['substeps'][ $substep ] );
			}

			$this->set_steps( $steps );

			$deleted = $this->save( array() );

			return array( 'success' => true, 'deleted' => $deleted, 'type' => $steps[ $search ]['type'] );

		}

		/**
		 * @param $steps
		 *
		 * @return mixed
		 */
		public function reposition_steps( $steps ) {
			if ( ! is_array( $steps ) || count( $steps ) === 0 ) {
				return;
			}
			$result_steps = array();

			foreach ( $steps as $step ) {
				$data         = array(
					'type' => $step['type'],
					'id'   => $step['id'],
				);
				$get_substeps = WFFN_Core()->substeps->get_substeps( $this->get_id(), $step['id'] );
				if ( is_array( $get_substeps ) && count( $get_substeps ) > 0 ) {
					$data['substeps'] = $get_substeps;
				} else {
					$data['substeps'] = [];
				}
				$result_steps[] = $data;

			}

			$this->set_steps( $result_steps );

			return $this->save( array() );

		}

		/**
		 * @param $step_id
		 * @param $type
		 * @param $substeps
		 *
		 * @return mixed
		 */
		public function reposition_substeps( $step_id, $type, $substeps ) {
			$index                                      = $this->get_step_index( $step_id );
			$this->steps[ $index ]['substeps'][ $type ] = array_map( 'absint', $substeps );

			return $this->save( array() );
		}

		/**
		 * @param $step_id
		 *
		 * @return false|int|string
		 */
		public function get_step_index( $step_id ) {
			$get_steps = wp_list_pluck( $this->steps, 'id' );

			return array_search( absint( $step_id ), array_map( 'absint', $get_steps ), true );

		}

		/**
		 * @param $data
		 *
		 * @return mixed
		 */
		public function update( $data ) {
			$funnel_id = isset( $data['funnel_id'] ) ? $data['funnel_id'] : $this->get_id();
			if ( $funnel_id > 0 && isset( $data['title'] ) && ! empty( $data['title'] ) ) {
				$this->set_title( $data['title'] );
				if ( isset( $data['desc'] ) ) {
					$this->set_desc( $data['desc'] );
				}

				return $this->save( array() );
			}
		}

		/**
		 * @return false|int
		 */
		public function get_start_date( $filter_data ) {
			$range = isset( $filter_data['range'] ) ? $filter_data['range'] : '';

			$date_added = '';
			if ( empty( $range ) || 'all' === $range ) {
				$date_added = strtotime( $this->get_date_added() );
			} else {
				switch ( $range ) {
					case '7' :
						$date_added = strtotime( '-6 days', strtotime( 'midnight', current_time( 'timestamp' ) ) );
						break;
					case '15' :
						$date_added = strtotime( '-14 days', strtotime( 'midnight', current_time( 'timestamp' ) ) );
						break;
					case '30' :
						$date_added = strtotime( '-29 days', strtotime( 'midnight', current_time( 'timestamp' ) ) );
						break;
					case 'custom':
						$date_added = strtotime( $filter_data['start_date'] );
						break;
				}
			}

			return empty( $date_added ) ? strtotime( current_time( 'mysql' ) ) : $date_added;
		}

		/**
		 * @param $filter_data
		 *
		 * @return false|int
		 */
		public function get_end_date( $filter_data ) {
			$range    = isset( $filter_data['range'] ) ? $filter_data['range'] : '';
			$end_date = isset( $filter_data['end_date'] ) ? $filter_data['end_date'] : '';
			if ( 'custom' === $range && ! empty( $end_date ) ) {
				return strtotime( '+1 days', strtotime( 'midnight', strtotime( $end_date ) ) );
			}

			return strtotime( current_time( 'mysql' ) );
		}

		public function get_view_link() {
			$steps = $this->get_steps();
			if ( is_array( $steps ) && count( $steps ) > 0 ) {
				foreach ( $steps as $step ) {
					$getstep_type = WFFN_Core()->steps->get_integration_object( $step['type'] );
					if ( $getstep_type instanceof WFFN_Step ) {
						$data_ = $getstep_type->populate_data_properties( $step, $this->get_id() );

						return $data_['_data']['view'];
					}
				}
			}

			return false;

		}

		/**
		 * @return array
		 */
		public function default_settings() {
			return array(
				'override_tracking_ids' => false,
				'fb_pixel_key'          => '',
				'ga_key'                => '',
				'gad_key'               => '',
				'gad_conversion_label'  => '',
				'pint_key'              => '',
				'tiktok_pixel'          => '',
				'snapchat_pixel'        => '',
			);
		}

		public function get_settings() {
			$db_options = WFFN_Core()->get_dB()->get_meta( $this->get_id(), '_settings' );
			$db_options = isset( $db_options ) ? $db_options : array();
			$db_options = ( ! empty( $db_options ) && is_array( $db_options ) ) ? $db_options : array();

			return wp_parse_args( $db_options, $this->default_settings() );
		}

		public function is_funnel_has_native_checkout() {
			$steps = $this->get_steps();
			if ( empty( $steps ) || ! is_array( $steps ) ) {
				return true;
			}

			/**
			 * iterate over the funnel step and if we found a checkout step with published status then return false
			 */
			foreach ( $steps as $step ) {
				if ( isset( $step['type'] ) && $step['type'] === 'wc_checkout' && 'publish' === get_post_status( $step['id'] ) ) {
					return false;
				}
			}

			return true;
		}

		public function funnel_has_checkout() {
			$steps        = $this->get_steps();
			$has_checkout = false;
			if ( empty( $steps ) ) {
				return true;
			}

			foreach ( $steps as $step ) {
				if ( isset( $step['type'] ) && $step['type'] === 'wc_checkout' ) {
					$has_checkout = true;
					break;
				}
			}

			return $has_checkout;
		}
	}
}
