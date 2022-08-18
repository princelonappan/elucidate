<?php

namespace App\Controller;

use App\Service\CommonService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use App\Request\InstitutionRequest;

class InstitutionController extends AbstractController
{
    /**
     * @var CommonService
     */
    private $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    /**
     * Search institutions.
     *
     * @Route("/api/institutions", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns all the institutions",
     * )
     * @OA\Parameter(
     *     name="search",
     *     in="query",
     *     required=true,
     *     description="Search Name"
     * )
     * @OA\Tag(name="Institution")
     * @Security(name="Bearer")
     */

    public function index(Request $request, InstitutionRequest $institutionsRequest): Response
    {
        $parameters['search'] = $request->query->get('search');
        $validation = $institutionsRequest->validateInstitutionData($parameters);
        if (count($validation) > 0) {
            $errorsString = (string)$validation;
            return new Response($errorsString);
        }

        $response = $this->commonService->getInstitutions($parameters['search']);
        if($response['status'] == 200) {
            if($response['count'] > 0) {
                return $this->json($response);
            } else {
                //create resource
                $data = $this->commonService->setTicketData($parameters['search']);
                $response = $this->commonService->createInstitutionTicket($data);
            }
        }

        return $this->json($response);
    }
}