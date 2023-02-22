<?php

/* ---- NOTE ----
* THIS PROJECT RUNS ON
* PHP Version 8.0.6 +
* ---------------
*/

// set Timezone
date_default_timezone_set("Europe/Prague");

// !!! Turn off displaying errors (FOR PRODUCTION ONLY) !!!
//error_reporting(0);

// Site settings
define("SITE_PATH", __DIR__);
define("SITE_TITLE", "SPŠEI BookMarket");
define("SITE_URL", "localhost");
define("SITE_IP", "127.0.0.1");

// Web sockets
define("WEBSOCKETS_PORT", 2021);
define("WEBSOCKETS_PROTOCOL", "http");

// Include path
set_include_path(SITE_PATH);

// Database credentials
define("DB_hostname", "localhost");
define("DB_username", "root");
define("DB_password", "");
define("DB_name", "spsei_marketplace");

// --- Constants ---

    // OFFERS
    define("OFFER_EXPIRATION_DAYS", 31);
    define("MAX_OFFER_PRICE", 1500);
    define("SUGGESTIONS_PRICE_TOLERANCE", 1000);

    // AUCTIONS
    define("AUCTION_BID_DELAY", 10);

    // PASSWORD
    define("RESET_PASSWORD_TOKEN_EXPIRATION", 1); // in hours