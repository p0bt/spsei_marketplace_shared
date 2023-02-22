/* AUCTION */

function print_auction_info() {
    $(".auction").each(function(index, obj) {
        let start_date = new Date($(this).find(".auction-start-date").data("date"));
        let end_date = new Date($(this).find(".auction-end-date").data("date"));
        let auction_info = $(this).find(".auction-info");

        auction_info.text("");

        if(is_auction_in_progress(start_date, end_date)) {
            auction_info.text("Do konce aukce zbývá " + time_left_to_date(end_date));
        } else if (!has_auction_started(start_date)){
            auction_info.text(time_left_to_date(start_date));
        } else {
            auction_info.text("Aukce již skončila");
        }
    });
}

function print_auction_price(price) {
    $('.auction-price').text("Přihodil/a: " + ((price.length > 0) ? price : "-") + " Kč");
}

function print_auction_owner(owner) {
    $('.auction-owner').text(owner + " ");
}
// DELETE BELOW
function print_auction(auction_id) {
    let state = get_current_state(auction_id);
    if(state.first_name.length > 0 && state.last_name.length > 0)
        print_auction_owner(state.first_name + " " + state.last_name.charAt(0) + ".");
    else if(state.user_id.length > 0)
        print_auction_owner("Uživatel č. " + state.user_id);
    else
        print_auction_owner("Uživatel č. -");

    print_auction_price(state.top_bid);
    print_auction_info();
}

function print_auction_state(state) {
    if(state.first_name.length > 0 && state.last_name.length > 0)
        print_auction_owner(state.first_name + " " + state.last_name.charAt(0) + ".");
    else if(state.user_id.length > 0)
        print_auction_owner("Uživatel č. " + state.user_id);
    else
        print_auction_owner("Uživatel č. -");
    print_auction_price(state.top_bid);
}

function print_current_time() {
    let now = new Date();
    $(".current-time").text(add_leading_zero(now.getHours()) + ":" + add_leading_zero(now.getMinutes()) + ":" + add_leading_zero(now.getSeconds()));
}

function add_leading_zero(number) {
    return ('0' + number).slice(-2);
}

function time_left_to_date(date) {
    let now = new Date();
    let seconds = parseInt((date.getTime() - now.getTime()) / 1000);
    let minutes = parseInt(seconds/60);
    seconds %= 60;
    let hours = parseInt(minutes / 60);
    minutes %= 60;
    let days = parseInt(hours / 24);
    hours %= 24;
    
    return days + "d " + hours + "h " + minutes + "m " + seconds + "s";
}

function is_auction_in_progress(start_date, end_date) {
    return has_auction_started(start_date) && !has_auction_ended(end_date);
}

function has_auction_started(start_date) {
    let now = new Date();
    return start_date < now;
}

function has_auction_ended(end_date) {
    let now = new Date();
    return end_date <= now;
}

function get_current_state(auction_id) {
    let result;

    $.ajax({
        type: "POST",
        async: false,
        dataType: "json",
        url: "/get-auction-current-state",
        data: {
            "auction_id": auction_id,
        },
        success: function(data) {
            result = data;
        },
    });

    return result;
}

function rise_price(auction_id, new_price) {
    $.ajax({
        type: "POST",
        async: false,
        dataType: "json",
        url: "/rise-auction-price",
        data: {
            "auction_id": auction_id,
            "new_price": new_price,
        },
    });
}

function can_user_bid(auction_id) {
    let result;

    $.ajax({
        type: "POST",
        async: false,
        dataType: "json",
        url: "/can-user-bid",
        data: {
            "auction_id": auction_id
        },
        success: function(data) {
            result = data;
        },
    });

    console.log(auction_id);

    return result;
}

/* WISHLIST */

function add_to_wishlist() {
    let val = parseInt($(".item-count-box").text()) + 1;
    $(".item-count-box").text(val);
    $(".item-count-box-dropdown").html("<b>(" + val + ")</b>");
}

function delete_from_wishlist() {
    let val = parseInt($(".item-count-box").text()) - 1;
    $(".item-count-box").text(val);
    $(".item-count-box-dropdown").html("<b>(" + val + ")</b>");
}

/* OTHER FUNCTIONS */

function copy_to_clipboard(value) {
    navigator.clipboard.writeText(value);
}