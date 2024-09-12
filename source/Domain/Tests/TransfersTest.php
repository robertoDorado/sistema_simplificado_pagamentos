<?php
namespace Source\Domain\Tests;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Nonstandard\Uuid;
use Source\Domain\Model\Transfers as ModelTransfers;
use Source\Models\Transfers;

/**
 * TransfersTest Domain\Tests
 * @link 
 * @author Roberto Dorado <robertodorado7@gmail.com>
 * @package Source\Domain\Tests
 */
class TransfersTest extends TestCase
{
    public function testPersistData()
    {
        $model = Transfers::class;
        $domainModel = new ModelTransfers($model);
        $response = $domainModel->persistData([
            "uuid" => Uuid::uuid4(),
            "id_from_transfer" => 2,
            "id_to_transfer" => 1,
            "transfer_value" => 100,
            "created_at" => date("Y-m-d")
        ]);

        $this->assertTrue($response);
        $model = new $model();
        $transferData = $model->findById($domainModel->id);
        $transferData->destroy();
    }
}
