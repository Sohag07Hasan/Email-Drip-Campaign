<?php 
/*
 * plugin name: Email Drip Campaign with Cfomrs
 * author: Mahibul Hasan
 * plugin url: http://google.com
 * author url: http://sohag07hasan.elance.com
 *
 */

define("EMAILDRIPCAMPAIGN_DIR", dirname(__FILE__));
define("EMAILDRIPCAMPAIGN_FILE", __FILE__);
define("EMAILDRIPCAMPAIGN_URL", plugins_url('/', __FILE__));

include EMAILDRIPCAMPAIGN_DIR . '/classes/email-templates.php';
emaildripcampaign_templates::init();