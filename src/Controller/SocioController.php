<?php

namespace App\Controller;

use App\Entity\Socio;
use App\Entity\Empresa;
use App\Repository\SocioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/socio')]
class SocioController extends AbstractController
{
    #[Route('/', name: 'app_socio_index', methods: ['GET'])]
    public function index(SocioRepository $socioRepository): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $socios = $socioRepository->findAll();
        return $this->json($socios);
    }

    #[Route('/new', name: 'app_socio_new', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $socio = new Socio();
        $data = json_decode($request->getContent(), true);

        $socio->setNome($data['nome']);
        $socio->setCpf($data['cpf']);
        $socio->setDataCriacao(new \DateTime());

        $empresa = $entityManager->getRepository(Empresa::class)->find((int)$data['empresa_id']);
        if ($empresa) {
            $socio->setEmpresa($empresa);
        } else {
            return $this->json(['error' => 'Empresa não encontrada'], Response::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($socio);
        $entityManager->flush();

        return $this->json($socio, Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'app_socio_show', methods: ['GET'])]
    public function show(Socio $socio): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->json($socio);
    }

    #[Route('/{id}/edit', name: 'app_socio_edit', methods: ['PUT'])]
    public function edit(Request $request, Socio $socio, EntityManagerInterface $entityManager): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $data = json_decode($request->getContent(), true);

        if (isset($data['nome'])) {
            $socio->setNome($data['nome']);
        }
        if (isset($data['cpf'])) {
            $socio->setCpf($data['cpf']);
        }
        if (isset($data['empresa_id'])) {
            $empresa = $entityManager->getRepository(Empresa::class)->find((int)$data['empresa_id']);
            if ($empresa) {
                $socio->setEmpresa($empresa);
            }
        }

        $entityManager->flush();

        return $this->json($socio);
    }

    #[Route('/{id}', name: 'app_socio_delete', methods: ['DELETE'])]
    public function delete(Socio $socio, EntityManagerInterface $entityManager): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $entityManager->remove($socio);
        $entityManager->flush();

        return $this->json(['success' => 'Sócio deletado com sucesso.']);
    }
}


