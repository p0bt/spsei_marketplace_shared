<?php
namespace SpseiMarketplace\Controllers;

use SpseiMarketplace\Core\Validator;
use SpseiMarketplace\Core\HelperFunctions;
use SpseiMarketplace\Core\Mail;
use SpseiMarketplace\Models\Token;
use SpseiMarketplace\Models\User;

class AuthController extends BaseController
{
    public const hashing_algorithm = PASSWORD_BCRYPT;
    private $validator;
    private $user_model;
    private $token_model;

    public function __construct()
    {
        $this->validator = new Validator();
        $this->user_model = new User();
        $this->token_model = new Token();
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
                    'password' => password_hash($_POST['password'], AuthController::hashing_algorithm),
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

    public function reset_password()
    {
        $data['show_new_password_form'] = false;

        if (isset($_POST['email']) && !empty($_POST['email']))
        {
            $this->validator->addMultipleRules([
                'email' => 'required|max_length[100]|is_not_unique[users.email]|is_valid_email',
            ]);
            if ($this->validator->run())
            {
                $email = trim($_POST['email']);

                // Generate token of length 30*2 = 60 characters
                $generated_token = bin2hex(openssl_random_pseudo_bytes(30));

                // Send email with instructions for reseting password
                $mail = new Mail();
                $mail->setReceiver($email);
                $mail->setSubject("Obnovení hesla" . " - " . SITE_TITLE);
                $mail->setMessage("Pro obnovení hesla na stránkách " . SITE_TITLE . " klikněte <a href='" . SITE_URL . "/obnovit-heslo?token=".$generated_token."'>zde.</a> Platnost odkazu vyprší za tento počet hodin - " . RESET_PASSWORD_TOKEN_EXPIRATION);
                //$mail->send();

                $this->token_model->post([
                    "token_id" => $generated_token,
                    "user_id" => $this->user_model->get_by_email($email)['user_id'],
                    // Token expiration date is from now after "RESET_PASSWORD_TOKEN_EXPIRATION" hours
                    "expiration_date" => date("Y-m-d H:i:s", time() + (RESET_PASSWORD_TOKEN_EXPIRATION * 3600)),
                ]);

                HelperFunctions::setAlert("success", "Pokyny pro resetování hesla byly odeslány na uvedený email");
            }
            else
            {
                HelperFunctions::setAlert("error", "Neplatné údaje");
            }
        }

        if (isset($_GET['token']) && !empty($_GET['token']))
        {
            if($this->token_model->is_valid($_GET['token']))
            {
                if(isset($_POST['new_password']) && !empty($_POST['new_password']))
                {
                    $target_user_id = $this->token_model->get_by_id($_GET['token'])['user_id'];

                    $this->validator->addMultipleRules([
                        'new_password' => 'required|min_length[8]|max_length[255]',
                        'new_cpassword' => 'required|min_length[8]|max_length[255]|matches[new_password]',
                    ]);
                    if ($this->validator->run())
                    {
                        $password = password_hash($_POST['new_password'], AuthController::hashing_algorithm);
                        // Update user's password with a new one
                        $this->user_model->update_password($password, $target_user_id);

                        // Delete used token
                        $this->token_model->delete_by_id($_GET['token']);

                        HelperFunctions::setAlert("success-password", "Heslo bylo změněno. <a href='/prihlaseni'> Přihlásit se</a>");
                    }
                    else
                    {
                        HelperFunctions::setAlert("error-password", "Zkontrolujte chyby a zkuste to znovu");
                    }
                }
                $data['show_new_password_form'] = true;
            }
        }

        $data['validator'] = $this->validator;
        
        $this->render("views/templates/header.php");
        $this->render("views/auth/reset_password.php", $data);
        $this->render("views/templates/footer.php");
    }
}