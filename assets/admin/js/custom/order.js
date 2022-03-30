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
		       aTargets: [0,6]
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

	/* Products Slider */
	$('.pro_image_slider').slick({
		dots: true,
		arrows: false,
		infinite: true,
		speed: 300,
		slidesToShow: 5,
		slidesToScroll: 1,
		centerMode: true,
  		focusOnSelect: true,
  		accessibility: false
	});  

	$('.pro_slider_main').slick({
		dots: false,
		arrows:true,
		infinite: true,
		speed: 300,
		slidesToShow: 1,
		slidesToScroll: 1,
		swipe:false,
		accessibility: false
	});

	$(document).on('click','.pro_slider_main .slide_img img', function() {
		$('img.main-img').attr('src',$(this).attr('src'));
		$('a.main-img').attr('href',$(this).attr('src'));
	});



	 // $(document).on('click','.pro_slider_main .slide_img img', function() {
	 // 	let self = $(this);
	 // 	$('h4.product-popup-title').text(self.parent().attr('data-product-name'));
	 // 	let product_gallery_images = JSON.parse(self.parent().attr('data-product-gallery-images'));
	 // 	// $('div#modal-section').html(resp);
  //       setTimeout(function(){
  //       	$('.pro_image_slider1').slick({
		// 	    dots: true,
		// 		arrows: false,
		// 		infinite: true,
		// 		speed: 300,
		// 		slidesToShow: 5,
		// 		slidesToScroll: 1,
		// 		centerMode: true,
		//   		focusOnSelect: true,
		//   		accessibility: false
		// 	});
  //           $('#noAnimation').modal({show:true});
  //       },200);
	 // 	// var thisSRC = $(this).attr('src');
	 // 	// $(this).parents('.slide_img').parents('.slick-track').parents('.slick-list').parents('.pro_image_slider ').prev('.pro_slider_singleimg').children('img').attr('src', thisSRC);
	 // });

	// setTimeout(function(){
	// 	$('.pro_slider_main *').removeAttr('tabindex');
	// 	$('.pro_slider_main *').removeAttr('data-slick-index');
	// },2000)

	$(document).on('click','.readmore', function(){
		$(this).addClass('lessmore');
		$(this).removeClass('readmore');
		$(this).text('Less More...');
		$(this).parents('.content_main').find('.content_block + .content_block').slideDown();
	});

	$(document).on('click','.lessmore', function(){
		$(this).addClass('readmore');
		$(this).removeClass('lessmore');
		$(this).text('Read More...');
		$(this).parents('.content_main').find('.content_block + .content_block').slideUp();
	});

	//Cat navigation start
	$('.cat-navbar-toggler').on('click', function(){
		$('.car_navigation').addClass('open');
	});
	$('.cat_nav_cloce').on('click', function(){
		$('.car_navigation').removeClass('open');
	});

	$(".sub_cat_list").parent("li").addClass("has_sub_cat");
	$(".has_sub_cat").append("<i class='catdd_arrow glyphicon glyphicon-chevron-down'></i>");
	$(".has_sub_cat > .catdd_arrow").click(function(){
	  $(this).parents('.has_sub_cat').find('.sub_cat_list').slideToggle();
	  $(this).parents('.has_sub_cat').toggleClass('open');
	  $(this).parents('.has_sub_cat').siblings('li').removeClass('open');
	  $(this).parents('.has_sub_cat').siblings('li').find('.sub_cat_list').slideUp();
	});

	/**************** Add Product Into Cart Start *************/

	$('body').on('change','input.product-variant',function(){
		let self = $(this);
		let product_guid = self.attr('data-product-guid');
		let product_variant_id = self.attr('data-product-variant-id');
		add_to_cart(product_guid,product_variant_id,self.val());
	})

	$('body').on('click','a.remove-product-order',function(){
		let self = $(this);
		let product_guid = self.attr('data-product-id');
		let product_variant_id = self.attr('data-product-variant-id');
		add_to_cart(product_guid,product_variant_id,0);
		$('#noAnimation').modal('hide');
		$.ajax({
	        url: api_url + 'orders/get_cart_details',
	        type:"POST",
	        data: {data_type:'html'},
	        success: function(resp){
	            $('div#modal-section').html(resp);
	            setTimeout(function(){
	                $('#noAnimation').modal({show:true});
	            },200);
	            hideProgressBar();
	        }
	    });
	})

	$('body').on('change','input[name="change-quantity"]',function(){
		let self = $(this);
		let product_guid = self.attr('data-product-id');
		let product_variant_id = self.attr('data-product-variant-id');
		let productnote = $("#productnote"+product_variant_id).val();
		add_to_cart(product_guid,product_variant_id,self.val(),productnote);
		$('#noAnimation').modal('hide');
		$.ajax({
	        url: api_url + 'orders/get_cart_details',
	        type:"POST",
	        data: {data_type:'html'},
	        success: function(resp){
	            $('div#modal-section').html(resp);
	            setTimeout(function(){
	                $('#noAnimation').modal({show:true});
	            },200);
	            hideProgressBar();
	        }
	    });
	})

	$('body').on('change','textarea[name="product_note"]',function(){ 
		let self = $(this);
		let product_guid = self.attr('data-product-id');
		let product_variant_id = self.attr('data-product-variant-id');
		let quantity = $("#change-quantity"+product_variant_id).val();
		add_to_cart(product_guid,product_variant_id,quantity,self.val());
		$('#noAnimation').modal('hide');
		$.ajax({
	        url: api_url + 'orders/get_cart_details',
	        type:"POST",
	        data: {data_type:'html'},
	        success: function(resp){
	            $('div#modal-section').html(resp);
	            setTimeout(function(){
	                $('#noAnimation').modal({show:true});
	            },200);
	            hideProgressBar();
	        }
	    });
	})

	$('body').on('change','input.product-variant-all-colors',function(){
		let self = $(this);
		let product_guid = self.attr('data-product-guid');
		let product_variant_ids = JSON.parse(self.attr('data-product-variant-ids'));
		let quantity = self.val();
		console.log('product_variant_ids',product_variant_ids)
		for (var i = 0; i < product_variant_ids.length; i++) {
			$('input[data-product-guid="'+product_guid+'"][data-product-variant-id="'+product_variant_ids[i]+'"]').val(quantity);
			add_to_cart(product_guid,product_variant_ids[i],quantity);
		}
	});

	/**************** Add Product Into Cart End *************/

	/**************** View Cart Details Start *************/

	$('body').on('click','div.cart-message',function(){
	    $.ajax({
	        url: api_url + 'orders/get_cart_details',
	        type:"POST",
	        data: {data_type:'html'},
	        success: function(resp){
	            $('div#modal-section').html(resp);
	            setTimeout(function(){
	                $('#noAnimation').modal({show:true});
	            },200);
	            hideProgressBar();
	        }
	    });
	})

	/**************** View Cart Details End *************/

	/**************** Finish Order Script Start *************/

	$('body').on('click','button.finish-order',function(){
		swal(
	      {
	        title: are_you_sure,
	        text: are_you_sure_finish_order,
	        type: "warning",
	        showCancelButton: true,
	        confirmButtonColor: "#DD6B55",
	        confirmButtonText: yes,
	        cancelButtonText: no,
	        closeOnConfirm: false,
	        closeOnCancel: false,
	        animation: true,
	        showLoaderOnConfirm: true
	      },
	      function(isConfirm) {
	        if (isConfirm) {
	          $.ajax({
	            url: api_url + 'orders/finish',
	            type:"POST",
	            dataType : "JSON",
	            data: {note:$("#order_note").val() },
	            success: function(resp){
	                if(resp.status == 200){
	                    showToaster('success',success,resp.message); 
	                    setTimeout(function(){
			            	window.location.reload();
			            },500); 
	                }else{
	                    showToaster('error',error,resp.message);  
	                }
	                hideProgressBar();
	            }
	        });
	        } else {
	          swal.close();
	        }
	      }
	    );
	});

	/**************** Finish Order Script End *************/

	/**************** View Order Details Start *************/

	$('body').on('click','button.view-order-details',function(){
		let order_guid = $(this).attr('data-order-guid');
	    $.ajax({
	        url: api_url + 'orders/get_order_details',
	        type:"POST",
	        data: {order_guid:order_guid, data_type:'html'},
	        success: function(resp){
	            $('div#modal-section').html(resp);
	            setTimeout(function(){
	                $('#noAnimation').modal({show:true});
	            },200);
	            hideProgressBar();
	        }
	    });
	})

	/**************** View Order Details End *************/
});

function add_to_cart(product_guid,product_variant_id,quantity,note=''){
	$.ajax({
        url: api_url + 'orders/product_add_to_cart',
        type:"POST",
        dataType : "JSON",
        data: {product_guid:product_guid,product_variant_id:product_variant_id,quantity:quantity,note:note},
        success: function(resp){
        	if(parseInt(resp.data.total_products) > 0){
        		$('div.cart-message').removeClass('hidden').html('<i class="zmdi zmdi-shopping-cart zmdi-hc-fw"></i> ' +resp.data.total_products+ " " + product_added_into_cart + ' ('+cart_total_amount+' ' +$('meta[name="viewport"]').attr('currency')+resp.data.total_cart_amount+ ')');
        	}else{
        		$('div.cart-message').addClass('hidden').html("");
        	}
            if(resp.status == 200){
                // showToaster('success',success,resp.message);  
            }else{
                // showToaster('error',error,resp.message);  
            }
            hideProgressBar();
        }
    });
}