<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Services\FileUploader;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/", name="profile_index")
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return Response
     * @throws Exception
     */
    public function index(Request $request, FileUploader $fileUploader): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        $form = $this->createForm(UserType::class, $this->getUser());
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneByUsername($this->getUser()->getUsername());

        if ($form->isSubmitted() && $form->isValid()) {
            $avatar = $form->get('avatar')->getData();
            if ($avatar) {
                $avatarFileName = $fileUploader->upload($avatar);
                $user->setAvatar($avatarFileName);
                $em->persist($user);
            }
            $this->getDoctrine()->getManager()->flush();
        }
        $uncheckedCartItems = $checkedCartItems = "";
        if ($user->getCart()) {
            $uncheckedCartItems = $user->getCart()->getUncheckedCartItem();
            $checkedCartItems = $user->getCart()->getCheckedCartItem();
        }

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'checkedCartItems' => $checkedCartItems,
            'uncheckedCartItems' => $uncheckedCartItems
        ]);
    }


}
