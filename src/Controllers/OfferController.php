<?php
namespace SpseiMarketplace\Controllers;

use Exception;
use SpseiMarketplace\Core\Validator;
use SpseiMarketplace\Core\HelperFunctions;
use SpseiMarketplace\Core\Pagination;
use SpseiMarketplace\Models\Offer;
use SpseiMarketplace\Models\Book;
use SpseiMarketplace\Models\Auction;
use SpseiMarketplace\Models\Category;
use SpseiMarketplace\Models\Major;
use SpseiMarketplace\Models\Notebook;

class OfferController extends BaseController
{
    private $validator;
    private $offers_model;
    private $books_model;
    private $category_model;
    private $majors_model;
    private $notebooks_model;

    public function __construct()
    {
        $this->validator = new Validator();
        $this->offers_model = new Offer();
        $this->auctions_model = new Auction();
        $this->books_model = new Book();
        $this->category_model = new Category();
        $this->majors_model = new Major();
        $this->notebooks_model = new Notebook();
    }

    public function offers()
    {
        $this->offers_model->read_filters();

        $data['allowed_display'] = [
            "list",
            "grid",
        ];

        $data['majors'] = $this->majors_model->get_all();

        $data['categories'] = $this->category_model->get_all();
    
        // Get max offer price for price slider
        $data['max_price'] = ($e = $this->offers_model->get_most_expensive()) ? $e : 0;
        
        // Pagination (5 offers per page)
        $data['pagination'] = new Pagination($this->offers_model->get_count(), "nabidky", "p");
        $data['pagination']->set_items_per_page(5);

        $this->offers_model->set_limit($data['pagination']->get_limit_a(), $data['pagination']->get_limit_b());
        $data['offers'] = $this->offers_model->get_all("o.date", "DESC");

        $data['auction_count'] = ($c = $this->auctions_model->get_count()) ? $c : 0;

        $this->render("views/templates/header.php");
        $this->render("views/offers/offers.php", $data);
        $this->render("views/templates/footer.php");
    }

    public function post_offer()
    {
        if ($_POST)
        {
            $this->validator->addMultipleRules([
                'category' => 'required|is_not_unique[categories.category_id]',
                'price_type' => 'required|in_list[pevna, aukce]',
                'description' => 'required|min_length[3]|max_length[100]',
                'photo*' => 'is_image|max_size[1024]|min_count[1]|max_count[4]'
            ]);
            try {
                if ($this->validator->run())
                {
                    $price = null;

                    switch(intval($_POST['category'])) 
                    {
                        case 1:
                        case 2:
                        case 3:
                            $this->validator->addMultipleRules([
                                'name' => 'required|is_not_unique[books.book_ISBN]',
                            ]);
                            break;

                        case 4:
                            $this->validator->addMultipleRules([
                                'name' => 'required|min_length[3]|max_length[50]',
                                // Grade 0 = All grades
                                'grade' => 'required|in_list[0,1,2,3,4]',
                                'major' => 'required|is_not_unique[majors.major_id]',
                            ]);
                            break;
                    }

                    switch($_POST['price_type']) 
                    {
                        case 'pevna':
                            $this->validator->addMultipleRules([
                                'price' => 'required|is_number|less_than['.(MAX_OFFER_PRICE+1).']',
                            ]);
                            $price = $_POST['price'];
                            break;
                        case 'aukce':
                            $this->validator->addMultipleRules([
                                'price' => 'permit_empty',
                                'start_date' => 'required|is_valid_datetime|datetime_greather_than['.(time() + 3600 - 1).']|datetime_less_than['.(time() + ((OFFER_EXPIRATION_DAYS - 1) * 86400) + 1).']',
                                'end_date' => 'required|is_valid_datetime|datetime_greather_than[start_date,'.(86400 - 1).']|datetime_less_than[start_date, '.(3600 + ((OFFER_EXPIRATION_DAYS - 1) * 86400) + 1).']',
                            ]);
                            break;
                    }
                    if ($this->validator->run()) 
                    {
                        $notebook_id = null;
                        $name = $_POST['name'];
                        $book_ISBN = $name;

                        if(intval($_POST['category']) == 4) {
                            $notebook_id = $this->notebooks_model->post([
                                'name' => $name,
                                'grade' => $_POST['grade'], 
                                'major_id' => $_POST['major'],
                            ]);
                            $book_ISBN = null;
                        }

                        $post_data = [
                            'user_id' => $_SESSION['user_data']['user_id'],
                            'notebook_id' => $notebook_id,
                            'book_ISBN' => $book_ISBN,
                            'description' => $_POST['description'],
                            'price' => $price,
                            'image_path' => "offer_u".$_SESSION['user_data']['user_id']."_".uniqid(),
                        ];
                        
                        $uploads_dir = SITE_PATH."/uploads";
                        mkdir($uploads_dir.'/'.$post_data['image_path']);

                        foreach($_FILES['photo']['name'] as $key => $value)
                        {
                            $extension = explode('.', $value)[1];
                            move_uploaded_file($_FILES['photo']['tmp_name'][$key], ($uploads_dir.'/'.$post_data['image_path'].'/'.$key.'.'.$extension));
                        }

                        $response = [
                            "success" => true,
                            "text" => "Nabídka byla zveřejněna",
                        ];

                        // Post new offer, and get it's offer_id
                        $offer_id = $this->offers_model->post($post_data);

                        if($_POST['price_type'] == "aukce")
                        {
                            $post_data = [
                                'offer_id' => $offer_id,
                                'start_date' => $_POST['start_date'],
                                'end_date' => $_POST['end_date'],
                            ];

                            $this->auctions_model->post($post_data);

                            unset($response);
                            $response = [
                                "success" => true,
                                "text" => "Nabídka byla zveřejněna, aukce začne ve zvolený čas",
                            ];
                        }
                    }
                }

                if(!isset($response))
                {
                    $response = [
                        "success" => false,
                        "errors" => $this->validator->getErrors(),
                    ];
                }
            } catch(Exception $e) {
                $response = [
                    "success" => false,
                    "errors" => "Něco se pokazilo, zkuste to prosím za chvíli znovu",
                ];
            }

            echo json_encode($response);
        }
    }

