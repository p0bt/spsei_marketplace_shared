<?php
namespace SpseiMarketplace\Controllers;

class ErrorController extends BaseController
{
    public function blocked_ip()
    {
        $this->render("views/templates/header.php");
        $this->render("views/templates/errors/blocked_ip.php");
        $this->render("views/templates/footer.php");
    }

    public function page_not_found()
    {
        $this->render("views/templates/header.php");
        $this->render("views/templates/errors/page_not_found.php");
        $this->render("views/templates/footer.php");
    }

    public function edit_item_doesnt_exist()
    {
        $this->render("views/templates/header.php");
        $this->render("views/templates/errors/edit_item_doesnt_exist.php");
        $this->render("views/templates/footer.php");
    }
}