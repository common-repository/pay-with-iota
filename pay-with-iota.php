<?php
/*
Plugin Name: Pay with IOTA
Author: Alexander K.
Description: Get a donation with a simple Button with IOTA via Firefly.
Version: 1.0.10
Text Domain: https://short-aktien.de
Author URI: https://short-aktien.de
License: GPLv2
Tested up to: 5.9.2
Stable tag: 1.0.10
Tags: payment, iota
*/

function iota_pay_admin_enqueue_styles() {
	wp_enqueue_style( 'admin-iota', plugin_dir_url( __FILE__ ) . 'admin-iota.css', array(), '', 'all' );
}

add_action( 'admin_enqueue_scripts', 'iota_pay_admin_enqueue_styles');

add_action('admin_menu', 'iotaPayMenuFunc');
function iotaPayMenuFunc(){
	add_options_page(__("IOTA Pay", 'iota-pay'), __("IOTA Pay", 'iota-pay'), 'manage_options', 'iota-button-setting', 'iotaPaySettingFunc');
}

function iotaPaySettingFunc(){
	if(current_user_can('administrator')){
		$message='';
	/*--------------settings--------------*/

		if(isset($_POST['save_iota_setting'])){
			$iotaSetting = [
				'address' => (sanitize_text_field($_POST['address']) ? sanitize_text_field($_POST['address']) : ""),
				'amount' => (sanitize_text_field($_POST['amount']) ? sanitize_text_field($_POST['amount']) : 0),
				'user' => (sanitize_text_field($_POST['user']) ? sanitize_text_field($_POST['user']) : ""),
				'currency' => (sanitize_text_field($_POST['currency']) ? sanitize_text_field($_POST['currency']) : "")
			];

			update_option('_iotaSetting', $iotaSetting);
		}
		$iotaSavedSetting = get_option('_iotaSetting');

		?>
		<div class="iota-button-setting">
		<h2>IOTA Pay Settings</h2>

			<?php echo esc_attr($message); ?>
			<form action="" method="post">
				<?php wp_nonce_field('_wpnonce'); ?>
	<!-- --------------adress-------------		 -->
				<label for="address"><strong>IOTA Address</strong></label>
				<input type="text" value="<?php echo (esc_attr($iotaSavedSetting['address']) ? esc_attr($iotaSavedSetting['address']) : "");?>" id="address" name="address" placeholder="Address" />
	<!-- --------------amount-------------	 -->

				<label for="amount"><strong>Amount</strong></label>
				<input type="text" value="<?php echo (esc_attr($iotaSavedSetting['amount']) ? esc_attr($iotaSavedSetting["amount"]) : "");?>" id="amount" name="amount" placeholder="Amount" />
	<!-- --------------currency----------- -->

				<label for="currency"><strong>Currency</strong></label>
				<input type="text" value="<?php echo (esc_attr($iotaSavedSetting["currency"]) ? esc_attr($iotaSavedSetting["currency"]) : "");?>" id="currency" name="currency" placeholder="Currency" />
	<!-- --------------user--------------- -->

				<label for="user"><strong>User</strong></label>
				<input type="text" value="<?php echo (esc_attr($iotaSavedSetting["user"]) ? esc_attr($iotaSavedSetting["user"]) : ""); ?>" id="user" name="user" placeholder="User" />
	<!-- --------------save--------------- -->

				<input name="save_iota_setting" type="submit" class="button button-primary button-large save-order-setting" value="Save Setting">
			</form>
		</div>


	<!-- ---------------checkbox for every post - donation----------------

	<br><br>Insert Donation Button in every Blogpost?

	<input type="checkbox" name="everyBlogPost" value="Yes" checked="unchecked"> insert';-->


	<!-- ----------------- Description Text in Settings Menu-------------- -->

	<script type="module" src="//shortaktien.de/build/iota-button.esm.js"></script>
 <script nomodule src="//shortaktien.de/build/iota-button.js"></script>

		<br>This <b>IOTA plugin</b> works on the basis of the IOTA button https://shortaktien.de which can be found here.<br>

		 This plugin lets you easily insert this button anywhere on your WordPress site. Whether in an article, on a page or as a widget.<br> <br>

		 There are 4 entries you need to make: <br><br>

		 <b>Address</b> - this is the same as your address in the Firefly app. This is needed so that you can receive your donations and earnings.
		 <br><br>

		 <b>The amount</b> - here you enter the amount of IOTA you want to receive. It is calculated in the smallest form of IOTA.
		 <br><br>

		 <b>Currency</b> - specify here which currency you want to have, in case you want to give people the possibility to convert to USD/EUR for 		 example, you will still get your donations and income in IOTA here. The conversion rate will be updated every minute.
		 <br><br>

		 <b>The user</b> - this input will be necessary for the [donation] button. The users will see who they are donating to. Here you can enter 		 your name or the name of your website.
		 <br><br>

		 <br><b>How to use:</b><br>

		 To insert the button anywhere on your WordPress site, simply paste the shortcode <b>[iota]</b>. This is the Button for a payment with 			 IOTA<br>

		 The shortcode <b>[fiat]</b> stand for the button with the option to make a transaktion with a  conversion fiat currency <br>

		 The shortcode <b>[donation]</b> gives you the Button for a Donation. The user can enter here set or own data for the sum.<br><br><br>
All updates, new features ect, can be found on my blog <a href="https://short-aktien.de/blog">Short-Aktien</a>. The code is open source and can be found on <a href="https://github.com/shortaktien/iota-pay-wordpress">GitHub</a>. Donations in form of IOTA are welcome <iota-button address="iota1qq53kq9yy0prcjme3ad88erraaqsy9judku2hhtd34ywrjfslykgvjjd627" amount="5000000"></iota-button>
		 <?php


	}
}



