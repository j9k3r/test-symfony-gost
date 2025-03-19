<?php

namespace App\Manager;

use App\DTO\ManageOrganizationDTO;
use App\Entity\Organization;
use App\Exception\BadRequestException;
use App\Exception\ConflictException;
use App\Exception\NoContentException;
use App\Exception\NotFoundException;
use App\Repository\OrganizationRepository;
use App\ApiResource\DadataApiService;
use Doctrine\ORM\EntityManagerInterface;

class OrganizationManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly OrganizationRepository $organizationRepository,
        private readonly DadataApiService $dataApiService
    ) {
    }

    public function findOrganizationByInn(string $inn): ?Organization
    {
        $organization = $this->organizationRepository->findOneBy(['inn' => $inn]);
        if (!$organization) {
            throw new NotFoundException("Организация с ИНН {$inn} не найдена");
        }

        return $organization;
    }
    public function findOrganizationServiceByInn(string $inn): ?array
    {
        $organizationData = $this->dataApiService->findOrganizationByInn($inn);

        if (!isset($organizationData['data'])) {
            throw new BadRequestException('Организация не найдена');
        }

        return $organizationData;
    }

    public function createOrganizationFromDTO(ManageOrganizationDTO $dto): ?int
    {
        $existingOrganization = $this->organizationRepository->findOneBy(['inn' => $dto->getInn()]);

        if ($existingOrganization) {
            throw new ConflictException('Организация уже существует');
        }

        $organization = new Organization();
        $organization->setName($dto->getName());
        $organization->setInn($dto->getInn());
        $organization->setKpp($dto->getKpp());
        $organization->setOgrn($dto->getOgrn());
        $organization->setAddress($dto->getAddress());

        $this->entityManager->persist($organization);
        $this->entityManager->flush();

        return $organization->getId();
    }

    /**
     * @return array[]
     */
    public function getOrganizations(int $page, int $perPage): array
    {
        /** @var OrganizationRepository $organzationRepository */
        $organzationRepository = $this->entityManager->getRepository(Organization::class);

        $organizations = $organzationRepository->getOrganizations($page, $perPage);
        $totalRecords = $organzationRepository->countOrganizations();

        if ($totalRecords === 0) {
            throw new NoContentException('Нет организаций');
        }

        $totalPages = $this->calculateTotalPages($totalRecords, $perPage);

        return [
            'organizations' => $organizations,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total_pages' => $totalPages,
                'total_records' => $totalRecords,
            ],
        ];
    }

    public function calculateTotalPages(int $totalRecords, int $perPage): int
    {
        return ceil($totalRecords / $perPage);
    }
}