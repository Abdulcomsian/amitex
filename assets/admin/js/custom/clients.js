var clients_error = [];

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
	       aTargets: [0,8]
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

/**************** Add New Client Script Start *************/

var form_object = jQuery(".add-new-client-form");
form_object.validate({
  ignore: ":hidden:not(select.chosen-select)",
  rules:{
        first_name:{
            required: true
        },
        email:{
            email: true,
        },
        account_number:{
            required: true,
        },
        phone_number:{
            required: true,
        },
        password:{
            required: true
        },
        amitex_pricelist_guid:{
            required: true,
        },
        michal_pricelist_guid:{
            required: true,
        },
        percent_reduction:{
            required: true,
        },
        percent_reduction_michal:{
            required: true,
        },
        see_cart_total_amount:{
            required: true,
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
            url: api_url + 'clients/add',
            type:"POST",
            data: $('.add-new-client-form').serialize(),
            success: function(resp){
                if(resp.status == 200){
                    showToaster('success',success,resp.message);  
                    setTimeout(function(){
		            	window.location.href = base_url + 'admin/clients/list';
		            },500);
                }else{
                    showToaster('error',error,resp.message);  
                }
                hideProgressBar();
            }
        });
    }
});

/**************** Add New Client Script End ***************/


/**************** Edit Client Script Start ***************/

var form_object = jQuery(".edit-client-form");
form_object.validate({
    ignore: ":hidden:not(select.chosen-select)",
    rules:{
        first_name:{
            required: true
        },
        email:{
            email: true,
        },
        user_status:{
            required: true,
        },
        amitex_pricelist_guid:{
            required: true,
        },
        michal_pricelist_guid:{
            required: true,
        },
        percent_reduction:{
            required: true,
        },
        percent_reduction_michal:{
            required: true,
        },
        see_cart_total_amount:{
            required: true,
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
            url: api_url + 'clients/edit',
            type:"POST",
            data: $('.edit-client-form').serialize(),
            success: function(resp){
                if(resp.status == 200){
                    showToaster('success',success,resp.message);  
                    setTimeout(function(){
                        window.location.href = base_url + 'admin/clients/list';
                    },500);
                }else{
                    showToaster('error',error,resp.message);  
                }
                hideProgressBar();
            }
        });
    }
});

/**************** Edit Client Script End *****************/

/**************** Upload Clients CSV Script Start *************/

var form_object = jQuery(".upload-clients-form");
form_object.validate({
    ignore: ":hidden:not(select.chosen-select)",
    rules:{
        clients_csv:{
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
        ajaxindicatorstart();
        $.ajax({
            url: api_url + 'clients/upload',
            type:"POST",
            data: new FormData($(".upload-clients-form")[0]),
            dataType : "JSON",   
            cache: false,
            contentType: false,
            processData: false,
            success: function(resp){
                if(resp.status == 200){
                    let data = resp.data;
                    let errors = data.error_array;
                    $('#noAnimation').modal('hide');
                    if(parseInt(data.total_success_records) > 0){
                        $('div.success-area').removeClass('hidden');
                        $('div.success-area > strong').text(data.total_success_records+' clients uploaded successfully.');
                    }
                    if(errors.length > 0){
                        clients_error = errors;
                        let td_html = '';
                        for (var i = 0; i < errors.length; i++) {
                            td_html += "<tr><td>"+(i+1)+"</td><td>"+errors[i]+"</td></tr>";
                        }
                        $('table.error-datatable > tbody').html(td_html);
                        setTimeout(function(){
                            jQuery('.error-datatable').dataTable({
                                dom: 'Bfrtip',
                                buttons: [
                                    'colvis'
                                ],
                                aoColumnDefs: [{
                                   bSortable: false,
                                   aTargets: [0]
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
                            $('table.error-datatable').removeClass('hidden');
                            $('#clients_error').modal({show:true});
                        },500);
                    }else{
                        showToaster('success',success,data.total_success_records+' clients uploaded successfully.');  
                        setTimeout(function(){
                            window.location.href = base_url + 'admin/clients/list';
                        },500);
                    }
                }else{
                    showToaster('error',error,error);  
                }
                ajaxindicatorstop();
                hideProgressBar();
            }
        });
    }
});

/**************** Upload Clients CSV Script End ***************/

/**************** View Client Script Start *************/ 

$('body').on('click','button.view-user-details',function(){
    let user_guid = $(this).attr('data-user-guid');
    $.ajax({
        url: api_url + 'clients/details',
        type:"POST",
        data: {user_guid:user_guid,data_type:'html'},
        success: function(resp){
            $('div#modal-section').html(resp);
            setTimeout(function(){
                $('#noAnimation').modal({show:true});
            },200);
            hideProgressBar();
        }
    });
})

/**************** View Client Script End ***************/

/**************** Upload Clients Script Start *************/ 

$('body').on('click','a.upload-clients-btn',function(){
    showProgressBar();
    $('form.upload-clients-form')[0].reset();
    resetFormValidations();
    setTimeout(function(){
        $('#noAnimation1').modal({show:true});
    },200);
    hideProgressBar();
})

/**************** Upload Clients Script End ***************/

$('body').on('click','button.download-report',function(){
    showProgressBar();

    //define the heading for each row of the data  
    var csv = 'Error descprition\n';  
      
    //merge the data with CSV  
    clients_error.forEach(function(row) {  
        csv += row;  
        csv += "\n";  
    });  
   
    var hiddenElement = document.createElement('a');  
    hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);  
    hiddenElement.target = '_blank';  
      
    //provide the name for the CSV file to be downloaded  
    hiddenElement.download = 'clients-errors.csv';  
    hiddenElement.click();  
    hideProgressBar();
})

});

