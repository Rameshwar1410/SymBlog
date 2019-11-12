<?php
declare (strict_types = 1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\{Request, Response};
use App\Service\{UserCreator, DataUpdator, UserDeletor};
use App\Form\{RegisterFormType, UsersFormType};
use Knp\Component\Pager\PaginatorInterface;

/**
 * User management Controller
 */
class UsersController extends AbstractController
{
    /** @var UserCreator $userCreator An instance of UserCreator */
    private $userCreator;
    
    /** @var DataUpdator $dataUpdator An instance of DataUpdator */
    private $dataUpdator;
    
    /** @var UserDeletor $userDeletor An instance of UserDeletor */
    private $userDeletor;

    /**
     * Constructor to initialize variable
     * 
     * @param UserCreator $userCreator An instance of UserCreator
     * @param DataUpdator $dataUpdator An instance of DataUpdator
     * @param UserDeletor $userDeletor An instance of UserDeletor 
     */
    public function __construct(
        UserCreator $userCreator,
        DataUpdator $dataUpdator,
        UserDeletor $userDeletor
    )
    {
        $this->userCreator = $userCreator;
        $this->dataUpdator = $dataUpdator;
        $this->userDeletor = $userDeletor;
    }

    /**
     * List of all user
     * 
     * @Route("/admin/users/", name="user.list", methods={"GET"})
     * @param UserRepository $usersRepository An instance of UserRepository
     * @param PaginatorInterface $paginator An instance of PaginatorInterface
     * @param Request $request An instance of Request
     * @return Response An instance of Response
     */
    public function list(
        UserRepository $usersRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response
    {
        $pagination = $paginator->paginate(
            $usersRepository->findAll(), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            2/*limit per page*/
        );
        return $this->render('users/list.html.twig', [
            'users' => $pagination,
        ]);
    }

    /**
     * Show user profile
     * 
     * @Route("/profile/{id}", name="user.profile")
     * @param User $user An instance of User
     * @return Response An instance of Response
     */
    public function showProfile(User $user): Response
    {
        return $this->render('users/info.html.twig', [
            'userData' => $user,
        ]);
    }

    /**
     * Edit user profile
     * 
     * @Route("/user/profile/{id}/edit", name="user.profile.edit")
     * @param Request $request An instance of Request
     * @param User $user An instance of User entity
     * @param DataUpdator $dataUpdator An instance of DataUpdator
     * @return Response An instance of Response
     */
    public function editProfile(Request $request, User $user, DataUpdator $dataUpdator): Response
    {
        $form = $this->createForm(UsersFormType::class, $user);
        $form->remove('roles');
        $form->remove('image');
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $dataUpdator->update();

            return $this->redirectToRoute('user.profile', [
                'id' => $user->getId(),
            ]);
        }

        return $this->render('users/edit.profile.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Add new user
     * 
     * @Route("/admin/users/new", name="users.new", methods={"GET", "POST"})
     * @param Request $request An instance of Request
     * @param UserCreator $userCreator An instance of UserCreator
     * @return Response An instance of Response
     */
    public function add(Request $request, UserCreator $userCreator): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->userCreator->add(
                $user,
                $this->getParameter('uploades_directory'),
                $form->get('image')->getdata()
            );

            return $this->redirectToRoute('user.list');
        }

        return $this->render('users/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Show User details
     * 
     * @Route("/admin/users/{id}", name="users.show", methods={"GET"})
     * @param User $user An instance of User entity
     * @return Response An instance of Response
     */
    public function showUser(User $user): Response
    {
        return $this->render('users/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * Edit User details
     * 
     * @Route("/admin/users/{id}/edit", name="users.edit", methods={"GET", "POST"})
     * @param Request $request An instance of Request
     * @param User $user An instance of User entity
     * @return Response An instance of Response
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UsersFormType::class, $user);
        //$form->remove('image');
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->dataUpdator->update();

            return $this->redirectToRoute('user.list', [
                'id' => $user->getId(),
            ]);
        }

        return $this->render('users/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Delete User
     * 
     * @Route("/admin/users/{id}", name="users.delete", methods={"DELETE"})
     * @param Request $request An instance of Request
     * @param User $user An instance of User entity
     * @return Response An instance of Response
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $this->userDeletor->remove($user, $this->getParameter('uploades_directory'));
        }

        return $this->redirectToRoute('user.list');
    }
}
