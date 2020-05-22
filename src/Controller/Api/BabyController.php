<?php

namespace App\Controller\Api;

use App\Entity\Baby;
use App\Repository\BabyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BabyController extends AbstractController
{
    private EntityManagerInterface $em;

    private BabyRepository $babyRepository;

    private ValidatorInterface $validator;

    private SerializerInterface $serializer;

    public function __construct(
        EntityManagerInterface $em,
        BabyRepository $babyRepository,
        ValidatorInterface $validator,
        SerializerInterface $serializer
    ) {
        $this->em = $em;
        $this->babyRepository = $babyRepository;
        $this->validator = $validator;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/api/baby", name="api_baby_list", methods={"GET"})
     */
    public function list()
    {
        $data = [];
        foreach ($this->babyRepository->findAll() as $baby) {
            $data[$baby->getId()] = $baby;
        }

        return $this->json($data);
    }

    /**
     * @Route("/api/baby/{id}", name="api_baby_show", methods={"GET"})
     */
    public function show(Baby $baby)
    {
        return $this->json($baby);
    }

    /**
     * @Route("/api/baby", name="api_baby_add", methods={"POST"})
     */
    public function add(Request $request)
    {
        $baby = $this->serializer->deserialize($request->getContent(), Baby::class, 'json');

        $errors = $this->validator->validate($baby);

        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }
        $this->em->persist($baby);
        $this->em->flush();

        return $this->json($baby);
    }

    /**
     * @Route("/api/baby/{id}", name="api_baby_edit", methods={"PUT"})
     */
    public function edit(Baby $baby, Request $request)
    {
        /** @var Baby $editedBaby */
        $editedBaby = $this->serializer->deserialize($request->getContent(), Baby::class, 'json');
        $baby
            ->setName($editedBaby->getName())
            ->setBirthDatetime($editedBaby->getBirthDatetime())
        ;

        $errors = $this->validator->validate($baby);

        if (count($errors) > 0) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }
        $this->em->flush();

        return $this->json($baby);
    }

    /**
     * @Route("/api/baby/{id}", name="api_baby_delete", methods={"DELETE"})
     */
    public function delete(Baby $baby)
    {
        $this->em->remove($baby);
        $this->em->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
