<?php
namespace Album\Controller;

use Album\Model\AlbumTable;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Album\Form\AlbumForm;
use Album\Form\UploadForm;
use Album\Model\Album;

class AlbumController extends AbstractActionController
{
    // Add this property:
    private $table;
    private $white_list;

    public function __construct(AlbumTable $table){
        $this->table = $table;
        $this->white_list = array('.jpg','.jpeg','.png');
    }

    public function indexAction()
    {
        return new ViewModel([
            'albums' => $this->table->fetchAll(),
        ]);
    }

    public function addAction()
    {
        $form = new AlbumForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (! $request->isPost()) {
            return ['form' => $form];
        }

        $album = new Album();
        $form->setInputFilter($album->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return ['form' => $form];
        }

        $album->exchangeArray($form->getData());
        $this->table->saveAlbum($album);
        return $this->redirect()->toRoute('album');
    }


    public function editAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);

        if (0 === $id) {
            return $this->redirect()->toRoute('album', ['action' => 'add']);
        }

        // Retrieve the album with the specified id. Doing so raises
        // an exception if the album is not found, which should result
        // in redirecting to the landing page.
        try {
            $album = $this->table->getAlbum($id);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('album', ['action' => 'index']);
        }

        $form = new AlbumForm();
        $form->bind($album);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['id' => $id, 'form' => $form];

        if (! $request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($album->getInputFilter());
        $form->setData($request->getPost());

        if (! $form->isValid()) {
            return $viewData;
        }

        $this->table->saveAlbum($album);

        // Redirect to album list
        return $this->redirect()->toRoute('album', ['action' => 'index']);
    }


    public function deleteAction()
    {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('album');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->table->deleteAlbum($id);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('album');
        }

        return [
            'id'    => $id,
            'album' => $this->table->getAlbum($id),
        ];
    }


    public function imgdeleteAction()
    {
        $request = $this->getRequest();
        if(isset($request->getPost()['imgpath'])){
            $imgpath = $request->getPost()['imgpath'];
            $base = substr($imgpath,-4,4);
            if(in_array($base,$this->white_list)){     //白名单
                @unlink($imgpath);
            }else{
                echo 'Only Img File Can Be Deleted!';
            }
        }
    }
    public function imguploadAction()
    {
        $form = new UploadForm('upload-form');

        $request = $this->getRequest();
        if ($request->isPost()) {
            // Make certain to merge the $_FILES info!
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );

            $form->setData($post);
            if ($form->isValid()) {
                $data = $form->getData();
                $base = substr($data["image-file"]["name"],-4,4);
                if(in_array($base,$this->white_list)){   //白名单限制
                    $cont = file_get_contents($data["image-file"]["tmp_name"]);
                    if (preg_match("/<\?|php|HALT\_COMPILER/i", $cont )) {
                        die("Not This");
                    }
                    if($data["image-file"]["size"]<3000){
                        die("The picture size must be more than 3kb");
                    }
                    $img_path = realpath(getcwd()).'/public/img/'.md5($data["image-file"]["name"]).$base;
                    echo $img_path;
                    $form->saveImg($data["image-file"]["tmp_name"],$img_path);
                }else{
                    echo 'Only Img Can Be Uploaded!';
                }
                // Form is valid, save the form!
                //return $this->redirect()->toRoute('upload-form/success');
            }
        }

        return ['form' => $form];
    }

}
