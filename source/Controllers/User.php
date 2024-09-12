<?php

namespace Source\Controllers;

use Ramsey\Uuid\Nonstandard\Uuid;
use Source\Core\Connect;
use Source\Core\Controller;
use Source\Domain\Model\Transfers;
use Source\Domain\Model\User as ModelUser;
use Source\Models\Transfers as ModelsTransfers;
use Source\Models\User as ModelsUser;
use Source\Support\Message;
use Source\Support\Services;

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
        $message->error("id pagante não pode ser o mesmo id do beneficiário");

        if ($post["payer"] == $post["payee"]) {
            http_response_code(400);
            echo $message->json();
            die;
        }

        $message = new Message();
        $validateKeyPost = ["payer", "payee", "value"];
        $validateValuePost = [
            "payer" => function ($value) use ($message) {
                $message->error("id do pagador está inválido");
                if (!preg_match("/^\d+$/", $value)) {
                    http_response_code(400);
                    echo $message->json();
                    die;
                }
                return $value;
            },

            "payee" => function ($value) use ($message) {
                $message->error("id do beneficiário está inválido");
                if (!preg_match("/^\d+$/", $value)) {
                    http_response_code(400);
                    echo $message->json();
                    die;
                }
                return $value;
            },

            "value" => function ($value) use ($message) {
                $message->error("valor do pagamento está inválido");
                if (!preg_match("/^\d+(\.\d{1,2})?$/", $value)) {
                    http_response_code(400);
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
                http_response_code(400);
                echo $message->json();
                die;
            }
            $value = $validateValuePost[$key]($value);
        }

        $userColumns = ["id", "user_balance", "user_email"];
        $user = new ModelUser(ModelsUser::class);
        $user->setId($post["payer"]);
        $userPayerData = $user->findUserById($userColumns, "payer");

        if (empty($userPayerData)) {
            http_response_code(400);
            echo $user->message->json();
            die;
        }

        if ($userPayerData->user_balance < $post["value"]) {
            http_response_code(400);
            echo json_encode(["error" => "usuário pagante não possui saldo suficiente na conta"]);
            die;
        }

        $user = new ModelUser(ModelsUser::class);
        $user->setId($post["payee"]);
        $userPayeeData = $user->findUserById($userColumns, "payee");

        if (empty($userPayeeData)) {
            http_response_code(400);
            echo $user->message->json();
            die;
        }

        $services = new Services();
        $response = $services->consultAuthorizationService($userPayerData->id);

        if (empty($response)) {
            http_response_code(400);
            echo $services->message->json();
            die;
        }

        $services = new Services();
        $response = $services->consultAuthorizationService($userPayeeData->id);

        if (empty($response)) {
            http_response_code(400);
            echo $services->message->json();
            die;
        }

        Connect::getInstance()->beginTransaction();
        $transfers = new Transfers(ModelsTransfers::class);
        $response = $transfers->persistData([
            "uuid" => Uuid::uuid4(),
            "id_from_transfer" => $userPayerData->id,
            "id_to_transfer" => $userPayeeData->id,
            "transfer_value" => $post["value"],
            "created_at" => date("Y-m-d")
        ]);

        if (!$response) {
            Connect::getInstance()->rollBack();
            http_response_code(400);
            echo $transfers->message->json();
            die;
        }

        $userPayeeData->setRequiredFields(["user_balance"]);
        $userPayeeData->user_balance += $post["value"];
        if (!$userPayeeData->save()) {
            Connect::getInstance()->rollBack();
            http_response_code(400);
            echo json_encode(["error" => "erro ao atualizar o saldo do beneficiário"]);
            die;
        }

        $userPayerData->setRequiredFields(["user_balance"]);
        $userPayerData->user_balance -= $post["value"];
        if (!$userPayerData->save()) {
            Connect::getInstance()->rollBack();
            http_response_code(400);
            echo json_encode(["error" => "não foi possível atualizar o saldo do usuário pagante"]);
            die;
        }

        $services = new Services();
        $response = $services->notifyReceiptPayment($userPayeeData->user_email);

        if (empty($response)) {
            Connect::getInstance()->rollBack();
            http_response_code(400);
            echo $services->message->json();
            die;
        }

        Connect::getInstance()->commit();
        echo json_encode(["success" => true, "response" => "transferência realizada com sucesso"]);
    }

    public function error(array $data)
    {
        $httpResponse = [
            300 => "Multiple Choices",
            301 => "Moved Permanently",
            302 => "Found",
            303 => "See Other",
            304 => "Not Modified",
            305 => "Use Proxy",
            306 => "Proxy Switch",
            400 => "Bad Request",
            401 => "Unauthorized",
            402 => "Payment Required",
            403 => "Forbidden",
            404 => "Not Found",
            405 => "Method Not Allowed",
            406 => "Not Acceptable",
            407 => "Proxy Authentication Required",
            408 => "Request Time-out",
            409 => "Conflict",
            410 => "Gone",
            411 => "Length Required",
            412 => "Precondition Failed",
            413 => "Request Entity Too Large",
            414 => "Request-URL Too Large",
            415 => "Unsupported Media Type",
            416 => "Request Range Not Satisfiable",
            417 => "Expectation Failed",
            500 => "Internal Server Error",
            501 => "Not Implemented",
            502 => "Bad Gateway",
            503 => "Service Unavailable",
            504 => "Gateway Time-out",
            505 => "HTTP Version Not Supported"
        ];

        header("Content-Type: application/json");
        http_response_code($data["error_code"]);
        echo json_encode(["status" => $data["error_code"], "response" => $httpResponse[$data["error_code"]]]);
    }
}
