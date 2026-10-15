<?php
/**
 * Contact Form 7 integration for the Contact block and the header's "Book a Demo" modal.
 *
 * The theme creates a contact form and a demo form for each language once, then leaves
 * them alone: they can be edited, replaced or deleted under Contact → Contact Forms.
 *
 * @package BGI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Form templates are laid out with their own markup, so CF7 must not add <p> and <br> tags.
add_filter( 'wpcf7_autop_or_not', '__return_false' );

add_filter( 'wpcf7_default_template', 'bgi_cf7_default_template', 10, 2 );
/**
 * Start new forms (Contact → Add New) as a copy of the English contact form.
 *
 * @param mixed  $template Default value of the property.
 * @param string $prop     Property name: "form", "mail", "messages", etc.
 * @return mixed
 */
function bgi_cf7_default_template( $template, $prop ) {
	$s = bgi_cf7_contact_strings()['en'];

	if ( 'form' === $prop ) {
		return bgi_cf7_contact_form_markup( $s );
	}

	if ( 'mail' === $prop && is_array( $template ) ) {
		return array_merge( $template, bgi_cf7_contact_mail( $s ) );
	}

	if ( 'messages' === $prop && is_array( $template ) ) {
		$template['mail_sent_ok'] = $s['success'];
	}

	return $template;
}

/**
 * Build form markup: labelled fields styled by .form-group, then a full-width button.
 *
 * @param array[] $fields       Each field is array( input id, label, CF7 form-tag ).
 * @param string  $submit_label Button text.
 * @return string Contact Form 7 form template.
 */
function bgi_cf7_build_form( $fields, $submit_label ) {
	$markup = '';
	foreach ( $fields as $field ) {
		$markup .= '<div class="form-group">' . "\n";
		$markup .= "\t" . '<label for="' . $field[0] . '">' . $field[1] . '</label>' . "\n";
		$markup .= "\t" . $field[2] . "\n";
		$markup .= '</div>' . "\n\n";
	}

	$markup .= '<div class="form-submit">' . "\n";
	$markup .= "\t" . '[submit class:btn class:btn-primary class:btn-block "' . $submit_label . '"]' . "\n";
	$markup .= '</div>';

	return $markup;
}

/**
 * Text of the contact form in each language the design ships with.
 *
 * @return array[] Strings keyed by language slug.
 */
function bgi_cf7_contact_strings() {
	return array(
		'en' => array(
			'locale'  => 'en_US',
			'title'   => 'Contact (EN)',
			'name'    => 'Full Name',
			'email'   => 'Email Address',
			'company' => 'Company',
			'message' => 'Message',
			'submit'  => 'Send Message',
			'success' => "Thanks! Your message has been received — we'll be in touch shortly.",
			'subject' => 'new enquiry from',
		),
		'ar' => array(
			'locale'  => 'ar',
			'title'   => 'Contact (AR)',
			'name'    => 'الاسم الكامل',
			'email'   => 'البريد الإلكتروني',
			'company' => 'الشركة',
			'message' => 'الرسالة',
			'submit'  => 'إرسال الرسالة',
			'success' => 'شكراً لك! تم استلام رسالتك — سنتواصل معك قريباً.',
			'subject' => 'استفسار جديد من',
		),
	);
}

/**
 * Contact form markup in one language.
 *
 * @param array $s Strings from bgi_cf7_contact_strings().
 * @return string Contact Form 7 form template.
 */
function bgi_cf7_contact_form_markup( $s ) {
	return bgi_cf7_build_form(
		array(
			array( 'cf-name', $s['name'], '[text* cf-name id:cf-name autocomplete:name]' ),
			array( 'cf-email', $s['email'], '[email* email id:cf-email autocomplete:email]' ),
			array( 'cf-company', $s['company'], '[text company id:cf-company autocomplete:organization]' ),
			array( 'cf-message', $s['message'], '[textarea* message id:cf-message x3]' ),
		),
		$s['submit']
	);
}

/**
 * Notification email settings for the contact form in one language.
 *
 * @param array $s Strings from bgi_cf7_contact_strings().
 * @return array Keys of the CF7 "mail" property.
 */
function bgi_cf7_contact_mail( $s ) {
	return array(
		'subject'            => '[_site_title]: ' . $s['subject'] . ' [cf-name]',
		'body'               => implode(
			"\n",
			array(
				$s['name'] . ': [cf-name]',
				$s['email'] . ': [email]',
				$s['company'] . ': [company]',
				'',
				$s['message'] . ':',
				'[message]',
				'',
				'-- ',
				'[_site_title] ([_site_url])',
			)
		),
		'additional_headers' => 'Reply-To: [email]',
	);
}

/**
 * Text of the "Book a Demo" form in each language the design ships with.
 *
 * @return array[] Strings keyed by language slug.
 */
