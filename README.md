# Accounting System
Core accounting services are provided in this package with some interfaces to get implemented for a full functionality.
To see some example implementation, checkout the <a href="https://github.com/waxwink/laracount">Laracount</a> package.
## Main interfaces to get implemented
### Repository interfaces
* \Waxwink\Accounting\Contracts\TransactionRecordInterface::class
* \Waxwink\Accounting\Contracts\VoucherRepositoryInterface::class
* \Waxwink\Accounting\Contracts\LockerInterface::class
### Data mapper model interfaces
* \Waxwink\Accounting\Contracts\TransactionRecordInterface::class
* \Waxwink\Accounting\Contracts\VoucherInterface::class
* \Waxwink\Accounting\Contracts\HasAccount::class