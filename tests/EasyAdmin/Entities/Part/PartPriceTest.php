<?php

declare(strict_types=1);

namespace App\Tests\EasyAdmin\Entities\Part;

use App\Fixtures\Part\GasketFixture;
use App\Tests\EasyAdminTestCase;

final class PartPriceTest extends EasyAdminTestCase
{
    /**
     * @see \App\Part\Controller\PartPriceController::newAction()
     */
    public function testNew(): void
    {
        $client = self::createClient();

        $client->request('GET', '/msk/?'.http_build_query([
            'part_id' => GasketFixture::ID,
            'action' => 'new',
            'entity' => 'PartPrice',
        ]));

        $response = $client->getResponse();

        self::assertSame(200, $response->getStatusCode());
    }
}
