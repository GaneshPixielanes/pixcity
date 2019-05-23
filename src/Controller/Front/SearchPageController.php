<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class SearchPageController extends Controller
{

    protected function getSearchParams(
        Request $request
    ){
        $params = [
            "text" => $request->query->get("search", $request->request->get("search", null)),
            "categories" => $request->query->get("categories", $request->request->get("categories", null)),
            "regions" => $request->query->get("regions", $request->request->get("regions", null)),
            "skills" => $request->query->get("skills", $request->request->get("skills", null)),
            "users" => $request->query->get("users", $request->request->get("users", null)),
            "pixie" => $request->query->get("pixie", $request->request->get("pixie", null)),
            "orderby" => $request->query->get("orderby", $request->request->get("orderby", null)),
            "userFavorite" => $request->query->get("userFavorite", $request->request->get("userFavorite", null)),
            "type" => $request->query->get("type", $request->request->get("type", "cards"))
        ];

        return $params;
    }

}