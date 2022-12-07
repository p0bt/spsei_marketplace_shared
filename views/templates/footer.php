<footer class="bg-dark text-white text-center">
    <div class="container">
        <div class="row py-5">
            <div class="col-md-6 col-12 mb-5 my-md-0">
                <img src="/assets/images/logo.png" height="auto" width="200">
            </div>
            <div class="col-md-6 col-12">
                <h3><?= SITE_TITLE ?></h3>
            </div>
        </div>
        <div class="row py-5">
            <div class="col-md-4 col-12 mb-5 my-md-0">
                <h4>Navigace</h4>
                <p><a class="text-white" href="/domu">Domů</a></p>
                <p><a class="text-white" href="/mapa">3D Mapa</a></p>
                <p><a class="text-white" href="/nabidky">Nabídky</a></p>
                <p><a class="text-white" href="/muj-ucet">Můj Účet</a></p>
                <p><a class="text-white" href="/admin">Admin</a></p>
            </div>
            <div class="col-md-4 col-12 mb-5 my-md-0">
                <h4>Kontakt</h4>
                <p><a class="text-white text-decoration-none" href="mailto:spsei-marketplace@email.cz">spsei-marketplace@email.cz</a></p>
            </div>
            <div class="col-md-4 col-12 mb-5 my-md-0">
                <h4>Nastavení</h4>
                <div class="d-inline-block justify-content-center">
                    <span class="align-middle me-1">Tmavý režim</span>
                    <button type="button" class="btn btn-dark btn-outline-light" id="btn-dark-mode"></button>
                </div>
                <div>
                    <span>Jazyk</span>
                    <div id="google-translate-element"></div>
                </div>
            </div>
        </div>
        <hr style="border: 1px solid white;">
        <div class="row py-5">
            <div class="col-12">
                <p>
                    &copy; <?= date('Y', time()) ?> Copyright: <?= SITE_TITLE ?>
                </p>
                <p class="small">
                    Made by: Peter Butora
                </p>
            </div>
        </div>
    </div>
</footer>
</main>

<!-- NOTIFICATION MODAL -->
<div class="modal fade" id="notifications-modal" tabindex="-1" aria-labelledby="notificationsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notificationsModalLabel">Oznámení</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <?php if(isset($notifications) && count($notifications) > 0): ?>
                    <table class="table b-0">
                        <thead>
                            <tr>
                                <th>Zpráva</th>
                                <th>Datum</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($notifications as $notification): ?>
                                <tr>
                                    <td><?= $notification["content"] ?></td>
                                    <td><?= date("d.m.Y H:i:s", strtotime($notification["date"])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    Nemáte žádné oznámení
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zavřít</button>
            </div>
        </div>
    </div>
</div>
<!-- Scroll to top button -->
<div id="btn-scroll-to-top">
    <i class="fa-solid fa-arrow-up-long h-100 w-100"></i>
</div>

<!-- SCRIPTS -->
<script>
    // Dark mode
    let theme = localStorage.getItem("theme") || "light";
    set_theme(theme);

    $("#btn-dark-mode").on("click", function() {
        if(theme == "dark")
            theme = "light";
        else if (theme == "light")
            theme = "dark";
            
        localStorage.setItem("theme", theme);
        set_theme(theme);
    });

    $(window).scroll(function() {
        let scrollTop = $(window).scrollTop();
        if (scrollTop > window.innerHeight/2) { 
            $("#btn-scroll-to-top").fadeIn();
        } else {
            $("#btn-scroll-to-top").fadeOut();
        }
    });

    $("#btn-scroll-to-top").on("click", function() {
        scroll_to_top();
    });

    function set_theme(theme) {
        if(theme == "dark") {
            $("html").addClass("dark-mode");
            $("#btn-dark-mode").html('<i class="fa-solid fa-sun"></i>');
        } else {
            $("html").removeClass("dark-mode");
            $("#btn-dark-mode").html('<i class="fa-solid fa-moon"></i>');
        }
    }

    function scroll_to_top() {
        window.scrollTo({ 
            top: 0, 
            behavior: 'smooth' 
        });
    }
</script>
<!-- Google Translate -->
<script type="text/javascript">
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({pageLanguage: 'en'}, 'google-translate-element');
        $("#google-translate-element select").addClass("form-select-sm bg-light");
    }

    if (localStorage.getItem("language") !== null) {
        let language = localStorage.getItem("language");
        $("#google-translate-element select").val(language);
    }

    $("#google-translate-element").on("change", function() {
        let language = $("#google-translate-element option:selected").val();
        localStorage.setItem("language", language);
    });
</script>
<!-- JS -->
<script src="/assets/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/fa-all.min.js"></script>
<script src="/assets/js/chart.min.js"></script>
<script src="/assets/js/dashboard-script.min.js"></script>
<script src="/assets/js/tilt.min.js"></script>
<script src="/assets/js/aos.js"></script>
<!-- SCRIPTS -->
<script>
    // Default settings for tilt library
    $('.js-tilt-scale').tilt({
        scale: 0.8
    });
    
    // Aos library init
    AOS.init();
</script>
</body>
</html>