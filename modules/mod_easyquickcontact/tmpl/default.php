<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_easyquickcontact
 *
 * @copyright   Copyright (C) 2012 - 2020 JoomBoost, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die();

?>

<script src="<?php echo $modURL . '/js/main.js'; ?>"></script>
<?php if ($popUp == "1") { ?>
    <div class="qcbutton">
        <ul>
            <li><a class="cd-signup je_button" href="#0"><?php echo $popUpButton; ?></a></li>
        </ul>
    </div>
<div class="cd-user-modal">
    <div class="cd-user-modal-container">
        <div class="cd-form">
            <?php } ?>

            <?php if ($recipient == "") { ?>
                <div id="je_contact">
                    <span class="error"><?php echo JText::_('MOD_EASYQUICKCONTACT_RECIPIENT_NOT_SET') ?></span>
                </div>
            <?php } else { ?>
                <div id="je_contact">
                    <?php if (isset($emailSent) && $emailSent == true) { ?>
                        <span class="success"><strong><?php echo JText::_('MOD_EASYQUICKCONTACT_THANKYOU') ?></strong> <?php echo JText::_('MOD_EASYQUICKCONTACT_EMAIL_DELIVERED') ?></span>
                    <?php } else { ?>
                        <form id="contact-je" action="<?php echo JURI::current(); ?>" method="post">
                            <div class="input">
                                <label id="je_hide" for="name"><?php echo JText::_("$name"); ?></label>
                                <input type="text" name="je_name" id="name"
                                       value="<?php if (isset($_POST['je_name'])) echo $_POST['je_name']; ?>"
                                       class="requiredField" placeholder="<?php echo $name; ?>"/>
                                <?php if (!isset($_SESSION)) {
                                    if ($nameError != '') { ?><span
                                            class="error"><?php echo $nameError; ?></span><?php }
                                } ?>
                            </div>

                            <div class="input">
                                <label id="je_hide" for="email"><?php echo JText::_("$email"); ?></label>
                                <input type="text" name="je_email" id="email"
                                       value="<?php if (isset($_POST['je_email'])) echo $_POST['je_email']; ?>"
                                       class="email requiredField" placeholder="<?php echo $email; ?>"/>
                                <?php if (!isset($_SESSION)) {
                                    if ($emailError != '') { ?><span
                                            class="error"><?php echo $emailError; ?></span><?php }
                                } ?>
                            </div>

                            <div class="input">
                                <label id="je_hide" for="message"><?php echo JText::_("$message"); ?></label>
                                <textarea name="je_message" id="message" class="requiredField" rows="6"
                                          placeholder="<?php echo $message; ?>"><?php if (isset($_POST['je_message'])) {
                                        if (function_exists('stripslashes')) {
                                            echo stripslashes($_POST['je_message']);
                                        } else {
                                            echo $_POST['je_message'];
                                        }
                                    } ?></textarea>
                                <?php if (!isset($_SESSION)) {
                                    if ($messageError != '') { ?><span
                                            class="error"><?php echo $messageError; ?></span><?php }
                                } ?>
                            </div>
                            <?php if ($captcha_label == "1") { ?>
                                <div class="input">
                                    <label for="captcha"><?php echo JText::_("$captcha"); ?></label>: <?= $_SESSION['n1'] ?>
                                    + <?= $_SESSION['n2'] ?> =
                                    <input type="text" class="requiredCaptcha" name="je_captcha" id="captcha"
                                           value="<?php if (isset($_POST['je_captcha'])) echo($_POST['je_captcha']); ?>"
                                           placeholder="<?php echo $captcha; ?>"/>
                                    <?php if (!isset($_SESSION)) {
                                        if ($captchaError != '') { ?><span
                                                class="error"><?php echo $captchaError; ?></span><?php }
                                    } ?>
                                </div>
                            <?php } ?>
                            <div class="input">
                                <button name="submit" type="submit"
                                        class="je_button"><?php echo JText::_("$submit") ?></button>
                                <input type="hidden" name="submitted" id="submitted" value="true"/>
                            </div>
                        </form>
                    <?php } ?>
                </div>

                <script type="text/javascript">
                    jQuery(document).ready(function () {
                        jQuery('form#contact-je').submit(function () {
                            jQuery('form#contact-je .error').remove();
                            var hasError = false;
                            jQuery('.requiredField').each(function () {
                                if (jQuery.trim(jQuery(this).val()) == '') {
                                    var labelText = jQuery(this).prev('label').text();
                                    jQuery(this).parent().append('<span class="error">Please enter your ' + labelText + '!</span>');
                                    jQuery(this).addClass('invalid');
                                    hasError = true;
                                } else if (jQuery(this).hasClass('email')) {
                                    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
                                    if (!emailReg.test(jQuery.trim(jQuery(this).val()))) {
                                        var labelText = jQuery(this).prev('label').text();
                                        jQuery(this).parent().append('<span class="error">You\'ve entered an invalid ' + labelText + '!</span>');
                                        jQuery(this).addClass('invalid');
                                        hasError = true;
                                    }
                                }
                            });<?php if ($captcha_label == "1") {?>
                            jQuery('.requiredCaptcha').each(function () {
                                if (jQuery.trim(jQuery(this).val()) != '<?php echo $_SESSION['expect'];?>') {
                                    var labelText = jQuery(this).prev('label').text();
                                    jQuery(this).parent().append('<span class="error">Please enter the correct ' + labelText + '!</span>');
                                    jQuery(this).addClass('invalid');
                                    hasError = true;
                                }
                            });<?php } ?>
                            if (!hasError) {
                                var formInput = jQuery(this).serialize();
                                jQuery.post(jQuery(this).attr('action'), formInput, function (data) {
                                    jQuery('form#contact-je').slideUp("fast", function () {
                                        jQuery(this).before('<span class="success"><strong>Thank you!</strong> Your email has been delivered.</span>');
                                    });
                                });
                            }
                            return false;
                        });
                    });
                </script>
            <?php } ?>
            <?php if ($popUp == "1") { ?>
        </div>
    </div>
</div>
<?php } ?>
