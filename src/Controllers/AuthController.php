<?php
namespace SpseiMarketplace\Controllers;

use SpseiMarketplace\Core\Validator;
use SpseiMarketplace\Core\HelperFunctions;
use SpseiMarketplace\Models\User;

class AuthController extends BaseController
{
    private $hashing_algorithm = PASSWORD_BCRYPT;
    private $validator;
    private $user_model;

    public function __construct()
    {
        $this->validator = new Validator();
        $this->user_model = new User();
    }

    public function login()
    {
        if ($_POST)
        {
            $this->validator->addMultipleRules([
                'email' => 'required|max_length[100]|is_not_unique[users.email]|is_valid_email',
                'password' => 'required|max_length[255]',
            ]);
            if ($this->validator->run())
            {
                $email = trim($_POST['email']);
                $password = $_POST['password'];
                $user_info = $this->user_model->get_by_email($email);

                // If user with posted email exists and posted password matches the stored user password
                if(isset($user_info) && password_verify($password, $user_info['password']))
                {
                    $_SESSION['user_data'] = $user_info;
                    header("Location: /domu");
                    die;
                }
                else
                {
                    HelperFunctions::setAlert("error", "Neplatné údaje");
                }
                unset($_POST);
            }
        }

        $this->render("views/templates/header.php");
        $this->render("views/auth/login.php");
        $this->render("views/templates/footer.php");
    }

    public function register()
    {
        if ($_POST)
        {
            $this->validator->addMultipleRules([
                'email' => 'required|max_length[100]|is_unique[users.email]|is_valid_email',
                'password' => 'required|min_length[8]|max_length[255]',
                'cpassword' => 'required|min_length[8]|max_length[255]|matches[password]',
            ]);
            if ($this->validator->run())
            {
                $post_data = [
                    'email' => trim($_POST['email']),
                    'password' => password_hash($_POST['password'], $this->hashing_algorithm),
                ];

                $success = $this->user_model->post($post_data);

                if($success)
                {
                    unset($_POST);
                    HelperFunctions::setAlert("success", "Registrace byla úspěšná. <a href='/prihlaseni'> Přihlásit se</a>");
                }
                else
                {
                    HelperFunctions::setAlert("error", "Něco se pokazilo, zkuste prosím akci opakovat");
                }

                header("Location: /registrace");
                die;
            }
        }

        $data['validator'] = $this->validator;
        
        $this->render("views/templates/header.php");
        $this->render("views/auth/register.php", $data);
        $this->render("views/templates/footer.php");
    }

    public function logout()
    {
        $this->render("views/templates/header.php");
        $this->render("views/templates/footer.php");

        unset($_SESSION['user_data']);
        unset($_SESSION['auction']);
        
        header("Location: /domu");
    }
}