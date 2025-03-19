<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ManageOrganizationDTO {

    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 10, max: 10)]
        public readonly string $inn,

        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public readonly string $name,

        #[Assert\NotBlank]
        #[Assert\Length(min: 9, max: 9)]
        public readonly string $kpp,

        #[Assert\NotBlank]
        #[Assert\Length(min: 13, max: 13)]
        public readonly string $ogrn,

        #[Assert\NotBlank]
        #[Assert\Length(min: 3)]
        public readonly string $address,
    ) {
    }

    public function getInn(): string
    {
        return $this->inn;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getKpp(): string
    {
        return $this->kpp;
    }

    public function getOgrn(): string
    {
        return $this->ogrn;
    }

    public function getAddress(): string
    {
        return $this->address;
    }
}