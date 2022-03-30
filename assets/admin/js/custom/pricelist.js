$(document).ready(function(){

/**************** Datatable Script Start *************/

if($('table').hasClass('my-datatable')){
	jQuery('.my-datatable').dataTable({
		dom: 'Bfrtip',
	    buttons: [
	        'colvis'
	    ],
	    aoColumnDefs: [{
	       bSortable: false,
	       aTargets: [0,4]
	    },
	 	],
        language : {
            search : "חיפוש",
            paginate: {
                first:      "ראשון",
                previous:   "הקודם",
                next:       "הבא",
                last:       "אחרון"
            },
            info : 'מראה _PAGE_ שֶׁל _PAGES_',
            infoFiltered:   "(מסונן מ _MAX_ סך השיאים)",
            zeroRecords : 'אין נתונים זמינים בטבלה'
        }
	  });
}

/**************** Datatable Script End *************/

/**************** Add/Edit Price List Script Start *************/

var form_object = jQuery(".add-new-pricelist-form");
form_object.validate({
    ignore: ":hidden:not(select.chosen-select)",
    rules:{
        pricelist_name:{
            required: true
        },
        pricelist_brand:{
            required: true
        },
        products_csv:{
            required: true
        }
    },
    highlight: function(element) {
      jQuery(element).closest('.form-group').removeClass('has-success').addClass('has-error');
    },
    success: function(element) {
      jQuery(element).closest('.form-group').removeClass('has-error').addClass('has-success');
      jQuery(element[0]).remove();
    },
    submitHandler: function() {
        $.ajax({
            url: api_url + 'pricelist/' + ((!$('input[name="pricelist_guid"]').val()) ? 'add' : 'edit'),
            type:"POST",
            data: new FormData($(".add-new-pricelist-form")[0]),
            dataType : "JSON",   
            cache: false,
            contentType: false,
            processData: false,
            success: function(resp){
                if(resp.status == 200){
                    showToaster('success',success,resp.message);  
                    setTimeout(function(){
		            	window.location.href = base_url + 'admin/pricelist/list';
		            },500);
                }else{
                    showToaster('error',error,resp.message);  
                }
                hideProgressBar();
            }
        });
    }
});

/**************** Add/Edit Price List Script End ***************/

/**************** Add Price List Script Start *************/ 

$('body').on('click','a.add-pricelist-btn',function(){
    showProgressBar();
    $('form.pricelist-form')[0].reset();
    $('input[name="pricelist_guid"]').val("");
    $('h4.pricelist-popup-title').text(add_new_price_list);
    resetFormValidations();
    setTimeout(function(){
        $('#noAnimation').modal({show:true});
    },200);
    hideProgressBar();
})

/**************** Add Price List Script End ***************/

/**************** Edit Price List Script Start *************/ 

$('body').on('click','button.edit-pricelist-details',function(){
    let pricelist_guid = $(this).attr('data-pricelist-guid');
    $.ajax({
        url: api_url + 'pricelist/details',
        type:"POST",
        data: {pricelist_guid:pricelist_guid},
        success: function(resp){
            $('input[name="pricelist_guid"]').val(pricelist_guid);
            $('input[name="pricelist_name"]').val(resp.data.pricelist_name);
            $('input[name="pricelist_brand"][value="'+resp.data.pricelist_brand+'"]').attr('checked','checked');
            $('input[name="is_main_pricelist"][value="'+resp.data.is_main_pricelist+'"]').attr('checked','checked');
            $('h4.pricelist-popup-title').text(edit_price_list);
            $('div.main-pricelist-section').removeClass('hidden');
            setTimeout(function(){
                $('#noAnimation').modal({show:true});
            },200);
            hideProgressBar();
        }
    });
})

/**************** Edit Price List Script End ***************/


$('body').on('click','a.product-download-btn',function(){
    var pricelist_brand = $('input[name="pricelist_brand"]:checked').val();
    document.location.href = base_url + 'admin/pricelist/download_product_csv?pricelist_brand='+pricelist_brand+'&pricelist_guid='+$('input[name="pricelist_guid"]').val();
});


});

