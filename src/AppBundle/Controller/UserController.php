<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\Type\UserAdminType;
use AppBundle\Form\Type\UserProfileType;
use AppBundle\Form\Type\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{
    /**
     * @Route("/register", name="user_registration")
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('login');
        }

        return $this->render(
            'AppBundle:users/registration:user_registration_form.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }

    /**
     * @Route("/login", name="login")
     */
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('AppBundle:users/registration:login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    /**
     * @Route("/users", name="users")
     */
    public function showUserListAction()
    {
        return $this->render('AppBundle:users/administration:user_list.html.twig', array(
            'users' => $this->getDoctrine()->getRepository('AppBundle:User')->findAll()
        ));
    }

    /**
     * @Route("/users/edit/{user}", name="edit_user")
     */
    public function editUserAction(User $user, Request $request)
    {
        $form = $this->createForm(UserAdminType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('users');
        }

        return $this->render(
            'AppBundle:users/administration:user_admin_form.html.twig',
            array(
                'form' => $form->createView(),
                'user' => $user
            )
        );
    }

    /**
     * @Route("/profile", name="profile")
     */
    public function editProfileAction(Request $request)
    {
        $user = $this->getUser();

        $form = $this->createForm(UserProfileType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $user->getPlainPassword();

            if($plainPassword !== null) {
                $password = $this->get('security.password_encoder')
                    ->encodePassword($user, $plainPassword);
                $user->setPassword($password);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('profile');
        }

        return $this->render(
            'AppBundle:users/profile:user_profile_form.html.twig',
            array(
                'form' => $form->createView(),
                'user' => $user
            )
        );
    }
}