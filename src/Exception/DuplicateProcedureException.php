<?php

declare(strict_types=1);

namespace BombenProdukt\JsonRpc\Exception;

use BombenProdukt\JsonRpc\Procedure\ProcedureInterface;
use Exception;

final class DuplicateProcedureException extends Exception
{
    public function __construct(ProcedureInterface $procedure)
    {
        parent::__construct("Procedure already registered: {$procedure->getMethod()}");
    }
}
