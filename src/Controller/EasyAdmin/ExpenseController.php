<?php

declare(strict_types=1);

namespace App\Controller\EasyAdmin;

use App\Entity\Tenant\Expense;
use App\Events;

/**
 * @author Konstantin Grachev <me@grachevko.ru>
 */
final class ExpenseController extends AbstractController
{
    /**
     * {@inheritdoc}
     */
    protected function persistEntity($entity): void
    {
        $model = $entity;
        \assert($model instanceof \stdClass);

        $entity = new Expense($model->name);

        parent::persistEntity($entity);

        $this->event(Events::EXPENSE_CREATED, $entity);
    }
}
