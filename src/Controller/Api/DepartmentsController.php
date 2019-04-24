<?php

namespace App\Controller\Api;

use App\Repository\DepartmentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/api/departments", name="api_departments_")
 */

class DepartmentsController extends Controller
{

    /**
     * AJAX
     * @Route("/list", name="list")
     * @Method({"GET"})
     */
    public function listing(Request $request, DepartmentRepository $departments)
    {
        $search = [];
        if($request->query->get('regionId')){
            $search['region'] = $request->query->get('regionId');
        }

        $departments = $departments->findBy($search);

        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();

        $serializer = new Serializer(array($normalizer), array($encoder));

        return new Response($serializer->serialize($departments, 'json', [
            'attributes' => [
                'id',
                'name',
                'slug',
                'region' => [
                    'id',
                    'name',
                    'slug'
                ]
            ]
        ]),
        Response::HTTP_OK,
        ['Content-type' => 'application/json']);
    }


}