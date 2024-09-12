<?php
namespace Source\Controllers;

use Source\Core\Controller;
use Source\Domain\Model\User as ModelUser;
use Source\Models\User as ModelsUser;
use Source\Support\Message;

/**
 * User Controllers
 * @link 
 * @author Roberto Dorado <robertodorado7@gmail.com>
 * @package Source\Controllers
 */
class User extends Controller
{
    /**
     * User constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function transferValue()
    {
        header("Content-Type: application/json");
        $post = json_decode(file_get_contents('php://input'), true);
        $message = new Message();
        
        $validateKeyPost = ["payer", "payee", "value"];
        $validateValuePost = [
            "payer" => function($value) use ($message) {
                $message->error("id do pagador está inválido");
                if (!preg_match("/^\d+$/", $value)) {
                    http_response_code(500);
                    echo $message->json();
                    die;
                }
                return $value;
            },

            "payee" => function($value) use ($message) {
                $message->error("id do beneficiário está inválido");
                if (!preg_match("/^\d+$/", $value)) {
                    http_response_code(500);
                    echo $message->json();
                    die;
                }
                return $value;
            },

            "value" => function($value) use ($message) {
                $message->error("valor do pagamento está inválido");
                if (!preg_match("/^\d+(\.\d{1,2})?$/", $value)) {
                    http_response_code(500);
                    echo $message->json();
                    die;
                }
                return $value;
            },
        ];

        $message = new Message();
        $message->error("post data inválido");

        foreach ($post as $key => $value) {
            if (!in_array($key, $validateKeyPost)) {
                http_response_code(500);
                echo $message->json();
                die;
            }
            $value = $validateValuePost[$key]($value);
        }
        
        $user = new ModelUser(ModelsUser::class);
        $user->setId($post["payer"]);
        $userData = $user->findUserById([]);
        echo json_encode($userData);
    }
}
