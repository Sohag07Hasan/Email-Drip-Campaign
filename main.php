<?php 
/*
 * plugin name: Email Drip Campaign with Cfomrs
 * author: Mahibul Hasan
 * plugin uri: http://google.com
 * author uri: http://sohag07hasan.elance.com
 * Description: Creates an interface to start email campaign with Cforms wp plugin
 */

define("EMAILDRIPCAMPAIGN_DIR", dirname(__FILE__));
define("EMAILDRIPCAMPAIGN_FILE", __FILE__);
define("EMAILDRIPCAMPAIGN_URL", plugins_url('/', __FILE__));



include EMAILDRIPCAMPAIGN_DIR . '/classes/email-templates.php';
emaildripcampaign_templates::init();

include EMAILDRIPCAMPAIGN_DIR . '/classes/email-respnder.php';
emaildripcampaign_responders::init();


//including cform handler
include EMAILDRIPCAMPAIGN_DIR . '/classes/cforms-handler.php';
Cforms_Handler::init();

