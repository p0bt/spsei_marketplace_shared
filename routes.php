<?php

/*
* ROUTES
*/

$routes = [
    "" => "Page:home",
    "/" => "Page:home",
    "/domu" => "Page:home",
    "/mapa" => "Page:map_3d",
    "/admin" => "Admin:dashboard",
    "/admin/panel" => "Admin:dashboard",
    "/admin/sprava-uzivatelu" => "Admin:user_maintenance",
    "/admin/sprava-nabidek" => "Admin:offer_maintenance",
    "/admin/sprava-aukci" => "Admin:auction_maintenance",
    "/admin/sprava-trid" => "Admin:class_maintenance",
    "/admin/sprava-knih" => "Admin:book_maintenance",
    "/admin/sprava-umisteni-trid" => "Admin:cr_maintenance",
    "/admin/sprava-zablokovanych-ip" => "Admin:banned_ip_maintenance",
    "/admin/sprava-api-klicu" => "Admin:api_key_maintenance",
    "/admin/get-auctions" => "Admin:get_auctions",
    "/ajax/process-list" => "Ajax:process_list",
    "/ajax/send-email" => "Ajax:send_email",
    "/cron/delete-old-offers" => "Cron:delete_old_offers",
    "/cron/close-old-auctions" => "Cron:close_old_auctions",
    "/prihlaseni" => "Auth:login",
    "/registrace" => "Auth:register",
    "/odhlaseni" => "Auth:logout",
    "/get-auction-current-state" => "Auction:current_state",
    "/rise-auction-price" => "Auction:rise_price",
    "/can-user-bid" => "Auction:can_user_bid_ajax",
    "/muj-ucet" => "Account:my_account",
    "/oblibene" => "Wishlist:wishlist",
    "/add-or-delete-from-wishlist" => "Wishlist:add_or_delete",
    "/nabidky" => "Offer:offers",
    "/nova-nabidka" => "Offer:new_offer",
    "/post-offer" => "Offer:post_offer",
    "/detail-nabidky" => "Offer:offer_detail",
    "/api/nabidky" => "Api:offers",
    "ip-adresa-zablokovana" => "Error:blocked_ip",
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
    ],
    "!is_logged_in" => [
        "/prihlaseni",
        "/registrace",
    ],
    "is_logged_in" => [
        "/odhlaseni",
        "/muj-ucet",
        "/nova-nabidka",
        // AJAX + LOGGED IN
        "/get-auction-current-state",
        "/rise-auction-price",
        "/can-user-bid",
    ],
    "is_ajax_request" => [
        "/ajax",
        "/ajax/process-list",
        "/ajax/send-email",
        "/add-or-delete-from-wishlist",
        // AJAX + LOGGED IN
        "/get-auction-current-state",
        "/rise-auction-price",
        "/can-user-bid",
        "/admin/get-auctions",
    ],
    "is_cron" => [
        "/cron/delete-old-offers",
        "/cron/close-old-auctions",
    ],
];