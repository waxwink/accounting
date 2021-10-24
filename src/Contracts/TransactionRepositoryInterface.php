<?php

namespace Waxwink\Accounting\Contracts;

interface TransactionRepositoryInterface
{
    public function transactional(callable $callback): mixed;

    public function createRecord(array $attributes): TransactionRecordInterface;

    public function balance(int $accountId, mixed $ref = null): int;

    public function findByAccount(int $accountId, array $options): \ArrayAccess;

}
