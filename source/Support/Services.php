<?php

namespace Source\Support;

/**
 * Services Support
 * @link 
 * @author Roberto Dorado <robertodorado7@gmail.com>
 * @package Source\Support
 */
class Services
{
    /** @var Message */
    public Message $message;

    /**
     * Services constructor
     */
    public function __construct()
    {
        $this->message = new Message();
    }

    public function notifyReceiptPayment(string $email): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            $this->message->error("e-mail inválido");
            return false;
        }

        $data["email"] = $email;
        $data = json_encode($data);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://util.devi.tools/api/v1/notify',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        if (!empty($response)) {
            $response = json_decode($response, true);
            if ($response["status"] == "error") {
                http_response_code(400);
                $this->message->error("não foi possível enviar o e-mail de notificação");
                return false;
            }
        }

        return true;
    }

    public function consultAuthorizationService(int $param): array
    {
        if (!preg_match("/^\d+$/", $param)) {
            http_response_code(400);
            $this->message->error("parâmetro inválido");
            return [];
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://util.devi.tools/api/v2/authorize/{$param}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        if (!$response) {
            $this->message->error("erro ao consultar serviço de autorização");
            return [];
        }

        $response = json_decode($response, true);
        if (!$response["data"]["authorization"]) {
            http_response_code(400);
            $this->message->error("usuário não autorizado");
            return [];
        }

        return $response;
    }
}
