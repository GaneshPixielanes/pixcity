<?php

namespace App\Controller\B2B\Client;

use App\Entity\Page;
use App\Repository\PackRepository;
use App\Repository\SkillRepository;
use App\Repository\UserPacksRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/freelance/", name="b2b_front_community_manager_")
 */
class CommunityManagerController extends AbstractController
{
    /**
     * @Route("community-manager-{city}/{name}/{id}", name="view")
     */
    public function index($id, UserRepository $userRepo, UserPacksRepository $packRepo,SkillRepository $skillRepository)
    {

        $user = $userRepo->find($id);
        $packs = $packRepo->findBy([
            'user' => $user,
            'deleted' => null
        ]);
        if($user->getB2bCmApproval() != 1 || count($packs) == 0)
        {
            return $this->redirectToRoute('b2b_client_search_index');
        }
        // Check if the user exists
        if(is_null($user) && !in_array('ROLE_CM', $user->getRoles()))
        {
            return $this->redirect('/client/search');
        }

        #SEO
        $page = new Page();
        $page->setMetaTitle($user.":".$user->getUserSkill()->first()." local à ".$user->getPixie()->getBilling()->getAddress()->getCity());
        $page->setMetaDescription('Retrouvez toutes les offres de '.$user.' pour des missions de '.$user->getUserSkill()->first().' près de chez vous à '.$user->getPixie()->getBilling()->getAddress()->getCity());


        $skills = $skillRepository->findAll();

        return $this->render('b2b/client/community_manager/index.html.twig', [
            'user' => $user,
            'packs' => $packs,
            'skills' => $skills,
            'page' => $page
        ]);
    }

    /**
     * @Route("{slug}/{id}",name="pack_view")
     */
    public function viewPack($id, UserPacksRepository $userPacksRepo)
    {

        $pack = $userPacksRepo->findBy(['id' => $id,'active' => null,'deleted' => null]);


        if(empty($pack))
        {
            return $this->redirect('/freelance/search');
        }

        $slug = $this->createSlug($pack[0]->getTitle());

        $session  = new Session();
        $session->set('chosen_pack_url', '/freelance/pack/'.$slug.'/'.$pack[0]->getId());

        #SEO
        $page = new Page();
        $page->setMetaTitle($pack[0]->getTitle());
        $page->setMetaDescription(substr($pack[0]->getDescription(),0,160));


        return $this->render('b2b/client/community_manager/pack_detail.html.twig',[
            'pack' => $pack[0],
            'page' => $page
        ]);
    }


    /**
     * Function used to create a slug associated to an "ugly" string.
     *
     * @param string $string the string to transform.
     *
     * @return string the resulting slug.
     */
    public function createSlug($string) {
        $table = array(
            'Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
            'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
            'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss',
            'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
            'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
            'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
            'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r', '/' => '-', ' ' => '-'
        );
        // -- Remove duplicated spaces
        $stripped = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $string);
        // -- Returns the slug
        return strtolower(strtr($string, $table));
    }




}
