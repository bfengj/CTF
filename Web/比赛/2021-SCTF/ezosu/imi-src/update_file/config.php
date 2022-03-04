<?php

use Imi\Log\LogLevel;
return [
    'configs'    =>    [
    ],
    // bean扫描目录
    'beanScan'    =>    [
        'ImiApp\ApiServer\Controller',
    ],
    'beans'    =>    [
        'SessionManager'    =>    [
            'handlerClass'    =>    \Imi\Server\Session\Handler\File::class,
        ],
        'SessionFile'    =>    [
            'savePath'    =>    dirname(__DIR__, 2) . '/.runtime/.session/',
            'formatHandlerClass'    =>   \Imi\Util\Format\PhpSession::class,
        ],
        'SessionConfig'    =>    [
            'name'    =>    'PHPSESSION',
        ],
        'SessionCookie'    =>    [
            'enable'    =>  true,
            'lifetime'    =>    86400 * 30,
            'httponly' => true,
        ],
        'HttpDispatcher'    =>    [
            'middlewares'    =>    [
                \ImiApp\ApiServer\Middleware\PoweredBy::class,
                \Imi\Server\Session\Middleware\HttpSessionMiddleware::class,
                \Imi\Server\Http\Middleware\RouteMiddleware::class,
            ],
        ],
        'HtmlView'    =>    [
            'templatePath'    =>    dirname(__DIR__) . '/template/',
            // 支持的模版文件扩展名，优先级按先后顺序
            'fileSuffixs'        =>    [
                'tpl',
                'html',
                'php'
            ],
        ]
    ],
];