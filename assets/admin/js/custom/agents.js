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

/**************** Add New Agent Script Start *************/

var form_object = jQuery(".add-new-agent-form");
form_object.validate({
  ignore: ":hidden:not(select.chosen-select)",
  rules:{
        first_name:{
            required: true
        },
        last_name:{
            required: true
        },
        email:{
            required: true,
            email: true,
        },
        phone_number:{
            required: true,
        },
        password:{
            required: true,
            minlength:6
        },
        gender:{
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
            url: api_url + 'agents/add',
            type:"POST",
            data: $('.add-new-agent-form').serialize(),
            success: function(resp){
                if(resp.status == 200){
                    showToaster('success',success,resp.message);  
                    setTimeout(function(){
		            	window.location.href = base_url + 'admin/agents/list';
		            },500);
                }else{
                    showToaster('error',error,resp.message);  
                }
                hideProgressBar();
            }
        });
    }
});

/**************** Add New Agent Script End ***************/


/**************** Edit Agent Script Start ***************/

var form_object = jQuery(".edit-agent-form");
form_object.validate({
    ignore: ":hidden:not(select.chosen-select)",
    rules:{
        first_name:{
            required: true
        },
        last_name:{
            required: true
        },
        gender:{
            required: true,
        },
        user_status:{
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
            url: api_url + 'agents/edit',
            type:"POST",
            data: $('.edit-agent-form').serialize(),
            success: function(resp){
                if(resp.status == 200){
                    showToaster('success',success,resp.message);  
                    setTimeout(function(){
                        window.location.href = base_url + 'admin/agents/list';
                    },500);
                }else{
                    showToaster('error',error,resp.message);  
                }
                hideProgressBar();
            }
        });
    }
});

/**************** Edit Agent Script End *****************/

/**************** View Agent Script Start *************/ 

$('body').on('click','button.view-user-details',function(){
    let user_guid = $(this).attr('data-user-guid');
    $.ajax({
        url: api_url + 'agents/details',
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

/**************** View Agent Script End ***************/



});

