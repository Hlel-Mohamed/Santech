<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationFormType;
use App\Repository\UsersRepository;
use App\Security\UserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;

use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{

    /**
     * @throws TransportExceptionInterface
     */
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher,
                             UserAuthenticatorInterface $authenticatorManager, UserAuthenticator $authenticator,
                             MailerInterface $mailer, EntityManagerInterface $entityManager): Response
    {
        $user = new Users();

        $form = $this->createForm(RegistrationFormType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user, $form->get('plainPassword')->getData()
                )
            );

            // On génère un token et on l'enregistre
            $user->setActivationToken(md5(uniqid()));

            $user->setRoles(['ROLE_USER']);
            $mails = $form->get('email')->getData();
            $user->setEmail($mails);
            $entityManager->persist($user);
            $entityManager->flush();

            // On crée le message
            $message = (new Email())
                // On attribue l'expéditeur
                //->setFrom('votre@adresse.fr')
                ->from($this->getParameter('sender'))

                //// On attribue le destinataire
                ->to($mails)

                //// On crée le texte avec la vue
                ->html
                (
                    $this->renderView(
                        'emails/activation.html.twig', ['token' => $user->getActivationToken()]
                    ),
                    'text/html'
                );
            $mailer->send($message);


            $authenticatedToken = $authenticatorManager->authenticateUser($user, $authenticator, $request);

            if ($authenticatedToken) {
                $this->addFlash('success', 'Registration successful!');

                return $this->redirectToRoute('main'); // replace 'main' with the route you want to redirect to
            }
        }


        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/activation/{token}', name: 'activation')]
    public function activation($token, UsersRepository $users, EntityManagerInterface $entityManager): RedirectResponse
    {

        // On recherche si un utilisateur avec ce token existe dans la base de données
        $user = $users->findOneBy(['activation_token' => $token]);

        // Si aucun utilisateur n'est associé à ce token
        if (!$user) {

            // On renvoie une erreur 404
            throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
        }

        // On supprime le token
        $user->setActivationToken(null);
        $entityManager->persist($user);
        $entityManager->flush();

        // On génère un message
        $this->addFlash('message', 'Utilisateur activé avec succès');

        // On retourne à l'accueil
        return $this->redirectToRoute('test');
    }

}
