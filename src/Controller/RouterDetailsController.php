<?php

namespace App\Controller;

use App\Repository\RouterDetailsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\RouterDetails;
use App\Repository\ApiTokenRepository;
use App\Entity\ApiToken;

class RouterDetailsController extends AbstractController
{
    private $routerDetailsRepository;
    private $apiTokenRepository;

    public function __construct(ApiTokenRepository $apiTokenRepository, RouterDetailsRepository $routerDetailsRepository)
    {
        $this->apiTokenRepository = $apiTokenRepository;
        $this->routerDetailsRepository = $routerDetailsRepository;
    }

    public function validate(Request $request): JsonResponse
    {
        $authorizationHeader = $request->headers->get('Authorization');
        $token = explode(' ', $authorizationHeader);
        $token = $token[1] ?? null;
        if($token != null) {
            $isTokenValid = $this->apiTokenRepository->findOneBy([
                'token' => $token
            ]);
            if(!$isTokenValid) {
                return new JsonResponse(['status' => 404, 'data' => null, 'message' => 'Token not valid. Please try with valid token.'], Response::HTTP_CREATED);
            }
        } else {
            return new JsonResponse(['status' => 400, 'data' => null, 'message' => 'Token Required. Please try with valid token.'], Response::HTTP_CREATED);
        }

        return new JsonResponse(['status' => 200], Response::HTTP_CREATED);
    }

