<?
include("config.php");

if (isset( $_COOKIE['login_cookie'] )) {
	$username = mysqli_real_escape_string($dblink, strtolower($_COOKIE['login_cookie']));
	$session_hash = mysqli_real_escape_string($dblink, $_COOKIE['hsh']);

	iu_check_user_setup(); //make sure user has finished setup

	/* GET LOGGED IN USER INFORMATION*/
	$query  = mysqli_query($dblink, "SELECT * FROM users WHERE username='$username' AND session_hash = '$session_hash'");
	$Rows = mysqli_fetch_assoc($query);
}

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
	<meta content="width=device-width, user-scalable=yes" name="viewport" />
	<link href="/stylesheets/<?= $stylesheet; ?>.css?<?= $__site['filedate']; ?>" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" type="image/png" href="/images/favicon.png?<?= $__site['filedate']; ?>">
	<title>Privacy Policy <?= $__site['name']; ?></title>
	<meta name="keywords" content="<?= $__site['keywords']; ?>">
	<meta name="description" content="<?= $__site['description']; ?>">
</head>
<body class="privacypolicy">

	<?php include("top.php"); ?>

	<div id="content">

		<?php include("left.php"); ?>
	
		<div id="right" class="privacypolicysection">
			<div class="pp">
				<h2>What information do we collect?</h2>

				<p>We collect information from you when you register on our site or subscribe to our newsletter.</p>

				<p>When ordering or registering on our site, as appropriate, you may be asked to enter your: name or e-mail address. You may, however, visit our site anonymously.</p>

				<p>Google, as a third party vendor, uses cookies to serve ads on your site. Google's use of the DART cookie enables it to serve ads to your users based on their visit to your sites and other sites on the Internet. Users may opt out of the use of the DART cookie by visiting the Google ad and content network privacy policy.</p>

				<h2>What do we use your information for?</h2>

				<p>Any of the information we collect from you may be used in one of the following ways:</p>

				<ul>
					<li>To personalize your experience (your information helps us to better respond to your individual needs)</li>

					<li>To improve our website (we continually strive to improve our website offerings based on the information and feedback we receive from you)</li>

					<li>To improve customer service (your information helps us to more effectively respond to your customer service requests and support needs)	</li>

					<li>To administer a contest, promotion, survey or other site feature</li>

					<li>To send periodic emails	(such as notifications, group invites, promotions, etc.)</li>
				</ul>

				<p>The email address you provide may be used to send you information, respond to inquiries, and/or other requests or questions.</p>

				<h2>How do we protect your information?</h2>

				<p>We implement a variety of security measures to maintain the safety of your personal information when you enter, submit, or access your personal information.</p>

				<h2>Do we use cookies?</h2>

				<p>Yes (Cookies are small files that a site or its service provider transfers to your computers hard drive through your Web browser (if you allow) that enables the sites or service providers systems to recognize your browser and capture and remember certain information</p>

				<p>We use cookies to understand and save your preferences for future visits.</p>

				<h2>Do we disclose any information to outside parties?</h2>

				<p>We do not sell, trade, or otherwise transfer to outside parties your personally identifiable information. This does not include trusted third parties who assist us in operating our website, conducting our business, or servicing you, so long as those parties agree to keep this information confidential. We may also release your information when we believe release is appropriate to comply with the law, enforce our site policies, or protect ours or others rights, property, or safety. However, non-personally identifiable visitor information may be provided to other parties for marketing, advertising, or other uses.</p>

				<h2>Third party links</h2>

				<p>Occasionally, at our discretion, we may include or offer third party products or services on our website. These third party sites have separate and independent privacy policies. We therefore have no responsibility or liability for the content and activities of these linked sites. Nonetheless, we seek to protect the integrity of our site and welcome any feedback about these sites.</p>

				<h2>California Online Privacy Protection Act Compliance</h2>

				<p>Because we value your privacy we have taken the necessary precautions to be in compliance with the California Online Privacy Protection Act. We therefore will not distribute your personal information to outside parties without your consent.</p>

				<p>As part of the California Online Privacy Protection Act, all users of our site may make any changes to their information at anytime by clicking into profile page and editing their profile information.</p>

				<h2>Childrens Online Privacy Protection Act Compliance</h2>

				<p>We are in compliance with the requirements of COPPA (Childrens Online Privacy Protection Act), we do not collect any information from anyone under 13 years of age. Our website, products and services are all directed to people who are at least 13 years old or older.</p>

				<h2>Online Privacy Policy Only</h2>

				<p>This online privacy policy applies only to information collected through our website and not to information collected offline.</p>

				<h2>Terms and Conditions</h2>

				<p>Please also visit our <a href="/terms">Terms and Conditions</a> section establishing the use, disclaimers, and limitations of liability governing the use of our website at <?= $__site['url']; ?>/terms</p>

				<h2>Your Consent</h2>

				<p>By using our site, you consent to our privacy policy.</p>

				<h2>Changes to our Privacy Policy</h2>

				<p>If we decide to change our privacy policy, we will send an email notifying you of any changes, and/or update the Privacy Policy modification date below.</p>

				<p>This policy was last modified on June 18, 2014</p>

				<h2>Contacting Us</h2>

				<p>If there are any questions regarding this privacy policy you may contact us using the information below.</p>

				<p><?= $__site['url']; ?><br />
				160 Maritime Terrace<br />
				Hercules, CA 94547<br />
				USA<br />
				<a href="mailto:<?= $__site['email']; ?>"><?= $__site['email']; ?></a></p>
			</div>
			
		</div><!--#right-->
	</div><!--#bottom-->
<?php include("scripts.php"); ?>
</body>
</html>