function bgi_cf7_demo_strings() {
	return array(
		'en' => array(
			'locale'   => 'en_US',
			'title'    => 'Book a Demo (EN)',
			'name'     => 'Full Name',
			'email'    => 'Email Address',
			'property' => 'Property / Company Name',
			'time'     => 'Preferred Date & Time',
			'time_ph'  => 'e.g. Weekday afternoons',
			'message'  => 'Message',
			'submit'   => 'Request Demo',
			'success'  => "Thanks! Your demo request has been received — we'll be in touch to confirm a time.",
			'subject'  => 'demo request from',
		),
		'ar' => array(
			'locale'   => 'ar',
			'title'    => 'Book a Demo (AR)',
			'name'     => 'الاسم الكامل',
			'email'    => 'البريد الإلكتروني',
			'property' => 'اسم العقار / الشركة',
			'time'     => 'التاريخ والوقت المفضلان',
			'time_ph'  => 'مثال: بعد الظهر في أيام الأسبوع',
			'message'  => 'الرسالة',
			'submit'   => 'طلب عرض تجريبي',
			'success'  => 'شكراً لك! تم استلام طلب العرض التجريبي — سنتواصل معك لتأكيد الموعد.',
			'subject'  => 'طلب عرض تجريبي من',
		),
	);
}

/**
 * Demo form markup in one language.
 *
 * @param array $s Strings from bgi_cf7_demo_strings().
 * @return string Contact Form 7 form template.
 */
function bgi_cf7_demo_form_markup( $s ) {
	return bgi_cf7_build_form(
		array(
			array( 'dm-name', $s['name'], '[text* your-name id:dm-name autocomplete:name]' ),
			array( 'dm-email', $s['email'], '[email* your-email id:dm-email autocomplete:email]' ),
			array( 'dm-property', $s['property'], '[text* your-property id:dm-property autocomplete:organization]' ),
			array( 'dm-time', $s['time'], '[text your-time id:dm-time placeholder "' . $s['time_ph'] . '"]' ),
			array( 'dm-message', $s['message'], '[textarea your-message id:dm-message x3]' ),
		),
		$s['submit']
	);
}

/**
 * Notification email settings for the demo form in one language.
 *
 * @param array $s Strings from bgi_cf7_demo_strings().
 * @return array Keys of the CF7 "mail" property.
 */
function bgi_cf7_demo_mail( $s ) {
	return array(
		'subject' => '[_site_title]: ' . $s['subject'] . ' [your-name]',
		'body'    => implode(
			"\n",
			array(
				$s['name'] . ': [your-name]',
				$s['email'] . ': [your-email]',
				$s['property'] . ': [your-property]',
				$s['time'] . ': [your-time]',
				'',
				$s['message'] . ':',
				'[your-message]',
				'',
				'-- ',
				'[_site_title] ([_site_url])',
			)
		),
	);
}

/**
 * Arabic versions of CF7's response and validation messages.
 *
 * CF7 may not ship an Arabic translation, so the Arabic forms get these directly.
 *
 * @return string[] Messages keyed by CF7 message name.
 */
function bgi_cf7_ar_messages() {
	return array(
		'mail_sent_ng'             => 'حدث خطأ أثناء إرسال رسالتك. يرجى المحاولة مرة أخرى لاحقاً.',
		'validation_error'         => 'يوجد خطأ في حقل أو أكثر. يرجى التحقق والمحاولة مرة أخرى.',
		'spam'                     => 'حدث خطأ أثناء إرسال رسالتك. يرجى المحاولة مرة أخرى لاحقاً.',
		'accept_terms'             => 'يجب الموافقة على الشروط والأحكام قبل إرسال رسالتك.',
		'invalid_required'         => 'يرجى تعبئة هذا الحقل.',
		'invalid_too_long'         => 'النص المدخل في هذا الحقل طويل جداً.',
		'invalid_too_short'        => 'النص المدخل في هذا الحقل قصير جداً.',
		'upload_failed'            => 'حدث خطأ غير معروف أثناء رفع الملف.',
		'upload_file_type_invalid' => 'لا يُسمح برفع ملفات من هذا النوع.',
		'upload_file_too_large'    => 'الملف المرفوع كبير جداً.',
		'upload_failed_php_error'  => 'حدث خطأ أثناء رفع الملف.',
		'invalid_date'             => 'يرجى إدخال التاريخ بصيغة YYYY-MM-DD.',
		'date_too_early'           => 'التاريخ المدخل مبكر جداً.',
		'date_too_late'            => 'التاريخ المدخل متأخر جداً.',
		'invalid_number'           => 'يرجى إدخال رقم.',
		'number_too_small'         => 'الرقم المدخل صغير جداً.',
		'number_too_large'         => 'الرقم المدخل كبير جداً.',
		'quiz_answer_not_correct'  => 'إجابة السؤال غير صحيحة.',
		'invalid_email'            => 'يرجى إدخال بريد إلكتروني صحيح.',
		'invalid_url'              => 'يرجى إدخال رابط صحيح.',
		'invalid_tel'              => 'يرجى إدخال رقم هاتف صحيح.',
	);
}

