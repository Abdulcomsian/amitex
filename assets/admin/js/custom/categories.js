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

/**************** Add/Edit Category Script Start *************/

var form_object = jQuery(".add-new-category-form");
form_object.validate({
    ignore: ":hidden:not(select.chosen-select)",
    rules:{
        category_name:{
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
            url: api_url + 'categories/' + ((!$('input[name="category_guid"]').val()) ? 'add' : 'edit'),
            type:"POST",
            data: $('.add-new-category-form').serialize(),
            success: function(resp){
                if(resp.status == 200){
                    showToaster('success',success,resp.message);  
                    setTimeout(function(){
		            	window.location.href = base_url + 'admin/categories/list';
		            },500);
                }else{
                    showToaster('error',error,resp.message);  
                }
                hideProgressBar();
            }
        });
    }
});

/**************** Add/Edit Category Script End ***************/

/**************** Add Category Script Start *************/ 

$('body').on('click','a.add-category-btn',function(){
    showProgressBar();
    $('form.category-form')[0].reset();
    $('h4.category-popup-title').text(add_new_category);
    resetFormValidations();
    setTimeout(function(){
        $('#noAnimation').modal({show:true});
    },200);
    hideProgressBar();
})

/**************** Add Category Script End ***************/

/**************** Edit Category Script Start *************/ 

$('body').on('click','button.edit-category-details',function(){
    let category_guid = $(this).attr('data-category-guid');
    $.ajax({
        url: api_url + 'categories/details',
        type:"POST",
        data: {category_guid:category_guid},
        success: function(resp){
            $('input[name="category_guid"]').val(category_guid);
            $('input[name="category_name"]').val(resp.data.category_name);
            $('h4.category-popup-title').text(edit_category);
            setTimeout(function(){
                $('#noAnimation').modal({show:true});
            },200);
            hideProgressBar();
        }
    });
})

/**************** Edit Category Script End ***************/

});

