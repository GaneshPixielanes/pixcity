<?php

namespace App\Controller\Admin\Pages;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Form\Admin\CardExtractionsType;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag; 
use Symfony\Component\HttpFoundation\RedirectResponse;

use App\Repository\CardCategoryRepository;

use App\Repository\CardRepository;
/**
 * @Route("/admin/card/extractions", name="admin_card_extractions_")
 * @Security("has_role('ROLE_MODERATOR')")
 */
class CardExtractionsController extends Controller
{
    /**
     * @Route("", name="search")
     * @Method({"GET"})
     */
    public function index(Request $request)
    {
        $filterForm = $this->createForm(CardExtractionsType::class);
        $filterForm->handleRequest($request);

        $data = $request->query->get('card_extractions');
        $query =$date_from=$date_to='';
        if(isset($data)){
            $date_from = new \DateTime($data['startDate']);
            $date_to = new \DateTime($data['endDate']);

            $em = $this->getDoctrine()->getEntityManager();
            $qb = $em->createQueryBuilder();

            $query = $qb
                ->select(["c", "u", "r", "d"])
                ->from('App\Entity\Card', 'c')
                ->leftJoin('c.address', 'u')
                ->leftJoin('c.region', 'r')
                ->leftJoin('c.department', 'd')
                ->where('c.createdAt >= :date_from')
                ->andWhere('c.createdAt <= :date_to')
                ->setParameter('date_from',$date_from->format('Y-m-d'))
                ->setParameter('date_to', $date_to->format('Y-m-d'))
                ->getQuery()
                ->getResult();
            ;
        }
        return $this->render('admin/card_extractions/index.html.twig',[
            'filterForm' => $filterForm->createView(),
            'list' => $query,
            'date_from' =>$date_from,
            'date_to' =>$date_to
        ]);
    }


    /**
     * @Route("/export", name="export")
     * @Method({"GET", "POST"})
     */
    public function export(Request $request)
    {		 
		$startDate = $request->query->get('startDate');		 
		$endDate = $request->query->get('endDate');
		
		if($startDate != null && $endDate != null){
			//$data = $request->query->get('card_extractions');
			$date_from = new \DateTime($startDate['date']);
			$date_to = new \DateTime($endDate['date']);  
			
			$em = $this->getDoctrine()->getEntityManager();
			$qb = $em->createQueryBuilder();
			
			$query = $qb
				->select(["c", "u", "r", "d"])
				->from('App\Entity\Card', 'c')
				->leftJoin('c.address', 'u')
				->leftJoin('c.region', 'r')
				->leftJoin('c.department', 'd')  
				->where('c.createdAt >= :date_from')
				->andWhere('c.createdAt <= :date_to')			 
				->setParameter('date_from',$date_from->format('Y-m-d'))
				->setParameter('date_to', $date_to->format('Y-m-d'))
				->getQuery()
				->getResult();
			;
			
			//if(count($query)){ 		
				$spreadsheet = new Spreadsheet();
				/* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
				
				//$sheet = $spreadsheet->getActiveSheet();
				$spreadsheet->setActiveSheetIndex(0);
				$activeSheet = $spreadsheet->getActiveSheet();
				//$sheet->setCellValue("A1", 'Hello World !')->mergeCells('A1:G1');
				//$sheet->setTitle("Card extractions");
				
				$columnNames = ["TITLE","URL","REGION","DEPARTMENT","CITY","CATEGORIES","CREATED DATE"];
				$columnLetter = 'A';
				foreach ($columnNames as $columnName) {
					// Allow to access AA column if needed and more
					
					$activeSheet->setCellValue($columnLetter.'1', $columnName);
					$columnLetter++;
				}
				
				$i = 2;			 
				foreach($query as $key=>$value){
					//dump($value);die();
					$activeSheet->setCellValue('A'.$i ,$value->getName());
					$activeSheet->setCellValue('B'.$i ,"https://www.pix.city/".$value->getSlug().".html");
					$activeSheet->setCellValue('C'.$i ,($value->getRegion())? $value->getRegion()->getName():'');
					$activeSheet->setCellValue('D'.$i ,($value->getDepartment())? $value->getDepartment()->getName():'');
					$activeSheet->setCellValue('E'.$i ,($value->getAddress())? $value->getAddress()->getCity():'');
					$activeSheet->setCellValue('F'.$i ,'');
					$activeSheet->setCellValue('G'.$i ,($value->getCreatedAt())? $value->getCreatedAt()->format('Y-m-d'):'');
					$i++;				
				} 
				
				// Create your Office 2007 Excel (XLSX Format)
				$writer = new Xlsx($spreadsheet);
				
				// Create a Temporary file in the system
				$fileName = 'card_extractions_'.$date_from->format('Y-m-d').'_'.$date_to->format('Y-m-d').'.xlsx';
				$temp_file = tempnam(sys_get_temp_dir(), $fileName);
				
				// Create the excel file in the tmp directory of the system
				$writer->save($temp_file);
				
				// Return the excel file as an attachment
				return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
			/*}
			else{
				return "";
			}*/
		}else{
			return $this->redirectToRoute('admin_card_extractions_search');
		}
		
    }
	/**
     * @Route("categories", name="search_categories")
     * @Method({"GET"})
     */
    public function categoriesIndex(Request $request)
    {
        $filterForm = $this->createForm(CardExtractionsType::class);
        $filterForm->handleRequest($request);

        $data = $request->query->get('card_extractions');
        $query =$date_from=$date_to='';

        if(isset($data)){
            $date_from = new \DateTime($data['startDate']);
            $date_to = new \DateTime($data['endDate']);

            $em = $this->getDoctrine()->getEntityManager();
            $qb = $em->createQueryBuilder();

            $query = $qb
                ->select(["c", "u", "r", "d"])
                ->from('App\Entity\Card', 'c')
                ->leftJoin('c.address', 'u')
                ->leftJoin('c.region', 'r')
                ->leftJoin('c.department', 'd')
                ->where('c.createdAt >= :date_from')
                ->andWhere('c.createdAt <= :date_to')
                ->setParameter('date_from', $date_from)
                ->setParameter('date_to', $date_to)
                ->getQuery()
                ->getResult();
            ;
        }
        return $this->render('admin/card_extractions/categories.html.twig',[
            'filterForm' => $filterForm->createView(),
            'list' => $query,
            'date_from' =>$date_from,
            'date_to' =>$date_to
        ]);
    }

