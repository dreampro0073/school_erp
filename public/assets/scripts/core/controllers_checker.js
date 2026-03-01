app.controller('CheckerReportCtrl', function($scope , $http, $timeout , DBService, Upload){
    
    $scope.formData = {};
    $scope.page_id = 0;
    $scope.all_na = false;
    $scope.param_images = {};
    $scope.param_references = []; 
    $scope.param_reference_ids = [];
    $scope.report_change_logs = [];

    $scope.edit_data = {};
    $scope.file = {};
    $scope.fileUploading = false;
    $scope.report_rel = {};
    $scope.uploadedFiles =[];

    $scope.editor_data = {};
    $scope.show_editor = false;
    $scope.py_edit = false;
    $scope.collation = false;
    $scope.input_pattern = null;
    $scope.check_lock = true;
    $scope.processing = false;
    $scope.file_edit = false;
    var input_type = 'text';

    $scope.dropdown_values = [];
    $scope.new_change_values = [];
    
    $scope.init = function(){
        $scope.loading = true;

        DBService.postCall({page_id:$scope.page_id},'/api/reports/make-report/init').then(function(data){
            $scope.loading = false;
            $scope.sub_category = data.sub_category;
            $scope.formData = data.formData;
            $scope.param_images = data.param_images;
            $scope.param_references = data.param_references;

            for (var i = 0; i < data.param_references.length; i++) {
                $scope.param_reference_ids.push(data.param_references[i].param_id);
            }

        });
        
    }

    $scope.addMoreItem = function(param_id){
        $scope.formData['param_'+param_id].push({demo:''});
    }

    $scope.removeItem = function(param_id,index){
        bootbox.confirm("Do you want to remove the item?",function(res){
            if(res){
                $scope.$apply(() => {
                    $scope.formData['param_'+param_id].splice(index,1);
                });
            }
        });
    }

    $scope.openEditor = function(obj, field){
        $scope.ck_obj = obj;
        $scope.ck_field = field;
        $scope.ck_multi_param = "";
        $scope.ck_multi_index = 0;
        $scope.show_editor = true;

        $scope.editor_data = $scope[obj][field];

        $("#editor_modal").modal("show");
    }

    $scope.openEditor2 = function(obj, field){
        $scope.ck_obj = obj;
        $scope.ck_field = field;
        $scope.ck_multi_param = "page_8";
        $scope.ck_multi_index = 0;
        $scope.show_editor = true;

        if($scope.formData[obj]){
            $scope.editor_data = $scope.formData[obj][field];
        }

        $("#editor_modal").modal("show");
    }

    $scope.openEditorMulti = function(obj, field, multi_param, multi_index){
        
        $scope.ck_obj = obj;
        $scope.ck_field = field;
        $scope.ck_multi_param = multi_param;
        $scope.ck_multi_index = multi_index;
        $scope.show_editor = true;

        $scope.editor_data = $scope.formData[multi_param][multi_index][field];

        $("#editor_modal").modal("show");
    }

    $scope.closeEditorModal= function(){
        $scope.show_editor = false;
        $("#editor_modal").modal("hide");   
    }

    $scope.saveEditorModal= function(){
        
        if($scope.ck_multi_param == ""){
            $scope[$scope.ck_obj][$scope.ck_field] = window.editor.getData();
        } else if($scope.ck_multi_param == "page_8"){
            if(!$scope.formData[$scope.ck_obj]){
                $scope.formData[$scope.ck_obj] = {};
            }
            $scope.formData[$scope.ck_obj][$scope.ck_field] = window.editor.getData();
        } else {
            $scope.formData[$scope.ck_multi_param][$scope.ck_multi_index][$scope.ck_field] = window.editor.getData();
        }

        $scope.show_editor = false;
        $("#editor_modal").modal("hide");   
    }

    $scope.setData = function(content){
        $scope.$apply(() => {
            $scope.editor_data = content;
        })
    }

    $scope.showInfo = function(param_id){
        var ext = $scope.param_images['image_'+param_id].split('.').pop();

        if(ext == 'pdf'){
            
            // window.location =  $scope.param_images['image_'+param_id];
            $("#image_modal").modal("show");
            $scope.image_content = '<div><a href="'+ $scope.param_images['image_'+param_id] +'" target="blank" class="btn btn-sm blue" >View PDF</a></div>'

        }else{
            $("#image_modal").modal("show");
            $scope.image_content = '<div><img src="'+ $scope.param_images['image_'+param_id] +'" /></div>'
        }
        
    }

    $scope.closeInfoModal = function(){
        $("#image_modal").modal("hide");
    }

    $scope.show_references = [];
    
    $scope.showReference = function(param_id){

        for (var i = 0; i < $scope.param_references.length ; i++) {
            
            if($scope.param_references[i].param_id == param_id){
                $scope.show_references.push($scope.param_references[i].reference);

            }

        }
        
        $("#reference_modal").modal("show");
    }

    $scope.closeReferenceModal = function(){
        $scope.show_references = [];
        $("#reference_modal").modal("hide");
    }

    $scope.editItem = function(ques_id, type, param_id, type_input, value, key_id, row_index, fy){
        $scope.editor_data = '';
        if (type_input == 'integer') {
            $scope.input_pattern = 'integer';
            input_type = 'text'; 
        } else 
        if (type_input == 'date') {
            $scope.input_pattern = 'date';
            input_type = 'text'; 
        } else 
        if (type_input == 'float') {
            $scope.input_pattern = 'float';
            input_type = 'text'; 
        } else 
        if (type_input == 'cin') {
            $scope.input_pattern = 'cin';
            input_type = 'text';
        } else 
        if (type_input == 'dropdown') {
            $scope.input_pattern = 'dropdown';
            input_type = 'text'; 
        } else 
        if (type_input == 'assurance_dropdown') {
            $scope.input_pattern = 'assurance_dropdown';
            input_type = 'text'; 
        } else if (type_input == 'q16_dropdown') {
            $scope.input_pattern = 'q16_dropdown';
            input_type = 'text'; 
        } else 
        if (type_input == 'unit') {
            $scope.input_pattern = 'unit';
            input_type = 'unit'; 
        } else {
            input_type = type_input;
            $scope.input_pattern = '';
        }
        
        $scope.edit_data = {
            input_pattern : $scope.input_pattern,
            type : type,
            param_id : param_id,
            input_type : input_type,
            value : value,
            key_id : key_id ? key_id :0,
            row_index : row_index ? row_index : 0,
            fy : fy,
        }

        if(input_type == "editor"){
            $scope.editor_data = value;
            $scope.show_editor = true;
        }

        $scope.updates_list = [];
        $scope.edit_data.page_id = $scope.page_id;
        $scope.edit_data.ques_id = ques_id ? ques_id : 0;
        DBService.postCall($scope.edit_data,'/api/reports/checker-report/updates_list').then(function(data){
            $scope.updates_list = data.updates_list;
            $scope.check_lock = data.check_lock;
            $scope.message = data.message;
            if(data.dropdown_values){
                $scope.dropdown_values = data.dropdown_values;
            }
            if(data.units){
                $scope.units = data.units;  
            }
            
            $("#edit-item").modal("show");
            

        });
        
    }

    $scope.storeCheckerReport = function(){
        $scope.processing = true;
        
        if($scope.edit_data.input_type == "editor"){
            $scope.edit_data.value = window.editor.getData();
        }

        DBService.postCall($scope.edit_data,'/api/reports/checker-report/store').then(function(data){
            if(data.success){
                if ($scope.edit_data.type == 'param') {
                    $scope.new_change_values = data.new_values;

                    $scope.formData['param_'+$scope.edit_data.param_id] = $scope.edit_data.value;
                    $scope.formData['param_change_'+$scope.edit_data.param_id] = true;

                    for (var i = 0; i < $scope.new_change_values.length; i++) {
                        var ch_p = $scope.new_change_values[i];
 
                        $scope.formData['param_'+ch_p.parameter_id] =  ch_p.value; 
                    }

                }

                if($scope.edit_data.type == 'row'){
                   $scope.formData['param_'+$scope.edit_data.param_id][$scope.edit_data.row_index-1]['key_'+$scope.edit_data.key_id] = $scope.edit_data.value;
                   
                   $scope.formData['param_'+$scope.edit_data.param_id][$scope.edit_data.row_index-1]['key_change_'+$scope.edit_data.key_id] = true;

                }

                $scope.processing = false;
                
                $("#edit-item").modal("hide");

                $scope.show_editor = false;
                
                bootbox.alert(data.message);

            } else {
                $scope.processing = false;
                bootbox.alert(data.message);
            }
        });

    }
    $scope.closeEditItem = function(){
        $scope.show_editor = false;
        $("#edit-item").modal("hide");
    }

    $scope.ShowData = function(collation_id, additional_param_id, ques_id){
        $scope.collation = true; 
        $scope.collation_id = collation_id;
        $scope.ques_id = ques_id;
        $scope.collate_type = 'division';
        $scope.getShowData();
    }

    $scope.ShowQuarterData = function(collation_id, additional_param_id, ques_id){
        $scope.quarter_collation = true; 
        $scope.collation_id = collation_id;
        $scope.ques_id = ques_id;
        $scope.collate_type = 'quarter';
        $scope.getShowData();
    }
 
    $scope.getShowData = function(){
        
        $("#collationData").modal("show");

        DBService.postCall({collation_id : $scope.collation_id, collate_type : $scope.collate_type, ques_id : $scope.ques_id},'/api/reports/collation-init').then(function(data){
            $scope.division_data = data.division_data;
            $scope.collat_data_type = data.collat_data_type;
            $scope.division_keys = data.division_keys;
            $scope.division_reports = data.division_reports;
            $scope.div_years = data.years;
            $scope.quarter_collation = false;            
            $scope.collation = false;            
            $scope.not_collat = true;            
            $scope.additional_collate = false;            
        });
    }


    $scope.viewCheckerChange = function(change_param){
        
        $scope.changes_list = change_param;
        
        $("#checkerChanges").modal("show");
    }

    $scope.uploadReportFile = function(){
        DBService.postCall({ page_id: $scope.page_id },'/admin/uploaded-files').then(function(data){
            $scope.uploadedFiles = data.uploadedFiles;
            
            $("#uploadReportFile").modal("show");

        });
    }

    $scope.reportRelFile = function (file) {
        

        if(file){
            if(file.size < 10*1024*1024){
                $scope.uploading = true;
                var url = base_url+'/admin/report-rel-file';
                Upload.upload({
                    url: url,
                    data: {
                        media: file
                    }
                }).then(function (resp) {
                    if(resp.data.success){
                        $scope.file.path = resp.data.path;
                        $scope.file.url = resp.data.path;
                        
                    } else {
                        alert(resp.data.message);
                    }
                    $scope.uploading = false;


                }, function (resp) {
                   
                    $scope.uploading = false;

                }, function (evt) {
                   
                });
            }else{
                alert('Maximum uploaded file size should be less then 10MB !');
            }
            
        }
    }

    $scope.removeReportRelFile = function(file_id){

        if (file_id == 0) {
            $scope.file = {};
        } else {
            bootbox.confirm("Are you sure?", function(result){ 
                if(result){
                    DBService.postCall({},'/admin/delete-report-file/'+file_id).then(function(data){
                        alert(data.message);
                        $scope.report_rel = {};
                        $scope.uploadReportFile();
                    });
                }
            });
        }
    }


    $scope.uploadReportRelFile = function(){
        if($scope.report_rel.file_name == null){
            alert("File name is required !");
            return;
        }
        $scope.fileUploading = true;
        $scope.report_rel.page_id = $scope.page_id;
        $scope.report_rel.file_path = $scope.file.path;
        DBService.postCall($scope.report_rel,'/admin/upload-file').then(function(data){
            alert(data.message);
            if (data.success) {
                $scope.file = {};
                $scope.report_rel = {};
                $("#uploadReportFile").modal("hide");
                $scope.uploadReportFile();
            }
        });
        $scope.fileUploading = false;
    }

    $scope.uploadQuestionFile = function(ques_id, file_edit){
        $scope.file_ques_id = ques_id;
        $scope.file_edit = file_edit;
        DBService.postCall({ ques_id: $scope.file_ques_id, page_id:$scope.page_id },'/admin/uploaded-ques-files').then(function(data){
            $scope.uploadedFiles = data.uploadedFiles;
            
            $("#uploadQuesFile").modal("show");

        });
    }

        $scope.reportQuesFile = function (file) {
        

        if(file){
            if(file.size < 10*1024*1024){
                $scope.uploading = true;
                var url = base_url+'/admin/report-ques-file';
                Upload.upload({
                    url: url,
                    data: {
                        media: file
                    }
                }).then(function (resp) {
                    if(resp.data.success){
                        $scope.file.path = resp.data.path;
                        $scope.file.url = resp.data.path;
                        
                    } else {
                        alert(resp.data.message);
                    }
                    $scope.uploading = false;


                }, function (resp) {
                   
                    $scope.uploading = false;

                }, function (evt) {
                   
                });
            }else{
                alert('Maximum uploaded file size should be less then 10MB !');
            }
            
        }
    } 

    $scope.removeQuesFile = function(file_id){

        if (file_id == 0) {
            $scope.file = {};
        } else {
            bootbox.confirm("Are you sure?", function(result){ 
                if(result){
                    DBService.postCall({},'/admin/delete-ques-file/'+file_id).then(function(data){
                        alert(data.message);
                        $scope.report_rel = {};
                        $scope.uploadQuestionFile($scope.file_ques_id, $scope.file_edit);
                    });
                }
            });
        }
    } 


    $scope.uploadQuesFile = function(){
        if($scope.report_rel.file_name == null){
            alert("File name is required !");
            return;
        }
        $scope.fileUploading = true;
        $scope.report_rel.ques_id = $scope.file_ques_id;
        $scope.report_rel.page_id = $scope.page_id;
        $scope.report_rel.file_path = $scope.file.path;
        DBService.postCall($scope.report_rel,'/admin/store-ques-file').then(function(data){
            alert(data.message);
            if (data.success) {
                $scope.file = {};
                $scope.report_rel = {};
                $("#uploadQuesFile").modal("hide");
                $scope.uploadQuestionFile($scope.file_ques_id, $scope.file_edit);
            }
        });
        $scope.fileUploading = false;
    }


    // *** For onclick update Parameter ***
    // $scope.editItemNew = function(ques_id, type, param_id, type_input, value, key_id, row_index, fy){

    //     if(type == "param"){
    //         $scope.editor_data = '';
    //         $scope.input_pattern = '';
    //         if (type_input == 'integer') {
    //             $scope.input_pattern = 'integer';
    //         } else 
    //         if (type_input == 'date') {
    //             $scope.input_pattern = 'date';
    //         } else 
    //         if (type_input == 'float') {
    //             $scope.input_pattern = 'float';
    //         } else 
    //         if (type_input == 'cin') {
    //             $scope.input_pattern = 'cin';
    //         } else
    //         if (type_input == 'text' || type_input == 'textarea') {
    //             $scope.input_pattern = 'text';
    //         } else {
    //             input_type = type_input;
    //             $scope.input_pattern = '';
    //         }

    //         if($scope.input_pattern != ''){
    //             DBService.postCall({param_id : param_id, input_pattern : $scope.input_pattern},'/validParam').then(function(data){
    //                 if(data.success){
    //                     alert('Done');
    //                 }
    //             });
    //         } else {
    //            alert(input_type); 
    //         }
    //     } else{
    //         alert(type);
    //     }
        
    // }
    // *** end ***



});