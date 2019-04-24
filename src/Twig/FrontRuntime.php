<?php
namespace App\Twig;

use App\Entity\CardCategory;
use App\Entity\Menu;
use App\Entity\Option;
use App\Entity\Region;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;

class FrontRuntime
{
    private $em;
    private $router;
    private $cache;

    public function __construct(EntityManagerInterface $em, RouterInterface $router, SessionInterface $session, CacheItemPoolInterface $cache)
    {
        $this->em = $em;
        $this->router = $router;
        $this->cache = $cache;
    }

    /**
     * Get global settings from the database
     *
     * @return  array
     */
    public function options(){
        $optionsRepo = $this->em->getRepository(Option::class);

        $options = [];
        foreach($optionsRepo->findAllCache() as $option){
            $options[$option->getSlug()] = $option->getValue();
        }

        return $options;
    }

    /**
     * Get regions list
     *
     * @return  array
     */
    public function regions(){
        $regionsRepo= $this->em->getRepository(Region::class);
        $regions = $regionsRepo->findAllActive();
        return $regions;
    }

    /**
     * Get categories list
     *
     * @return  array
     */
    public function categories(){
        $categoriesRepo= $this->em->getRepository(CardCategory::class);
        $categories = $categoriesRepo->findAllActive();
        return $categories;
    }

    /**
     * Get menu by slug
     *
     * @param   string  $slug
     * @return  string
     */
    public function menu($slug, $class){
        $optionsReposity = $this->em->getRepository(Menu::class);
        $menu = $optionsReposity->findOneBySlug($slug);

        $html = "";

        foreach($menu->getItems() as $item){
            $html .= "<li>";
                if($item->getPage()){
                    $html .= "<a href='".$this->router->generate("front_pages_index", ["slug" => $item->getPage()->getSlug()])."'>".$item->getPage()->getName()."</a>";
                }
                else{
                    $html .= "<a href='".$item->getLink()."' target='".($item->getBlank()?'_blank':'_self')."'>".$item->getName()."</a>";
                }
            $html .= "</li>";
        }

        return "<ul class='$class'>".$html."</ul>";
    }

    /**
     * Get Instagram feed from the API
     *
     * @return  string
     */
    public function instagramFeed(){

        $cachedInstagramFeed = $this->cache->getItem("instagram.feed");
        if(!empty($cachedInstagramFeed->get())){
            return $cachedInstagramFeed->get();
        }

        $options = $this->options();

        if(!$options["instagram_token"]){
            return "Instagram Token Not Found";
        }

        $access_token = $options["instagram_token"];

        $images = [];

        if(!$json = @file_get_contents("https://api.instagram.com/v1/users/self/media/recent/?access_token={$access_token}&count=8")){
            return [];
        }
        else{
            $obj = json_decode(preg_replace('/("\w+"):(\d+)/', '\\1:"\\2"', $json), true);
            foreach ($obj['data'] as $post){

                $pic_text = $post['caption']['text'];
                $pic_link = $post['link'];
                $pic_src=str_replace("http://", "https://", $post['images']['thumbnail']['url']);

                $images[] = [
                    "link" => $pic_link,
                    "image" => $pic_src,
                    "title" => $pic_text,
                ];
            }

            $cachedInstagramFeed->set($images);
            $cachedInstagramFeed->expiresAfter(60 * 60 * 4);
            $this->cache->save($cachedInstagramFeed);
        }


        return $images;

    }
}