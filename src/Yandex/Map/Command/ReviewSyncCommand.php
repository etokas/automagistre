<?php

declare(strict_types=1);

namespace App\Yandex\Map\Command;

use App\Shared\Doctrine\Registry;
use App\Tenant\Tenant;
use App\Yandex\Map\Entity\Review;
use function ceil;
use function explode;
use function str_starts_with;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class ReviewSyncCommand extends Command
{
    private const PAGE_SIZE = 5;
    private const URL = 'https://yandex.ru/maps/api/business/fetchReviews';

    protected static $defaultName = 'yandex:map:review:sync';

    private Registry $registry;

    private HttpClientInterface $httpClient;

    private Tenant $tenant;

    public function __construct(Registry $registry, HttpClientInterface $httpClient, Tenant $tenant)
    {
        parent::__construct();

        $this->registry = $registry;
        $this->httpClient = $httpClient;
        $this->tenant = $tenant;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (null === $this->tenant->toYandexMapBusinessId()) {
            return 0;
        }

        [$yandexUid, $csrfToken] = $this->preFlight();
        $page = $this->lastPage();

        $response = $this->request($yandexUid, $csrfToken, $page);

        while (null !== $response) {
            $data = $response->toArray()['data'];

            foreach ($data['reviews'] ?? [] as $review) {
                $this->handleReview($review);
            }

            if ($data['params']['totalPages'] > $page) {
                ++$page;

                $response = $this->request($yandexUid, $csrfToken, $page);

                continue;
            }

            $response = null;
        }

        return 0;
    }

    private function handleReview(array $payload): void
    {
        $reviewId = $payload['reviewId'];

        $conn = $this->registry->connection();
        $exists = $conn->fetchOne('SELECT 1 FROM yandex_map_review WHERE review_id = :reviewId', [
            'reviewId' => $reviewId,
        ]);
        if (1 === $exists) {
            return;
        }

        $em = $this->registry->manager();
        $em->persist(
            Review::create(
                $reviewId,
                $payload,
            )
        );
        $em->flush();
    }

    private function request(string $yandexUid, string $csrfToken, int $page): ResponseInterface
    {
        return $this->httpClient->request('POST', self::URL, [
            'headers' => [
                'Cookie' => [
                    $yandexUid,
                ],
            ],
            'query' => [
                'businessId' => $this->tenant->toYandexMapBusinessId(),
                'csrfToken' => $csrfToken,
                'page' => $page,
                'pageSize' => self::PAGE_SIZE,
                'ranking' => 'by_time',
            ],
        ]);
    }

    private function lastPage(): int
    {
        $conn = $this->registry->connection();

        $count = $conn->fetchOne('SELECT COUNT(*) FROM yandex_map_review');
        $count = false === $count ? 0 : $count;

        $page = (int) ceil($count / self::PAGE_SIZE);

        return 0 === $page ? 1 : $page;
    }

    private function preFlight(): array
    {
        $response = $this->httpClient->request('GET', self::URL);

        $yandexuid = '';
        foreach ($response->getHeaders() as $header => $value) {
            if ('set-cookie' !== $header) {
                continue;
            }

            foreach ($value as $item) {
                if (!str_starts_with($item, 'yandexuid')) {
                    continue;
                }

                $yandexuid = explode(';', $item)[0];

                break;
            }
        }

        $csrfToken = $response->toArray()['csrfToken'];

        return [$yandexuid, $csrfToken];
    }
}
