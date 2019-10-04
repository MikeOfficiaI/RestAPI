<?php
namespace App\Controller;
use FOS\RestBundle\Tests\Fixtures\User;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\Movie;
use App\Form\MovieType;
/**
 * Movie controller.
 * @Route("/api", name="api_")
 */
class MovieController extends FOSRestController
{
    /**
     * Find movie.
     * @Rest\Get("/movies/{id}")
     *
     * @return Response
     */
    public function getSingleMovie($id)
    {
        $repository = $this->getDoctrine()->getRepository(Movie::class);
        $movie = $repository->find($id);
        return $this->handleView($this->view($movie));
    }
    //**
    // * Find movie.
    // * @Rest\Get("/movies/{id}")
    // *
    // * @return Response
    // */
    //public function getSingleMovie($id)
    //{
    //    $repository = $this->getDoctrine()->getRepository(Movie::class);
    //    $movie = $repository->find($id);
    //    return $this->handleView($this->view($movie));
    //}
    /**
     * Lists all Movies.
     * @Rest\Get("/movies")
     *
     * @return Response
     */
    public function getMovieAction()
    {
        $repository = $this->getDoctrine()->getRepository(Movie::class);
        $movies = $repository->findall();
        return $this->handleView($this->view($movies));
    }
    /**
     * Create Movie.
     * @Rest\Post("/movies")
     *
     * @return Response
     */
    public function postMovieAction(Request $request)
    {
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($movie);
            $em->flush();
            return $this->handleView($this->view(['status' => 'ok'], Response::HTTP_CREATED));
        }
        return $this->handleView($this->view($form->getErrors()));
    }
    /**
     * Delete Movie.
     * @Rest\Delete("/movies/delete/{id}")
     */
    public function deleteMovieAction($id)
    {
        $sn = $this->getDoctrine()->getManager();
        $movie = $sn->getRepository(Movie::class)->find($id);
        $sn->remove($movie);
        $sn->flush();
        return $this->handleView($this->view(["Movie was deleted."], Response::HTTP_CREATED));
    }
    /**
     * Update Movie.
     * @Rest\Update("/movies/update/{id}")
     */
    public function updateMovie($id, Request $request)
    {
        $sn = $this->getDoctrine()->getManager();
        $movie = $sn->getRepository(Movie::class)->find($id);
        $data = json_decode($request->getContent(), true);
        $newName = $data['name'];
        $newDescription = $data['description'];
        $movie->setName($newName);
        $movie->setDescription($newDescription);
        $sn->flush();
        return $this->handleView($this->view(["$newName : $newDescription"], Response::HTTP_CREATED));
    }
}