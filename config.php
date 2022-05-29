<?php

// set Timezone
date_default_timezone_set("Europe/Prague");

// Turn off displaying errors (FOR PRODUCTION ONLY)
//error_reporting(0);

// Site settings
define("SITE_PATH", __DIR__);
define("SITE_TITLE", "SPŠEI Marketplace");

// Include path
set_include_path(SITE_PATH);

// Database credentials
define("DB_hostname", "localhost");
define("DB_username", "root");
define("DB_password", "");
define("DB_name", "spsei_marketplace");

// Constants
define("OFFER_EXPIRATION_DAYS", 31);
define("AUCTION_BID_DELAY", 10);
define("SUGGESTIONS_PRICE_TOLERANCE", 1000);