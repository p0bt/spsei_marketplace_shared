        </div>
    </div><a class="border rounded d-inline scroll-to-top" href="#" onclick="window.scrollTo({ top: 0, behavior: 'smooth' });"><i class="fas fa-angle-up"></i></a>
</div>
<footer class="text-white text-center" id="admin-footer">
    <div class="container">
        <div class="row py-5">
            <div class="col-md-6 col-12 mb-5 my-md-0">
                <a href="/domu">
                    <img src="/assets/images/logo.png" height="auto" width="200">
                </a>
                <div class="my-1 d-flex justify-content-center">
                    <p class="mr-3">Tmavý režim</p>
                    <button type="button" class="btn btn-dark btn-outline-light" id="btn-dark-mode"></button>
                </div>
            </div>
            <div class="col-md-6 col-12 mb-5 my-md-0">
                <h4><a href="/admin/dashboard" class="text-white text-decoration-none">Administrátorské rozhraní</a></h4>
                <p>
                    &copy; <?= date('Y', time()) ?> Copyright: <?= SITE_TITLE ?>
                </p>
            </div>
        </div>
    </div>
</footer>
</main>

<!-- JS -->
<script src="/assets/js/fa-all.min.js"></script>
<script src="/assets/js/chart.min.js"></script>
<script src="/assets/js/dashboard-script.min.js"></script>
<script src="/assets/js/datatables.min.js"></script>
<script src="/assets/js/natural.js"></script>

<script>
    // Prevent form resubmission (temporary solution)
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }

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

    // Default DataTables settings applied to all DataTable instances
    $.extend(true, $.fn.dataTable.defaults, {
        language: {
            url: "/assets/language/datatables/czech.json"
        },
        lengthMenu: [[10, 50, 100, -1], [10, 50, 100, "Vše"]],
        pageLength: 50,
        colReorder: true,
        responsive: true,
        dom: 'Bfrtip',
        buttons: {
            dom: {
                button: {
                    className: 'my-3'
                }
            },
            buttons: [
                {
                    extend: 'copyHtml5', 
                    className: 'btn btn-primary' 
                },
                {
                    extend: 'excelHtml5', 
                    className: 'btn btn-primary' 
                },
                {
                    extend: 'csvHtml5', 
                    className: 'btn btn-primary' 
                },
                {
                    extend: 'pdfHtml5', 
                    className: 'btn btn-primary' 
                },
            ],
        },
        columnDefs: [{
            targets: '_all',
            defaultContent: '<i class="fa-solid fa-xmark small text-danger"></i>',
            className: "align-middle text-center",
        }],
    });
</script>
</body>

</html>