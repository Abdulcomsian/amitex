$(document).ready(function(){

/**************** User Edit Profile Script Start *************/

$("#profile-form").submit(function(event) {
	event.preventDefault();
	var form_data = new FormData($('#profile-form')[0]);
	$.ajax({
            url  : api_url + "user/edit_profile",
            type : "POST",
            data : form_data,   
            dataType : "JSON",   
            cache: false,
            contentType: false,
            processData: false,   
            success: function(resp){
	           if(resp.status == 200){
                    showToaster('success','Success !',resp.message);  
                }else{
                    showToaster('error','Error !',resp.message);  
                }
                hideProgressBar();
	        }
        });
});

/**************** User Edit Profile Script End **************/

$('body').on('click','a.add-more-material',function(){
    var html = '<tr>';
        html += '<td colspan="2"><input class="form-control" type="text" name="materials[]" value="" /></td>';
        html += '<td><input class="form-control custom_feild" type="text" name="material_prices[]" value="" /></td>';
        html += '<td><input class="form-control custom_feild" type="text" name="material_miles[]" value="" /></td>';
        html += '<td><a href="javascript:void();" class="btn btn-danger remove-row">-</a></td>';
        html += '</tr>';
    $('tr.first-tr').after(html);
})

$('body').on('click','a.add-more-date-times',function(){
    var html = '<tr>';
        html += '<td><input class="form-control datepicker" readonly style="min-width: 160px;" type="text" name="dates[]" value="" /></td>';
        html += '<td><input class="form-control" type="text" name="time_onsites[]" value="" /></td>';
        html += '<td><input class="form-control" type="text" name="time_offsites[]" value="" /></td>';
        html += '<td><input class="form-control" type="text" name="site_hourse[]" value="" /></td>';
        html += '<td><input class="form-control" type="text" name="travel_hourse[]" value="" /></td>';
        html += '<td><input class="form-control" type="text" name="over_time[]" value="" /></td>';
        html += '<td><input class="form-control" type="text" name="eng[]" value="" /></td>';
        html += '<td><a href="javascript:void();" class="btn btn-danger remove-row">-</a></td>';
        html += '</tr>';
    $('tr.first-datetime-row').after(html);
    $("input.datepicker").datepicker({
      showOn: 'button', 
      buttonImageOnly: true, 
      buttonImage: '../assets/img/calendar.png',
      changeMonth: true,
      changeYear: true,
      dateFormat: "dd/mm/yy"
    }); 
})

$('body').on('click','a.remove-row',function(){
    $(this).parent().parent().remove();
});

if($('input').hasClass('datepicker')){
   $("input.datepicker").datepicker({
      showOn: 'button', 
      buttonImageOnly: true, 
      buttonImage: '../assets/img/calendar.png',
      changeMonth: true,
      changeYear: true,
      dateFormat: "dd/mm/yy"
    }); 
}

/**************** Submit Job Worksheet Script Start *************/

$("#job-form").submit(function(event) {
    event.preventDefault();
    let job_status = $('select[name="job_status"]').val();
    if(job_status === 'Completed'){
        swal(
          {
            title: 'Want to complete job ?',
            text: "After completing job, you will not be able to modify any job details",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            closeOnConfirm: false,
            closeOnCancel: false
          },
          function(isConfirm) {
            if (isConfirm) {
              update_job(job_status);
            } else {
              swal.close();
            }
          }
        );
    }else{
        update_job(job_status);
    }
});

/**************** Submit Job Worksheet Script End **************/

});

function update_job(job_status){
    $.ajax({
        url  : api_url + "user/submit_job_worksheet",
        type : "POST",
        data : new FormData($('#job-form')[0]),   
        dataType : "JSON",   
        cache: false,
        contentType: false,
        processData: false,   
        success: function(resp){
            if(resp.status == 200){
               showToaster('success','Success !',resp.message);  
               setTimeout(function(){
                   window.location.reload();
               },500)
            }else{
                showToaster('error','Error !',resp.message);  
            }
            hideProgressBar();
        }
    });
}
