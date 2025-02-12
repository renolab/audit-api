<?php

namespace App\Api\Production;

use App\Domain\Production\Production;
use App\Domain\Production\ProductionRepository;
use App\Domain\Common\Type\Id;

final class GetProductionHandler
{
    public function __construct(private ProductionRepository $repository) {}

    public function __invoke(Id $id): ?Production
    {
        return $this->repository->find($id);
    }
}