    public function new_offer()
    {
        $data['books'] = [
            'mandatory' => $this->books_model->get_all_by_category_value("povinne_ucebnice"),
            'recommended' => $this->books_model->get_all_by_category_value("doporucene_ucebnice"),
            'reading' => $this->books_model->get_all_by_category_value("povinna_cetba")
        ];

        $data['categories'] = $this->category_model->get_all();
        $data['majors'] = $this->majors_model->get_all();

        $this->render("views/templates/header.php");
        $this->render("views/offers/new_offer.php", $data);
        $this->render("views/templates/footer.php");
    }

    public function offer_detail()
    {
        // Handle non-existing offer
        if(isset($_GET['id']))
        {
            $data['offer'] = $this->offers_model->get_by_id($_GET['id']);
            $data['thumbnail'] = '/assets/images/no_image.png';
            $data['is_auction'] = isset($data['offer']['a_auction_id']) && !empty($data['offer']['a_auction_id']);
            
            if(is_dir(SITE_PATH.'/uploads/'.$data['offer']['image_path']))
            {
                $data['images'] = array_values(array_diff(scandir(SITE_PATH.'/uploads/'.$data['offer']['image_path']), ['.', '..']));
                $data['thumbnail'] = '/uploads/'.$data['offer']['image_path'].'/'.$data['images'][0];
            }
        }
        if(!isset($_GET['id']) || !$data['offer'])
        {
            $this->render("views/templates/header.php");
            $this->render("views/templates/errors/offer_not_found.php");
            $this->render("views/templates/footer.php");
            die;
        }
        // If email was sent
        if($_POST)
        {
            $this->validator->addMultipleRules([
                'email' => 'required|max_length[100]',
                'text' => 'required|max_length[255]',
            ]);
            if($this->validator->run())
            {
                $from = $_POST['email'];
                $to = $data['offer']['email'];
                $message = $_POST['text'];

                $subject = "Nová zpráva - " . SITE_TITLE;
                $headers = 'From: ' . $from . "\r\n" .
                'Reply-To: ' . $from . "\r\n" .
                'X-Mailer: PHP/' . phpversion();

                if(mail($to, $subject, $message, $headers)) 
                    HelperFunctions::setAlert("success", "Zpráva byla odeslána prodejci");
                else
                    HelperFunctions::setAlert("error", "Někde nastala chyba, zkuste prosím akci opakovat");
            }
        }

        $this->render("views/templates/header.php");
        $this->render("views/offers/offer_detail.php", $data);
        $this->render("views/templates/footer.php");
    }
}