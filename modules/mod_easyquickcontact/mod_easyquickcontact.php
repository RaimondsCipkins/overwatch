<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_easyquickcontact
 *
 * @copyright   Copyright (C) 2012 - 2020 JoomBoost, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

//no direct access
defined('_JEXEC') or die();

// Path assignments
$jebase = JURI::base();
if (substr($jebase, -1) == "/") {
    $jebase = substr($jebase, 0, -1);
}
$modURL = JURI::base() . 'modules/mod_easyquickcontact';

// params
$jQuery = $params->get("jQuery");
$popUp = $params->get("popUp", "0");
$popUpButton = $params->get("popUpButton", "Quick Contact");

$name = $params->get("name", "Name");
$email = $params->get("email", "Email");
$message = $params->get("message", "Message");
$captcha_label = $params->get("captcha_label", "1");
$captcha = $params->get("captcha", "Captcha");
$submit = $params->get("submit", "Send");

$subject = $params->get("subject", "");
$recipient = $params->get("recipient", "");

$buttonBg = $params->get('buttonBg', '#E60000');
$buttonText = $params->get('buttonText', '#ffffff');
$buttonBgH = $params->get('buttonBgH', '#333333');

$label_text = $params->get("label_text", "#333333");
$input_bg = $params->get("input_bg", "#ffffff");
$input_brd = $params->get("input_brd", "#cccccc");
$input_text = $params->get("input_text", "#333333");

// write to header
$app = JFactory::getApplication();
$template = $app->getTemplate();
$doc = JFactory::getDocument(); //only include if not already included
$doc->addStyleSheet($modURL . '/css/style.css');
$doc->addStyleSheet($modURL . '/css/modal.css');
$style = '
#je_contact button[type="submit"], .qcbutton a.je_button{ background:' . $buttonBg . '; color:' . $buttonText . ' ;}
#je_contact button[type="submit"]:hover, .qcbutton a.je_button:hover{ background:' . $buttonBgH . ' }
#je_contact input, #je_contact textarea{background-color:' . $input_bg . '; border:1px solid ' . $input_brd . '; color:' . $input_text . '}
';
$doc->addStyleDeclaration($style);
if ($params->get('jQuery')) {
    $doc->addScript('http://code.jquery.com/jquery-latest.pack.js');
}

$doc->addScript($modURL . '/js/modernizr.js');

$js = '';
$doc->addScriptDeclaration($js);

if (!isset($_SESSION)) {
    session_start();
}

if (isset($_POST['submitted'])) {
    // require a name from user
    if (trim($_POST['je_name']) === '') {
        $nameError = JText::_('MOD_EASYQUICKCONTACT_PLEASE_ENTER_YOUR_NAME');
        $hasError = true;
    } else {
        $name = trim($_POST['je_name']);
    }
    // need valid email
    if (trim($_POST['je_email']) === '') {
        $emailError = JText::_('MOD_EASYQUICKCONTACT_PLEASE_ENTER_YOUR_EMAIL');
        $hasError = true;
    } else if (!preg_match("/^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$/i", trim($_POST['je_email']))) {
        $emailError = 'You entered an invalid email address.';
        $hasError = true;
    } else {
        $email = trim($_POST['je_email']);
    }
    // we need at least some content
    if (trim($_POST['je_message']) === '') {
        $messageError = JText::_('MOD_EASYQUICKCONTACT_PLEASE_ENTER_YOUR_MESSAGE');
        $hasError = true;
    } else {
        if (function_exists('stripslashes')) {
            $message = stripslashes(trim($_POST['je_message']));
        } else {
            $message = trim($_POST['je_message']);
        }
    }

    // require a valid captcha
    if ($captcha_label == "1") {
        if (trim($_POST['je_captcha']) != $_SESSION['expect']) {
            $captchaError = JText::_('MOD_EASYQUICKCONTACT_PLEASE_ENTER_CORRECT_CAPTCHA');
            $hasError = true;
        } else {
            unset ($_SESSION['n1']);
            unset ($_SESSION['n2']);
            unset ($_SESSION['expect']);
            $captcha = trim($_POST['je_captcha']);
        }
    }


    // upon no failure errors let's email now!
    if (!isset($hasError)) {
        $mail = JFactory::getMailer();
        $config = JFactory::getConfig();
        $sender = array($_POST['je_email'], $_POST['je_name']);
        $mail->setSender($sender);
        $mail->setSubject($subject);
        $mail->addRecipient($recipient);

        $body = "Subject: " . $subject . "<br/>";
        $body .= "Name: " . $_POST['je_name'] . "<br/>";
        $body .= "Email: " . $_POST['je_email'] . "<br/><br/>";
        $body .= $_POST['je_message'] . "<br/>";

        $mail->setBody($body);
        $mail->IsHTML(true);
        $send = $mail->Send();
        $emailSent = true;
    }
}
if ($captcha_label == "1") {
    $_SESSION['n1'] = rand(1, 15);
    $_SESSION['n2'] = rand(1, 15);
    $_SESSION['expect'] = $_SESSION['n1'] + $_SESSION['n2'];
}

require JModuleHelper::getLayoutPath('mod_easyquickcontact', $params->get('layout', 'default'));



