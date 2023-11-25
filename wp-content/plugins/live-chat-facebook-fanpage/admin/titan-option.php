<?php

/*
 * Titan Framework options sample code. We've placed here some
 * working examples to get your feet wet
 * @see	http://www.titanframework.net/get-started/
 */


add_action( 'tf_create_options', 'titb_create_options' );

/**
 * Initialize Titan & options here
 */
function titb_create_options() {

	$titan = TitanFramework::getInstance( 'titb_flc' );

	/**
	 * Create an admin panel & tabs
	 * You should put options here that do not change the look of your theme
	 */

	$adminPanel = $titan->createAdminPanel( array(
	    'name' => __( 'FB Live Chat', 'titb_flc' ),
	) );


	/* ============
	  HEADER
	==============*/

	$generalTab = $adminPanel->createTab( array(
	    'name' => __( 'General', 'titb_flc' ),
	) );

	$generalTab->createOption( array(
	    'name' => 'Logo',
	    'type' => 'heading',
	) );

	$generalTab->createOption( array(
	    'name' => 'Insert Your URL Fanpage',
	    'id' => 'titb_flc_url',
	    'type' => 'text',
	    'desc' => 'Please insert here your fanpage url'
	) );

	$generalTab->createOption( array(
	    'name' => 'Define Your Button Label',
	    'id' => 'titb_flc_btnlabel',
	    'type' => 'text',
	    'default' => 'Chat with Us!',
	    'desc' => 'Please insert here button label'
	) );

	$generalTab->createOption( array(
		'name' => 'Select Title Size',
		'id' => 'titb_flc_titlesize',
		'type' => 'radio-image',
		'options' => array(
		'true' => plugins_url( '../assets/img/title_small.png', __FILE__ ),
		'false' => plugins_url( '../assets/img/title_big.png', __FILE__ ),
	),
	'default' => 'true',
	) );
	
	$generalTab->createOption( array(
		'name' => 'Select Language',
		'id' => 'titb_flc_language',
		'type' => 'select',
		'desc' => 'Please select your localization',
		'options' => array(
			'af_ZA' => 'Afrikaans',
			'ak_GH' => 'Akan',
			'am_ET' => 'Amharic',
			'ar_AR' => 'Arabic',
			'as_IN' => 'Assamese',
			'ay_BO' => 'Aymara',
			'az_AZ' => 'Azerbaijani',
			'be_BY' => 'Belarusian',
			'bg_BG' => 'Bulgarian',
			'bn_IN' => 'Bengali',
			'br_FR' => 'Breton',
			'bs_BA' => 'Bosnian',
			'ca_ES' => 'Catalan',
			'cb_IQ' => 'Sorani Kurdish',
			'ck_US' => 'Cherokee',
			'co_FR' => 'Corsican',
			'cs_CZ' => 'Czech',
			'cx_PH' => 'Cebuano',
			'cy_GB' => 'Welsh',
			'da_DK' => 'Danish',
			'de_DE' => 'German',
			'el_GR' => 'Greek',
			'en_GB' => 'English (UK)',
			'en_IN' => 'English (India)',
			'en_PI' => 'English (Pirate)',
			'en_UD' => 'English (Upside Down)',
			'en_US' => 'English (US)',
			'eo_EO' => 'Esperanto',
			'es_CL' => 'Spanish (Chile)',
			'es_CO' => 'Spanish (Colombia)',
			'es_ES' => 'Spanish (Spain)',
			'es_LA' => 'Spanish',
			'es_MX' => 'Spanish (Mexico)',
			'es_VE' => 'Spanish (Venezuela)',
			'et_EE' => 'Estonian',
			'eu_ES' => 'Basque',
			'fa_IR' => 'Persian',
			'fb_LT' => 'Leet Speak',
			'ff_NG' => 'Fulah',
			'fi_FI' => 'Finnish',
			'fo_FO' => 'Faroese',
			'fr_CA' => 'French (Canada)',
			'fr_FR' => 'French (France)',
			'fy_NL' => 'Frisian',
			'ga_IE' => 'Irish',
			'gl_ES' => 'Galician',
			'gn_PY' => 'Guarani',
			'gu_IN' => 'Gujarati',
			'gx_GR' => 'Classical Greek',
			'ha_NG' => 'Hausa',
			'he_IL' => 'Hebrew',
			'hi_IN' => 'Hindi',
			'hr_HR' => 'Croatian',
			'ht_HT' => 'Haitian Creole',
			'hu_HU' => 'Hungarian',
			'hy_AM' => 'Armenian',
			'id_ID' => 'Indonesian',
			'ig_NG' => 'Igbo',
			'is_IS' => 'Icelandic',
			'it_IT' => 'Italian',
			'ja_JP' => 'Japanese',
			'ja_KS' => 'Japanese (Kansai)',
			'jv_ID' => 'Javanese',
			'ka_GE' => 'Georgian',
			'kk_KZ' => 'Kazakh',
			'km_KH' => 'Khmer',
			'kn_IN' => 'Kannada',
			'ko_KR' => 'Korean',
			'ku_TR' => 'Kurdish (Kurmanji)',
			'ky_KG' => 'Kyrgyz',
			'la_VA' => 'Latin',
			'lg_UG' => 'Ganda',
			'li_NL' => 'Limburgish',
			'ln_CD' => 'Lingala',
			'lo_LA' => 'Lao',
			'lt_LT' => 'Lithuanian',
			'lv_LV' => 'Latvian',
			'mg_MG' => 'Malagasy',
			'mi_NZ' => 'Māori',
			'mk_MK' => 'Macedonian',
			'ml_IN' => 'Malayalam',
			'mn_MN' => 'Mongolian',
			'mr_IN' => 'Marathi',
			'ms_MY' => 'Malay',
			'mt_MT' => 'Maltese',
			'my_MM' => 'Burmese',
			'nb_NO' => 'Norwegian (bokmal)',
			'nd_ZW' => 'Ndebele',
			'ne_NP' => 'Nepali',
			'nl_BE' => 'Dutch (België)',
			'nl_NL' => 'Dutch',
			'nn_NO' => 'Norwegian (nynorsk)',
			'ny_MW' => 'Chewa',
			'or_IN' => 'Oriya',
			'pa_IN' => 'Punjabi',
			'pl_PL' => 'Polish',
			'ps_AF' => 'Pashto',
			'pt_BR' => 'Portuguese (Brazil)',
			'pt_PT' => 'Portuguese (Portugal)',
			'qc_GT' => 'Quiché',
			'qu_PE' => 'Quechua',
			'rm_CH' => 'Romansh',
			'ro_RO' => 'Romanian',
			'ru_RU' => 'Russian',
			'rw_RW' => 'Kinyarwanda',
			'sa_IN' => 'Sanskrit',
			'sc_IT' => 'Sardinian',
			'se_NO' => 'Northern Sámi',
			'si_LK' => 'Sinhala',
			'sk_SK' => 'Slovak',
			'sl_SI' => 'Slovenian',
			'sn_ZW' => 'Shona',
			'so_SO' => 'Somali',
			'sq_AL' => 'Albanian',
			'sr_RS' => 'Serbian',
			'sv_SE' => 'Swedish',
			'sw_KE' => 'Swahili',
			'sy_SY' => 'Syriac',
			'sz_PL' => 'Silesian',
			'ta_IN' => 'Tamil',
			'te_IN' => 'Telugu',
			'tg_TJ' => 'Tajik',
			'th_TH' => 'Thai',
			'tk_TM' => 'Turkmen',
			'tl_PH' => 'Filipino',
			'tl_ST' => 'Klingon',
			'tr_TR' => 'Turkish',
			'tt_RU' => 'Tatar',
			'tz_MA' => 'Tamazight',
			'uk_UA' => 'Ukrainian',
			'ur_PK' => 'Urdu',
			'uz_UZ' => 'Uzbek',
			'vi_VN' => 'Vietnamese',
			'wo_SN' => 'Wolof',
			'xh_ZA' => 'Xhosa',
			'yi_DE' => 'Yiddish',
			'yo_NG' => 'Yoruba',
			'zh_CN' => 'Simplified Chinese (China)',
			'zh_HK' => 'Traditional Chinese (Hong Kong)',
			'zh_TW' => 'Traditional Chinese (Taiwan)',
			'zu_ZA' => 'Zulu',
			'zz_TR' => 'Zazaki',
		),
		'default' => 'en_GB',
	) );
	
	$generalTab->createOption( array(
		'name' => 'Button Position',
		'id' => 'button_position',
		'options' => array(
		'left' => 'Left',
		'right' => 'Right',
	),
		'type' => 'radio',
		'desc' => 'Select the button position',
		'default' => 'right',
	) );


	$generalTab->createOption( array(
	    'type' => 'save',
	) );


	/* ============
	  HEADER
	==============*/


	/* ============
	  DESIGN
	==============*/

	$designTab = $adminPanel->createTab( array(
	    'name' => __( 'Design', 'titb_flc' ),
	) );

	$designTab->createOption( array(
	    'name' => 'Color',
	    'type' => 'heading',
	) );

	$designTab->createOption( array(
		'name' => 'Button Background Color',
		'id' => 'button_background_color',
		'type' => 'color',
		'alpha' => 'true',
		'css' => '.round.hollow button {background-color: value;}',
		'desc' => 'Select Background Color of the Button',
		'default' => 'rgba(63, 189, 151, 0)',
	) );

	$designTab->createOption( array(
		'name' => 'Button Border Color',
		'id' => 'button_border_color',
		'type' => 'color',
		'alpha' => 'true',
		'css' => '.round.hollow button {border-color: value;}',
		'desc' => 'Select Border Color of the Button',
		'default' => '#3fbd98',
	) );

	$designTab->createOption( array(
		'name' => 'Button Text Color',
		'id' => 'button_text_color',
		'type' => 'color',
		'alpha' => 'true',
		'css' => '.round.hollow button {color: value;}',
		'desc' => 'Select Text Color of the Button',
		'default' => '#3fbd98',
	) );
	
	$designTab->createOption( array(
		'name' => 'Button Size',
		'id' => 'button_size',
		'options' => array(
		'flc_tiny' => 'Tiny',
		'flc_medium' => 'Medium',
		'flc_large' => 'Large',
	),
		'type' => 'radio',
		'desc' => 'Select the button size',
		'default' => 'flc_large',
	) );


	$designTab->createOption( array(
	    'type' => 'save',
	) );



	/* ============
	  DESIGN
	==============*/







	/* ============
	  PREMIUM
	==============*/

/*
	$premiumTab = $adminPanel->createTab( array(
	    'name' => __( 'Premium Options', 'titb_flc' ),
	) );


	$premiumTab->createOption( array(

		'type' => 'custom',
		'custom' => '

			<div id="pricing">

			</div>
		',

	));
*/
}
