<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     */
    public function index(Request $request): Response
    {
        $con = mysqli_init();
        $key = $request->query->get('key');
        $value = $request->query->get('value');

        if (is_numeric($key) && is_string($value)) {
            mysqli_options($con, $key, $value);
        }
        
        mysqli_options($con, MYSQLI_OPT_LOCAL_INFILE, 0);
        if (!mysqli_real_connect($con, "127.0.0.1", "ctf", "gmlsec123456", "mysql")) {
            $content = '数据库连接失败';
        } else {
            $content = '数据库连接成功';
        }

        mysqli_close($con);

        return new Response(
            $content,
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );
    }
}

