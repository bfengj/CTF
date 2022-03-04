<?php

namespace ImiApp\ApiServer\Controller;

use Imi\App;
use Imi\Db\Db;
use Imi\Redis\Redis;
use Imi\Server\Http\Controller\HttpController;
use Imi\Server\Http\Route\Annotation\Action;
use Imi\Server\Http\Route\Annotation\Controller;
use Imi\Server\Http\Route\Annotation\Route;
use Imi\Server\View\Annotation\HtmlView;
use Imi\Server\View\Annotation\View;
use Imi\Server\Session\Session;

/**
 * @Controller("/")
 */
class IndexController extends HttpController
{
    /**
     * @Action
     * @Route("/")
     *
     * @return array
     */
    public function index()
    {
        return $this->response->redirect("/index.html");
    }

    /**
     * @Action
     * 
     * @return array
     */
    public function config()
    {
        $method = $this->request->getMethod();
        $res = [
            "msg" => "ok",
            "status" => "200",
            "value" => true
        ];

        if ($method === "POST") {
            Session::clear();
            $configData = $this->request->getParsedBody();
            foreach ($configData as $k => $v) {
                Session::set($k, $v);
            }
        } else if ($method === "GET") {
            $configData = Session::get();
            if ($configData != null) {
                $res["value"] = $configData;
            } else {
                $res = [
                    "msg" => "Not Find",
                    "status" => "404",
                    "value" => null
                ];
            }
        } else {
            $res = [
                "msg" => "Unsupported method",
                "status" => "405",
                "value" => false
            ];
        }
        return $res;
    }
}
