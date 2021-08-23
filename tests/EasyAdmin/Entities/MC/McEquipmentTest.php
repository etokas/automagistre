<?php

declare(strict_types=1);

namespace App\Tests\EasyAdmin\Entities\MC;

use App\Tests\EasyAdminTestCase;

final class McEquipmentTest extends EasyAdminTestCase
{
    /**
     * @see \App\MC\Controller\EquipmentController::listAction()
     */
    public function testList(): void
    {
        $client = self::createClient();

        $client->request('GET', '/msk/?'.http_build_query([
            'action' => 'list',
            'entity' => 'McEquipment',
        ]));

        $response = $client->getResponse();

        self::assertSame(200, $response->getStatusCode());
    }

    /**
     * @see \App\MC\Controller\EquipmentController::newAction()
     */
    public function testNew(): void
    {
        $client = self::createClient();

        $client->request('GET', '/msk/?'.http_build_query([
            'action' => 'new',
            'entity' => 'McEquipment',
        ]));

        $response = $client->getResponse();

        self::assertSame(200, $response->getStatusCode());
    }

    /**
     * @see \App\MC\Controller\EquipmentController::editAction()
     */
    public function testEdit(): void
    {
        $client = self::createClient();

        $client->request('GET', '/msk/?'.http_build_query([
            'action' => 'edit',
            'entity' => 'McEquipment',
        ]));

        $response = $client->getResponse();

        self::assertSame(200, $response->getStatusCode());
    }

    /**
     * @see \App\MC\Controller\EquipmentController::searchAction()
     */
    public function testSearch(): void
    {
        $client = self::createClient();

        $client->request('GET', '/msk/?'.http_build_query([
            'query' => 'bla',
            'action' => 'search',
            'entity' => 'McEquipment',
        ]));

        $response = $client->getResponse();

        self::assertSame(200, $response->getStatusCode());
    }
}
