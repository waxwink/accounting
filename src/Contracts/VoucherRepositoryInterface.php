<?php

namespace Waxwink\Accounting\Contracts;

interface VoucherRepositoryInterface
{
    public function create(): VoucherInterface;

    public function exists(string $voucherId): bool;

}