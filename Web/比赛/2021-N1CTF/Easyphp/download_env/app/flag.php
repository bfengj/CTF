<?php

CLASS FLAG {
    private $_flag = 'n1ctf{************************}';
    public function __destruct(){
        echo "FLAG: " . $this->_flag;
    } 
}
