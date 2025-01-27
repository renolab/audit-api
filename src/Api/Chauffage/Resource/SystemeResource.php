<?php

namespace App\Api\Chauffage\Resource;

use App\Domain\Common\Type\Id;
use App\Domain\Common\ValueObject\Consommation;
use App\Domain\Chauffage\Entity\{Systeme as Entity, SystemeCollection as EntityCollection};
use App\Domain\Chauffage\ValueObject\{Performance, Rendement, Reseau};

final class SystemeResource
{
    public function __construct(
        public readonly Id $id,
        public readonly Id $generateur_id,
        public readonly ?Reseau $reseau,
        public readonly ?float $rdim,
        public readonly ?Performance $performance,
        /** @var EmetteurResource[] */
        public readonly array $emetteurs,
        /** @var Rendement[] */
        public readonly array $rendements,
        /** @var Consommation[] */
        public readonly array $consommations,
        /** @var Consommation[] */
        public readonly array $consommations_auxiliaires,
    ) {}

    public static function from(Entity $entity): self
    {
        return new self(
            id: $entity->id(),
            generateur_id: $entity->generateur()->id(),
            reseau: $entity->reseau(),
            rdim: $entity->rdim(),
            performance: $entity->generateur()->performance(),
            emetteurs: EmetteurResource::from_collection($entity->emetteurs()),
            rendements: $entity->rendements()?->values() ?? [],
            consommations: $entity->consommations()?->values() ?? [],
            consommations_auxiliaires: $entity->consommations_auxiliaires()?->values() ?? [],
        );
    }

    /** @return self[] */
    public static function from_collection(EntityCollection $collection): array
    {
        return $collection->map(fn(Entity $entity) => self::from($entity))->values();
    }
}
