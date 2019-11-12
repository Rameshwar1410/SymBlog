<?php

namespace App\Tests\Controller;

use App\Controller\CommentController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Service\{CommentCreator, DataUpdator, DataDeletor};
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use PHPUnit\Framework\TestCase;
use App\Entity\User;

/**
 * CommentControllerTest Used to test CommentController
 * 
 * @coversDefaultClass App\Service\CommentController
 */
class CommentControllerTest extends TestCase
{
    /** @var CommentController $commentController */
    private $commentController;

    /** @var MockInterface $commentCreator  */
    private $commentCreator;
    
    /** @var MockInterface $dataUpdator  */
    private $dataUpdator;
    
    /** @var MockInterface $dataDeletor  */
    private $dataDeletor;

    /** @var TokenInterface $token  */
    private $token;

    /** @var MockInterface $container */
    protected $container;

    public function setUp()
    {
        $this->container = \Mockery::mock(ContainerInterface::class);
        $this->commentCreator = \Mockery::mock(CommentCreator::class);
        $tokenStorage = \Mockery::mock(TokenStorageInterface::class);
        $this->router = \Mockery::mock(RouterInterface::class);
        $this->token = \Mockery::mock(TokenInterface::class);
        $this->dataUpdator = \Mockery::mock(DataUpdator::class);
        $this->dataDeletor = \Mockery::mock(DataDeletor::class);
        $tokenStorage->shouldReceive('getToken')
            ->between(1, 2)
            ->andReturn($this->token);
        $this->container->shouldReceive('has')
            ->between(1, 2)
            ->with('security.token_storage')
            ->andReturn(true);
        $this->container->shouldReceive('get')
            ->between(1, 2)
            ->with('security.token_storage')
            ->andReturn($tokenStorage);
        
        $this->commentController = new CommentController(
            $this->commentCreator,
            $this->dataUpdator,
            $this->dataDeletor
        );   
        $this->commentController->setContainer($this->container);
    }

    /**
     * Used to test add method
     * 
     * @covers::add
     */
    public function testAdd()
    {    
        $request = \Mockery::mock(Request::class);
        $user = \Mockery::mock(User::class);
        $requestObj = new Request();
        $commentMock = \Mockery::mock(CommentController::class);
        $commentData = [
            "comment" => "comment",
            "post_id" => 1
        ];
        
        $request->shouldReceive('get')
            ->once()
            ->with('comment')
            ->andReturn('comment');
        $request->shouldReceive('request')
            ->shouldReceive('all')
            ->once()
            ->withNoArgs()
            ->andReturn($commentData);

        $this->token->shouldReceive('getUser')
            ->once()
            ->withNoArgs()
            ->andReturn($user);
        $this->container->shouldReceive('get')
            ->once()
            ->andReturn($user);

        $this->commentCreator->shouldReceive('add')
            ->once()
            ->with($commentData, $user);
        
        $this->container->shouldReceive('get')
            ->with('router')
            ->once()
            ->andReturn($this->router);
        $this->router->shouldReceive('generate')
            ->once()
            ->with('post.show', ['id' => 1])
            ->andReturn('http://127.0.0.1:8000/1');
        
        $requestObj->request->set('comment', 'comment');
        $requestObj->request->set('post_id', 1);
        $response = $this->commentController->add($requestObj);
        $this->assertInstanceOf(Response::class, $response);        
    }
}
