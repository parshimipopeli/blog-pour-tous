<?php

namespace App\Controller;

use App\Entity\UsersBlog;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class SecurityController extends AbstractController
{
    /**
     * @route("/inscription", name="security_registration")
     */
    public function registration(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder) {
        $user = new UsersBlog();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pwdHash = $encoder->encodePassword($user, $user->getPassword());

            $user->setPassword($pwdHash);


            $em->persist($user);
            $em->flush();

            return  $this->redirectToRoute('security_login');
        }

        return $this->render('security/registration.html.twig', [
        'form' => $form->createView()
    ]);
    }

    /**
     * @route("/connexion", name="security_login")
     */
    public function login() {
        return $this->render('security/login.html.twig');
    }

    /**
     * route("/deconnexion", name="security_logout")
     */
    public function logout() {

    }
}
