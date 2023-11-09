<?php

namespace api\demo\swagger\request;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(@OA\Xml(name="DemoArticlesSave"))
 */
class DemoArticlesSave
{

    /**
     * @OA\Property(format="int64")
     * @var int
     */
    public $id;

    /**
     * @OA\Property()
     * @var string
     */
    public $username;

    /**
     * @OA\Property()
     * @var string
     */
    public $firstName;

    /**
     * @OA\Property()
     * @var string
     */
    public $lastName;

    /**
     * @var string
     * @OA\Property()
     */
    public $email;

    /**
     * @var string
     * @OA\Property()
     */
    public $password;

    /**
     * @var string
     * @OA\Property()
     */
    public $phone;

    /**
     * User Status
     * @var int
     * @OA\Property(format="int32")
     */
    public $userStatus;
}
