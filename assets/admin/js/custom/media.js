var gallery_images = [];

$(document).ready(function(){

/* DropZone */
Dropzone.autoDiscover = false;
$("#dZUpload").dropzone({
    url: api_url + 'main/upload_product_gallery_image',
    addRemoveLinks: true,
    autoProcessQueue: true,
    paramName: "gallery_image",
    maxFiles: 100000,
    init: function() {
        myDropzone = this;
        this.on("removedfile", function(file){
            if(file.hasOwnProperty('php_file_name')){ // Add
                const index = gallery_images.indexOf(file.php_file_name);
                if (index > -1) {
                  gallery_images.splice(index, 1);
                }
                $.ajax({
                    url: api_url + 'main/remove_product_image',
                    type:"POST",
                    data: {gallery_image:file.php_file_name},
                    success: function(resp){
                       console.log('file removed !!');
                    }
                });
            }else{ // Edit
               $.ajax({
                    url: api_url + 'main/remove_product_image',
                    type:"POST",
                    data: {gallery_image:file.name},
                    success: function(resp){
                       console.log('file removed !!');
                    }
                });
            }
        });
        let previous_media_images = $('input[name="previous_media_images"]').val();
        if(previous_media_images){
            $.each(JSON.parse(previous_media_images), function(key,value) {
              var mockFile = { name: value.name, size: value.size };
              myDropzone.emit("addedfile", mockFile);
              myDropzone.emit("thumbnail", mockFile, value.path);
              myDropzone.emit("complete", mockFile);
            });
            $('input[name="previous_media_images"]').removeAttr('name');
        }
    },
    success: function (file, response) {
        console.log('response',response)
        gallery_images.push(response);
        file.php_file_name = response;
        file.previewElement.classList.add("dz-success");
    },
    error: function (file, response) {
        showToaster('error',error,response);  
        file.previewElement.classList.add("dz-error");
        this.removeFile(file);
    }
});

});