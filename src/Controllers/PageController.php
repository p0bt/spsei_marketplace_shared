<?php

class PageController extends BaseController
{
    public function __construct()
    {
        $this->users_model = new User();
        $this->offers_model = new Offer();
        $this->auctions_model = new Auction();
    }

    public function home()
    {
        $data["overview"] = [
            "offer_count" => $this->offers_model->get_count(),
            "user_count" => $this->users_model->get_count(),
            "running_auctions_count" => count($this->auctions_model->get_running_auctions()),
            "old_auctions_count" => count($this->auctions_model->get_old_auctions()),
        ];

        $this->render("views/templates/header.php");
        $this->render("views/pages/home.php", $data);
        $this->render("views/templates/footer.php");
    }

    public function map_3d()
    {
        $this->render("views/pages/map_3d.php");
    }
}