<?php

namespace App\Domain\Porte;

use App\Domain\Common\Type\Id;
use App\Domain\Enveloppe\Entity\Paroi;
use App\Domain\Enveloppe\Enveloppe;
use App\Domain\Lnc\Lnc;
use App\Domain\Porte\Enum\Mitoyennete;
use App\Domain\Porte\Service\MoteurPerformance;
use App\Domain\Porte\ValueObject\{Caracteristique, Performance, Position};
use Webmozart\Assert\Assert;

final class Porte implements Paroi
{
    private ?Performance $performance = null;

    public function __construct(
        private readonly Id $id,
        private readonly Enveloppe $enveloppe,
        private string $description,
        private Position $position,
        private Caracteristique $caracteristique,
    ) {}

    public static function create(
        Id $id,
        Enveloppe $enveloppe,
        string $description,
        Position $position,
        Caracteristique $caracteristique,
    ): self {
        Assert::nullOrGreaterThanEq($caracteristique->annee_installation, $enveloppe->annee_construction_batiment());

        return new self(
            id: $id,
            enveloppe: $enveloppe,
            description: $description,
            position: $position,
            caracteristique: $caracteristique,
        );
    }

    public function controle(): void
    {
        Assert::nullOrGreaterThanEq($this->caracteristique->annee_installation, $this->enveloppe->annee_construction_batiment());
    }

    public function reinitialise(): void
    {
        $this->performance = null;
    }

    public function calcule_performance(MoteurPerformance $moteur): self
    {
        $this->performance = $moteur->calcule_performance($this);
        return $this;
    }

    public function id(): Id
    {
        return $this->id;
    }

    public function enveloppe(): Enveloppe
    {
        return $this->enveloppe;
    }

    public function paroi(): ?Paroi
    {
        return $this->position->paroi_id ? $this->enveloppe->parois()->get(id: $this->position->paroi_id) : null;
    }

    public function local_non_chauffe(): ?Lnc
    {
        if ($this->paroi()) {
            return $this->paroi()->local_non_chauffe();
        }
        return $this->position->local_non_chauffe_id
            ? $this->enveloppe->locaux_non_chauffes()->find(id: $this->position->local_non_chauffe_id)
            : null;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function position(): Position
    {
        return $this->position;
    }

    public function mitoyennete(): Mitoyennete
    {
        return $this->paroi()?->mitoyennete() ? Mitoyennete::from($this->paroi()->mitoyennete()->value) : $this->position->mitoyennete;
    }

    public function orientation(): ?float
    {
        return $this->paroi()?->orientation() ?? $this->position->orientation;
    }

    public function caracteristique(): Caracteristique
    {
        return $this->caracteristique;
    }

    public function performance(): ?Performance
    {
        return $this->performance;
    }

    public function surface_deperditive(): float
    {
        return $this->mitoyennete() === Mitoyennete::LOCAL_RESIDENTIEL ? 0 : $this->caracteristique->surface;
    }

    public function est_isole(): bool
    {
        return $this->caracteristique->isolation->est_isole();
    }

    public function b(): ?float
    {
        return $this->performance?->b;
    }
}
