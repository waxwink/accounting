<?php

namespace Waxwink\Accounting;

use Waxwink\Accounting\Contracts\LockerInterface;
use Waxwink\Accounting\Exceptions\LockedAccountException;

class LockHandler
{
    protected int $tries = 0;

    protected int $maxTries = 2;

    public function __construct(protected LockerInterface $locker)
    {
    }

    /**
     * @throws LockedAccountException
     * @throws \Exception
     */
    public function handle(callable $callback, ?int $accountId, bool $withLock = true):mixed
    {
        $this->checkLock($accountId);

        if (!$withLock) {
            return call_user_func($callback);
        }

        return $this->handleProcessWithLock($accountId, $callback);
    }

    /**
     * @throws \Exception
     */
    protected function handleProcessWithLock(int $accountId, callable $callback): mixed
    {
        try {
            $this->locker->lock($accountId);
            $result = call_user_func($callback);
            $this->locker->releaseLock($accountId);
        } catch (\Exception $exception) {
            $this->locker->releaseLock($accountId);
            throw $exception;
        }

        return $result;
    }

    /**
     * @param int $accountId
     *
     * @throws LockedAccountException
     */
    protected function checkLock(int $accountId): void
    {
        if (!$this->locker->isLocked($accountId)) {
            return;
        }

        if ($this->tries++ > $this->maxTries) {
            throw new LockedAccountException("Account $accountId is locked.");
        }

        sleep(0.5);
        $this->checkLock($accountId);
    }

}
