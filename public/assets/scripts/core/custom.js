$(document).on("click",".datepicker",function(){
	$(this).datepicker({
    	format:"dd-mm-yyyy",
    	todayHighlight:true,
    	autoclose: true,
    });
	$(this).datepicker("show");
});
$(document).on("click",".datepicker1",function(){
	$(this).datepicker({
    	format:"dd-mm-yyyy",
    	todayHighlight:true,
    	autoclose: true,
    });
	$(this).datepicker("show");
});

var category = null;

// for (var i = 0; i < catgories.length; i++) {
// 	var categ = catgories[i];

// 	if(categ.canteen_item_id == canteen_item_id){
// 		var category = categ;
// 	}
// }
// $(document).on("click","#upload_btn",function(){
// 	$(this).removeAttribute("disabled");
// });

