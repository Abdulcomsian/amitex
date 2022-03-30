$(document).ready(function(){


/**************** Datatable Script Start *************/

if($('table').hasClass('my-datatable')){
    jQuery('.my-datatable').dataTable({
        dom: 'Bfrtip',
        buttons: [
            'colvis'
        ],
        aoColumnDefs: [{
           bSortable: false  },
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

/**************** Admin Profile Script Start *************/

var form_object = jQuery(".profile-form"); 
form_object.validate({
  rules:{
        current_password:{
            required: true,
        },
        new_password:{
            required: true,
            minlength:6,
            maxlength:14
        },
        confirm_password:{
            required: true,
            minlength:6,
            maxlength:14,
            equalTo  :"#new_password"
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
            url: api_url + 'dashboard/change_password',
            type:"POST",
            data: $('#profile-form').serialize(),
            success: function(resp){
                if(resp.status == 200){
                    $('#profile-form')[0].reset();
                    showToaster('success',success,resp.message);  
                }else{
                    showToaster('error',error,resp.message);  
                }
                hideProgressBar();
            }
        });
    }
});

/**************** Admin Profile Script End ***************/

/**************** Edit My Profile Script Start ***************/

var form_object = jQuery(".edit-profile-form");
form_object.validate({
    ignore: ":hidden:not(select.chosen-select)",
    rules:{
        first_name:{
            required: true,
        },
        last_name:{
            required: true,
        },
        email:{
            email: true,
        },
        phone_number:{
            required: true,
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
            url: api_url + 'dashboard/edit_profile',
            type:"POST",
            data: $('.edit-profile-form').serialize(),
            success: function(resp){
                if(resp.status == 200){
                    showToaster('success',success,resp.message);  
                }else{
                    showToaster('error',error,resp.message);  
                }
                hideProgressBar();
            }
        });
    }
});
/**************** Edit Staff Script End *****************/

$('body').on('click','a.add-more-emails',function(){
    let html = '<div class="row m-t-10"><div class="col-sm-8">';
        html += '<input type="email" class="form-control" name="email_addresses[]" placeholder="'+email_address+'" maxlength="250" autocomplete="off">';
        html += '</div><div class="col-sm-4"><a href="javascript:void(0);" style="width:80px;" class="btn btn-danger remove-email">Remove</a>';
        html += '</div></div>';
    $('div.more-emails').append(html);
});

$('body').on('click','a.remove-email',function(){
    $(this).parent().parent().remove();
});

});