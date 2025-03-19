<?php
namespace App\DTO;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class InnOrganizationDTO {

    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 9, max: 10)]
        public readonly string $inn,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->request->get('inn') ?? $request->query->get('inn')
        );
    }

    public function getInn(): string
    {
        return $this->inn;
    }
}