<?php

namespace Waxwink\Accounting\Contracts;

interface HasAccount
{
    public function getAccountId(): int;
}