/*------------Pay with IOTA--------------*/


function iota_pay_enqueue_scripts() {

		// echo '<script type="module" src="//iota-button.org/build/iota-button.esm.js"></script>
		// <script nomodule src="//iota-button.org/build/iota-button.js"></script>';
		echo wp_get_script_tag(
		     array(
		         'id'        => 'iota-button.esm.js',
		         'type' => 'module',
		         'src'       => esc_url( 'https://shortaktien.de/build/iota-button.esm.js' ),
		     )
		 );
		echo wp_get_script_tag(
		     array(
		         'id'        => 'iota-button.js',
		         'nomodule' => true,
		         'src'       => esc_url( 'shortaktien.de/build/iota-button.js' ),
		     )
		 );
}


add_action( 'wp_enqueue_scripts', 'iota_pay_enqueue_scripts' );

add_shortcode('iota', 'iotaPayButtonFunc');
function iotaPayButtonFunc(){
	$buttonHtml = '';
	ob_start();
		$iotaSavedSetting = get_option('_iotaSetting');
		?>
		<iota-button address="<?php echo (esc_attr($iotaSavedSetting["address"]) ? esc_attr($iotaSavedSetting["address"]) : ""); ?>" amount="<?php echo (esc_attr($iotaSavedSetting["amount"]) ? esc_attr($iotaSavedSetting["amount"]) : ""); ?>"></iota-button>
		<?php
	$contents = ob_get_contents();ob_get_clean();
	return $contents;
}



/*------------Pay with USD/EUR-------------*/


add_shortcode('fiat', 'iotaPayButtonFuncFiat');
function iotaPayButtonFuncFiat(){
	$buttonHtml = '';
	ob_start();
		$iotaSavedSetting = get_option('_iotaSetting');
		?>
		<iota-button address="<?php echo (esc_attr($iotaSavedSetting["address"]) ? esc_attr($iotaSavedSetting["address"]) : ""); ?>"
										amount="<?php echo (esc_attr($iotaSavedSetting["amount"]) ? esc_attr($iotaSavedSetting["amount"]) : ""); ?> "
										currency="<?php echo (esc_attr($iotaSavedSetting["currency"]) ? esc_attr($iotaSavedSetting["currency"]) : "")?>">
										</iota-button>

		<?php
	$contents = ob_get_contents();
	ob_get_clean();
	return $contents;
}


/*------------Donation Button with IOTA-------------*/


add_shortcode('donation', 'iotaPayButtonFuncDonate');
function iotaPayButtonFuncDonate(){
	$buttonHtml = '';
	ob_start();
		$iotaSavedSetting = get_option('_iotaSetting');
		?>
		<iota-button address="<?php echo (esc_attr($iotaSavedSetting["address"]) ? esc_attr($iotaSavedSetting["address"]) : ""); ?>"
					 currency="<?php echo (esc_attr($iotaSavedSetting["currency"]) ? esc_attr($iotaSavedSetting["currency"]) : ""); ?>"
					 label="Donate"
					 merchant="<?php echo (esc_attr($iotaSavedSetting["user"]) ? esc_attr($iotaSavedSetting["user"]) : ""); ?>"
					 type="donation">
					 </iota-button>

		<?php
	$contents = ob_get_contents();
	ob_get_clean();
	return $contents;
}
