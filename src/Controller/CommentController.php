<?php
declare(strict_types = 1);

namespace App\Controller;

use App\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;
use App\Service\{CommentCreator, DataUpdator, DataDeletor};
use App\Form\CommentFormType;

/**
 * Used to manage comment
 * 
 * @Route("/user/comment")
 */
class CommentController extends AbstractController
{
    /** @var CommentCreator $commentCreator An instance of CommentCreator */
    private $commentCreator;
    
    /** @var DataUpdator $dataUpdator An instance of DataUpdator */
    private $dataUpdator;
    
    /** @var DataDeletor $dataDeletor An instance of DataDeletor */
    private $dataDeletor;

    /** @var array $commentData Comment added values */
    private $commentData;

    /**
     * Constructor to initialize variable
     * 
     * @param CommentCreator $commentCreator An instance of CommentCreator
     * @param DataUpdator $dataUpdator An instance of DataUpdator
     * @param DataDeletor $dataDeletor An instance of DataDeletor 
     */
    public function __construct(
        CommentCreator $commentCreator,
        DataUpdator $dataUpdator,
        DataDeletor $dataDeletor
    ) {
        $this->commentCreator = $commentCreator;
        $this->dataUpdator = $dataUpdator;
        $this->dataDeletor = $dataDeletor;
    }

    /**
     * Add new comment
     * 
     * @Route("/comment_add", name="comment.add", methods={"POST"})
     * @param Request $request An instance of Request
     * @return Response An instance of Response
     */
    public function add(Request $request): Response 
    {
        
        if ($request->get('comment')) {
            $this->commentData = $request->request->all();
            $this->commentCreator->add($this->commentData, $this->getUser());
        }

        return $this->redirectToPost((int)$this->commentData['post_id']);
    }

    /**
     * Edit comment
     * 
     * @Route("/comment/{id}/edit", name="comment.edit", methods={"GET", "POST"})
     * @param Request $request An instance of Request
     * @param Comment $comment An instance of Comment
     * @return Response An instance of Response
     */
    public function edit(Request $request, Comment $comment): Response
    {
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->dataUpdator->update();

            return $this->redirectToPost((int)$comment->getPost()->getId());
        }

        return $this->render('comment/edit.html.twig', [
            'form' => $form->createView(),
            'comment' => $comment,
        ]);
    }

    /**
     * Delete comment
     * 
     * @Route("/comment/{id}", name="comment.delete", methods={"DELETE"})
     * @param Request $request An instance of Request
     * @param Comment $comment An instance of Comment
     * @return Response An instance of Response
     */
    public function delete(Request $request, Comment $comment): Response
    {
        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
            $this->dataDeletor->remove($comment);
        }

        return $this->redirectToPost((int)$comment->getPost()->getId());
    }

    /**
     * Used to redirect to post
     * 
     * @param int $id Is a post id
     * @return Response An intance of Response
     */
    private function redirectToPost(int $id): Response
    {
        return $this->redirectToRoute('post.show', [
            'id' => $id,
        ]);
    }
}
