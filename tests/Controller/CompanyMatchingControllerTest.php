<?php
/**
 * This file contains the unit tests for the CompanyMatchingController.
 *
 * PHP Version 5
 *
 * @author Yiğit Oner <yigit.oner@hijob.me>
 */

namespace Hijob\WebsiteBundle\Tests\Controller;

use Hijob\WebsiteBundle\Controller\CompanyMatchingController;
use Hijob\WebsiteBundle\Entity\CompanyMatchingPage;
use Hijob\WebsiteBundle\Entity\Company;
use Hijob\WebsiteBundle\Entity\User;
use Hijob\WebsiteBundle\Services\CompanyMatching\MatchingVisitCreator;
use Hijob\WebsiteBundle\Services\Scoring\Processing\ResponseData;
use Hijob\WebsiteBundle\Services\Scoring\Processing\UserInitializer;
use Hijob\WebsiteBundle\Services\Scoring\Processing\StatusChecker;
use Hijob\WebsiteBundle\Services\Scoring\Processing\MatchingRequestHandler;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Mockery\MockInterface;

/**
 * Unit tests for the CompanyMatchingController.
 *
 * @coversDefaultClass \Hijob\WebsiteBundle\Controller\CompanyMatchingController
 * @author Yiğit Oner <yigit.oner@hijob.me>
 */
class CompanyMatchingControllerTest extends \PHPUnit_Framework_TestCase
{
    /* @var CompanyMatchingController $controller /
    protected $controller;

    /* @var MockInterface $container /
    protected $container;

    /* @var MockInterface $token /
    protected $token;

    /* @var  MockInterface $templating /
    protected $templating;

    /* @var MockInterface $request /
    protected $request;

    /* @var MockInterface $requestHandler /
    protected $requestHandler;

    /**
     * Set up test.
     *
     * @author Yiğit Oner <yigit.oner@hijob.me>
     */
    public function setUp()
    {
        $this->container = \Mockery::mock(ContainerInterface::class);
        $this->token = \Mockery::mock(TokenInterface::class);
        $tokenStorage = \Mockery::mock(TokenStorageInterface::class);
        $this->request = \Mockery::mock(Request::class);
        $this->requestHandler = \Mockery::mock(MatchingRequestHandler::class);

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

        $this->controller = new CompanyMatchingController();
        $this->controller->setContainer($this->container);
    }

    /**
     * Tests matching page for guests.
     *
     * @covers ::show
     * @author Yiğit Oner <yigit.oner@hijob.me>
     */
    public function testRendersMatchingTemplateOnGuests()
    {
        $company = \Mockery::mock(Company::class);
        $response = \Mockery::mock(Response::class);
        $templating = \Mockery::mock(TwigEngine::class);

        $this->token->shouldReceive('getUser')
            ->once()
            ->withNoArgs()
            ->andReturnNull();
        $this->container->shouldReceive('has')
            ->once()
            ->with('templating')
            ->andReturn(true);
        $this->container->shouldReceive('get')
            ->once()
            ->with('templating')
            ->andReturn($templating);
        $templating->shouldReceive('render')
            ->once()
            ->with(
                '@HijobWebsite/CompanyMatchingPage/companyMatching.html.twig',
                ['company' => $company]
            )
            ->andReturn("foobar-content");

        $company->shouldReceive('getMatchingPageEnabled')
            ->once()
            ->andReturn(true);
        $company->shouldReceive('getCompanyMatchingPage')
            ->once()
            ->andReturn(new CompanyMatchingPage());

        $response = $this->controller->show($this->request, $company);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame('foobar-content', $response->getContent());
    }

