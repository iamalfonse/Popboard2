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
	<link href="/stylesheets/<?= $stylesheet; ?>.css" rel="stylesheet" type="text/css" />
	<link rel="shortcut icon" type="image/png" href="/images/favicon.png">
	<title>Import Underground | Terms and Conditions</title>
	<meta name="keywords" content="Import Underground, Imports, Racing, Tuner, Cars, Car Meets, Underground, Street, Pictures, Events, Meets, Groups, Car Groups">
	<meta name="description" content="Post and discuss about Import Cars">
</head>
<body class="terms">

	<?php include("top.php"); ?>

	<div id="content">

		<?php include("left.php"); ?>
	
		<div id="right" class="termssection">
			<div class="pp">
				<h2>Introduction</h2>

				<p>These terms and conditions govern your use of this website; by using this website, you accept these terms and conditions in full.   If you disagree with these terms and conditions or any part of these terms and conditions, you must not use this website.</p>

				<p>You must be at least 13 years of age to use this website.  By using this website and by agreeing to these terms and conditions you warrant and represent that you are at least 13 years of age.</p>

				<p>This website uses cookies. By using this website and agreeing to these terms and conditions, you consent to ImportUnderground's use of cookies in accordance with the terms of ImportUnderground's <a href="/privacypolicy">privacy policy / cookies policy</a>.</p>

				<h2>License to use website</h2>

				<p>Unless otherwise stated, ImportUnderground and/or its licensors own the intellectual property rights in the website and material on the website.  Subject to the license below, all these intellectual property rights are reserved.</p>

				<p>You may view, download for caching purposes only, and print pages from the website for your own personal use, subject to the restrictions set out below and elsewhere in these terms and conditions.</p>
				
				<p>You must not:</p>
				<ul>
					<li>Republish material from this website (including republication on another website)</li>

					<li>Sell, rent or sub-license material from the website</li>

					<li>Show any material from the website in public</li>

					<li>Reproduce, duplicate, copy or otherwise exploit material on this website for a commercial purpose</li>

					<li>Edit or otherwise modify any material on the website (unless it is part of a standard feature of the website such as editing posts, or editing profile information, etc.)</li>
				
					<li>redistribute material from this website (except for content specifically and expressly made available for redistribution)</li>
				</ul>

				<p>Where content is specifically made available for redistribution, it may only be redistributed (within your organisation).</p>

				<h2>Acceptable use</h2>

				<p>You must not use this website in any way that causes, or may cause, damage to the website or impairment of the availability or accessibility of the website; or in any way which is unlawful, illegal, fraudulent or harmful, or in connection with any unlawful, illegal, fraudulent or harmful purpose or activity.</p>

				<p>You must not use this website to copy, store, host, transmit, send, use, publish or distribute any material which consists of (or is linked to) any spyware, computer virus, Trojan horse, worm, keystroke logger, rootkit or other malicious computer software.</p>

				<p>You must not conduct any systematic or automated data collection activities (including without limitation scraping, data mining, data extraction and data harvesting) on or in relation to this website.</p>

				<p>You must not use this website to transmit or send unsolicited commercial communications.</p>

				<p>You must not use this website for any purposes related to marketing without ImportUnderground's' express written consent.</p>

				<h2>Restricted access</h2>

				<p>Access to certain areas of this website is restricted. ImportUnderground reserves the right to restrict access to other areas of this website, or indeed this entire website, at ImportUnderground's discretion</p>

				<p>If ImportUnderground provides you with a user ID and password to enable you to access restricted areas of this website or other content or services, you must ensure that the user ID and password are kept confidential.</p>

				<p>ImportUnderground may disable your user ID and password in ImportUnderground's sole discretion without notice or explanation.</p>

				<h2>User content</h2>

				<p>In these terms and conditions, "your user content" means material (including without limitation text, images, audio material, video material and audio-visual material) that you submit to this website, for whatever purpose.</p>

				<p>You grant to ImportUnderground a worldwide, irrevocable, non-exclusive, royalty-free license to use, reproduce, adapt, publish, translate and distribute your user content in any existing or future media.  You also grant to ImportUnderground the right to sub-license these rights, and the right to bring an action for infringement of these rights.</p>

				<p>Your user content must not be illegal or unlawful, must not infringe any third party's legal rights, and must not be capable of giving rise to legal action whether against you or ImportUnderground or a third party (in each case under any applicable law). </p>

				<p>You must not submit any user content to the website that is or has ever been the subject of any threatened or actual legal proceedings or other similar complaint.</p>

				<p>ImportUnderground reserves the right to edit or remove any material submitted to this website, or stored on [NAME'S] servers, or hosted or published upon this website.</p>

				<p>Notwithstanding ImportUnderground's rights under these terms and conditions in relation to user content, ImportUnderground does not undertake to monitor the submission of such content to, or the publication of such content on, this website.</p>
				
				<h2>No warranties</h2>

				<p>This website is provided "as is" without any representations or warranties, express or implied. ImportUnderground makes no representations or warranties in relation to this website or the information and materials provided on this website.</p>

				<p>Without prejudice to the generality of the foregoing paragraph, ImportUnderground does not warrant that:</p>

				<ul>
					<li>This website will be constantly available, or available at all</li>
					<li>The information on this website is complete, true, accurate or non-misleading</li>
				</ul>

				<p>Nothing on this website constitutes, or is meant to constitute, advice of any kind. If you require advice in relation to any legal, financial or medical matter you should consult an appropriate professional.</p>

				<h2>Limitations of liability</h2>

				<p>ImportUnderground will not be liable to you (whether under the law of contact, the law of torts or otherwise) in relation to the contents of, or use of, or otherwise in connection with, this website:</p>

				<ul>
					<li>To the extent that the website is provided free-of-charge, for any direct loss</li>
					<li>For any indirect, special or consequential loss or</li>
					<li>For any business losses, loss of revenue, income, profits or anticipated savings, loss of contracts or business relationships, loss of reputation or goodwill, or loss or corruption of information or data.</li>
				</ul>

				<p>These limitations of liability apply even if ImportUnderground has been expressly advised of the potential loss.</p>
	
				<h2>Exceptions</h2>

				<p>Nothing in this website disclaimer will exclude or limit any warranty implied by law that it would be unlawful to exclude or limit; and nothing in this website disclaimer will exclude or limit ImportUnderground's liability in respect of any:</p>

				<ul>
					<li>Death or personal injury caused by ImportUnderground's negligence</li>
					<li>Fraud or fraudulent misrepresentation on the part of ImportUnderground or</li>
					<li>Matter which it would be illegal or unlawful for ImportUnderground to exclude or limit, or to attempt or purport to exclude or limit, its liability.</li>
				</ul>

				<h2>Reasonableness</h2>

				<p>By using this website, you agree that the exclusions and limitations of liability set out in this website disclaimer are reasonable.</p>

				<p>If you do not think they are reasonable, you must not use this website.</p>

				<h2>Other parties</h2>

				<p>You accept that, as a limited liability entity, ImportUnderground has an interest in limiting the personal liability of its officers and employees.  You agree that you will not bring any claim personally against ImportUnderground's officers or employees in respect of any losses you suffer in connection with the website.</p>

				<p>Without prejudice to the foregoing paragraph,] you agree that the limitations of warranties and liability set out in this website disclaimer will protect ImportUnderground's officers, employees, agents, subsidiaries, successors, assigns and sub-contractors as well as ImportUnderground.</p>

				<h2>Unenforceable provisions</h2>

				<p>If any provision of this website disclaimer is, or is found to be, unenforceable under applicable law, that will not affect the enforceability of the other provisions of this website disclaimer.</p>

				<h2>Indemnity</h2>

				<p>You hereby indemnify ImportUnderground and undertake to keep ImportUnderground indemnified against any losses, damages, costs, liabilities and expenses (including without limitation legal expenses and any amounts paid by ImportUnderground to a third party in settlement of a claim or dispute on the advice of ImportUnderground's legal advisers) incurred or suffered by ImportUnderground arising out of any breach by you of any provision of these terms and conditions, or arising out of any claim that you have breached any provision of these terms and conditions.</p>

				<h2>Breaches of these terms and conditions</h2>

				<p>Without prejudice to ImportUnderground's other rights under these terms and conditions, if you breach these terms and conditions in any way, ImportUnderground may take such action as ImportUnderground deems appropriate to deal with the breach, including suspending your access to the website, prohibiting you from accessing the website, blocking computers using your IP address from accessing the website, contacting your internet service provider to request that they block your access to the website and/or bringing court proceedings against you.</p>

				<h2>Variation</h2>

				<p>ImportUnderground may revise these terms and conditions from time-to-time. Revised terms and conditions will apply to the use of this website from the date of the publication of the revised terms and conditions on this website. Please check this page regularly to ensure you are familiar with the current version.</p>

				<h2>Assignment</h2>

				<p>ImportUnderground may transfer, sub-contract or otherwise deal with [NAME'S] rights and/or obligations under these terms and conditions without notifying you or obtaining your consent.</p>

				<p>You may not transfer, sub-contract or otherwise deal with your rights and/or obligations under these terms and conditions.</p>

				<h2>Severability</h2>

				<p>If a provision of these terms and conditions is determined by any court or other competent authority to be unlawful and/or unenforceable, the other provisions will continue in effect.  If any unlawful and/or unenforceable provision would be lawful or enforceable if part of it were deleted, that part will be deemed to be deleted, and the rest of the provision will continue in effect.</p>

				<h2>Entire agreement</h2>

				<p>These terms and conditions, together with ImportUnderground's <a href="/privacypolicy">privacy policy</a>, constitute the entire agreement between you and ImportUnderground in relation to your use of this website, and supersede all previous agreements in respect of your use of this website.</p>

				<h2>Law and jurisdiction</h2>

				<p>These terms and conditions will be governed by and construed in accordance with United States of America law, and any disputes relating to these terms and conditions will be subject to the non-exclusive jurisdiction of the courts of The United States of America.</p>

				<h2>ImportUnderground's details</h2>

				<p>http://www.importunderground.com<br />
				160 Maritime Terrace<br />
				Hercules, CA 94547<br />
				USA<br />
				<a href="mailto:support@importunderground.com">support@importunderground.com</a></p>

				<h4>Credit</h4>

				<p>This document was created using a Contractology template available at <a href="http://www.freenetlaw.com">http://www.freenetlaw.com</a>.</p>
			</div>
			
		</div><!--#right-->
	</div><!--#bottom-->
<?php include("scripts.php"); ?>
</body>
</html>