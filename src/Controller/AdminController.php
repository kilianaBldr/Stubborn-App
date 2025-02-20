<?php 
namespace App\Controller; 
use App\Entity\Sweatshirt; 
use App\Form\SweatshirtType; 
use App\Repository\SweatshirtRepository; 
use Doctrine\ORM\EntityManagerInterface; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; 
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\HttpFoundation\File\Exception\FileException; 
use Symfony\Component\Routing\Annotation\Route; 
use Symfony\Bundle\SecurityBundle\Attribute\IsGranted; 

#[IsGranted('ROLE_ADMIN')] 
class AdminController extends AbstractController 
{ 
    #[Route('/admin/sweatshirts', name: 'admin_sweatshirts')] 
    public function manageSweatshirts( SweatshirtRepository $sweatshirtRepository, Request $request, EntityManagerInterface $entityManager ): Response 
    { 
        $sweatshirts = $sweatshirtRepository->findAll(); 
        $sweatshirt = new Sweatshirt(); 
        $form = $this->createForm(SweatshirtType::class, $sweatshirt, [ 
            'attr' => ['enctype' => 'multipart/form-data'], 
        ]); 
        $form->handleRequest($request); 
        if ($form->isSubmitted() && $form->isValid()) { 
            $imageFile = $form->get('imageFile')->getData(); 
            if ($imageFile) { 
                $newFilename = uniqid() . '.' . $imageFile->guessExtension(); 
                try { $imageFile->move($this->getParameter('images_directory'), $newFilename); 
                } catch (FileException $e) { 
                    $this->addFlash('error', 'Erreur lors de l’upload de l’image'); 
                    return $this->redirectToRoute('admin_sweatshirts'); 
                } 
                $sweatshirt->setImage($newFilename); 
            } 
            $entityManager->persist($sweatshirt); 
            $entityManager->flush(); 
            $this->addFlash('success', 'Sweat-shirt ajouté avec succès'); 
            return $this->redirectToRoute('admin_sweatshirts'); 
        } 
        return $this->render('admin/dashboard.html.twig', [ 
            'form' => $form->createView(), 
            'sweatshirts' => $sweatshirts, 
        ]); 
    } 
    #[Route('/admin/sweatshirt/{id}/edit', name: 'admin_sweatshirt_edit')] 
    public function editSweatshirt( Sweatshirt $sweatshirt, Request $request, EntityManagerInterface $entityManager ): Response 
    { 
        $form = $this->createForm(SweatshirtType::class, $sweatshirt); 
        $form->handleRequest($request); 
        if ($form->isSubmitted() && $form->isValid()) { 
            $imageFile = $form->get('imageFile')->getData(); 
            if ($imageFile) { $newFilename = uniqid() . '.' . $imageFile->guessExtension(); 
                try { 
                    $imageFile->move($this->getParameter('images_directory'), $newFilename); 
                } catch (FileException $e) { 
                    $this->addFlash('error', 'Erreur lors de l’upload de l’image'); 
                    return $this->redirectToRoute('admin_sweatshirts'); 
                } $sweatshirt->setImage($newFilename); 
            } 
            $entityManager->flush(); 
            $this->addFlash('success', 'Sweat-shirt mis à jour avec succès'); 
            return $this->redirectToRoute('admin_sweatshirts'); 
        } 
        return $this->render('admin/sweatshirt_edit.html.twig', [ 
            'form' => $form->createView(), 
            'sweatshirt' => $sweatshirt, 
        ]); 
    } 
    #[Route('/admin/sweatshirt/{id}/delete', name: 'admin_sweatshirt_delete', methods: ['POST'])] 
    public function deleteSweatshirt( Sweatshirt $sweatshirt, EntityManagerInterface $entityManager ): Response 
    { 
        $imagePath = $this->getParameter('images_directory') . '/' . $sweatshirt->getImage(); 
        if ($sweatshirt->getImage() && file_exists($imagePath)) { 
            unlink($imagePath); 
        } 
        $entityManager->remove($sweatshirt); 
        $entityManager->flush(); 
        $this->addFlash('success', 'Sweat-shirt supprimé avec succès'); 
        return $this->redirectToRoute('admin_sweatshirts'); 
    }
 }