	/**
     * @Route("/export/category", name="exportbycategory")
     * @Method({"GET", "POST"})
     */
    public function exportByCategory(Request $request, CardCategoryRepository $cardCategoryRepo)
    {	
		$startDate = $request->query->get('startDate');		 
		$endDate = $request->query->get('endDate');
		
		if($startDate != null && $endDate != null){
			//$data = $request->query->get('card_extractions');
			$date_from = new \DateTime($startDate['date']);
			$date_to = new \DateTime($endDate['date']);  
		 
			$em = $this->getDoctrine()->getEntityManager();
			$qb = $em->createQueryBuilder();
			
			$query = $qb
				->select(["c", "u", "r", "d"])
				->from('App\Entity\Card', 'c')
				->leftJoin('c.address', 'u')
				->leftJoin('c.region', 'r')
				->leftJoin('c.department', 'd') 
				->where('c.createdAt >= :date_from')
				->andWhere('c.createdAt <= :date_to')			 
				->setParameter('date_from', $date_from)
				->setParameter('date_to', $date_to)
				->getQuery()
				->getResult();
			;
			
			//if(count($query)){ 		
				$spreadsheet = new Spreadsheet();
				/* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
				
				$spreadsheet->setActiveSheetIndex(0);
				$activeSheet = $spreadsheet->getActiveSheet();
				//$sheet->setTitle("Card extractions");
				
				$columnNames = ["CARD ID","CARD TITLE","CARD URL","CATEGORIES","","","CATEGORY","NO OF CARDS"];
				$columnLetter = 'A';
				foreach ($columnNames as $columnName) {
					// Allow to access AA column if needed and more
					
					$activeSheet->setCellValue($columnLetter.'1', $columnName);
					$columnLetter++;
				}
				
				$i = 2;
				foreach($query as $key=>$value){				
					if($value->getCategories() != null){
						$string = "";
						foreach($value->getCategories() as $k=>$v){
							$nameCategories = ($v->getName())? $v->getName(): '';
							$string .= ",".$nameCategories; 						
						}
						$string = substr($string, 1);
					}
					$activeSheet->setCellValue('A'.$i ,$value->getId());
					$activeSheet->setCellValue('B'.$i ,$value->getName());
					$activeSheet->setCellValue('C'.$i ,"https://www.pix.city/".$value->getSlug().".html");
					$activeSheet->setCellValue('D'.$i ,$string);	
					$i++;
				} 
				$j = 2;
				foreach ($cardCategoryRepo->findAllActiveWithCardsDesc() as $cardCategoryWithCount) {
					$activeSheet->setCellValue('G'.$j ,$cardCategoryWithCount[0]->getSlug());
					$activeSheet->setCellValue('H'.$j ,$cardCategoryWithCount[1]);
					$j++;
				}
				
				// Create your Office 2007 Excel (XLSX Format)
				$writer = new Xlsx($spreadsheet);
				
				// Create a Temporary file in the system
				$fileName = 'card_extractions_'.$date_from->format('Y-m-d').'_'.$date_to->format('Y-m-d').'.xlsx';
				$temp_file = tempnam(sys_get_temp_dir(), $fileName);
				
				// Create the excel file in the tmp directory of the system
				$writer->save($temp_file);
				
				// Return the excel file as an attachment
				return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
			/*}
			else{
				return "";
			}*/
		}else{
			return $this->redirectToRoute('admin_card_extractions_search_categories');
		}

    }

}