    /**
     * @Route("/router/add", name="add_router_details", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $validateData = $this->validate($request);
        $response = json_decode($validateData->getContent());
        $status = $response->status;

        if($status == 200) {
            $data = json_decode($request->getContent(), true);
            $sap_id = $data['sapid'];
            $host_name = $data['hostname'];
            $loopback = $data['loopback'];
            $mac_address = $data['mac_address'];
    
            if (empty($sap_id) || empty($host_name) || empty($loopback) || empty($mac_address)) {
                return new JsonResponse(['status' => 500, 'data' => null ,'message' => 'Expecting mandatory parameters!'], Response::HTTP_CREATED);
            }
            
            // check any value exist
            foreach ($data as $key => $value) {
                $exist = $this->routerDetailsRepository->findOneBy([
                    $key => $value
                ]);

                if($exist) {
                    return new JsonResponse(['status' => 500, 'data' => null, 'message' => $key." with the value of ".$value." already exist into the record. Please check."], Response::HTTP_CREATED);
                }
            }

            $id = $this->routerDetailsRepository->saveRouterDetails($sap_id, $host_name, $loopback, $mac_address);
    
            return new JsonResponse(['status' => 200, 'data' => $id ,'message' => 'Router Details created!'], Response::HTTP_CREATED);

        } elseif ($status == 400)  {
            return new JsonResponse(['status' => 400, 'data' => null, 'message' => 'Token Required. Please try with valid token.'], Response::HTTP_CREATED);
        }

        return new JsonResponse(['status' => 404, 'data' => null, 'message' => 'Token not valid. Please try with valid token.'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/router/update", name="update_router_details", methods={"PUT"})
     */
    public function update(Request $request): JsonResponse
    {
        $validateData = $this->validate($request);
        $response = json_decode($validateData->getContent());
        $status = $response->status;

        if($status == 200) {
            $data = json_decode($request->getContent(), true);
            $id = $data['id'];
            $sap_id = $data['sapid'];
            $host_name = $data['hostname'];
            $loopback = $data['loopback'];
            $mac_address = $data['mac_address'];
            $status = $data['status'];
            
            if (empty($id) || empty($sap_id) || empty($host_name) || empty($loopback) || empty($mac_address)) {
                return new JsonResponse(['status' => 500, 'data' => null ,'message' => 'Router Details are Required!'], Response::HTTP_CREATED);
            }

            $routerDetails = $this->routerDetailsRepository->findById($id);
            $routerDetails = $routerDetails[0];

            $routerDetails->setSapid($sap_id);
            $routerDetails->setHostname($host_name);
            $routerDetails->setLoopback($loopback);
            $routerDetails->setMacAddress($mac_address);
            $routerDetails->setStatus($status);
            $em = $this->getDoctrine()->getManager();
            $em->persist($routerDetails);
            $em->flush();

            return new JsonResponse(['status' => 200, 'data' => $id ,'message' => 'Router Details Updated Successfully!'], Response::HTTP_CREATED);

        } elseif ($status == 400)  {
            return new JsonResponse(['status' => 400, 'data' => null, 'message' => 'Token Required. Please try with valid token.'], Response::HTTP_CREATED);
        }

        return new JsonResponse(['status' => 404, 'data' => null, 'message' => 'Token not valid. Please try with valid token.'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/router/list", name="list_router_details", methods={"GET"})
     */
    public function list(Request $request): JsonResponse
    {
        $validateData = $this->validate($request);
        $response = json_decode($validateData->getContent());
        $status = $response->status;

        if($status == 200) {
            $details = $this->routerDetailsRepository->findAll();
            $data = [];
            foreach ($details as $detail) {
                if($detail->toArray()['status']) {
                    $data[] = $detail->toArray();
                }
            }

            return new JsonResponse(['status' => 200, 'data' => $data, 'message' => 'Router details list.'], Response::HTTP_CREATED);

        } elseif ($status == 400)  {
            return new JsonResponse(['status' => 400, 'data' => null, 'message' => 'Token Required. Please try with valid token.'], Response::HTTP_CREATED);
        }

        return new JsonResponse(['status' => 404, 'data' => null, 'message' => 'Token not valid. Please try with valid token.'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/router/delete", name="remove_router_details", methods={"DELETE"})
     */
    public function delete(Request $request): JsonResponse
    {
        $validateData = $this->validate($request);
        $response = json_decode($validateData->getContent());
        $status = $response->status;

        if($status == 200) {
            $data = json_decode($request->getContent(), true);
            $id = $data['id'];
            
            if (empty($id)) {
                return new JsonResponse(['status' => 500, 'data' => null ,'message' => 'Router ID is Required!'], Response::HTTP_CREATED);
            }

            $routerDetails = $this->routerDetailsRepository->findById($id);
            $routerDetails = $routerDetails[0];
            $routerDetails->setStatus(false);
            $em = $this->getDoctrine()->getManager();
            $em->persist($routerDetails);
            $em->flush();

            return new JsonResponse(['status' => 200, 'data' => $id ,'message' => 'Router Details Deleted Successfully!'], Response::HTTP_CREATED);

        } elseif ($status == 400)  {
            return new JsonResponse(['status' => 400, 'data' => null, 'message' => 'Token Required. Please try with valid token.'], Response::HTTP_CREATED);
        }

        return new JsonResponse(['status' => 404, 'data' => null, 'message' => 'Token not valid. Please try with valid token.'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/router/range/list", name="ip_range_list_router_details", methods={"POST"})
     */
    public function ipRangeList(Request $request): JsonResponse
    {
        $validateData = $this->validate($request);
        $response = json_decode($validateData->getContent());
        $status = $response->status;

        if($status == 200) {
            $IpData = json_decode($request->getContent(), true);
            $start = $IpData['start'];
            // $start = explode('.',$start);
            $end = $IpData['end'];
            // $end = explode('.',$end);
            $details = $this->routerDetailsRepository->findAll();
            $data = [];
            foreach ($details as $detail) {
                if($detail->toArray()['status']) {
                    // $rIp = explode('.',$detail->toArray()['loopback']);
                    // foreach ($rIp as $key => $value) {
                        if ($start <= $detail->toArray()['loopback'] && $detail->toArray()['loopback'] < $end) {
                            $data[] = $detail->toArray();
                        }
                    // }
                }
            }

            return new JsonResponse(['status' => 200, 'data' => $data, 'message' => 'Router details list.'], Response::HTTP_CREATED);

        } elseif ($status == 400)  {
            return new JsonResponse(['status' => 400, 'data' => null, 'message' => 'Token Required. Please try with valid token.'], Response::HTTP_CREATED);
        }

        return new JsonResponse(['status' => 404, 'data' => null, 'message' => 'Token not valid. Please try with valid token.'], Response::HTTP_CREATED);
    }
}
