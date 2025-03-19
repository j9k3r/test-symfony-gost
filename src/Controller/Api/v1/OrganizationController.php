<?php

namespace App\Controller\Api\v1;

use App\DTO\InnOrganizationDTO;
use App\DTO\ManageOrganizationDTO;
use App\Manager\OrganizationManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route(path: '/api/v1/organization')]
final class OrganizationController extends AbstractController
{
    public function __construct(
        private ValidatorInterface $validator,
        private OrganizationManager $organizationManager,
        private SerializerInterface $serializer
    ) {
    }

    #[Route(path: '', name: 'v1_api_organization_create', methods: ['POST'])]
    public function createOrganizationAction(Request $request): JsonResponse
    {
        $innDto = InnOrganizationDTO::fromRequest($request);

        $errors = $this->validator->validate($innDto);

        if (count($errors) > 0) {
            throw new ValidationFailedException('', $errors);
        }

        $organizationData = $this->organizationManager->findOrganizationServiceByInn($innDto->getInn());

        $organizationDto = new ManageOrganizationDTO(
            $organizationData['data']['inn'],
            $organizationData['data']['name']['full_with_opf'],
            $organizationData['data']['kpp'],
            $organizationData['data']['ogrn'],
            $organizationData['data']['address']['unrestricted_value']
        );

        $organizationErrors = $this->validator->validate($organizationDto);

        if (count($organizationErrors) > 0) {
            throw new ValidationFailedException('', $organizationErrors);
        }

        $id = $this->organizationManager->createOrganizationFromDTO($organizationDto);

        return new JsonResponse(['success' => true, 'message' => 'Организация успешно добавлена', 'id' => $id], Response::HTTP_OK);
    }

    #[Route(path: '/{inn}', requirements: ['inn' => '\d+'], methods: ['GET'])]
    public function getOrganizationAction(string $inn): Response
    {
        $organization = $this->organizationManager->findOrganizationByInn($inn);

        $jsonContent = $this->serializer->serialize($organization, 'json');

        return new JsonResponse(['success' => true, 'organization' => json_decode($jsonContent)], Response::HTTP_OK);
    }

    #[Route(path: '', methods: ['GET'])]
    public function getOrganizationsAction(Request $request): Response
    {
        $perPage = $request->query->get('perPage');
        $page = $request->query->get('page');
        $organizations = $this->organizationManager->getOrganizations($page ?? 1, $perPage ?? 2);

        $jsonContent = $this->serializer->serialize($organizations, 'json');
        return new JsonResponse(['success' => true, 'organizations' => json_decode($jsonContent)], Response::HTTP_OK);
    }
}
