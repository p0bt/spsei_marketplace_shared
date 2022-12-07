<div class="container">
    <div class="row my-5">
        <div class="col-12 text-white card border border-dark shadow-sm py-5 banner-gradient">
            <h1 class="ms-5">Můj účet</h1>
        </div>
    </div>
    <?php require_once("views/templates/account/tabs.php") ?>
    <div class="row">
        <?php require_once("views/templates/account/sidepanel.php") ?>
        <div id="selected-tab-preview" class="col-lg-8 col-12 p-5"></div>
    </div>
</div>

<script>
    $(document).ready(function() {
        let default_tab = "<?= (isset($_GET['t']) && !empty($_GET['t'])) ? $_GET['t'] : 'my-offers' ?>";
        load_tab(default_tab);
        $(".link-tabs").removeClass("active text-primary");
        $("a[data-link='" + default_tab + "'").addClass("active text-primary");

        $(".link-tabs").click(function() {
            $(".link-tabs").removeClass("active text-primary");
            $(this).addClass("active text-primary");
            let selected_tab = $(this).data('link');
            load_tab(selected_tab);
        });
    });

    function load_tab(tab_link) {

        // Fade loading inspired from:
        // https://stackoverflow.com/questions/9337220/jquery-load-with-fadein-effect

        $("#selected-tab-preview").empty();
        $('#selected-tab-preview').fadeOut('fast', function() {
            $("#selected-tab-preview").load(("/muj-ucet/" + tab_link + "?<?= http_build_query($_GET) ?>"), function() {
                $('#selected-tab-preview').fadeIn('fast');
            });
        });
    }

    $("#selected-tab-preview").on("click", "#btn-send-message", function() {
        let user_id = $(this).data('id');
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "/create-new-chat",
            data: {
                "user_id": user_id,
            },
            success: function(data) {
                if(data) {
                    window.location.href = data;
                }
            },
        });
    });
</script>