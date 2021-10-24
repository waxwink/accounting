<?php

namespace Waxwink\Accounting\Contracts;

interface LockerInterface
{
    public function isLocked(int $account): bool;

    public function lock(int $account): bool;

    public function releaseLock(int $account): bool;
}