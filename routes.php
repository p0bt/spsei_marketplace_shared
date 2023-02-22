<?php

/*
* ROUTES
*/

$routes = [
    "" => "Page:home",
    "/" => "Page:home",
    "/domu" => "Page:home",
    "/3d" => "Page:offers_3d",
    "/zpravy" => "Chat:index",
    "/send-message" => "Chat:send_message",
    "/send-message-contact-form" => "Chat:send_message_contact_form",
    "/create-new-chat" => "Chat:create_new_chat",
    // ADMIN
    "/admin" => "Admin:dashboard",
    "/admin/panel" => "Admin:dashboard",
    "/admin/get-auctions" => "Admin:get_auctions",
    // ADMIN MAINTENANCE
    "/admin/sprava-uzivatelu" => "Admin:user_maintenance",
    "/admin/sprava-nabidek" => "Admin:offer_maintenance",
    "/admin/sprava-aukci" => "Admin:auction_maintenance",
    "/admin/sprava-trid" => "Admin:class_maintenance",
    "/admin/sprava-knih" => "Admin:book_maintenance",
    "/admin/sprava-sesitu" => "Admin:notebook_maintenance",
    "/admin/sprava-umisteni-trid" => "Admin:cr_maintenance",
    "/admin/sprava-zablokovanych-ip" => "Admin:banned_ip_maintenance",
    "/admin/sprava-api-klicu" => "Admin:api_key_maintenance",
    // ADMIN EDIT
    "/admin/upravit-uzivatele" => "Admin:user_edit",
    "/admin/upravit-nabidku" => "Admin:offer_edit",
    "/admin/upravit-aukci" => "Admin:auction_edit",
    "/admin/upravit-umisteni-tridy" => "Admin:cr_edit",
    "/admin/upravit-tridu" => "Admin:class_edit",
    "/admin/upravit-knihu" => "Admin:book_edit",
    "/admin/upravit-sesit" => "Admin:notebook_edit",
    "/admin/upravit-api-klic" => "Admin:api_key_edit",
    "/admin/upravit-zablokovanou-ip" => "Admin:banned_ip_edit",
    // AJAX
    "/ajax/process-list" => "Ajax:process_list",
    "/ajax/send-email" => "Ajax:send_email",
    "/ajax/get-socketio-shell-output" => "Ajax:socketio_shell_output",
    "/ajax/start-socketio-server" => "Ajax:start_socketio_server",
    // CRON
    "/cron/delete-old-offers" => "Cron:delete_old_offers",
    "/cron/close-old-auctions" => "Cron:close_old_auctions",
    "/cron/delete-old-tokens" => "Cron:delete_old_tokens",
    // AUTH
    "/prihlaseni" => "Auth:login",
    "/registrace" => "Auth:register",
    "/odhlaseni" => "Auth:logout",
    "/obnovit-heslo" => "Auth:reset_password",
    // AUCTION
    "/get-auction-current-state" => "Auction:current_state",
    "/rise-auction-price" => "Auction:rise_price",
    "/can-user-bid" => "Auction:can_user_bid_ajax",
    // ACCOUNT + WISHLIST
    "/muj-ucet" => "Account:my_account",
    "/muj-ucet/my-offers" => "Account:tab_my_offers",
    "/muj-ucet/my-won-auction" => "Account:tab_my_won_auctions",
    "/muj-ucet/wishlist" => "Wishlist:tab_wishlist",
    "/add-or-delete-from-wishlist" => "Wishlist:add_or_delete",
    "/change-password" => "Account:change_password",
    // OFFERS
    "/nabidky" => "Offer:offers",
    "/nova-nabidka" => "Offer:new_offer",
    "/post-offer" => "Offer:post_offer",
    "/detail-nabidky" => "Offer:offer_detail",
    // API
    "/api/nabidky" => "Api:offers",
    "/api/tridy" => "Api:classes",
    // ERROR
    "/ip-adresa-zablokovana" => "Error:blocked_ip",
    "/polozka-neexistuje" => "Error:edit_item_doesnt_exist",
];

/*
* FILTERS
*/

$filters = [
    "is_admin" => [
        "/admin",
        "/admin/panel",
        "/admin/sprava-uzivatelu",
        "/admin/sprava-nabidek",
        "/admin/sprava-aukci",
        "/admin/sprava-trid",
        "/admin/sprava-knih",
        "/admin/sprava-umisteni-trid",
        "/admin/sprava-zablokovanych-ip",
        "/admin/sprava-api-klicu",
        // AJAX + ADMIN
        "/ajax/process-list",
        "/admin/get-auctions",
        "/ajax/get-socketio-shell-output",
        "/ajax/start-socketio-server",
    ],
    "!is_logged_in" => [
        "/prihlaseni",
        "/registrace",
    ],
    "is_logged_in" => [
        "/odhlaseni",
        "/muj-ucet",
        "/nova-nabidka",
        "/change-password" => "Account:change_password",
        // AJAX + LOGGED IN
        "/get-auction-current-state",
        "/rise-auction-price",
        "/can-user-bid",
        "/zpravy",
        "/send-message",
        "/send-message-contact-form",
        "/create-new-chat",
        "/muj-ucet/my-offers",
        "/muj-ucet/my-won-auction",
    ],
    "is_ajax_request" => [
        "/ajax",
        "/ajax/process-list",
        "/add-or-delete-from-wishlist",
        "/ajax/get-socketio-shell-output",
        "/ajax/start-socketio-server",
        // AJAX + LOGGED IN
        "/get-auction-current-state",
        "/rise-auction-price",
        "/can-user-bid",
        "/admin/get-auctions",
        "/send-message-contact-form",
        "/create-new-chat",
        "/muj-ucet/my-offers",
        "/muj-ucet/my-won-auction",
    ],
    "is_cron" => [
        "/cron/delete-old-offers",
        "/cron/close-old-auctions",
        "/cron/delete-old-tokens",
    ],
];