/**
 * The form sets the theme creates, each remembered in its own option.
 *
 * @return array[] Each set: option name, strings per language, markup and mail builders.
 */
function bgi_cf7_form_sets() {
	return array(
		array(
			'option'  => 'bgi_cf7_contact_forms',
			'strings' => bgi_cf7_contact_strings(),
			'markup'  => 'bgi_cf7_contact_form_markup',
			'mail'    => 'bgi_cf7_contact_mail',
		),
		array(
			'option'  => 'bgi_cf7_demo_forms',
			'strings' => bgi_cf7_demo_strings(),
			'markup'  => 'bgi_cf7_demo_form_markup',
			'mail'    => 'bgi_cf7_demo_mail',
		),
	);
}

add_action( 'admin_init', 'bgi_cf7_create_forms' );
/**
 * Create the contact and "Book a Demo" forms once, so the site works without setup.
 *
 * Each set runs a single time: forms deleted afterwards are not recreated.
 *
 * @return void
 */
function bgi_cf7_create_forms() {
	if ( ! class_exists( 'WPCF7_ContactForm' ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	foreach ( bgi_cf7_form_sets() as $set ) {
		if ( false !== get_option( $set['option'] ) ) {
			continue;
		}

		$ids = array();
		foreach ( $set['strings'] as $lang => $s ) {
			$form = WPCF7_ContactForm::get_template(
				array(
					'title'  => $s['title'],
					'locale' => $s['locale'],
				)
			);

			$props = $form->get_properties();

			$props['form']                     = call_user_func( $set['markup'], $s );
			$props['mail']                     = array_merge( $props['mail'], call_user_func( $set['mail'], $s ) );
			$props['messages']['mail_sent_ok'] = $s['success'];

			if ( 'ar' === $lang ) {
				$props['messages'] = array_merge( $props['messages'], bgi_cf7_ar_messages() );
			}

			$form->set_properties( $props );
			$ids[ $lang ] = (int) $form->save();
		}

		update_option( $set['option'], $ids, false );
	}
}

/**
 * First form ID in the list that still exists.
 *
 * Each language is tried in turn (the given one, then English): the form chosen for
 * it, then the form the theme created for it.
 *
 * @param string   $option Option holding the created forms, e.g. "bgi_cf7_demo_forms".
 * @param callable $chosen Returns the form chosen for a language slug, or 0.
 * @param string   $lang   Language slug.
 * @return int Form ID, or 0 when Contact Form 7 is inactive or no form exists.
 */
function bgi_cf7_pick_form( $option, $chosen, $lang ) {
	if ( ! function_exists( 'wpcf7_contact_form' ) ) {
		return 0;
	}

	$created = (array) get_option( $option, array() );
	foreach ( array_unique( array( $lang, 'en' ) ) as $try ) {
		$candidates = array(
			(int) call_user_func( $chosen, $try ),
			isset( $created[ $try ] ) ? (int) $created[ $try ] : 0,
		);
		foreach ( $candidates as $id ) {
			if ( $id && wpcf7_contact_form( $id ) ) {
				return $id;
			}
		}
	}

	return 0;
}

/**
 * ID of the form shown in the header's "Book a Demo" modal for the current language.
 *
 * Order: the form chosen on this language's Texts page, the form created for this
 * language, then the English choices.
 *
 * @return int Form ID, or 0 when none exists.
 */
function bgi_demo_form_id() {
	return bgi_cf7_pick_form(
		'bgi_cf7_demo_forms',
		function ( $lang ) {
			return function_exists( 'get_field' ) ? get_field( 'demo_form', 'bgi_' . $lang ) : 0;
		},
		bgi_lang()
	);
}

/**
 * ID of the form shown by a Contact block.
 *
 * Order: the form chosen in the block, the form created for the page's language,
 * then the English one.
 *
 * @param int $chosen Form chosen in the block, or 0.
 * @return int Form ID, or 0 when none exists.
 */
function bgi_contact_form_id( $chosen ) {
	$chosen = (int) $chosen;
	if ( $chosen && function_exists( 'wpcf7_contact_form' ) && wpcf7_contact_form( $chosen ) ) {
		return $chosen;
	}

	// The page's own language, so the block editor preview matches the front end.
	$lang = function_exists( 'pll_get_post_language' ) ? pll_get_post_language( get_the_ID() ) : '';

	return bgi_cf7_pick_form( 'bgi_cf7_contact_forms', '__return_zero', $lang ? $lang : bgi_lang() );
}
