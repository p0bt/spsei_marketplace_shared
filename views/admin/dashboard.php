<?php
use SpseiMarketplace\Core\HelperFunctions;
?>

<div class="container-fluid">
    <?php if ($alert = HelperFunctions::getAlert("success")) : ?>
        <div class="row mt-3">
            <div class="col-12">
                <div class="alert alert-success">
                    <?= $alert ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="row my-2">
        <div class="col-12 d-flex justify-content-between">
            <h3>Přehled</h3>
            <button class="btn btn-primary text-uppercase" onclick="window.location.reload();"><i class="fa-solid fa-arrows-rotate mr-2"></i>obnovit</button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-xl-3 mb-4">
            <div class="card shadow border-start-primary py-2">
                <div class="card-body">
                    <div class="row align-items-center no-gutters">
                        <div class="col me-2">
                            <div class="text-uppercase text-primary fw-bold text-xs mb-1"><span>Zveřejněných nabídek</span></div>
                            <div class="text-dark fw-bold h5 mb-0"><span><?= $cards['offer_count'] ?> (Z toho aukcí - <?= $cards['auction_count'] ?>)</span></div>
                        </div>
                        <div class="col-auto"><i class="fa-solid fa-book fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3 mb-4">
            <div class="card shadow border-start-success py-2">
                <div class="card-body">
                    <div class="row align-items-center no-gutters">
                        <div class="col me-2">
                            <div class="text-uppercase text-success fw-bold text-xs mb-1"><span>Zaregistrováných uživatelů</span></div>
                            <div class="text-dark fw-bold h5 mb-0">
                                <span><?= $cards['user_count'] ?></span>
                            </div>
                        </div>
                        <div class="col-auto"><i class="fa-solid fa-users fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3 mb-4">
            <div class="card shadow border-start-success py-2">
                <div class="card-body">
                    <div class="row align-items-center no-gutters">
                        <div class="col me-2">
                            <div class="text-uppercase text-danger fw-bold text-xs mb-1"><span>Zablokovaných IP adres</span></div>
                            <div class="text-dark fw-bold h5 mb-0">
                                <span><?= $cards['banned_ip_count'] ?></span>
                            </div>
                        </div>
                        <div class="col-auto"><i class="fa-solid fa-ban fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-3 mb-4">
            <div class="card shadow border-start-warning py-2">
                <div class="card-body">
                    <div class="row align-items-center no-gutters">
                        <div class="col me-2">
                            <div class="text-uppercase text-black fw-bold text-xs mb-1"><span>Nastaveno umístění tříd</span></div>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" style="width: <?= $cards['class_room_percentage'] ?>%" aria-valuenow="<?= $cards['class_room_percentage'] ?>" aria-valuemin="0" aria-valuemax="100">
                                    <small class="justify-content-center d-flex position-absolute w-100"><?= $cards['class_room_percentage'] ?>%</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto"><i class="fas fa-comments fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-xl-7">
            <div class="card shadow mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="text-primary fw-bold m-0">Zveřejněné nabídky</h6>
                </div>
                <div class="card-body">
                    <div>
                        <canvas id="offers-by-date-chart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-xl-5">
            <div class="card shadow mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="text-primary fw-bold m-0">Učebnic / Sešitů (ostatní)</h6>
                    <div class="dropdown no-arrow"><button class="btn btn-link btn-sm dropdown-toggle" aria-expanded="false" data-bs-toggle="dropdown" type="button"><i class="fas fa-ellipsis-v text-gray-400"></i></button>
                        <div class="dropdown-menu shadow dropdown-menu-end animated--fade-in">
                            <p class="text-center dropdown-header">dropdown header:</p><a class="dropdown-item" href="#">&nbsp;Action</a><a class="dropdown-item" href="#">&nbsp;Another action</a>
                            <div class="dropdown-divider"></div><a class="dropdown-item" href="#">&nbsp;Something else here</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <canvas id="offers-type-count-chart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="text-primary fw-bold m-0">Běžící procesy na databázi</h6>
                </div>
                <div class="card-body">
                    <table class="table table-striped" id="process-list-table">
                        <thead>
                            <th>ID procesu</th>
                            <th>Čas</th>
                            <th>Dotaz</th>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="text-primary fw-bold m-0">Nové nabídky</h6>
                </div>
                <ul class="list-group list-group-flush">
                    <?php foreach($widgets['last_offers'] as $offer): ?>
                        <?php
                            // Get first image from user image directory
                            if(is_dir(SITE_PATH.'/uploads/'.$offer['image_path']))
                            {
                                $images = array_values(array_diff(scandir(SITE_PATH.'/uploads/'.$offer['image_path']), ['.', '..']));
                                $thumbnail = '/uploads/'.$offer['image_path'].'/'.$images[0];
                            }
                            else
                                $thumbnail = '/assets/images/no_image.png';
                            
                            // Show book name and it's author or just name in case of notebooks
                            if(isset($offer['b_name']) && !empty($offer['b_name']))
                            {
                                $name = $offer['b_name'].' ('.$offer['b_author'].')';
                            }
                            else
                                $name = $offer['name'];
                        ?>
                        <li class="list-group-item">
                            <div class="row align-items-center no-gutters">
                                <div class="col-auto">
                                    <img src="<?= $thumbnail ?>" alt="<?= $name ?>" height="100">
                                </div>
                                <div class="col mx-2">
                                    <h6 class="mb-0">
                                        <b><?= $name ?></b>
                                    </h6>
                                    <span class="text-xs">
                                        <?= $offer['date'] ?>
                                    </span>
                                </div>
                                <div class="col-auto">
                                    <div class="form-check"><input class="form-check-input" type="checkbox" id="formCheck-1"><label class="form-check-label" for="formCheck-1"></label></div>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="text-primary fw-bold m-0">Nové oznámení</h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="target">Cíl</label>
                                <select name="target" id="target" class="form-control">
                                    <option value="*" selected>-- Všem uživatelům --</option>
                                    <?php foreach($widgets['users'] as $user): ?>
                                        <option value="<?= $user['user_id'] ?>"><?= $user['first_name'] ?> <?= $user['last_name'] ?> (ID: <?= $user['user_id'] ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="content">Obsah</label>
                                <textarea name="content" id="content" class="form-control" minlength="3" maxlength="255"></textarea>
                            </div>
                            <button type="submit" class="btn btn-success text-uppercase">Odeslat</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
</div>

<script>
    $(document).ready(function() {
        const offers_by_date_chart = new Chart($("#offers-by-date-chart"), {
            type: 'line',
            responsive: true,
            data: {
                labels: [
                    <?php foreach($charts['offers_by_date'] as $offer): ?>
                        '<?= $offer['date'] ?>',
                    <?php endforeach; ?>
                ],
                datasets: [{
                    label: 'Počet zveřejněných nabídek',
                    data: [
                        <?php foreach($charts['offers_by_date'] as $offer): ?>
                            <?= $offer['count'] ?>,
                        <?php endforeach; ?>
                    ],
                    backgroundColor: [
                        'rgba(227, 216, 9, 0.2)',
                    ],
                    borderColor: [
                        'rgba(227, 216, 9, 1)',
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                    }
                }
            }
        });

        const offers_type_count_chart = new Chart($("#offers-type-count-chart"), {
            type: 'pie',
            responsive: true,
            data: {
                labels: ["Knihy", "Sešity (ostatní)"],
                datasets: [{
                    label: 'Počet zveřejněných nabídek',
                    data: [<?= $charts['offer_book_count'] ?>, <?= $charts['offer_other_count'] ?>],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(0, 98, 255, 0.2)',
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(0, 98, 255, 1)',
                    ],
                    borderWidth: 2
                }]
            },
        });

        let dataTable = $("#process-list-table").DataTable({
            ajax: {
                 url: "/ajax/process-list",
                 dataSrc: "",
            },
            pageLength: 100,
            responsive: true,
            bPaginate: false,
            bInfo: false,
            order: [[1, "desc"]],
            columns: [
                {
                    data: "Id",
                },
                {
                    data: "Time",
                },
                {
                    data: "Info",
                },
            ]
        });

        setInterval(function() {
            dataTable.ajax.reload(null, false);
        }, 1000);
    });
</script>