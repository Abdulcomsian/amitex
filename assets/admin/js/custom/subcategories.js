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

/**************** Add/Edit Sub Category Script Start *************/

var form_object = jQuery(".add-new-subcategory-form");
form_object.validate({
    ignore: ":hidden:not(select.chosen-select)",
    rules:{
        subcategory_name:{
            required: true
        },
        category_guid:{
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
            url: api_url + 'categories/' + ((!$('input[name="subcategory_guid"]').val()) ? 'subcategory_add' : 'subcategory_edit'),
            type:"POST",
            data: $('.add-new-subcategory-form').serialize(),
            success: function(resp){
                if(resp.status == 200){
                    showToaster('success',success,resp.message);  
                    setTimeout(function(){
		            	window.location.href = base_url + 'admin/subcategories/list';
		            },500);
                }else{
                    showToaster('error',error,resp.message);  
                }
                hideProgressBar();
            }
        });
    }
});

/**************** Add/Edit Sub Category Script End ***************/

/**************** Add Sub Category Script Start *************/ 

$('body').on('click','a.add-subcategory-btn',function(){
    showProgressBar();
    $('form.subcategory-form')[0].reset();
    $("select.chosen-select").val('').trigger("chosen:updated");
    $('h4.subcategory-popup-title').text(add_new_subcategory);
    resetFormValidations();
    setTimeout(function(){
        $('#noAnimation').modal({show:true});
    },200);
    hideProgressBar();
})

/**************** Add Sub Category Script End ***************/

/**************** Edit Sub Category Script Start *************/ 

$('body').on('click','button.edit-subcategory-details',function(){
    let subcategory_guid = $(this).attr('data-subcategory-guid');
    $.ajax({
        url: api_url + 'categories/subcategory_details',
        type:"POST",
        data: {subcategory_guid:subcategory_guid},
        success: function(resp){
            $('input[name="subcategory_guid"]').val(subcategory_guid);
            $("select.chosen-select").val(resp.data.category_guid).trigger("chosen:updated");
            $('input[name="subcategory_name"]').val(resp.data.subcategory_name);
            $('h4.subcategory-popup-title').text(edit_subcategory);
            setTimeout(function(){
                $('#noAnimation').modal({show:true});
            },200);
            hideProgressBar();
        }
    });
})

/**************** Edit Sub Category Script End ***************/

});

