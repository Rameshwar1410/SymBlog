<?php
/**
 * This file contains the controller class for the company matching pages.
 *
 * PHP version 5
 *
 * @author Yiğit Oner <yigit.oner@hijob.me>
 */

namespace Hijob\WebsiteBundle\Controller;

use Hijob\WebsiteBundle\Entity\Company;
use Hijob\WebsiteBundle\Entity\CompanyMatchingPage;
use Hijob\WebsiteBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Controller class for the company matching pages.
 *
 * @author Yiğit Oner <yigit.oner@hijob.me>
 */
class CompanyMatchingController extends Controller
{
    /**
     * Renders the company matching page
     *
     * @Route("/jobs/{companySlug}/matching", name="company.matching")
     * @ParamConverter("company",
     *     class="hj:Company",
     *     options={"mapping" : {"companySlug" : "url_slug"}})
     * @param Request $request An instance of Request.
     * @return Response An instance of Response.
     * @author Yiğit Oner <yigit.oner@hijob.me>
     */
    public function show(Request $request, Company $company)
    {
        if ($company->getMatchingPageEnabled() === false) {
            return $this->redirectToRoute('company.overview', ['companySlug' => $company->getUrlSlug()]);
        }

        if (! $company->getCompanyMatchingPage() instanceof CompanyMatchingPage) {
            return $this->redirectToRoute('company.overview', ['companySlug' => $company->getUrlSlug()]);
        }

        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->render(
                '@HijobWebsite/CompanyMatchingPage/companyMatching.html.twig',
                ['company' => $company]
            );
        }

        $this->get('hijob.services.company_matching.matching_visit_creator')
            ->createVisit($user, $company);
        $this->get('hijob.services.scoring.processing.user_initializer')
            ->enableScoring($user);
        $requestHandler = $this
                ->get('hijob.services.scoring.processing.request_handler');

        $responseData = $requestHandler->getResponseData($request, $user);
        $response = $this->redirectToRoute('company.job.list',
            ['companySlug' => $responseData->getCompany()->getUrlSlug()]);
        $requestHandler->setCookie($response, $responseData);

        return $response;

    }

    /**
     * Ajax route for checking the status of scoring.
     *
     * @Route("/ajax/check-matching", name="ajax.company.matching.checking",
     *     condition="request.isXmlHttpRequest()")
     * @param Request $request An instance of Request.
     * @return JsonResponse An instance of JsonResponse
     * @author Yiğit Oner <yigit.oner@hijob.me>
     */
    public function checkScoring(Request $request)
    {
        $user = $this->getUser();
        if (is_null($user)) {
            throw  new AccessDeniedHttpException();
        }

        return $this->get('hijob.services.scoring.processing.status_checker')
            ->getResponse($user, $request);
    }

    /**
     * Resets matching results.
     *
     * @Security("has_role('ROLE_HIJOB_ADMIN') or has_role('ROLE_HIJOB_EDITOR')")
     * @Route("/reset-matching", name="reset.matching")
     * @param Request $request An instance of Request.
     * @return Response An instance of Response.
     * @author Yiğit Oner <yigit.oner@hijob.me>
     */
    public function resetMatching(Request $request)
    {
        $connection = $this->get('database_connection');

        if ($request->getHost() === 'demo.hijob.me' &&
            $connection->getDatabase() === 'hijob_demo'
        ) {
            $connection->executeQuery('UPDATE user_preferences SET scoring=0');
            $connection->executeQuery('TRUNCATE user_classification');
            $connection->executeQuery('TRUNCATE scoring_job');
            $connection->executeQuery('TRUNCATE user_job_score');

            return new Response('Matching results cleared!');
        }

        throw new NotFoundHttpException();
    }
}