    /**
     * Tests matching page for logged-in users.
     *
     * @covers ::show
     * @author Yiğit Oner <yigit.oner@hijob.me>
     */
    public function testCallsResponseCreatorOnLoggedInUser()
    {
        $company = \Mockery::mock(Company::class);
        $user = \Mockery::mock(User::class);
        $responseData = \Mockery::mock(ResponseData::class);
        $router = \Mockery::mock(Router::class);
        $userInitializer = \Mockery::mock(UserInitializer::class);

        $this->token->shouldReceive('getUser')
            ->once()
            ->withNoArgs()
            ->andReturn($user);
        $this->container->shouldReceive('get')
            ->once()
            ->with('hijob.services.scoring.processing.user_initializer')
            ->andReturn($userInitializer);
        $userInitializer->shouldReceive('enableScoring')
            ->once()
            ->with($user);
        $this->container->shouldReceive('get')
            ->once()
            ->with('hijob.services.scoring.processing.request_handler')
            ->andReturn($this->requestHandler);
        $this->requestHandler->shouldReceive('getResponseData')
            ->once()
            ->with($this->request, $user)
            ->andReturn($responseData);
        $this->container->shouldReceive('get')
            ->once()
            ->with('router')
            ->andReturn($router);
        $responseData->shouldReceive('getCompany')
            ->once()
            ->andReturn($company);
        $router->shouldReceive('generate')
            ->once()
            ->with('company.job.list', ['companySlug' => 'foobar'], 1)
            ->andReturn('hijob.me/de/jobs/foobar/jobs');
        $this->requestHandler->shouldReceive('setCookie')
            ->once()
            ->with(\Mockery::on(function ($arg1){
                return $arg1 instanceof RedirectResponse
                    && $arg1->getTargetUrl() === 'hijob.me/de/jobs/foobar/jobs'
                    && $arg1->getStatusCode() === 302;}),
                $responseData
            );
        $company->shouldReceive('getMatchingPageEnabled')
            ->once()
            ->andReturn(true);
        $company->shouldReceive('getCompanyMatchingPage')
            ->once()
            ->andReturn(new CompanyMatchingPage());
        $company->shouldReceive('getUrlSlug')
            ->once()
            ->andReturn('foobar');
        $this->setCompanyMatchingVisitExpectations($user, $company);
        
        $response = $this->controller->show($this->request, $company);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    /**
     * Tests status checking for guests.
     *
     * @covers ::checkScoring
     * @author Yiğit Oner <yigit.oner@hijob.me>
     */
    public function testExceptionOnGuestsCheckingStatus()
    {
        $this->setExpectedException(AccessDeniedHttpException::class);

        $this->token->shouldReceive('getUser')
            ->once()
            ->withNoArgs()
            ->andReturn(null);

        $this->controller->checkScoring($this->request);
    }

    /**
     * Tests status checking for logged-in users.
     *
     * @covers ::checkScoring
     * @author Yiğit Oner <yigit.oner@hijob.me>
     */
    public function testGettingResponseWhileCheckingStatus()
    {
        $user = \Mockery::mock(User::class);
        $responseMock = \Mockery::mock(JsonResponse::class);
        $statusChecker = \Mockery::mock(StatusChecker::class);

        $this->token->shouldReceive('getUser')
            ->once()
            ->withNoArgs()
            ->andReturn($user);
        $this->container->shouldReceive('get')
            ->once()
            ->with('hijob.services.scoring.processing.status_checker')
            ->andReturn($statusChecker);
        $statusChecker->shouldReceive('getResponse')
            ->once()
            ->with($user, $this->request)
            ->andReturn($responseMock);

        $response = $this->controller->checkScoring($this->request);
        $this->assertSame($responseMock, $response);
    }
    
    /**
     * Set MatchingVisitCreator service expectations
     * 
     * @param MockInterface $userMock The user entity mock
     * @param MockInterface $companyMock The company entity mock
     * @author Pravin Kudale <p.kudale@easternenterprise.com>
     */
    private function setCompanyMatchingVisitExpectations($userMock, $companyMock) 
    {
        $matchingVisitCreatorMock = \Mockery::mock(MatchingVisitCreator::class)
            ->shouldReceive('createVisit')
            ->once()
            ->with(\Mockery::on(function($user) use ($userMock) {
                $this->assertSame($userMock, $user);
                
                return true;
            }),
            \Mockery::on(function($company) use ($companyMock) {
                $this->assertSame($companyMock, $company);
                
                return true;
            }))    
            ->getMock();
        $this->container->shouldReceive('get')
            ->once()
            ->with('hijob.services.company_matching.matching_visit_creator')
            ->andReturn($matchingVisitCreatorMock);
    }
}