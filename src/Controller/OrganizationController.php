<?php

namespace App\Controller;

use App\Entity\Organization;
use App\Repository\OrganizationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

class OrganizationController extends AbstractController
{
    /**
     * @Route("/organization", name="app_organization")
     */
    public function index(OrganizationRepository $organizationRepository): Response
    {
        $organizations = $organizationRepository->findAll();
        return $this->render('organization/index.html.twig', [
            'controller_name' => 'OrganizationController',
            'organizations' => $organizations,
        ]);
    }
    /**
     * @Route("/organization/store", name="app_organization_store")
     */
    public function createOrganization(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $organization = new Organization();
        $organization->setName('Organization'.rand(1,100));
        $entityManager->persist($organization);
        $entityManager->flush();// действительно выполните запросы (например, запрос INSERT)
        return new Response('Saved new data with id '.$organization->getId());
    }
    /**
     * @Route("/organization/{id}", name="organization_show")
     */
    public function show(int $id, OrganizationRepository $organizationRepository): Response
    {
        $organization = $organizationRepository->find($id);
        if (!$organization) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        return new Response('Check out this great product: '.$organization->getName());
        // return $this->render('organization/show.html.twig', ['product' => $product]);
    }
    /**
     * @Route("/organization/delete/{id}", name="organization_delete")
     */
    public function delete(int $id): Response
    {
        $em = $this->getDoctrine()->getEntityManager();
        $organization = $em->getRepository(OrganizationRepository::class)->find($id);

        if (!$organization) {
            throw $this->createNotFoundException('No data found for id '.$id);
        }

        $em->remove($organization);
        $em->flush();

        return new Response('data was deleted: '.$id);
        // return $this->render('organization/show.html.twig', ['product' => $product]);
    }
}
