<?php


namespace App\Controller\Api;


use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

abstract class BaseApiController extends AbstractFOSRestController
{

    /**
     * Returns validation errors as an array from provided Form
     * ex: [
     *     'title' => 'Title cannot be empty'
     * ]
     *
     * @author Nadeen Nilanka <ntwobike@gmail.com>
     *
     * @param NormalizerInterface $errorNormalizer
     * @param FormInterface       $form
     *
     * @param String              $statusCode
     *
     * @return array
     */
    protected function getErrors(NormalizerInterface $errorNormalizer, FormInterface $form, String $statusCode): array
    {
        return $errorNormalizer->normalize($form, null, ['status_code' => $statusCode]);
    }

    /**
     * Returns contents of the request as array
     *
     * @param Request $request
     *
     * @return array | null
     */
    protected function getJsonDecodedFromRequest(Request $request)
    {
        return json_decode($request->getContent(), true);
    }

    /**
     * Reads user id from the JWT token
     *
     * @param Request $request
     *
     * @return int
     */
    protected function getUserIdFromToken(Request $request)
    {
        return 1;
    }

}