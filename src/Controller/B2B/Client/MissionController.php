<?php

namespace App\Controller\B2B\Client;

use App\Constant\MissionStatus;
use App\Repository\MissionRepository;
use App\Repository\UserMissionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
/**
 * @Route("client/mission", name="b2b_client_mission_")
 * @Security("has_role('ROLE_USER')")
 */
class MissionController extends AbstractController
{

    /**
     * @Route("/list", name="list")
     */
    public function index(UserMissionRepository $missionRepo)
    {
        $missions['ongoing'] = $missionRepo->findOngoingMissions($this->getUser(),'client');
        $missions['cancelled'] = $missionRepo->findBy(['status' => MissionStatus::CANCELLED, 'client' => $this->getUser()],[]);
        $missions['terminated'] = $missionRepo->findBy(['status' => MissionStatus::TERMINATED, 'client' => $this->getUser()],[]);

        return $this->render('b2b/client/mission/index.html.twig', [
            'missions' => $missions,
        ]);
    }

    /**
     * @Route("/view/{id}",name="view")
     */
    public function view($id, UserMissionRepository $missionRepo)
    {
        $mission = $missionRepo->find($id);

        if(is_null($id) || is_null($mission) || ($this->getUser()->getId() != $mission->getClient()->getId()))
        {
            return $this->redirect('b2b_client_mission_list');
        }

        return $this->render('/b2b/client/mission/view.html.twig',
            [
               'mission' => $mission
            ]);
    }

    /**
     * @Route("/missions",name="missions")
     */
    public function missions(UserMissionRepository $missionRepo)
    {
        #Get the corresponding mission proposals that haven't been handled yet
        $missions = $missionRepo->findBy(['missionAgreedClient' => null, 'client' => $this->getUser()]);

        return $this->render('b2b/client/mission/missions.html.twig',[
            'missions' => $missions
        ]);
    }
}
