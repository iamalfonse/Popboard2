<?php
// /**
//  * forgotpassword.php
//  *
//  **/

// /* Database params */
// include("config.php");

// This form will go through AJAX call instead
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
	<meta content="width=device-width, user-scalable=yes" name="viewport" />
    <title>Forgot Password | <?= $__site['name']; ?></title>
    <meta name="keywords" content="<?= $__site['keywords']; ?>">
    <meta name="description" content="<?= $__site['description']; ?>">
    <link href="/stylesheets/<?= $stylesheet; ?>.css?<?= $__site['filedate']; ?>" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" type="image/png" href="/images/favicon.png?<?= $__site['filedate']; ?>">
</head>
<body>

    <?php include("top.php"); ?>

    <div id="content">
        
        <div id="signup">
            <div class="signup-banner">
                <h2>Forgot Password</h2>
            </div>
            <?php
                // if ($have_error) {
                //     echo "<p class='error'>$errmsg</p>\n";
                // }

                // This form will go through AJAX call instead
            ?>
            <form action="/forgotpasswordsubmit.php" method="post" id="forgotpasswordform">
                <label>Enter your email</label>
                <p><input class="userinput email" type="text" name="old_email" size="50" /></p>
                <p class='note'>Note: We'll send you an email to reset your password.</p>
                <input class="submitbtn" type="submit" name="submit" value="Reset Password" />
            </form>
        
        </div><!--#signup-->
    </div><!--#bottom-->

<?php include("scripts.php"); ?>
<script src="/js/forgotpassword.js" type="text/javascript"></script>
<!-- <script src="/js/jquery.validate.min.js" type="text/javascript"></script> -->
</body>
</html>