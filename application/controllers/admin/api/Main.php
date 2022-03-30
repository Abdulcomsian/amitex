<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends API_Controller {

    function __construct() {
        parent::__construct();
    }

    /*
      Description:  To upload product gallery image
      URL:          /admin/api/main/upload_product_gallery_image/
    */
    public function upload_product_gallery_image_post() {

        /* Upload gallery image */
        if(!empty($_FILES['gallery_image']['name'])){
            $image_data = fileUploading('gallery_image','products','jpg|jpeg|png|gif',"","","","same");
            if(!empty($image_data['error'])){
                $this->response($image_data['error'],500);exit;
            }
            $this->response($image_data['upload_data']['file_name'],200);
        }else{
            $this->response(lang('select_gallery_image'),500);
        }
    }

    /*
      Description:  To remove product gallery image
      URL:          /admin/api/main/remove_product_image/
    */
    public function remove_product_image_post() {

        /* Remove gallery image */
        unlink(FCPATH.'uploads/products/'.$this->Post['gallery_image']);
    }

}
