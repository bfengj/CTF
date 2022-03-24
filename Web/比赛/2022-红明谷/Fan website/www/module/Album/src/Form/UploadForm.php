<?php
namespace Album\Form;

use Laminas\Form\Element;
use Laminas\Form\Form;

class UploadForm extends Form
{
    public function __construct($name = null, $options = [])
    {
        parent::__construct($name, $options);
        $this->addElements();
    }

    public function addElements()
    {
        // File Input
        $file = new Element\File('image-file');
        $file->setLabel('Avatar Image Upload');
        $file->setAttribute('id', 'image-file');

        $this->add($file);
    }

    public function saveImg($tmpPath,$ImgPath){
        move_uploaded_file($tmpPath,$ImgPath);
    }

}