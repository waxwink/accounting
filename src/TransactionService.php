<?php

namespace Waxwink\Accounting;

use Waxwink\Accounting\Concerns\HasPayload;
use Waxwink\Accounting\Contracts\TransactionRepositoryInterface;
use Waxwink\Accounting\Exceptions\InvalidPayloadException;
use Waxwink\Accounting\Exceptions\TransactionFailedException;
use Waxwink\Accounting\UseCases\InsertRecords;

class TransactionService
{
    use HasPayload;

    protected bool $withLock = false;

    protected ?int $lockedAccountId = null;

    public function __construct(
        protected TransactionRepositoryInterface $transactionRepository,
        protected LockHandler $lockHandler,
        protected InsertRecords $insertRecords,
        TransactionPayload $transactionPayload
    ) {
        $this->setTransactionPayload($transactionPayload);
    }

    /**
     * @throws TransactionFailedException
     * @throws InvalidPayloadException
     */
    public function transfer(): mixed
    {
        try {
            return $this->lockHandler->handle(
                fn() => $this->transactionalInsert() ,
                $this->lockedAccountId,
                $this->withLock);
        } catch (InvalidPayloadException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            throw new TransactionFailedException($exception->getMessage());
        }
    }

    public function balance(int $accountId, mixed $ref = null): int
    {
        return $this->transactionRepository->balance($accountId, $ref);
    }
    
    public function transactionsList(int $accountId, array $options): \ArrayAccess
    {
        return $this->transactionRepository->findByAccount($accountId, $options);
    }

    public function transactionsListByRef(mixed $refId, array $options): \ArrayAccess
    {
        return $this->transactionRepository->findByRef($refId, $options);
    }
    public function withLock(int $accountId, bool $withLock = true): static
    {
        $this->withLock = $withLock;
        $this->lockedAccountId = $accountId;

        return $this;
    }

    protected function transactionalInsert(): mixed
    {
        return $this->transactionRepository->transactional(
            fn() => $this->insertRecords->setTransactionPayload($this->transactionPayload)->fire());
    }


}
