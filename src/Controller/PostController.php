<?php
declare (strict_types = 1);

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\{CommentRepository, PostRepository};
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;
use App\Service\{PostCreator, DataUpdator, DataDeletor};

/**
 * Controller use to manage posts
 */
class PostController extends AbstractController
{
    /** @var PostCreator $postCreator An instance of PostCreator */
    private $postCreator;
    
    /** @var DataUpdator $dataUpdator An instance of DataUpdator */
    private $dataUpdator;
    
    /** @var DataDeletor $dataDeletor An instance of DataDeletor */
    private $dataDeletor;

    /**
     * Constructor to initialize variable
     * 
     * @param PostCreator $postCreator An instance of PostCreator
     * @param DataUpdator $dataUpdator An instance of DataUpdator
     * @param DataDeletor $dataDeletor An instance of DataDeletor 
     */
    public function __construct(
        PostCreator $postCreator,
        DataUpdator $dataUpdator,
        DataDeletor $dataDeletor
    )
    {
        $this->postCreator = $postCreator;
        $this->dataUpdator = $dataUpdator;
        $this->dataDeletor = $dataDeletor;
    }

    /**
     * Showing all users post  
     * 
     * @Route("/", name="all.users.post")
     * @param PostRepository $postRepository An instance of PostRepository
     * @return Response An instance of Response
     */
    public function showAllUserPost(PostRepository $postRepository): Response
    {
        return $this->render('post/showAllUserPost.html.twig', [
            'posts' => $postRepository->findAll(),
        ]);
    }

    /**
     * Showing post details
     * 
     * @Route("/{id}", name="post.show", requirements={"id":"\d+"})
     * @param Post $post An instance of Post entity
     * @param CommentRepository $commentRepository An instance of CommentRepository
     * @return Response An instance of Response
     */
    public function viewPost(Post $post, CommentRepository $commentRepository): Response
    {
        $comments = $commentRepository->findBy(['post' => $post->getId()]);

        return $this->render('post/show.html.twig', [
            'post' => $post,
            'comments' => $comments,
        ]);
    }

    /**
     * List of login user added post only (admin can see all users post)
     * 
     * @Route("/user/post/", name="post.list", methods={"GET"})
     * @param PostRepository $postRepository An instance of PostRepository
     * @return Response An instance of Response
     */
    public function listUserPost(PostRepository $postRepository): Response
    {//dd($postRepository->findAll());
        return $this->render('post/list.html.twig', [
            'posts' => $postRepository->findAll(),
        ]);
    }

    /**
     * Add post
     * 
     * @Route("/user/post/new/", name="post.new", methods={"GET", "POST"})
     * @param Request $request An instance of Request
     * @return Response An instance of Response
     */
    public function add(Request $request): Response 
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->postCreator->add($post, $this->getUser());

            return $this->redirectToRoute('post.list');
        }

        return $this->render('post/new.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Preview user added post
     * 
     * @Route("/user/post/{id}", name="post.preview", methods={"GET"})
     * @param Post $post An instance of Post entity
     * @return Response An instance of Response
     */
    public function previewUserPost(Post $post): Response
    {
        return $this->render('post/preview.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * Edit post
     * 
     * @Route("/user/post/{id}/edit", name="post.edit", methods={"GET", "POST"})
     * @param Request $request An instance of Request
     * @param Post $post An instance of Post entity
     * @return Response An instance of Response
     */
    public function edit(Request $request, Post $post): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->dataUpdator->update();

            return $this->redirectToRoute('post.list', [
                'id' => $post->getId(),
            ]);
        }

        return $this->render('post/edit.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Delete user added post
     * 
     * @Route("/user/post/{id}", name="post.delete", methods={"DELETE"})
     * @param Request $request An instance of Request
     * @param Post $post An instance of Post entity
     * @return Response An instance of Response
     */
    public function delete(Request $request, Post $post): Response
    {
        if ($this->isCsrfTokenValid('delete' . $post->getId(), $request->request->get('_token'))) {
            $this->dataDeletor->remove($post);
        }

        return $this->redirectToRoute('post.list');
    }
}
