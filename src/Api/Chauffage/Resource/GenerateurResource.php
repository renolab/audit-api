<?php

namespace App\Api\Chauffage\Resource;

use App\Domain\Common\Type\Id;
use App\Domain\Chauffage\Entity\{Generateur as Entity, GenerateurCollection as EntityCollection};
use App\Domain\Chauffage\Enum\{CategorieGenerateur, EnergieGenerateur, TypeGenerateur};
use App\Domain\Chauffage\ValueObject\{Performance, Perte, Signaletique};

final class GenerateurResource
{
    public function __construct(
        public readonly Id $id,
        public readonly ?Id $generateur_mixte_id,
        public readonly ?Id $reseau_chaleur_id,
        public readonly string $description,
        public readonly Signaletique $signaletique,
        public readonly ?int $annee_installation,
        public readonly ?Performance $performance,
        /** @var Perte[] */
        public readonly array $pertes_generation,
    ) {}

    public static function from(Entity $entity): self
    {
        return new self(
            id: $entity->id(),
            generateur_mixte_id: $entity->generateur_mixte_id(),
            reseau_chaleur_id: $entity->reseau_chaleur_id(),
            description: $entity->description(),
            signaletique: $entity->signaletique(),
            annee_installation: $entity->annee_installation(),
            performance: $entity->performance(),
            pertes_generation: $entity->pertes_generation()?->values() ?? [],
        );
    }

    /** @return self[] */
    public static function from_collection(EntityCollection $collection): array
    {
        return $collection->map(fn(Entity $entity) => self::from($entity))->values();
    }
}
