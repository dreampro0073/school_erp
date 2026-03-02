app.controller('DashboardCtrl', function($scope , $http, $timeout , DBService){
    
    $scope.formData = {};
    $scope.loading = true;
    $scope.startingReport = false;
    $scope.pending_reports = [];
    $scope.division = {};
    $scope.d_financial_year = 2025;
    $scope.division_reports = [];
    $scope.division_ids = [];
    $scope.start_report = {};
    $scope.industry_id = 0;
    $scope.sub_industry_id = 0;
    $scope.reportlist = false;
    $scope.industry_link = '';
    $scope.inactive_reports = [];

    $scope.init = function(){

        DBService.postCall({
            financial_year: $scope.d_financial_year
        },'/api/reports/dashboard_init').then(function(data){
            $scope.reports = data.reports;
            $scope.inactive_reports = data.inactive_reports;
            $scope.sub_industries = data.sub_industries;
            $scope.industries = data.industries;
            $scope.companies = data.companies;
            $scope.divisions = data.divisions;
            $scope.pending_reports = data.pending_reports;
            $scope.progress_status = data.progress_status ? data.progress_status : null;
            $scope.loading = false;
            if(data.is_new_news){
                $scope.news = data.news;
                $("#newsModal").modal("show"); 
            }
        });

    }

    $scope.startFyDetails = function(){
        $scope.formData = {};
        $scope.addForm.$setPristine();
        $("#pop-fy").modal("show");
    }

    $scope.getIndustry = function(sub_industry_id){
        DBService.postCall({}, '/api/reports/get-industry/'+sub_industry_id).then(function(data){
            $scope.industries = data.industries;
        });
    }

    $scope.isIgnore = function(){
        DBService.getCall('/api/ignore-news').then(function(data){
            if(data.success){
                $("#newsModal").modal("hide"); 

            }
        });
    }

    $scope.getIndustryLink  = function(){
        
        for (var i = 0; i < $scope.industries.length; i++) {
            if($scope.industries[i].id == $scope.formData.industry_id){
               $scope.industry_link = $scope.industries[i].industry_link;
               
            } 
        }
    }

    $scope.editReport = function(report_id){
        DBService.postCall({},'/api/reports/edit-report/'+report_id).then(function(data){
            $scope.formData = data.report;
            $scope.sub_industry_id= data.report.sub_industry_id;
            // $scope.getIndustry($scope.sub_industry_id);
            $("#pop-fy").modal("show");            
        });
    }

    $scope.changeSessionType = function(){
        $scope.formData.quarter_session = 0;
    }

    $scope.reportSubmitForm = function(){
        $scope.formData.processing = true;
        DBService.postCall($scope.formData,'/api/reports/create').then(function(data){
            if(data.success){
                window.location = base_url+'/admin/dashboard?financial_year='+$scope.d_financial_year;
            }else{
                alert(data.message);
            }
            $scope.formData.processing = false;
        });
    }

    $scope.resumeReport = function(report, user_role){
        bootbox.confirm("Do you want to continue this report?",function(res){
            if(res){
                report.processing = true;
                DBService.postCall(report,'/api/reports/resume/'+user_role).then(function(data){
                    if(data.success){
                        window.location = base_url+'/admin/dashboard?financial_year='+$scope.d_financial_year;
                    }else{
                        bootbox.alert(data.message);
                    }
                    report.processing = false;
                });
            }
        });
    }

    $scope.stopReport = function(){
        DBService.postCall({},'/api/reports/stop-report').then(function(data){
            window.location = base_url+'/admin/dashboard?financial_year='+$scope.d_financial_year;
        });
    }

    $scope.is_starting = false;

    $scope.startDivReport = function(pending_report){
        $scope.is_starting = true;
        $scope.pending_report = pending_report;
        $scope.formData.processing = true;
        DBService.postCall($scope.pending_report,'/api/reports/start-div-report').then(function(data){
            $scope.formData.processing = false;
            if(data.success){
                window.location = base_url+'/admin/dashboard';
            }else{
                bootbox.alert(data.message);
                $scope.is_starting = false;

            }
        });  
    }


    $scope.divisionReports = function(report){
        // console.log(report);return;
        $scope.division.company_id = report.company_id;
        $scope.division.division_id = report.company_division_id;
        $scope.division.quarter_session = report.quarter_session;
        $scope.industry_id = report.industry_id;
        $scope.division.financial_year = $scope.d_financial_year;
        
        DBService.postCall($scope.division,'/api/reports/division-reports').then(function(data){
            $scope.divisions = data.divisions;
            $scope.center_com_per = data.center_com_per;
            $("#division_modal").modal("show");
        });
    }
    $scope.all_check = false;
    $scope.addReport = function(division_id){
        var division = $scope.division_ids.indexOf(division_id);

        if(division > -1){
            $scope.division_ids.splice(division,1);
            $scope.all_check = false;

        }else{
            $scope.division_ids.push(division_id);
        } 
        if ($scope.division_ids == 0) {
           $scope.reportlist = false; 
        } else {
            $scope.reportlist = true;
        }

    }

    $scope.addAllReport = function(){
        $scope.division_ids = [];
        for (var i = 0; i < $scope.divisions.length; i++) {
            if(!$scope.divisions[i].start_report){
                var id = $scope.divisions[i].id;
                $scope.division_ids.push(id);
            }
        }
        $scope.all_check = true;
        if ($scope.division_ids == 0) {
           $scope.reportlist = false; 
        } else {
            $scope.reportlist = true;
        }    
    }

    $scope.startReports = function(){
        $scope.startingReport = true;

        $scope.start_report.division_ids = $scope.division_ids;
        $scope.start_report.financial_year = $scope.d_financial_year;
        $scope.start_report.industry_id = $scope.industry_id;
        $scope.start_report.quarter_session = $scope.division.quarter_session;

        DBService.postCall($scope.start_report, '/api/reports/start-reports').then(function(data){
            alert(data.message);
            $("#division_modal").modal("hide");
            $scope.startingReport = false;
        });
    }

    $scope.activeReport = function(report_id){
        DBService.postCall({report_id : report_id},'/api/reports/active-report').then(function(data){
            alert(data.message);
            if(data.success){
                $scope.init();
            }
        });
    }


});


app.controller('ReportCtrl', function($scope , $http, $timeout , DBService, Upload){
    
    $scope.formData = {};
    $scope.page_id = 0;
    $scope.ques_id = 0;
    $scope.file_ques_id = 0;
    $scope.all_na = false;
    $scope.collation = false;
    $scope.checkerChange = true;
    $scope.collated = false;
    $scope.py_edit = false;
    $scope.not_collat = false;
    $scope.param_images = {};
    $scope.active_report = {};
    $scope.param_references = []; 
    $scope.param_reference_ids = [];
    $scope.formData.update_status = 0; 
    $scope.PreviousYearData = [];
    $scope.emissionList = [{
        data_points: 0, 
        application: '',
        conversion_rate: 0, 
        value: 0 
    }];
    $scope.emission_total = 0;
    
    $scope.file = {};
    $scope.report_rel = {};
    $scope.uploadedFiles =[];
    $scope.fileUploading = false;

    $scope.editor_data = "";
    $scope.show_editor = false;
    $scope.page_locked = false;
    $scope.loading_excel = false;
    $scope.loading_py_xbrl = false;
    $scope.fetchInternal = false;
    $scope.files_loading = false;

    $scope.param = {
        unit_param_id : 0,
        param_id : 0,
    }
    $scope.units =[];
    $scope.convert = {};
    $scope.convert = {
        inputUnit : '',
        outputUnit : '',
        input_value : 1,
        output_value : 0,
    }
    $scope.conversions =[];
    $scope.unit_type_id = 0;
    $scope.conversion_rate =1;
    $scope.output_unit_disable = false;
    $scope.is_error = false;
    $scope.my_ar = [];
    $scope.modelData = [];

    $scope.quarter_collation = false; 
    $scope.show_additional = false;
    $scope.fetchPreviousData = false;
    $scope.show_editor = true;
    $scope.show_message = 0;

    $scope.quarter_reports_data = [];
    $scope.quarter_reports = [];
    $scope.report_data = [];
    $scope.levels = [];
    $scope.level = '';
    $scope.all_access = '';

    $scope.waterCalData = {
        liter_per_day : 45,
        employees : '',
        working_days : '',
        kl_total : '',
    }

    $scope.min_comparison = 0;
    $scope.max_comparison = 0;

    $scope.collatReportIds = [];
    $scope.cal_param_ids = [];

    $scope.file_edit = false;

    $scope.init = function(){
        $scope.loading = true;
        if($scope.page_id == 0){
            DBService.postCall({page_id:$scope.page_id},'/api/reports/make-report/disclosure_init').then(function(data){
                
                $scope.loading = false;
                $scope.params = data.params;
                
                $scope.formData = data.formData;
                $scope.param_images = data.disclosure_images;
                $scope.param_references = data.disclosure_references;

                for (var i = 0; i < data.disclosure_references.length; i++) {
                    $scope.param_reference_ids.push(data.disclosure_references[i].param_id);
                }

                for (var i = 9; i >= 1; i--) {
                    $scope['mark_all_P'+i] = false;
                }
            });
        }else{

            DBService.postCall({page_id:$scope.page_id},'/api/reports/make-report/init').then(function(data){
                $scope.loading = false;
                $scope.all_access = data.all_access;
                $scope.sub_category = data.sub_category;
                $scope.formData = data.formData;
                $scope.param_images = data.param_images;
                $scope.param_references = data.param_references;
                $scope.page_locked = data.page_locked;

                $scope.min_comparison = data.min_comparison;
                $scope.max_comparison = data.max_comparison;
                $scope.cal_param_ids = data.cal_param_ids;

                if($scope.show_message == 1){
                    $scope.page_locked = true;
                }
                $scope.active_report = data.active_report;

                if(data.param_references){
                    for (var i = 0; i < data.param_references.length; i++) {
                        $scope.param_reference_ids.push(data.param_references[i].param_id);
                    }
                }
            });
        }
    }

    $scope.editItem1 = function(ques_id, type, param_id, type_input, value, key_id, row_index, fy){
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
            key_id : key_id ? key_id : 0,
            row_index : row_index ? row_index : 0,
            fy : fy,
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

    $scope.closeEditItem = function(){
        $scope.show_editor = false;
        $("#edit-item").modal("hide");
    }


    $scope.onSubmit = function(status){
        if (status) {
            $scope.formData.update_status = 1;
        } else{
            $scope.formData.update_status = 0;
        }
        $scope.processing = true;
        $scope.formData.page_id = $scope.page_id;

        if($scope.page_id == 0){
            DBService.postCall($scope.formData,'/api/reports/make-report/store_disclosures').then(function(data){
                $scope.processing = false;
                bootbox.alert(data.message);
            });
        } else {

            DBService.postCall($scope.formData,'/api/reports/make-report/store_department').then(function(data){
               
            }); 
           
            DBService.postCall($scope.formData,'/api/reports/make-report/store').then(function(data){
                $scope.processing = false;
                bootbox.alert(data.message);
            });
        }
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

    $scope.calculateSum = function(obj , param_ids, set_param, toFixed){
        
        total = 0;
        for (var i = 0; i < param_ids.length; i++) {
            if(!isNaN(obj['param_'+param_ids[i]]) && obj['param_'+param_ids[i]] != "" ){
                total += parseFloat( obj['param_'+param_ids[i]] )
            }
        }

        obj['param_'+set_param] = total.toFixed(toFixed);
    }

    $scope.getDivision = function (obj, param1, param2, set_param, toFixed) {
        
        if( !isNaN(obj['param_'+param2]) && obj['param_'+param2] != "" && obj['param_'+param2] != 0 && obj['param_'+param1] != "" && !isNaN(obj['param_'+param1] && obj['param_'+param1] != 0 ) ) {
            value =  ((obj['param_'+param1])/obj['param_'+param2]).toFixed(toFixed);
        }else {
            value = "0.00";
        }
        
        obj['param_'+set_param] = value;

        return value;
    }    

    $scope.getArSumDivision = function (obj, params, param2, set_param, toFixed) {
        
        var sum_value = 0;

        for (var i = 0; i < params.length ; i++) {
            if (!isNaN(obj['param_'+params[i]]) && obj['param_'+params[i]] != "") {
                sum_value += parseFloat(obj['param_'+params[i]])
            }        
        }

        if( !isNaN(obj['param_'+param2]) && obj['param_'+param2] != "" && obj['param_'+param2] != 0 && sum_value != "" && !isNaN(sum_value && sum_value != 0 ) ) {
            value =  ((sum_value)/obj['param_'+param2]).toFixed(toFixed);
        }else {
            value = "0.00";
        }
        
        obj['param_'+set_param] = value;

        return value;
    }
    
    $scope.getPerc = function (obj, param1, param2, set_param) {

        if( !isNaN(obj['param_'+param2]) && obj['param_'+param2] != "" && obj['param_'+param2] != 0 && obj['param_'+param1] != "" && !isNaN(obj['param_'+param1] && obj['param_'+param1] != 0 ) ) {
            value =  ((obj['param_'+param1]*100)/obj['param_'+param2]).toFixed(2);
        }else {
            value = "0.00";
        }
        
        obj['param_'+set_param] = value;
        
        if(value > 100){
            obj['over_'+set_param] = true;
            
        }else{
            obj['over_'+set_param] = false;
        }

        return value;
    }

    $scope.getMulti = function(obj, param1, param2, set_param, toFixed) {

        if( !isNaN(obj['param_'+param2]) && obj['param_'+param2] != "" && obj['param_'+param2] != 0 && obj['param_'+param1] != "" && !isNaN(obj['param_'+param1] && obj['param_'+param1] != 0 ) ) {
            value = (obj['param_'+param1] * obj['param_'+param2]).toFixed(toFixed);
        }else {
            value = "0.00";
        }
        obj['param_'+set_param] = value;
        return value;
    }
    
    
    $scope.getPercAr = function (obj, param1, param2, set_param) {
        total = 0; 
        for (var i = 0; i < param2.length ; i++) {
            if (!isNaN(obj['param_'+param2[i]]) && obj['param_'+param2[i]] != "") {
                total += parseFloat(obj['param_'+param2[i]])
            }        
        }

        if( !isNaN(total) && total != "" && total != 0 && obj['param_'+param1] != "" && !isNaN(obj['param_'+param1] && obj['param_'+param1] != 0 ) ) {
            value =  ((obj['param_'+param1]*100)/total).toFixed(2);

        }else {
            value = "0.00";
        }
        
        obj['param_'+set_param] = value;
        return value;
    }    

    $scope.calBenchmark_1 = function (obj, param1, param2, set_param) {
        total = 0;
        
        if( !isNaN(obj['param_'+param2]) && obj['param_'+param2] != "" && obj['param_'+param2] != 0 && obj['param_'+param1] != "" && !isNaN(obj['param_'+param1] && obj['param_'+param1] != 0 ) ) {
            value =  (((obj['param_'+param1])-(obj['param_'+param2])) / (obj['param_'+param2])).toFixed(2);

        }else {
            value = "0.00";
        }
        
        obj['param_'+set_param] = value;
        return value;
    }

    $scope.getSum = function(obj, param_ids, toFixed, set_param){
        total = 0;
        for (var i = 0; i < param_ids.length ; i++) {
            if (!isNaN(obj['param_'+param_ids[i]]) && obj['param_'+param_ids[i]] != "" && obj['param_'+param_ids[i]] != 0 && obj['param_'+param_ids[i]] != null) {
                total += parseFloat(obj['param_'+param_ids[i]])
            }        
        }
        obj['param_'+set_param] = total.toFixed(toFixed);
        sum = total.toFixed(toFixed);
        return sum;
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

    $scope.markAllNA = function(sn_no,param_ids){

        var start = param_ids[0];
        var end = param_ids[param_ids.length-1];

        if($scope.formData['mark_all_'+start+'_'+end+'_p'+sn_no]){
            angular.forEach(param_ids, function(value, key) {
                if(!$scope.formData['param_'+value]){
                    $scope.formData['param_'+value] = {};
                }
                $scope.formData['param_'+value]['p'+sn_no] = 'NA';
            });

        }
    }

    $scope.showInfo = function(param_id){
        var ext = $scope.param_images['image_'+param_id].split('.').pop();

        if(ext == 'pdf'){
            
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

    $scope.ShowData = function(collation_id, additional_param_id, ques_id){
        additional_param_id = additional_param_id ? additional_param_id : 0;
        $scope.collation = true; 
        $scope.collation_id = collation_id;
        $scope.additional_param_id = additional_param_id;
        $scope.ques_id = ques_id;
        $scope.collate_type = 'division';
        $scope.level = '';
        $scope.getShowData();
    }

    $scope.changeLevel = function(level){
        $scope.collation = true; 
        $scope.level = level;
        $scope.getShowData();
    }
    
    $scope.ShowQuarterData = function(collation_id, additional_param_id, ques_id){
        additional_param_id = additional_param_id ? additional_param_id : 0;
        $scope.quarter_collation = true; 
        $scope.collation_id = collation_id;
        $scope.additional_param_id = additional_param_id;
        $scope.ques_id = ques_id;
        $scope.collate_type = 'quarter';
        $scope.level = '';
        $scope.getShowData();
    }


    $scope.getShowData = function(){ 

        $scope.collatReportIds = [];

        DBService.postCall({collation_id : $scope.collation_id, collate_type : $scope.collate_type, ques_id : $scope.ques_id, level:$scope.level},'/api/reports/collation-init').then(function(data){
            $scope.division_data = data.division_data;
            $scope.collat_data_type = data.collat_data_type;
            $scope.division_keys = data.division_keys;
            $scope.division_reports = data.division_reports;
            $scope.div_years = data.years;
            $scope.quarter_collation = false;            
            $scope.collation = false;            
            $scope.not_collat = data.not_collat;  
            $scope.additional_collate = true;
            $scope.cr_report = data.report;
            $scope.levels = data.levels ? data.levels : [];
            $scope.collatReportIds = data.report_ids;
            $("#collationData").modal("show");
            
        });
    }

    $scope.viewCheckerChange = function(change_param){
        
        $scope.changes_list = change_param;
        
        $("#checkerChanges").modal("show");
    }

    $scope.CollatFill = function(){

        $scope.collated = true;
        if($scope.collate_type == 'division'){
            alert("To avoid data discrepancies in the report, click Save Draft after ticking/unticking a division checkbox.")
        }

        DBService.postCall({collation_id : $scope.collation_id, collate_type : $scope.collate_type, ques_id : $scope.ques_id, report_ids : $scope.collatReportIds},'/api/reports/collation-fill').then(function(data){
            if(data.success){
                if(data.cal_param_id > 0){
                    $scope.formData["use_calculator_"+data.cal_param_id] = 1;
                }

                $scope.collatReportIds = [];
                if($scope.collat_data_type == "params" || $scope.collat_data_type == "params-join"){
                    for (var i = 0; i < data.collat_data.length; i++) {
                        param = data.collat_data[i];
                        $scope.formData['param_'+param.id] = param.value;
                    }
                }

                if($scope.collat_data_type == "years"){
                    $scope.formData['param_'+data.collat_data_param_id+'_years'] = data.years_data;
                }

                if($scope.collat_data_type == "multiple"){
                    $scope.formData['param_'+data.collat_data_param_id] = data.collat_data;
                } 

                $scope.collated = false;
                $("#collationData").modal("hide");
            } else {
                $scope.collated = false;
                alert(data.message);
            }

        });

    }    

    $scope.SingalQuarterCollatFill = function(report_id){

        $scope.collated = true;

        DBService.postCall({collation_id : $scope.collation_id, report_id : report_id},'/api/reports/singal-collation-fill').then(function(data){

            if($scope.collat_data_type == "params" || $scope.collat_data_type == "params-join"){
                for (var i = 0; i < data.collat_data.length; i++) {
                    param = data.collat_data[i];
                    $scope.formData['param_'+param.id] = param.value;
                }
            }

            if($scope.collat_data_type == "years"){
                $scope.formData['param_'+data.collat_data_param_id+'_years'] = data.years_data;
            }

            if($scope.collat_data_type == "multiple"){
                $scope.formData['param_'+data.collat_data_param_id] = data.collat_data;
            } 

            $scope.collated = false;
            $("#collationData").modal("hide");

        });

    }

    $scope.fetchXBRL = function(){
        $scope.loading_xbrl = true;
        DBService.postCall({ page_id: $scope.page_id },'/api/reports/fetch-xbrl').then(function(data){
            // $scope.formData = data.formData;
            for (var i = 0; i < $scope.cal_param_ids.length; i++) {
                $scope.formData['use_calculator_'+$scope.cal_param_ids[i]] = 0;
            }

            for (var i = 0; i < data.param_ids.length; i++) {
                $scope.formData['param_'+data.param_ids[i]] = data.formData['param_'+data.param_ids[i]];
            }
            $scope.loading_xbrl = false;

        });   
    }        

    $scope.fetchPyXBRL = function(){
        $scope.loading_py_xbrl = true;

        DBService.postCall({ page_id: $scope.page_id },'/api/reports/fetch-py-xbrl').then(function(data){
            for (var i = 0; i < $scope.cal_param_ids.length; i++) {
                $scope.formData['use_calculator_'+$scope.cal_param_ids[i]] = 0;
            }

            for (var i = 0; i < data.param_ids.length; i++) {
                $scope.formData['param_'+data.param_ids[i]] = data.formData['param_'+data.param_ids[i]];
            }  
             
            $scope.loading_py_xbrl = false;

        });   
    }    

    $scope.fetchExcel = function(){
        $scope.loading_excel = true;

        DBService.postCall({ page_id: $scope.page_id },'/api/reports/fetch-excel').then(function(data){

            // $scope.formData = data.formData;
            for (var i = 0; i < data.param_ids.length; i++) {
                $scope.formData['param_'+data.param_ids[i]] = data.formData['param_'+data.param_ids[i]];
            }
            $scope.loading_excel = false;
        });   
    }

    $scope.setNullVal = function(param_id){
        $scope.formData['param_'+param_id] = '';
    }     

    $scope.setMultiNullVal = function(param_ids){
        for (var i = 0; i < param_ids.length; i++) {
            var arr = [197,709];
            if(arr.indexOf(param_ids[i]) > -1){
               $scope.formData['param_'+param_ids[i]] = []; 
            } else {
                $scope.formData['param_'+param_ids[i]] = '';
            }
            
        }
        
    }     

    $scope.setNullKey = function(param_id, key_id, index){
        for(i= 0; i < $scope.formData['param_'+param_id].length; i++){
            if($scope.formData['param_'+param_id][i]['key_'+key_id] != 'undefined'){
                if (i == index) {
                    $scope.formData['param_'+param_id][i]['key_'+key_id]  = null;
                }
            }
        }
    }     

    $scope.setMultiNullKey = function(param_id, key_ids, index){
        for(i= 0; i < $scope.formData['param_'+param_id].length; i++){
            for (var j = 0; j < key_ids.length; j++) {
            
                if($scope.formData['param_'+param_id][i]['key_'+key_ids[j]] != 'undefined'){
                    if (i == index) {
                        $scope.formData['param_'+param_id][i]['key_'+key_ids[j]]  = null;
                    }
                }
            }
        }
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
            alert("File name is required ss!");
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
            $scope.fileUploading = false;
        });
    }

    $scope.unitCalculator = function(unit_type_id, param_id, output_unit){
        $scope.unit_type_id = unit_type_id;
        $scope.param.unit_type_id = unit_type_id;
        $scope.param.param_id = param_id;
        $scope.param.outputUnit = output_unit;
        $scope.convert.outputUnit = output_unit;
        if(output_unit > 0){
            $scope.output_unit_disable = true;
        } else {
            $scope.output_unit_disable = false;
        }
        $scope.is_error = false; 
        DBService.postCall($scope.param, '/api/reports/unit-calculator').then(function(data){
            $scope.units = data.units;
            $scope.conversions = data.conversions;
            $("#unitCalculator").modal("show");

        });
    }

    $scope.unitConvert = function(){
        $scope.convert.unit_type_id = $scope.unit_type_id;
        $scope.is_error = false;
        if ($scope.convert.unit_type_id == 0 || $scope.convert.inputUnit <= 0 || $scope.convert.outputUnit == 0 || $scope.convert.inputUnit == $scope.convert.outputUnit) {
            $scope.conversion_rate = 1;
            $scope.convert.output_value = $scope.convert.input_value*$scope.conversion_rate;
        } else {
            DBService.postCall($scope.convert, '/api/reports/unit-calculate').then(function(data){
                if (data.success) {
                    $scope.conversion_rate = data.unit_rel.conversion_rate;

                    if (data.unit_rel.type == 1) {
                        $scope.convert.output_value = $scope.convert.input_value*$scope.conversion_rate;
                    } else if (data.unit_rel.type == 2) {
                        $scope.convert.output_value = $scope.convert.input_value/$scope.conversion_rate;
                    }
                    
                } else {
                    $scope.is_error = true;
                    $scope.convert.inputUnit = '';
                }
            });
        }

    }        

    $scope.copyValue = function(){
        $scope.formData['param_'+$scope.param.param_id] = $scope.convert.output_value;
        $scope.param = {};
        $scope.convert = {};

        $("#unitCalculator").modal("hide");

    }

    $scope.ShowAdditional = function(){
      $scope.show_additional = true;

        DBService.postCall({collation_id : $scope.collation_id, collate_type : $scope.collate_type, ques_id : $scope.ques_id},'/api/reports/additional-fill').then(function(data){

            $scope.formData['param_'+$scope.additional_param_id] = $scope.formData['param_'+$scope.additional_param_id] + data.html;

            $scope.show_additional = false;

            $("#collationData").modal("hide");
        });  
    } 

    $scope.openTable = function(table) {
        $scope.my_ar = [];
       $("#"+table).modal("show"); 
    } 


    $scope.calSum = function(obj, param_ids, toFixed, set_param){
        
        total = 0;
        for (var i = 0; i < param_ids.length ; i++) {
            if (!isNaN(obj['value_'+param_ids[i]]) && obj['value_'+param_ids[i]] != "") {
                total += parseFloat(obj['value_'+param_ids[i]])
            }        
        }
        obj['value_'+set_param] = total.toFixed(toFixed);
        sum = total.toFixed(toFixed);
        return sum;
    }


    $scope.calPercAr = function(ar1,ar2,set_param,formParam){
        param1 = 0;

        for (var i = 0; i < ar1.length ; i++) {

            if (!isNaN($scope.modelData['value_'+ar1[i]]) && $scope.modelData['value_'+ar1[i]] != "") {
                param1 += parseFloat($scope.modelData['value_'+ar1[i]])
            }        
        }
        param2 = 0;

        for (var i = 0; i < ar2.length ; i++) {

            if (!isNaN($scope.modelData['value_'+ar2[i]]) && $scope.modelData['value_'+ar2[i]] != "") {
                param2 += parseFloat($scope.modelData['value_'+ar2[i]])
            }
        }

        if(!isNaN(param2) && param2 != "" && param2 != 0 && param1 != "" && !isNaN(param1 && param1 != 0 ) ) {
            value =  ((param1*100)/param2).toFixed(2);
        }else {
            value = "0.00";
        }
        
        $scope.modelData['value_'+set_param] = value;
        $scope.modelData['param_'+formParam] = value;
        var idx = $scope.my_ar.indexOf(formParam);
        if(idx == -1){
            $scope.my_ar.push(formParam);
        }

        
    }
    
    $scope.calPerc = function (obj, param1, param2, set_param, formParam) {


        if(!isNaN(obj['value_'+param2]) && obj['value_'+param2] != "" && obj['value_'+param2] != 0 && obj['value_'+param1] != "" && !isNaN(obj['value_'+param1] && obj['value_'+param1] != 0 ) ) {
            value =  ((obj['value_'+param1]*100)/obj['value_'+param2]).toFixed(2);
        }else {
            value = "0.00";
        }
        
        obj['value_'+set_param] = value;
        $scope.modelData['param_'+formParam] = value;
        var idx = $scope.my_ar.indexOf(formParam);
        if(idx == -1){
            $scope.my_ar.push(formParam);
        }

        return value;
    }

    $scope.calPercEmp = function (obj, param1, param2, formParam, toFixed) {
        

        if(!isNaN(obj['param_'+param2]) && obj['param_'+param2] != "" && obj['param_'+param2] != 0 && obj['param_'+param1] != "" && !isNaN(obj['param_'+param1] && obj['param_'+param1] != 0 ) ) {
            value =  ((obj['param_'+param1]*100)/obj['param_'+param2]).toFixed(toFixed);
        }else {
            value = "0.00";
        }
        $scope.formData['param_'+formParam] = value;
        return;
    }

    $scope.calPercEmpAr = function(ar1,ar2,formParam, toFixed){
        
        param1 = 0;

        for (var i = 0; i < ar1.length ; i++) {

            if (!isNaN($scope.formData['param_'+ar1[i]]) && $scope.formData['param_'+ar1[i]] != "") {
                param1 += parseFloat($scope.formData['param_'+ar1[i]])
            }        
        }
        param2 = 0;

        for (var i = 0; i < ar2.length ; i++) {

            if (!isNaN($scope.formData['param_'+ar2[i]]) && $scope.formData['param_'+ar2[i]] != "") {
                param2 += parseFloat($scope.formData['param_'+ar2[i]])
            }
        }

        if(!isNaN(param2) && param2 != "" && param2 != 0 && param1 != "" && !isNaN(param1 && param1 != 0 ) ) {
            value =  ((param1*100)/param2).toFixed(toFixed);
        }else {
            value = "0.00";
        }
        
        $scope.formData['param_'+formParam] = value;
        return value;
        
    }

    
    $scope.calLTIFR = function(obj, param1, param2, set_param){
        if(!isNaN(obj['value_'+param2]) && obj['value_'+param2] != "" && obj['value_'+param2] != 0 && obj['value_'+param1] != "" && !isNaN(obj['value_'+param1] && obj['value_'+param1] != 0 ) ) {
            value =  ((obj['value_'+param1]*1000000)/obj['value_'+param2]).toFixed(2);
        }else {
            value = "0.00";
        }
        
        obj['value_'+set_param] = value;

        var idx = $scope.my_ar.indexOf(set_param);
        if(idx == -1){
            $scope.my_ar.push(set_param);
        }
 
    }

    $scope.closeTableLTIFR = function(table) {
        $("#"+table).modal("hide");
        for (var i = 0; i < $scope.my_ar.length; i++) {
            $scope.formData['param_'+$scope.my_ar[i]] = $scope.modelData['value_'+$scope.my_ar[i]]; 
        } 
    } 

    $scope.closeTable = function(table) {
        $("#"+table).modal("hide");
        for (var i = 0; i < $scope.my_ar.length; i++) {
            $scope.formData['param_'+$scope.my_ar[i]] = $scope.modelData['param_'+$scope.my_ar[i]]; 
        } 
    } 

    $scope.PreviousYearData = function(){
        $scope.fetchPreviousData = true;
        DBService.postCall({},'/api/reports/previous-year-data/'+$scope.page_id).then(function(data){
            if(data.success){
                $scope.previousYearData = data.PreviousYearData;
                for (var i = 0; i < $scope.previousYearData.length; i++) {
                    if($scope.previousYearData[i].py_param == 10)console.log($scope.previousYearData[i].value);
                    $scope.formData['param_'+$scope.previousYearData[i].py_param] = $scope.previousYearData[i].value;
                } 
            }
        }); 
        $scope.fetchPreviousData = false; 
    }  

    $scope.changeStock = function(index){
        $scope.formData.param_10[index]['key_216'] = '';
        $scope.formData.param_10[index]['key_217'] = '';
    }

    $scope.exportViaJS = function(table_id, sheet_name){
        let table = document.getElementById(table_id);

        console.log(table);

        let wb = XLSX.utils.table_to_book(table);

        XLSX.writeFile(wb, sheet_name+"sData.xlsx");
    }

    $scope.changeAssurance = function(param_id){

        if($scope.formData['param_'+param_id] != "All"){
            return;
        }
        
        DBService.postCall({param_id : param_id}, '/api/reports/child-params').then(function(data){

            for (var i = 0; i < data.change_params.length; i++) {
                if(data.change_params[i].assurance_level == 4){
                    $scope.formData['param_'+data.change_params[i].id] = "Yes";
                } else if(data.change_params[i].assurance_level == 2 || data.change_params[i].assurance_level == 3){
                    $scope.formData['param_'+data.change_params[i].id] = "All";
                }
            }

        });
    }

    $scope.changeAssuranceStatus = function(){
        DBService.postCall({}, '/api/reports/assurance-params').then(function(data){
            for (var i = 0; i < data.param_ids.length; i++) {
                $scope.formData['param_'+data.param_ids[i]] = "";
            }
            $scope.formData['param_2657'] = [];
        });       
    }

    $scope.changeStatus = function(param_id){
        if($scope.formData['param_'+param_id] != 'Yes'){
            bootbox.confirm('Are you Sure? If you select "No", then all the data that has been entered by the user would be deleted.', function(result){ 
                if(result){
                    DBService.postCall({param_id : param_id}, '/api/reports/get-null-params').then(function(data){
                        for (var i = 0; i < data.param_ids.length; i++) {
                            $scope.formData['param_'+data.param_ids[i]] = "";
                        }
                        if(param_id == 2715){
                            $scope.formData['param_1628'] = [];
                            $scope.formData['param_1629'] = [];
                        }
                        if(param_id == 2717){
                            $scope.formData['param_671'] = [];
                        }
                    });
                } else {
                    $scope.$apply(function() {
                        $scope.formData['param_'+param_id] = 'Yes';
                    });
                }
            });
        } else {
            DBService.postCall({param_id : param_id}, '/api/reports/get-null-params').then(function(data){
                for (var i = 0; i < data.param_ids.length; i++) {
                    $scope.formData['param_'+data.param_ids[i]] = "";
                }
                if(param_id == 2715){
                    $scope.formData['param_1628'] = [];
                    $scope.formData['param_1629'] = [];
                }
                if(param_id == 2717){
                    $scope.formData['param_671'] = [];
                }
            });
        }
    }


    $scope.unitPerRupee = function(rupee_param, unit_param){
        if($scope.formData['param_'+unit_param] != ""){
            $scope.formData['param_'+rupee_param] = $scope.formData['param_'+unit_param]+'PerINR';
        } else {
            $scope.formData['param_'+rupee_param] = "";
        }
    }

    $scope.sumKeysInParam = function(multi_param_id, key_id, param_id){
        var sum_value = 0;
        for (var i = 0; i < $scope.formData['param_'+multi_param_id].length; i++) {
             // $scope.formData['param_'+multi_param_id];
            if($scope.formData['param_'+multi_param_id][i]['key_'+key_id]){
                sum_value += parseFloat($scope.formData['param_'+multi_param_id][i]['key_'+key_id]);
            }
        }
        $scope.formData['param_'+param_id] = sum_value;
    }

    $scope.yearOnYearVariation = function(modal_id){
        $("#"+modal_id).modal('show');
    }

    $scope.getPercVar = function(CYParam, PYParam){
        var val = 0;
        if($scope.formData['param_'+PYParam] == 0){
            value = "Variation cannot be calculated for this data point as value for Previous Year was '0'";
        } else if(!isNaN($scope.formData['param_'+CYParam]) && $scope.formData['param_'+CYParam] != "" && !isNaN($scope.formData['param_'+PYParam]) && $scope.formData['param_'+PYParam] != ""){
            val = $scope.formData['param_'+CYParam] - $scope.formData['param_'+PYParam];
            value =  ((val*100)/$scope.formData['param_'+PYParam]).toFixed(2);
            value = value+"%";
        }else {
            value = "0.00%";
        }
        return value;
    }

    $scope.getVariation = function(CYParam, PYParam){
        var value = ($scope.formData['param_'+CYParam] - $scope.formData['param_'+PYParam]).toFixed(2);
        value = value+"%";
        return value;
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

        bootbox.confirm("Are you sure?", function(result){ 
            if(result){
                if(file_id){
                    DBService.postCall({},'/admin/delete-ques-file/'+file_id).then(function(data){
                        alert(data.message);
                        $scope.report_rel = {};
                        $scope.uploadQuestionFile($scope.file_ques_id, $scope.file_edit);
                    });
                } else {
                    $scope.file = {};
                    $scope.report_rel = {};
                    $scope.uploadQuestionFile($scope.file_ques_id, $scope.file_edit);   
                }
            }
        });
    }


    $scope.uploadQuesFile = function(){
        console.log($scope.report_rel);
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

    $scope.FetchInternalData = function(ques_id){
        $scope.fetchInternal = true;
        DBService.postCall({ques_id : ques_id},'/api/reports/fetch-internal-data').then(function(data){
            if (data.success) {
                for (var i = 0; i < data.collat_data.length; i++) {
                    param = data.collat_data[i];
                    $scope.formData['param_'+param.id] = param.value;
                }
                $scope.fetchInternal = false;
            } else {
                alert(data.message);
                $scope.fetchInternal = false;
            }
        });
    }

    $scope.emissionsCalculator = function(param_id){
        $scope.em_param_id = param_id;
        DBService.postCall({param_id : $scope.em_param_id},'/api/reports/cal-emission').then(function(data){
            if(data.success){
                if(data.emissionList.length > 0){
                    $scope.emissionList = data.emissionList;
                } else {
                    $scope.emissionList = $scope.emissionList = [{
                        data_points: 0, 
                        application: '',
                        conversion_rate: 0, 
                        value: 0 
                    }];
                }
            }
            $("#emiCalModal").modal("show");
            $scope.chnageTotalEmission();
        });
    }

    $scope.addEmission = function(){
        $scope.emissionList.push({demo:''});
    }

    $scope.alert_msg = `This data doesn't match "Employees- Section A."`;
    
    $scope.InternalDataNotMatched = function(param_id){
        const value = $scope.formData['param_'+param_id];
        return value !== undefined &&  value !== '' && value !== null && value != $scope.formData['intCon_'+param_id] && $scope.formData['intCon_'+param_id] !== null && $scope.formData['intCon_'+param_id] !== "" && $scope.formData['intCon_'+param_id]!== undefined;
    };


    $scope.removeEmission = function(index){
        $scope.emissionList.splice(index,1);
        $scope.chnageTotalEmission();
    }

    $scope.changeEmission = function(index){
        const dataPoint = $scope.emissionList[index].data_points;
        const application = $scope.emissionList[index].application;
        const conRate = $scope.emissionList[index].conversion_rate;
        if (dataPoint == null || application == null || conRate == null || dataPoint == '' || application == '' || conRate == '') {
            $scope.emissionList[index].value = 0;
        } else {
            $scope.emissionList[index].value = eval(`${dataPoint} ${application} ${conRate}`);
        }
        $scope.chnageTotalEmission();
    }

    $scope.chnageTotalEmission = function(){
        $scope.emission_total = 0;
        for (var i = 0; i < $scope.emissionList.length; i++) {
            $scope.emission_total = $scope.emission_total + ($scope.emissionList[i].value ? ($scope.emissionList[i].value*1) : 0);
        }
    }

    $scope.collectTotalEmission = function(){
        $scope.formData['param_'+$scope.em_param_id] = ($scope.emission_total*1);
        DBService.postCall({param_id : $scope.em_param_id, emissionList:$scope.emissionList},'/api/reports/store-emission').then(function(data){
            $("#emiCalModal").modal("hide");
        });
    }

    $scope.waterCalculator = function(param_id){
        $scope.wt_param_id = param_id;
        DBService.postCall({param_id : $scope.wt_param_id},'/api/reports/cal-water').then(function(data){
            if(data.success && data.waterCalData){
                $scope.waterCalData = data.waterCalData;
            }
            
            $("#waterCalModal").modal("show");
            $scope.chnageTotalWater();
        });
    }

    $scope.chnageTotalWater = function(){

        if($scope.waterCalData.employees != "" && !isNaN($scope.waterCalData.employees) && $scope.waterCalData.employees != 0 && $scope.waterCalData.working_days != "" && !isNaN($scope.waterCalData.working_days) && $scope.waterCalData.working_days != 0 ){
            $scope.waterCalData.kl_total =  (($scope.waterCalData.employees  * $scope.waterCalData.working_days * 45)  / 1000).toFixed(2);
        } else {
            $scope.waterCalData.kl_total = 0;
        }
    }

    $scope.collectTotal = function(){
        $scope.formData['param_'+$scope.wt_param_id] = ($scope.waterCalData.kl_total*1);
        DBService.postCall({param_id : $scope.wt_param_id, waterCalData:$scope.waterCalData},'/api/reports/store-water').then(function(data){
            $("#waterCalModal").modal("hide");
        });
    }

    $scope.YOYVariationComparison = function(CYParam, PYParam, type){
        $scope.alert_variation_msg = 'The figures entered in this field exceed the permissible range of minimum '+ $scope.min_comparison+'% or maximum '+$scope.max_comparison+'% set by the Admin. Please verify the data before proceeding.';
        if($scope.min_comparison != 0 || $scope.max_comparison != 0){
            if(!isNaN($scope.formData['param_'+CYParam]) && $scope.formData['param_'+CYParam] != "" && !isNaN($scope.formData['param_'+PYParam]) && $scope.formData['param_'+PYParam] != ""){
                
                const CYParam_value = $scope.formData['param_'+CYParam];
                const PYParam_value = $scope.formData['param_'+PYParam];

                if(type == "percent"){
                    var variation = PYParam_value - CYParam_value;
                
                    if (variation > $scope.max_comparison || variation < (0 - $scope.min_comparison)) {
                        return true;
                    } 

                } else {
                    var minRange = 100 - $scope.min_comparison;
                    var maxRange = 100 + $scope.max_comparison;
                    var diffPer = CYParam_value / PYParam_value * 100;
                    
                    if ((minRange > diffPer) || (maxRange < diffPer)) {
                        return true;
                    } else {
                        return false;
                    }
                }
            }

        } 

        return false;

    };    

    $scope.YOYVariationComparisonAddMore = function(param, CYKey, PYKey, index, type){
        $scope.alert_variation_msg = 'The figures entered in this field exceed the permissible range of minimum '+ $scope.min_comparison+'% or maximum '+$scope.max_comparison+'% set by the Admin. Please verify the data before proceeding.';
        if($scope.min_comparison != 0 || $scope.max_comparison != 0){

            const CYKey_value = $scope.formData['param_'+param][index]['key_'+CYKey];
            const PYKey_value = $scope.formData['param_'+param][index]['key_'+PYKey];

            if(!isNaN(CYKey_value) && CYKey_value != "" && !isNaN(PYKey_value) && PYKey_value != ""){
                

                if(type == "percent"){
                    var variation = PYKey_value - CYKey_value;
                
                    if (variation > $scope.max_comparison || variation < (0 - $scope.min_comparison)) {
                        return true;
                    } 

                } else {
                    var minRange = 100 - $scope.min_comparison;
                    var maxRange = 100 + $scope.max_comparison;
                    var diffPer = CYKey_value / PYKey_value * 100;
                    
                    if ((minRange > diffPer) || (maxRange < diffPer)) {
                        return true;
                    } else {
                        return false;
                    }
                }
            }

        } 

        return false;

    };

    $scope.selectDivReportForCollate = function(report_id){
        console.log(report_id);
        var report = $scope.collatReportIds.indexOf(report_id);
      
        if(report > -1){
            $scope.collatReportIds.splice(report, 1);
        }else{
            $scope.collatReportIds.push(report_id);
        }

    }

    $scope.useCalculator = function(question_id){
        var message = "Are you sure you want to continue? This action will permanently erase or modify the existing data filled in this question."; 

        bootbox.confirm(message, function(result){ 
            if(result){
                $scope.$apply(() => {
                    $scope.formData['use_calculator_'+question_id] =  $scope.formData['use_calculator_'+question_id] == 1 ? 0 : 1;
                });
            }
        });
    }


    $scope.viewQuesRelFiles = function(report_id){
        $scope.files_loading = true;
        DBService.postCall({ report_id: report_id, ques_id: $scope.ques_id},'/admin/view-ques-files').then(function(data){
            $scope.report_rel_files = data.report_rel_files;
            $("#ViewQuesFile").modal("show");
            $scope.files_loading = false;

        });
    }

});


app.controller('SesParamCtrl', function($scope , $timeout, DBService, Upload){
    
    $scope.param_id = 0;
    $scope.parameter_id = 0;
    $scope.param = {};
    $scope.indus_id = 0;
    $scope.industry_id = 0;
    $scope.loading = true;
    $scope.unit_types = [];
    $scope.global = {
        parameter_id : 0,
        industry_id : 0,
    };

    $scope.param_init = function(){
        DBService.getCall('/ses/parameter_init/'+$scope.indus_id).then(function(data){
            $scope.parameters = data.parameters;
            $scope.unit_types = data.unit_types;
            $scope.loading = false;
        });
    }
   
    $scope.editParameter = function(param_id) {
        $scope.param_id = param_id;
        DBService.postCall({},'/ses/edit-param/'+$scope.param_id).then(function(data){
            $scope.param = data.param;
            $scope.industries = data.industries;
            $("#param_modal").modal('show');
        });
    }
    

    $scope.updateParam = function(param){
        $scope.param = param; 
        DBService.postCall( $scope.param ,'/ses/update-param').then(function(data){
            if (data.success) {
                $scope.param = {};
                $("#param_modal").modal("hide");
                $scope.param_init(false);
            }
            alert(data.message);
        });
    }

    $scope.uploadMultifile = function(param_id){
        $scope.param_id = param_id;
        DBService.getCall('/ses/get-param-reference/'+param_id+'/'+$scope.indus_id).then(function(data){
            if (data.success){

                $scope.param_references = data.param_references;
                $("#multiple_reference_modal").modal("show");
            }
        });
    }

    $scope.uploadFile = function (file,name,obj) {
        if(file){
            obj.uploading = true;
            var url = base_url+'/uploadParamFile';
            Upload.upload({
                url: url,
                data: {
                    media: file,
                    param_id:obj.id,
                    indus_id : $scope.indus_id,
                }
            }).then(function (resp) {
                if(resp.data.success){
                    obj[name] = resp.data.media;
                } else {
                    alert(resp.data.message);
                }
                obj.uploading = false;

            }, function (resp) {
               
                obj.uploading = false;

            }, function (evt) {
               
            });
        }
    }

    $scope.removeParamImage = function(param , indus_id){
        bootbox.confirm("Are you sure?", function(result){ 
            if(result){
                param.image = ''; 
                DBService.postCall( { param_id : param.id , indus_id } ,'/ses/remove-param-image').then(function(data){
                });
            }

        });       

    }


    $scope.uploadFileReference = function (file,name,obj) {
        obj.uploading = true;
        if(file){
            var url = base_url+'/uploadParamReference';
            Upload.upload({
                url: url,
                data: {
                    media: file,
                    param_id:$scope.param_id,
                    indus_id : $scope.indus_id,
                }
            }).then(function (resp) {          
                if(resp.data.success){
                    $scope.uploadMultifile($scope.param_id);
                } else {
                    alert(resp.data.message);
                }
                obj.uploading = false;

            }, function (resp) {

                obj.uploading = false;

            }, function (evt) {
               
            });
        }
    }

    $scope.removeParamReference = function(reference_id){

        bootbox.confirm("Are you sure?", function(result){ 
            if(result){
                DBService.postCall( { reference_id : reference_id } ,'/ses/remove-param-reference').then(function(data){
                
                    if (data.success) {
                        $scope.uploadMultifile($scope.param_id);
                    }
                });
            }
        });       
    }

    $scope.globalFrameworks = function(parameter_id) {
        $scope.global = {};
        $scope.global.parameter_id = parameter_id;
        $scope.global.industry_id = $scope.indus_id;
        DBService.postCall($scope.global,'/ses/edit-global').then(function(data){
            if(data.global_framework){
                $scope.global = data.global_framework;
            }
            $("#framework_modal").modal('show');
        });
    }    

    $scope.storeFrameworks = function() {

        DBService.postCall($scope.global,'/ses/update-global').then(function(data){
            
            if(data.success){
                $("#framework_modal").modal('hide');
                $scope.global = {};
            }
            alert(data.message);
            
        });
    }

});

app.controller('SesIndusCtrl', function($scope , $timeout, DBService, Upload){
    
    $scope.indus_id = 0;
    $scope.indus = {};
    $scope.loading = true;
    
    $scope.indus_init = function(){
        DBService.postCall({},'/ses/industry_init').then(function(data){
            $scope.industries = data.industries;
            $scope.loading = false;
        });
    }

    $scope.addIndustry = function() {
        $scope.indus = { industry: '' };
        $("#indus_modal").modal("show");
    } 

    $scope.editIndustry = function(indus_id) {
        $scope.indus_id = indus_id;
        DBService.postCall({},'/ses/edit-indus/'+$scope.indus_id).then(function(data){
            $scope.indus = data.indus;
            $("#indus_modal").modal('show');
        });
    }

    $scope.updateIndus = function(valid){
        DBService.postCall( $scope.indus ,'/ses/update-indus').then(function(data){
            if (data.success) { 
                $("#indus_modal").modal("hide"); 
                $scope.indus_init();
            }
            alert(data.message);
        });
    }   
});


app.controller('CompanyCtrl', function($scope , $timeout, DBService, Upload){
    
    $scope.company_id = 0;
    $scope.report_id = 0;
    $scope.parent_id = 0;
    $scope.level = 1;
    $scope.loading = true;
    $scope.laddaCompany = false;
    $scope.laddaDivision = false;
    $scope.addCompanyCheck = false;
    $scope.company_tree = 0;
    $scope.company = {};
    $scope.master_company = {};
    $scope.financial_years = [];
    $scope.financial_year = '';
    $scope.tree_id = 0;
    $scope.sub_tree_id = 0;
    $scope.enc_division_id = '';

    
    $scope.company_init = function(){
        DBService.postCall({},'/admin/company_init').then(function(data){
            $scope.companies = data.companies;
            $scope.master_company = data.master_company;
            $scope.loading = false;
            $scope.addCompanyCheck = data.addCompanyCheck;
        });
    }

    $scope.addCompany = function() {
        $scope.company_id = 0;
        $scope.company = {};
        $scope.companyAdd.$setPristine();
        $("#company_modal").modal("show");
    } 

    $scope.editCompany = function(company_id) {
        $scope.company_id = company_id;
        DBService.postCall({},'/admin/edit-company/'+$scope.company_id).then(function(data){
            $scope.company = data.company;
            $scope.companyAdd.$setPristine();
            $("#company_modal").modal('show');
        });
    }

    $scope.deactivateCompany = function(company_id) {
        $scope.company_id = company_id;
        bootbox.confirm("Are you sure?", function(result){ 
            if(result){
                DBService.postCall({},'/admin/deactivate-company/'+$scope.company_id).then(function(data){
                    $scope.company_init(false);
                    alert(data.message);
                });
            }
        });
    }    

    $scope.deleteCompany = function(company_id) {
        $scope.company_id = company_id;
        bootbox.confirm("Are you sure?", function(result){ 
            if(result){
                DBService.postCall({},'/admin/delete-company/'+$scope.company_id).then(function(data){
                    $scope.company_init(false);
                    alert(data.message);
                });
            }
        });
    }

    $scope.updateCompany = function(valid) {
        $scope.laddaCompany = true;
        DBService.postCall( $scope.company ,'/admin/update-company').then(function(data){
            if (data.success) {
                $scope.company = {};
                $("#company_modal").modal("hide");
                $scope.company_init();
            }
            alert(data.message);
            $scope.laddaCompany = false;
        });
    } 

    $scope.clearCompanyYear = function(company_id) {
        $scope.company_id = company_id;
        DBService.getCall('/admin/select-year/'+$scope.company_id).then(function(data){
            if (data.success) {
                $scope.financial_years = data.financial_years;
                $("#financial_years_modal").modal('show');
            }
        });
    } 
    
    $scope.clearDivisionYear = function(enc_division_id) {
        $scope.enc_division_id = enc_division_id;
        DBService.getCall('/admin/select-division-year/'+$scope.enc_division_id).then(function(data){
            if (data.success) {
                $scope.financial_years = data.financial_years;
                $("#financial_years_modal").modal('show');
            }
        });
    } 

    $scope.clearReport = function(report_id) {
        $scope.report_id = report_id;
        bootbox.confirm("Are you sure?", function(result){ 
            if(result) {
                DBService.getCall('/admin/clear-report/'+$scope.report_id).then(function(data){
                    alert(data.message);
                });

            }
        });
    }

    $scope.addDivision = function(level, parent_division, company_id){

        $scope.level = level;
        $scope.parent_id = parent_division;
        // $scope.company = company;
        $scope.company_id = company_id;
        $scope.division = {

        };

        $scope.DivisionForm.$setPristine(); 

        $("#division_modal").modal("show");
    }

    $scope.editDivision = function(enc_division_id){
        $scope.enc_division_id = enc_division_id;

        DBService.getCall('/admin/edit-division/'+$scope.enc_division_id).then(function(data){
            if(data.division) {
                $scope.division = data.division; 
                $scope.level = $scope.division.level;
                $("#division_modal").modal("show"); 
            }
        
        });
    }

    $scope.storeDivision = function(valid){
        $scope.laddaDivision = true;
        $scope.division.level = $scope.level;
        $scope.division.parent_id = $scope.parent_id;
        
        if(!$scope.division.company_id){
            $scope.division.company_id = $scope.company_id;
        }

        DBService.postCall( $scope.division ,'/admin/store-division').then(function(data){
            if (data.success) {
                $("#division_modal").modal("hide");
                $scope.company_init();
            }
            alert(data.message);
            $scope.laddaDivision = false;
        }); 
    }

    $scope.deleteDivision =function(enc_division_id){
        $scope.enc_division_id = enc_division_id;
         bootbox.confirm("Are you sure?", function(result){ 
            if(result){
                DBService.getCall('/admin/delete-division/'+$scope.enc_division_id).then(function(data){
                    alert(data.message)
                    $scope.company_init();
                });
            }
        });

    }

    $scope.showMap = function(company){
        $scope.company = company;
        $scope.company_tree = $scope.company.id;
        $scope.master_id = 0;

    }    

    $scope.openDivisions = function(division_id){
        
        $scope.tree_id = division_id;
    }

    $scope.openSubDivisions = function(sub_division_id){
        
        $scope.sub_tree_id = sub_division_id;
    }

    $scope.viewSubDivisions = function(division_id){
        
        DBService.postCall( {} ,'/admin/sub-division/'+division_id).then(function(data){
            if (data.success) {
                $scope.division_tree = division_id;
                $scope.tree_divisions = data.tree_divisions;
            }
        }); 
    } 

    $scope.masterMap = function(company_id){
        DBService.postCall({},'/admin/master-map/'+company_id).then(function(data){
            if(data.success){
                $scope.master_map = data.masterMap;
                $scope.master_id = company_id;
                $scope.company_tree = 0;
            }
        });
    }
});



app.controller('SesClientCtrl', function($scope , $timeout, DBService){
    $scope.client_id = 0
    $scope.client = {};
    $scope.loading = true;

    $scope.clients_init = function(){
        DBService.postCall({},'/ses/ses-clients-list').then(function(data){
            $scope.clients = data.clients;
            $scope.loading = false;
        });
    } 
});

app.controller('SesDisclosureCtrl', function($scope , $timeout, DBService, Upload){
    
    $scope.disclosures_id = 0;
    $scope.indus_id = 0;
    $scope.disclosures = {};
    $scope.loading = true;

    $scope.disclosure_init = function(){
        DBService.getCall('/ses/disclosure_init/'+$scope.indus_id).then(function(data){
            $scope.disclosures = data.disclosures;
            $scope.loading = false;
        });
    } 

    $scope.uploadDisclosureFile = function (file,name,obj) { 
        if(file){
            obj.uploading = true;
            var url = base_url+'/disclosureFileUpload';
            Upload.upload({
                url: url,
                data: {
                    media: file,
                    disclosure_id:obj.id,
                    indus_id : $scope.indus_id,                    
                }
            }).then(function (resp) {
                if(resp.data.success){
                    obj[name] = resp.data.media;
                } else {
                    alert(resp.data.message);
                }
                obj.uploading = false;

            }, function (resp) {
                obj.uploading = false;

            }, function (evt) {
               
            });
        }
    }

    $scope.removeDisclosureImage = function(disclosure, indus_id){
        bootbox.confirm("Are you sure?", function(result){ 
            if(result){
                disclosure.image = ''; 
                DBService.postCall( { disclosure_id : disclosure.id , indus_id} ,'/ses/remove-disclosure-image').then(function(data){
                });
            }

        });
    }
    
    $scope.uploadDisclosureMultifile = function(disclosure_id){
        $scope.disclosure_id = disclosure_id;
        DBService.getCall('/ses/get-disclosure-reference/'+disclosure_id+'/'+$scope.indus_id).then(function(data){
            if (data.success){ 
                $scope.disclosure_references = data.disclosure_references;
                $("#multiple_disclosure_reference_modal").modal("show");
            }
        });
    }


    $scope.uploadDisclosureReference = function (file,name) {

        if(file){
            var url = base_url+'/uploadDisclosureReference';
            Upload.upload({
                url: url,
                data: {
                    media: file,
                    disclosure_id:$scope.disclosure_id,
                    indus_id : $scope.indus_id,
                }
            }).then(function (resp) {          
                if(resp.data.success){
                    $scope.uploadDisclosureMultifile($scope.disclosure_id);
                } else {
                    alert(resp.data.message);
                }
                obj.uploading = false;

            }, function (resp) {
               
                obj.uploading = false;

            }, function (evt) {
               
            });
        }
    }

    $scope.removeDisclosureReference = function(reference_id){

        bootbox.confirm("Are you sure?", function(result){ 
            if(result){
                DBService.postCall( { reference_id : reference_id } ,'/ses/remove-disclosure-reference').then(function(data){
                
                    if (data.success) {
                        $scope.uploadDisclosureMultifile($scope.disclosure_id);
                    }
                });
            }
        });       
    }


});

app.controller('contactUsCtrl', function($scope , $http, $timeout , DBService){
    
    $scope.formData = {};         

    $scope.init = function(id){
        $scope.loading = true;
        DBService.getCall('/contact-us/init').then(function(data){
            if(data.success){
                $scope.messages = data.messages;
                $scope.meetings = data.meetings;
            }
            $scope.loading = false;
        });
    };

    $scope.sendMessage = function(type,entity_id){
        $scope.formData = {type:type,entity_id:entity_id};
        $scope.messageForm.$setPristine();
        $("#message-box").modal("show");
    }

    $scope.postMessage = function(){
        $scope.processing = true;
        DBService.postCall($scope.formData,'/contact-us/send-message').then(function(data){
            if(data.success){
                $scope.formData = {};
                $scope.messageForm.$setPristine();
                $scope.messages = data.messages;
                $scope.meetings = data.meetings;
            }
            $("#message-box").modal("hide");

            $scope.processing = false;
            bootbox.alert(data.message);
        });
    }

    $scope.showMessage = function(message){
        $scope.openContent = message;
        $("#mail-content").modal("show");
    }

   
});


app.controller('userManagementCtrl', function($scope , $timeout, DBService){

    $scope.user_id = 0;
    $scope.user = {
        name : "",
        username : "",
        company_admin : "",
        user_admin : "" ,
        contact : "",
        all_access: 0,
        allow_py_edit: 0,
        company_id: 0,
        company_division_id: 0,
        selected_ids: [],
    };
    $scope.ques = {};
    $scope.storeUserProcess = false;

    $scope.addUser = function(){

        DBService.getCall('/user_management/get-user/'+$scope.user_id).then(function(data){
            if(data.success){
                if($scope.user_id != 0){
                    if(data.user.allow_py_edit == 0){
                        data.user.allow_py_edit = -1;  //** 0 as -1 **
                    }
                    $scope.user = data.user;

                    if($scope.user.level > 1){
                        $scope.getDivisions();
                    }

                    if($scope.user.level > 2){
                        $scope.getSubDivisions();
                    }

                }
            }
        }); 
    }    


    $scope.getCompanies = function(){
        DBService.getCall('/user_management/companies').then(function(data){
            if(data.success){
                $scope.companies = data.companies;
            }
        });
    }
    $scope.getCompanies();

    $scope.storeUser = function(){
        $scope.storeUserProcess = true;
        
        DBService.postCall($scope.user,'/user_management/store-user').then(function(data){
           if(data.success){
                bootbox.alert(data.message, function(){ 
                    window.location = data.redirect_url;
                });
            } else {
                bootbox.alert(data.message);  
            }
            $scope.storeUserProcess = false;
           
        });
    }

    $scope.changeLevel = function(){
        $scope.user.company_id = 0;
        $scope.user.selected_ids = [];
        $scope.divisions = [];
        $scope.sub_divisions = [];

        $scope.getDivisions();
    }

    $scope.changeAllAccess = function(){

        $scope.user.selected_ids = [];

        if($scope.user.all_access == 0){
            $scope.user.all_access = 1;
        } else {
            $scope.user.all_access = 0;
        }
    }

    $scope.selectCompany = function(com_id){

        idx = $scope.user.selected_ids.indexOf(com_id);

        if(idx == -1){
            $scope.user.selected_ids.push(com_id);
        } else {
            $scope.user.selected_ids.splice(idx,1);
        } 
    }

    $scope.getDivisions = function(){
        
        if(!$scope.user.level || !$scope.user.company_id){
            return;
        }

        DBService.postCall({
            level : $scope.user.level,
            company_id : $scope.user.company_id
        },'/user_management/divisions').then(function(data){
            if(data.success){
                $scope.divisions = data.divisions;

                if($scope.user.level == 2){
                    $scope.sub_divisions = data.divisions;
                }
            }
        });

    }

    $scope.getSubDivisions = function(){
        
        if(!$scope.user.level || !$scope.user.company_id){
            return;
        }

        DBService.postCall({
            level : $scope.user.level,
            company_id : $scope.user.company_id,
            company_division_id : $scope.user.company_division_id,
        },'/user_management/divisions').then(function(data){
            if(data.success){
                $scope.sub_divisions = data.divisions;
            }
        });

    }

    $scope.changeQuestionnaire = function(){
        $scope.user.user_type = '';
    }    

    $scope.changeUserType = function(){
        if($scope.user.user_type == 1){
            $scope.user.level = 1;
            $scope.user.company_admin = 1;
            $scope.user.q_to_q = 0;
            $scope.user.user_admin = 0;
            $scope.user.all_access = 0;
            $scope.user.company_division_id = 0;
        } 
    }

    $scope.storeQuesList = function(){
        DBService.postCall($scope.ques,'/user_management/store-ques-list').then(function(data){
            alert('Done');
        });
    }

    
});

app.controller('userManagementQuesWiseAllo', function($scope , $timeout, DBService){

    $scope.user_id = 0;
    $scope.user_access = {

    };

    $scope.user = {

    };
    
    $scope.loading = false;
    $scope.processPYAccess = false;
    $scope.copyProcessPYAccess = false;
    $scope.user_access_list = [];
    
    $scope.page_access_ids = [];
    $scope.pre_access_user_ids = [];
    $scope.access_id  = 
    $scope.changeType = false;
    $scope.pages = [];
    $scope.quarter_sessions = [];
    $scope.no_of_mails = 0;
    $scope.list_length_flag = false;
    $scope.select_all_user_id_flag_not_submit = false;
    $scope.access_user_ids = [];
    $scope.access_details = {
        'financial_year' : 2025,
        'page_id' : 0,
        'level' : 0,
        'item_id' : 0,
        'quarter_session' : 0,
    }
    $scope.eligible_users = [];

    $scope.setAccess = function(){
        DBService.getCall('/user_management/set-access-init').then(function(data){
            if(data.success){
                if($scope.user_id != 0){
                    $scope.user = data.user;
                }
                $scope.no_of_mails = data.no_of_mails;
                $scope.levels = data.levels;
                $scope.quarter_sessions = data.quarter_sessions;
            }
        });

    } 

    $scope.fetchCompanies = function(){
        $scope.access_details.item_id = 0;
        DBService.postCall($scope.access_details, '/user_management/get-level-dropdown').then(function(data){
            if(data.success){
                $scope.items = data.items;
            } 
        });
    } 

    $scope.changeAccessDetails = function( ){
        $scope.pages = [];
        if ($scope.access_details.item_id > 0) {
            
            DBService.postCall($scope.access_details, '/user_management/change-allocations-details').then(function(data){
                if(data.success){
                    $scope.pages = data.pages;
                    $scope.access_details.company_id = data.company_id;
                    $scope.access_details.division_id = data.division_id;
                }
            });
        }

    }

    $scope.addAccess = function(page_id, question_id,user_role){
        $scope.access_details.page_id = page_id;
        $scope.access_details.user_role = user_role;
        $scope.access_details.question_id = question_id;
        $scope.access_user_ids = [];

        DBService.postCall($scope.access_details, '/user_management/question-access').then(function(data){
            $scope.eligible_users = data.eligible_users;
            $scope.access_list = data.access_list;
            for (var i = 0; i < $scope.access_list.length; i++) {
                $scope.access_user_ids.push($scope.access_list[i].user_id);
            }
        });

        $("#add_access_modal").modal("show"); 
    }

    $scope.closeAccessModal= function(){
        $scope.access_list = [];
        $("#add_access_modal").modal("hide");   
    }

    $scope.addMoreUser = function(){
        $scope.access_list.push({demo:''});
    }

    $scope.removeUser = function(index){
        bootbox.confirm("Do you want to remove the Access?",function(res){
            if(res){
                $scope.$apply(() => {
                    $scope.access_list.splice(index,1);
                });
            }
        });
       
    } 

    $scope.storeAccessUser = function(){
        $scope.loading = true;
        DBService.postCall({access_list:$scope.access_list,access_details:$scope.access_details},'/user_management/store-question-access').then(function(data){
            alert(data.message);
            $scope.loading = false;
            $scope.no_of_mails = data.no_of_mails;
            if(data.success == true){
                $scope.select_all_user_id_flag_not_submit = false;
                console.log(data.maker_users);
                for (var i = 0; i < $scope.pages.length; i++) {
                    if($scope.pages[i].id == $scope.access_details.page_id){
                        for (var j = 0; j < $scope.pages[i].question_list.length; j++) {
                            if($scope.pages[i].question_list[j].id == $scope.access_details.question_id){
                                $scope.pages[i].question_list[j].maker_users = data.maker_users;   
                                $scope.pages[i].question_list[j].checker_users = data.checker_users;   
                            }
                        }
                    }
                }
                $scope.pre_access_user_ids = [];
                $("#add_access_modal").modal("hide");
            }
            
        });
    } 

    $scope.sendNotification = function(){
        bootbox.confirm("Are you sure?",function(res){
            if(res){
                DBService.postCall({},'/user_management/send-access-mail').then(function(data){
                    $scope.no_of_mails = data.no_of_mails;
                    alert("Mail sent successfully!");
                });
            }
        });

    }

    $scope.selectMultiUser = function(){
        for (var i = 0; i < $scope.access_list.length; i++) {
            if($scope.access_user_ids.indexOf($scope.access_list[i].user_id) == -1){
                $scope.access_user_ids.push($scope.access_list[i].user_id);
            }

            if(!$scope.select_all_user_id_flag_not_submit){
                $scope.pre_access_user_ids.push($scope.access_list[i].user_id);
            }
        }
        $scope.check_length();
        $("#selectUsers").modal("show");   
    }

    $scope.check_length = function(){
        if($scope.access_list.length == $scope.eligible_users.length){
            $scope.list_length_flag = true;
        } else {
            $scope.list_length_flag = false;
        }
    }

    $scope.copyToAllDate = function(){
        for (var i = 1; i < $scope.access_list.length; i++) {
            $scope.access_list[i].deadline = $scope.access_list[0].deadline;
        }
    }    

    $scope.copyToAllAlert = function(){
        for (var i = 1; i < $scope.access_list.length; i++) {
            $scope.access_list[i].prior_alert = $scope.access_list[0].prior_alert;
        }
    }

    $scope.userIds = function(user_id){
        var user = $scope.access_user_ids.indexOf(user_id);

        if(user > -1){
            for (var j = 0; j < $scope.access_list.length; j++) {
                if($scope.access_list[j].user_id == $scope.access_user_ids[user]){
                    $scope.access_list.splice([j], 1);
                }
            }
            $scope.access_user_ids.splice(user,1);
        }else{
            $scope.access_user_ids.push(user_id);
            $scope.access_list.push({user_id:user_id});
        }
        $scope.check_length();

    }

    $scope.allUserIdsOld = function(eligible_users){
        if($scope.access_list.length == $scope.eligible_users.length){
        } else {
            for (var i = 0; i < eligible_users.length; i++) {
                var user = $scope.access_user_ids.indexOf(eligible_users[i].id);
                if(user == -1){
                    $scope.access_user_ids.push(eligible_users[i].id);
                    $scope.access_list.push({user_id:eligible_users[i].id});
                }
            }
        }
        $scope.check_length();
    }

    $scope.allUserIds = function() {
        $scope.list_length_flag = !$scope.list_length_flag;
        if ($scope.list_length_flag) {
            for (var i = 0; i < $scope.eligible_users.length; i++) {
                var userId = $scope.eligible_users[i].id;
                if ($scope.access_user_ids.indexOf(userId) === -1 && $scope.eligible_users[i].is_full_page_access != 1) {
                    $scope.access_user_ids.push(userId);
                    $scope.access_list.push({ user_id: userId });
                }
            }
        } else {
            for (var i = 0; i < $scope.eligible_users.length; i++) {
                var userId = $scope.eligible_users[i].id;
                if ($scope.pre_access_user_ids.indexOf(userId) === -1) {
                    var userIndex = $scope.access_user_ids.indexOf(userId);
                    if (userIndex !== -1) {
                        $scope.access_user_ids.splice(userIndex, 1);
                        var accessListIndex = $scope.access_list.findIndex(item => item.user_id === userId);
                        if (accessListIndex !== -1) {
                            $scope.access_list.splice(accessListIndex, 1);
                        }
                    }
                }
            }
        }

        if (!$scope.list_length_flag) {
            for (var i = 0; i < $scope.pre_access_user_ids.length; i++) {
                var preUserId = $scope.pre_access_user_ids[i];
                if ($scope.access_user_ids.indexOf(preUserId) === -1) {
                    $scope.access_user_ids.push(preUserId);
                    $scope.access_list.push({ user_id: preUserId });
                }
            }
        }
    }

    $scope.addMultiUser = function(){
        $scope.access_user_ids = [];
        $scope.select_all_user_id_flag_not_submit = true;
        $("#selectUsers").modal("hide");   
    }

    $scope.showPYAccess = function(page_id){
        $scope.processPYAccess = true;
        DBService.postCall($scope.access_details, '/user_management/py-ques-access/'+page_id).then(function(data){
            if(data.success){
                $scope.page = data.page;
                $("#PYquesAccessList").modal("show"); 
                $scope.processPYAccess = false;  
            } else {
                alert(data.message);
                $scope.processPYAccess = false;
            } 
        });
    }

    $scope.copyAllPYAccess = function(){
        $scope.copyProcessPYAccess = true;
        DBService.postCall({page : $scope.page, access_details : $scope.access_details}, '/user_management/py-ques-access-copy').then(function(data){
            if(data.success){
                $scope.changeAccessDetails();
                $("#PYquesAccessList").modal("hide");
                $scope.copyProcessPYAccess = false;
                bootbox.alert(data.message);
            } else {
                bootbox.alert(data.message);
                $scope.copyProcessPYAccess = false;
            }
        });
    }

    $scope.removePYMakerAcc = function(makers, index){
        makers.splice(index,1);
    }   

});

app.controller('userManagementAccessCtrl', function($scope , $timeout, DBService){

    $scope.user_id = 0;
    $scope.user_access = {

    };

    $scope.user = {

    };
    
    $scope.loading = false;
    $scope.list_length_flag = false;
    $scope.select_all_user_id_flag = false;
    $scope.select_all_user_id_flag_not_submit = false;
    $scope.user_access_list = [];
    
    $scope.page_access_ids = [];
    $scope.access_id  = 
    $scope.changeType = false;
    $scope.pages = [];
    $scope.quarter_sessions = [];
    $scope.access_user_ids = [];
    $scope.already_access = [];
    $scope.pre_access_user_ids = [];

    $scope.old_access = [];
    $scope.py_access_list = [];

    $scope.access_details = {
        'financial_year' : 2025,
        'page_id' : 0,
        'level' : 0,
        'item_id' : 0,
        'quarter_session' : 0,
    }
    $scope.eligible_users = [];

    $scope.setAccess = function(){

        DBService.getCall('/user_management/set-access-init').then(function(data){

            if(data.success){
                if($scope.user_id != 0){
                    $scope.user = data.user;
                }
                $scope.levels = data.levels;
                $scope.quarter_sessions = data.quarter_sessions;
            }
        });

    } 

    $scope.fetchCompanies = function(){
        $scope.access_details.item_id = 0;
        DBService.postCall($scope.access_details, '/user_management/get-level-dropdown').then(function(data){
            if(data.success){
                $scope.items = data.items;
            } 
        });
    } 

    $scope.changeAccessDetails = function( ){
        
        $scope.pages = [];

        if ($scope.access_details.item_id > 0) {
            
            DBService.postCall($scope.access_details, '/user_management/change-access-details').then(function(data){
                if(data.success){
                    $scope.pages = data.pages;
                    $scope.access_details.company_id = data.company_id;
                    $scope.access_details.division_id = data.division_id;
                }
                
            });
        }

    }

    $scope.addAccess = function(page_id, user_role){
        $scope.access_details.page_id = page_id;
        $scope.access_details.user_role = user_role;
        $scope.access_user_ids = [];
    
        DBService.postCall($scope.access_details, '/user_management/add-access').then(function(data){
            $scope.eligible_users = data.eligible_users;
            $scope.old_access = data.access_list;
            $scope.access_list = data.access_list;
            for (var i = 0; i < $scope.access_list.length; i++) {
                $scope.access_user_ids.push($scope.access_list[i].user_id);
            }

        });

        $("#add_access_modal").modal("show"); 
    }    

    $scope.checkPYAccess = function(){
        
        DBService.postCall($scope.access_details, '/user_management/check-py-access').then(function(data){
            $scope.py_pages = data.pages;
            $("#PYAccessModal").modal("show"); 
        });

        
    }

    $scope.closeAccessModal= function(){
        $scope.access_list = [];
        $("#add_access_modal").modal("hide");   
        $("#PYAccessModal").modal("hide");   
    }

    $scope.getDivision = function(){
        DBService.getCall('/user_management/get-divisions/'+$scope.user_access.company_id).then(function(data){
            $scope.divisions = data.divisions;
         });
    }

    $scope.getPageAccess = function(){
        $scope.user_access.division_id = 0;
        $scope.page_access_ids = [];
        $scope.changeType = false;
    }

    $scope.storeAccessUser = function(){

        $scope.loading = true;
        DBService.postCall({access_list:$scope.access_list,access_details:$scope.access_details},'/user_management/store-access').then(function(data){
            alert(data.message);
            $scope.loading = false;
            if(data.success == true){
                $("#add_access_modal").modal("hide");
                $scope.select_all_user_id_flag_not_submit = false;
                $scope.pre_access_user_ids = [];
                $scope.changeAccessDetails(); 
            }
            
        });
    } 

    $scope.addMoreUser = function(){
        $scope.access_list.push({demo:''});
    }

    $scope.selectMultiUser = function(){
        for (var i = 0; i < $scope.access_list.length; i++) {
            if($scope.access_user_ids.indexOf($scope.access_list[i].user_id) == -1){
                $scope.access_user_ids.push($scope.access_list[i].user_id);
            }

            if(!$scope.select_all_user_id_flag_not_submit){
                $scope.pre_access_user_ids.push($scope.access_list[i].user_id);
            }
        }
        $scope.check_length();
        $("#selectUsers").modal("show");   
    }

    $scope.check_length = function(){
        if($scope.access_list.length == $scope.eligible_users.length){
            $scope.list_length_flag = true;
        } else {
            $scope.list_length_flag = false;
        }
    }

    $scope.copyToAllDate = function(){
        for (var i = 1; i < $scope.access_list.length; i++) {
            $scope.access_list[i].deadline = $scope.access_list[0].deadline;
        }
    }    

    $scope.copyToAllAlert = function(){
        for (var i = 1; i < $scope.access_list.length; i++) {
            $scope.access_list[i].prior_alert = $scope.access_list[0].prior_alert;
        }
    }

    $scope.userIds = function(user_id){
        var user = $scope.access_user_ids.indexOf(user_id);

        if(user > -1){
            for (var j = 0; j < $scope.access_list.length; j++) {
                if($scope.access_list[j].user_id == $scope.access_user_ids[user]){
                    $scope.access_list.splice([j], 1);
                }
            }
            $scope.access_user_ids.splice(user,1);
        }else{
            $scope.access_user_ids.push(user_id);
            $scope.access_list.push({user_id:user_id});
        }
        $scope.check_length();

    }

    $scope.userIdsPY = function(user_id, index){
        var user = $scope.access_user_ids.indexOf(user_id);

        if(user > -1){
        }else{
            $scope.access_user_ids.push(user_id);
            $scope.access_list.push({user_id:user_id, prior_alert: $scope.py_access_list[index].prior_alert });
        }
        $scope.check_length();

    }

    $scope.allUserIdsOLd = function(eligible_users){
        if($scope.access_list.length == $scope.eligible_users.length){

 
        } else {
            for (var i = 0; i < eligible_users.length; i++) {
                var user = $scope.access_user_ids.indexOf(eligible_users[i].id);
                if(user == -1){
                    $scope.access_user_ids.push(eligible_users[i].id);
                    $scope.access_list.push({user_id:eligible_users[i].id});
                }
            }
        }
        $scope.check_length();
    }

    $scope.allUserIds = function() {
        $scope.list_length_flag = !$scope.list_length_flag;

        if ($scope.list_length_flag) {
            for (var i = 0; i < $scope.eligible_users.length; i++) {
                var userId = $scope.eligible_users[i].id;
                if ($scope.access_user_ids.indexOf(userId) === -1) {
                    $scope.access_user_ids.push(userId);
                    $scope.access_list.push({ user_id: userId });
                }
            }
        } else {
            for (var i = 0; i < $scope.eligible_users.length; i++) {
                var userId = $scope.eligible_users[i].id;
                if ($scope.pre_access_user_ids.indexOf(userId) === -1) {
                    var userIndex = $scope.access_user_ids.indexOf(userId);
                    if (userIndex !== -1) {
                        $scope.access_user_ids.splice(userIndex, 1);
                        var accessListIndex = $scope.access_list.findIndex(item => item.user_id === userId);
                        if (accessListIndex !== -1) {
                            $scope.access_list.splice(accessListIndex, 1);
                        }
                    }
                }
            }
        }

        if (!$scope.list_length_flag) {
            for (var i = 0; i < $scope.pre_access_user_ids.length; i++) {
                var preUserId = $scope.pre_access_user_ids[i];
                if ($scope.access_user_ids.indexOf(preUserId) === -1) {
                    $scope.access_user_ids.push(preUserId);
                    $scope.access_list.push({ user_id: preUserId });
                }
            }
        }

    }    

    $scope.copyAllPYAccess = function(){
        $scope.copyProcessPYAccess = true;
        
        DBService.postCall({pages : $scope.py_pages, access_details : $scope.access_details}, '/user_management/py-access-copy').then(function(data){
            if(data.success){
                $scope.changeAccessDetails();
                $("#PYAccessModal").modal("hide");   
                $scope.copyProcessPYAccess = false;
                bootbox.alert(data.message);
            } else {
                bootbox.alert(data.message);
                $scope.copyProcessPYAccess = false;
            }
        });
    }



    $scope.addMultiUser = function(){
        $scope.access_user_ids = [];
        $scope.select_all_user_id_flag_not_submit = true;
        $("#selectUsers").modal("hide");   
    }


    $scope.removeUser = function(index){
        bootbox.confirm("Do you want to remove the Access?",function(res){
            if(res){
                $scope.$apply(() => {
                    $scope.access_user_ids.splice($scope.access_list[index],1);
                    $scope.access_list.splice(index,1);
                });
            }
        });
       
    }

    $scope.removePYMakerAcc = function(makers, index){
        makers.splice(index,1);
    }    

}); 

app.controller('UnitTypeCtrl', function($scope , $timeout, DBService){
    
    $scope.unit_type_id = 0;
    $scope.unit_id = 0;
    
    $scope.unit_types = [];
    $scope.units = [];

    $scope.unit_type = {};
    $scope.unit = {};
    $scope.convert = {
        input_value : 1,
    } 

    $scope.loading = true;
    
    $scope.unit_init = function(){
        DBService.postCall({},'/ses/unit-type/type-init').then(function(data){
            $scope.unit_types = data.unit_types;
            $scope.loading = false;
        });
    }

    $scope.addUnitType = function(type_id){
        $scope.unit_type_id  = type_id;
        DBService.getCall('/ses/unit-type/edit/'+$scope.unit_type_id).then(function(data){
                $scope.unit_type = data.unit_type; 
                $("#unit_type_modal").modal("show"); 
        
        });
    }

    $scope.deleteUnitType = function(type_id){
        $scope.unit_type_id  = type_id;
        bootbox.confirm("Are you sure?", function(result){ 
            if(result){
                DBService.getCall('/ses/unit-type/delete/'+$scope.unit_type_id).then(function(data){
                    alert(data.message);
                    $scope.unit_init();
                });
            }
        });
    }
    
    $scope.storeUnitType = function(valid){
        DBService.postCall( $scope.unit_type ,'/ses/unit-type/store-type').then(function(data){
            if (data.success) { 
                $("#unit_type_modal").modal("hide"); 
                $scope.unit_init();
            }
            alert(data.message);
        });
    }

    $scope.viewUnits = function(type_id){
        $scope.unit_type_id = type_id;
        DBService.getCall( '/ses/unit-type/units/'+$scope.unit_type_id).then(function(data){
            $scope.units = data.units;
            $scope.unit_type = data.unit_type;
            $("#units_list").modal("show");
        });
    }

    $scope.deleteUnit = function(unit_id ){
        $scope.unit_id  = unit_id;bootbox.confirm("Are you sure?", function(result){ 
            if(result){
                DBService.getCall('/ses/unit-type/delete-unit/'+$scope.unit_id).then(function(data){
                    alert(data.message);
                    $scope.reloadUnits();
                });
            }
        });
    }

    $scope.editUnit = function(unit_id){
        $scope.unit_id = unit_id ;
        DBService.getCall( '/ses/unit-type/edit-unit/'+$scope.unit_id).then(function(data){
            $scope.unit = data.unit;
            $("#units_list").modal("show");
        });
    }

    $scope.storeUnit = function(valid){
       DBService.postCall( $scope.unit ,'/ses/unit-type/store-unit/'+$scope.unit_type_id).then(function(data){
            if (data.success) { 
                $scope.reloadUnits();
                $scope.unit = {};

            }
            alert(data.message);
        }); 
    }

    $scope.reloadUnits = function(){
        DBService.getCall( '/ses/unit-type/units/'+$scope.unit_type_id).then(function(data){
            $scope.units = data.units;
        });
    }

    $scope.viewConversions = function(type_id){
        $scope.unit_type_id = type_id;
        DBService.getCall( '/ses/unit-type/conversions/'+$scope.unit_type_id).then(function(data){
            $scope.units = data.units;
            $scope.unit_type = data.unit_type;
            $scope.conversions = data.conversions;
            $("#conversions_list").modal("show");
        });
    }

    $scope.storeConversion = function(valid){
        DBService.postCall( $scope.convert ,'/ses/unit-type/store-conversion/'+$scope.unit_type_id).then(function(data){
            alert(data.message);
            if (data.success) { 
                $scope.convert ={
                    input_value : 1,
                }
                $scope.viewConversions($scope.unit_type_id);
                $scope.unit = {}; 
            }
        }); 
    } 

    $scope.editConversion = function(convert_id){
        DBService.getCall('/ses/unit-type/edit-conversion/'+convert_id).then(function(data){
            $scope.convert = data.convert;
        });

    }    

    $scope.deleteConversion = function(convert_id){
        bootbox.confirm("Are you sure?", function(result){ 
            if(result){
                DBService.getCall('/ses/unit-type/delete-conversion/'+convert_id).then(function(data){
                    if(data.success){
                        alert(data.message);
                    }
                });
            }
        });
    } 
}); 

app.controller('trainingCtrl', function($scope , $timeout, DBService,Upload){
    
    $scope.loading = false;
    $scope.fileUploading = false;
    $scope.trainings = [];
    $scope.training = {};
    $scope.eligible_users = [];
    $scope.training_id = 0;
    $scope.training_files = [];
    
    
    $scope.training_init = function(){
        $scope.loading = true;

        DBService.postCall({},'/training-programs/training-init').then(function(data){
            $scope.trainings = data.trainings;
            $scope.loading = false;
        });
    }

    $scope.setDateOfCompletion = function(){

        DBService.postCall( $scope.trainings, '/training-programs/set-completion').then(function(data){
            if (data.success) {
                alert(data.message);
                $scope.training_init();
            }
        });        
    }

    $scope.eligibelUsers = function(training_id){
        $scope.training_id = training_id;
        DBService.getCall('/training-programs/eligibl-users/'+training_id).then(function(data){
            if (data.success) {

                $scope.eligible_users = data.eligible_users;
            }
        });
        $("#assignUser").modal("show");
    }

    $scope.addEligibleUser = function(user_id){

        for (var i = 0; i < $scope.eligible_users.length; i++) {
            var user = $scope.eligible_users[i];
            if(user_id == user.id){
                user.checked = !user.checked;
            }
        }

    }

    $scope.assingUsers = function(){
        DBService.postCall( $scope.eligible_users , '/training-programs/assign-users/'+$scope.training_id).then(function(data){
            if (data.success) {
                $("#assignUser").modal("hide");
                alert(data.message);
                $scope.training_init();
            }
        }); 
    }


    $scope.trainingFiles = function(training_id){
        $scope.training_files = [];
        $scope.training_id = training_id;
        DBService.postCall({}, '/training-programs/get-files/'+$scope.training_id).then(function(data){
            $scope.training_files = data.training_files;
            $("#trainingFiles").modal("show");
        });
        
    }

    $scope.uploadTrainingFile = function(file) {        
        if(file){
            if(file.size < 10*1024*1024){
                $scope.uploading = true;
                var url = base_url+'/training-programs/upload-file/'+$scope.training_id;
                Upload.upload({
                    url: url,
                    data: {
                        media: file
                    }
                }).then(function (resp) {
                    if(resp.data.success){
                        $scope.training.file_path = resp.data.path;
                        
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

    $scope.storeTrainingFile = function(){
        $scope.fileUploading = true;
        DBService.postCall($scope.training, '/training-programs/store-file/'+$scope.training_id).then(function(data){
            if(data.success){
                alert("Successfully Stored !");
                $scope.training = {};
                $scope.trainingFiles($scope.training_id);
            }
        });
        $scope.fileUploading = false;
    }

    $scope.removeTrainingFile = function(){
        
        bootbox.confirm("Are you sure?", function(result){ 
            if(result){
                $scope.training.file_path = '';
            }
        });
    }

    $scope.deleteTrainingFile = function(file_id ){
        
        bootbox.confirm("Are you sure?", function(result){ 
            if(result){
                DBService.postCall({}, '/training-programs/delete-file/'+file_id).then(function(data){
                    if(data.success){
                        alert("Successfully Deleted !");

                        $scope.trainingFiles($scope.training_id);
                    }
                });
            }
        });
    }

});

app.controller('questionLockCtrl', function($scope, $timeout, DBService){
        
    $scope.lock_processing = false;
    $scope.ques_ids = [];
    $scope.unlock_ids = [];
    $scope.maker_ques_ids = [];
    $scope.checker_ques_ids = [];
    $scope.unlock_admin_ques_ids = [];
    $scope.report_id = 0;
    $scope.report = {
        'financial_year' : 2025,
        'level' : 0,
        'item_id' : 0,
        'quarter_session' : 0,
    }

    $scope.levels = [];
    $scope.quarter_sessions = [];

    $scope.init = function(){
        $scope.lock_processing = true;

        DBService.postCall($scope.report ,'/api/question-lock/init').then(function(data){  
            if (data.success) {
                $scope.pages = data.pages;
                $scope.report_id = data.report_id;
            }
            $scope.levels = data.levels;
            $scope.quarter_sessions = data.quarter_sessions;
            $scope.lock_processing = false;
        });
    } 

    $scope.storeQuesLock = function(){
        $scope.lock_processing = true;
        $scope.report.id = $scope.report_id;
        $scope.report.ques_ids = $scope.ques_ids;
        $scope.report.unlock_ids = $scope.unlock_ids;

        DBService.postCall($scope.report ,'/api/question-lock/store-lock').then(function(data){
            if (data.success) {
                alert(data.message);
                $scope.ques_ids = [];
                $scope.unlock_id = [];
            }

            $scope.maker_page_ids = [];
            $scope.checker_page_ids = [];
            $scope.unlock_ids = [];

            $scope.init();
            $scope.lock_processing = false;
        }); 
    }

    $scope.changeReportDetails = function(){        
        $scope.pages = [];
        if ($scope.report.item_id > 0) {
            DBService.postCall($scope.report, '/question-lock/change-report').then(function(data){
                if(data.success){
                    $scope.pages = data.pages;
                    $scope.report.company_id = data.company_id;
                    $scope.report.division_id = data.division_id;
                    $scope.report_id = data.report_id;
                }
                
            });
        }

    }

    $scope.fetchCompanies = function(){
        
        $scope.report.item_id = 0;
        DBService.postCall($scope.report, '/report-lock/get-level-dropdown').then(function(data){
            if(data.success){
                $scope.items = data.items;
                $scope.pages = [];
            } 
        });
    }

    $scope.quesIds = function(ques_id){

        var question = $scope.ques_ids.indexOf(ques_id);
      
        if(question > -1){
            $scope.ques_ids.splice(question,1);
        }else{
            $scope.ques_ids.push(ques_id);
        }

    }

    $scope.unlockIds = function(unlock_id){ 

        var unlock = $scope.unlock_ids.indexOf(unlock_id);
      
        if(unlock > -1){
            $scope.unlock_ids.splice(unlock,1);
        }else{
            $scope.unlock_ids.push(unlock_id);
        } 
    }

    $scope.makerQuesIds = function(ques_id){

        var question = $scope.maker_ques_ids.indexOf(ques_id);
      
        if(question > -1){
            $scope.maker_ques_ids.splice(question,1);
        }else{
            $scope.maker_ques_ids.push(ques_id);
        }

    }

    $scope.checkerQuesIds = function(ques_id){

        var question = $scope.checker_ques_ids.indexOf(ques_id);
      
        if(question > -1){
            $scope.checker_ques_ids.splice(question,1);
        }else{
            $scope.checker_ques_ids.push(ques_id);
        }

    }

    $scope.unlockAdminQuesIds = function(ques_id){

        var question = $scope.unlock_admin_ques_ids.indexOf(ques_id);
      
        if(question > -1){
            $scope.unlock_admin_ques_ids.splice(question,1);
        }else{
            $scope.unlock_admin_ques_ids.push(ques_id);
        }
    }


    $scope.storeAdminQuesLock = function(){
        $scope.lock_processing = true;
        $scope.report.id = $scope.report_id;
        $scope.report.maker_ques_ids = $scope.maker_ques_ids;
        $scope.report.checker_ques_ids = $scope.checker_ques_ids;
        $scope.report.unlock_admin_ques_ids = $scope.unlock_admin_ques_ids;

        DBService.postCall($scope.report ,'/api/question-lock/store-admin-lock').then(function(data){
            if (data.success) {
                alert(data.message);
                $scope.maker_ques_ids = [];
                $scope.checker_ques_ids = [];
                $scope.unlock_admin_ques_ids = [];
            }

            $scope.maker_ques_ids = [];
            $scope.checker_ques_ids = [];
            $scope.unlock_admin_ques_ids = [];

            $scope.changeReportDetails();
            $scope.lock_processing = false;
        }); 
    }

});

app.controller('reportLockCtrl', function($scope, $timeout, DBService){
        
    $scope.lock_processing = false;
    $scope.save_draft = false;  
    $scope.pages = [];
    $scope.page_ids = [];
    $scope.quarter_sessions = [];
    $scope.page_list = [];
    $scope.report_id = 0;
    $scope.report = {};
    $scope.page_id = '';

    $scope.report = {
        'financial_year' : 2025,
        'level' : 0,
        'item_id' : 0,
        'quarter_session' : 0,
    }

    $scope.init = function(){

        $scope.page_ids = [];
        $scope.maker_page_ids = [];
        $scope.checker_page_ids = [];
        $scope.unlock_ids = [];
        $scope.lock_processing = true;

        DBService.postCall($scope.report ,'/api/report-lock/init').then(function(data){  
            if (data.success) {
                $scope.pages = data.pages;
                $scope.report_id = data.report_id;
            }
            $scope.levels = data.levels;
            $scope.quarter_sessions = data.quarter_sessions;
            $scope.lock_processing = false;
        });
    } 

    $scope.storePageLock = function(){
        $scope.lock_processing = true;
        $scope.report.id = $scope.report_id;

        $scope.report.page_ids = $scope.page_ids;

        DBService.postCall($scope.report ,'/report-lock/store-lock').then(function(data){
            if (data.success) {
                alert(data.message);
            }

            $scope.maker_page_ids = [];
            $scope.checker_page_ids = [];
            $scope.unlock_ids = [];

            $scope.init();
            $scope.lock_processing = false;
        }); 
    }

    $scope.fetchCompanies = function(){
        
        $scope.report.item_id = 0;
        DBService.postCall($scope.report, '/report-lock/get-level-dropdown').then(function(data){
            if(data.success){
                $scope.items = data.items;
                $scope.pages = [];
            } 
        });
    } 

    $scope.changeReportDetails = function(){        
        $scope.pages = [];

        if ($scope.report.item_id > 0) {

            DBService.postCall($scope.report, '/report-lock/change-report').then(function(data){
                if(data.success){
                    $scope.pages = data.pages;
                    $scope.report.company_id = data.company_id;
                    $scope.report.division_id = data.division_id;
                    $scope.report_id = data.report_id;
                }
                
            });
        }

    }

    $scope.pageIds = function(page_id){

        var page = $scope.page_ids.indexOf(page_id);
      
        if(page > -1){
            $scope.page_ids.splice(page,1);
        }else{
            $scope.page_ids.push(page_id);
        }

    }

    $scope.makerPageIds = function(page_id){

        var page = $scope.page_ids.indexOf(page_id);
      
        if(page > -1){
            $scope.maker_page_ids.splice(page,1);
        }else{
            $scope.maker_page_ids.push(page_id);
        }
        
    }
    
    $scope.checkerPageIds = function(page_id){

        var page = $scope.checker_page_ids.indexOf(page_id);
      
        if(page > -1){
            $scope.checker_page_ids.splice(page,1);
        }else{
            $scope.checker_page_ids.push(page_id);
        }
        
    }

    $scope.unlockIds = function(unlock_id){ 

        var unlock = $scope.unlock_ids.indexOf(unlock_id);
      
        if(unlock > -1){
            $scope.unlock_ids.splice(unlock,1);
        }else{
            $scope.unlock_ids.push(unlock_id);
        }
        
    }

    $scope.storeReportStatus = function(){
        $scope.lock_processing = true;
        $scope.report.id = $scope.report_id
        $scope.report.makerPageIds = $scope.maker_page_ids;
        $scope.report.checkerPageIds = $scope.checker_page_ids;
        $scope.report.unlockIds = $scope.unlock_ids;

        DBService.postCall($scope.report ,'/report-lock/store-report-status').then(function(data){
            alert(data.message);
            $scope.lock_processing = false;
            if (data.success) {
                $scope.page_ids = [];
                $scope.maker_page_ids = [];
                $scope.checker_page_ids = [];
                $scope.unlock_ids = [];
                $scope.changeReportDetails(); 
            }
            
        }); 
    }
    $scope.testParamInit = function(){
        DBService.postCall({page_id : $scope.page_id} ,'/testParamInit').then(function(data){
            $scope.page_list = data.pages;
            
        }); 

    }

    $scope.updateQuesId = function(){
        $scope.save_draft = true;
        DBService.postCall($scope.page_list ,'/updateQuesId').then(function(data){
            if(data.success){
                $scope.save_draft = false;
                $scope.testParamInit();
            }
        }); 
    }
});

app.controller('GRICtrl', function($scope, $timeout, DBService){

    $scope.indicators = [];
    $scope.sdg_ids = [];
    $scope.pages = [];
    $scope.indicator = {
        locations:[{ex:1}],
        sdg_ids:[],
    };

    $scope.url = 'admin/make-report?page_id=';

    $scope.location = {};

    $scope.GRIInit = function(){
        DBService.postCall({},'/ses/gri/init').then(function(data){
            $scope.pages = data.pages;
            $scope.indicators = data.indicators;
            $scope.sdg_list = data.sdg_list;

        }); 
    }
    
    $scope.griAdd = function(){
        $scope.sdg_ids = [];
        $scope.indicator = {
            locations:[],
            sdg_ids:[],
        };

        $("#GRIAdd").modal("show");
    }
    
    $scope.griView = function(indicator){
        $scope.indicator = indicator;
        $("#GRIView").modal("show");
    }

    $scope.griEdit = function(indicator){
        $scope.indicator = indicator;

        if($scope.indicator){
            let val_ar = [];
            for (var i = 0; i < $scope.indicator.sdg_ar.length; i++) {
                val_ar.push(parseInt($scope.indicator.sdg_ar[i]));
            }

            $scope.indicator.sdg_ar = val_ar;
            $scope.sdg_ids = $scope.indicator.sdg_ar;
        }
        $("#GRIAdd").modal("show");
    }

    $scope.addLocation = function(){

        $scope.indicator.locations.push(JSON.parse(JSON.stringify($scope.location)));
    }

    $scope.removeLocation = function(index){
        bootbox.confirm("Do you want to remove the location?",function(res){
            if(res){
                $scope.$apply(() => {
                    $scope.indicator.locations.splice(index,1);
                });
            }
        });
       
    } 


    // $scope.checkSdgIds = function(sdg_id){

    //     var sdg = $scope.indicator.sdg_ids.indexOf(sdg_id);
      
    //     if(sdg > -1){
    //         $scope.indicator.sdg_ids.splice(sdg,1);
    //     }else{
    //         $scope.indicator.sdg_ids.push(sdg_id);
    //     }
    // }
    $scope.checkSdgIds = function(sdg_id){

        var sdg = $scope.sdg_ids.indexOf(sdg_id);
      
        if(sdg > -1){
            $scope.sdg_ids.splice(sdg,1);
        }else{
            $scope.sdg_ids.push(sdg_id);
        }
    }

    $scope.indicatorStore = function(){
        $scope.indicator.sdg_ids = $scope.sdg_ids;
        DBService.postCall($scope.indicator,'/ses/gri/store').then(function(data){
            alert(data.message);
            if (data.success) {
                $scope.indicator = {};
                $("#GRIAdd").modal("hide");
                $scope.GRIInit();
            }
        });
    }

    $scope.deleteIndicator = function(indi_id){
        bootbox.confirm("Are you sure?",function(res){
            if(res){
                DBService.postCall({},'/ses/gri/delete/'+indi_id).then(function(data){
                    alert(data.message);
                    $scope.GRIInit();
                });
            }
        });
    }

});

app.controller('QuestionCtrl', function($scope , $http, $timeout , DBService,Upload){
    $scope.loading = true;
    $scope.question_id = 0;
    $scope.subject_id = 0;
    $scope.processing = false;
    $scope.types = [];

    $scope.question = {
        question : '',
        subject_id: '',
        type: '',
        rows: [],
        columns: []
    }

    $scope.init = function(){
        $scope.loading = true;
        DBService.getCall('/admin/questionnaire/init/'+$scope.subject_id+'/'+$scope.question_id).then(function(data){
            if(data.success){
                if(data.question){
                    $scope.question = data.question;
                }
                $scope.types = data.types;
                $scope.loading = false;
            } else{
                alert(data.message);
               window.location = base_url + '/admin/questionnaire/subject/'+$scope.subject_id; 
            }
        });
    }

    $scope.submitQuestion = function(){
        $scope.processing = true;
        $scope.question.subject_id = $scope.subject_id;
        DBService.postCall($scope.question,'/admin/questionnaire/save').then(function(data){
            if(data.success){
                alert(data.message);
                $scope.question = {};
                $scope.question_id = 0;
                window.location = base_url + '/admin/questionnaire/subject/'+$scope.subject_id;
            } else {
                bootbox.alert(data.message);
            }
            $scope.processing = false;
        });
    }

    $scope.uploadFile = function (file,name) {
        if(file){
            $scope.uploading = true;
            var url = base_url+'/admin/uploadFile';
            Upload.upload({
                url: url,
                data: {
                    media: file,
                    _token:CSRF_TOKEN
                }
            }).then(function (resp) {
                if(resp.data.success){
                    $scope.question[name] = resp.data.media;
                    $scope[name] = resp.data.media_link;
                } else {
                    bootbox.alert(resp.data.message);
                }
                $scope.uploading = false;
            }, function (resp) {
                // console.log('Error status: ' + resp.status);
                $scope.uploading = false;
            }, function (evt) {
                $scope.uploading_percentage = parseInt(100.0 * evt.loaded / evt.total) + '%';
            });
        }
    };

    $scope.removeFile = function(name){
        $scope.formData[name] = '';
    }


    $scope.viewEligibleUsers = function(){
        $scope.eligible_users = [];
    }

    $scope.addRow = function(){
        $scope.question.rows.push({
            row_name: ""
        })
    }

    $scope.addColumn = function(){
        $scope.question.columns.push({
            column_name: ""
        })
    }
    
    $scope.deleteColumn = function(index){
        bootbox.confirm('Are you sure to delete?',function(result){
            if (result) {
                $scope.$apply(() => {
                    $scope.question['columns'].splice(index,1);
                });
            }
        });
    }

    $scope.deleteRow = function(index){
        bootbox.confirm('Are you sure to delete?',function(result){
            $scope.$apply(() => {
                $scope.question['rows'].splice(index,1);
            });
        });
    }    

});

app.controller('SubjectCtrl', function($scope,$http,DBService){

    $scope.exporting = false;
    $scope.sub_id = 0;
    $scope.formData = {};
    $scope.subjects = [];
    $scope.eligibleUsers = [];
    $scope.access_list = [];
    $scope.selected_ids = [];
    $scope.ques_id = 0;
    
    $scope.init = function(){
        $scope.init_process = true;
        DBService.postCall({sub_id:$scope.sub_id},'/admin/questionnaire/subjects/init').then(function(data){
            if(data.success){
                $scope.subjects = data.subjects; 
                if (data.subject) {
                    $scope.formData= data.subject;
                }             
            }
            $scope.init_process = false;
        }); 
    } 

    $scope.onSubmit = function(){
        $scope.processing = true;
        DBService.postCall($scope.formData,'/admin/questionnaire/subjects/store').then(function(data){
            if (data.success) {
                alert(data.message);
                $scope.formData = {
                    name : '',
                };
                // $scope.init(); 
                if($scope.sub_id != 0 ){
                    for (var i = 0; i < $scope.subjects.length; i++) {
                        if($scope.subjects[i].id == data.subject.id){
                            $scope.subjects[i] = data.subject;
                        }
                    }
                } else {
                    $scope.subjects.push(data.subject);
                } 
                $scope.sub_id = 0;
                $("#SubjectModal").modal("hide");
            }else{
                bootbox.alert(data.message);
            }
            $scope.processing = false;
        });

    }

    $scope.updateSubject = function(subject){
        $scope.sub_id = subject.id;
        $scope.formData = subject;
        $("#SubjectModal").modal("show");
    }

    $scope.addSubject = function(){
        $scope.formData = {};
        $scope.sub_id = 0;
        $("#SubjectModal").modal("show");
    }
    $scope.deleteSubjects = function(subject,index){
        bootbox.confirm('Are you sure to delete?',function(result){
            if (result) {
                DBService.postCall({ subject_id : subject.id },'/admin/questionnaire/subjects/delete').then(function(data){
                    if (data.success) {
                        alert(data.message);
                        $scope.subjects.splice(index,1);

                    }else{
                        bootbox.alert(data.message);
                    }
                });
            }
        });
    }


    $scope.viewEligibleUsers = function(sub_id){
        $scope.sub_id = sub_id;
        DBService.postCall({subject_id : sub_id }, '/admin/questionnaire/subjects/eligible-users').then(function(data){
            $scope.eligibleUsers = data.eligibleUsers;
            $scope.selected_ids = data.selected_ids;
            $("#user_access_modal").modal("show");
        });
    }

    $scope.addEligibleUser = function(user_id){

        idx = $scope.selected_ids.indexOf(user_id);

        if(idx == -1){
            $scope.selected_ids.push(user_id);
        } else {
            $scope.selected_ids.splice(idx,1);
        }
    }

    $scope.closeAccessModal= function(){
        $scope.access_list = [];
        $("#user_access_modal").modal("hide");   
    } 

    $scope.storeAccessUser = function(){

        $scope.loading = true;
        DBService.postCall({subject_id : $scope.sub_id, selected_ids : $scope.selected_ids},'/admin/questionnaire/subjects/store-access').then(function(data){
            alert(data.message);
            $scope.loading = false;
            if(data.success == true){
                $scope.sub_id = 0;
                $("#user_access_modal").modal("hide");
            }
            
        });
    }

    $scope.viewAnswers = function(ques_id){
        $scope.ques_id = ques_id;
        DBService.postCall({ques_id : $scope.ques_id },'/admin/questionnaire/subject/view-answers').then(function(data){
            $scope.answers = data.answers;
            $scope.question = data.question;
            $("#answersModal").modal("show");
            
        });
    }

});

app.controller('AnswersCtrl', function($scope,$http,DBService){
    $scope.questions = [];
    
    $scope.init = function(){
        $scope.init_process = true;
        DBService.postCall({},'/admin/questionnaire/answers/init').then(function(data){
            if(data.success){
                $scope.subjects = data.subjects;
            }
            $scope.init_process = false;
        }); 
    }

    $scope.onSubmit = function(){
        $scope.processing = true;
        
        DBService.postCall($scope.subjects,'/admin/questionnaire/answers/store').then(function(data){
            if (data.success) {
                alert(data.message);
            }else{
                bootbox.alert(data.message);
            }
            $scope.processing = false;
        });

    }

});

app.controller('VendorCtrl', function($scope,$http,DBService){
    
    $scope.companies = [];
    $scope.init_process = false;
    $scope.subjects = [];
    
    $scope.init = function(){
        $scope.init_process = true;
        DBService.postCall({},'/vendor/answers/init').then(function(data){
            if(data.subjects){
                $scope.subjects = data.subjects;
            } else {
                $scope.subjects = [];
            }
            $scope.companies = data.companies;
            $scope.init_process = false;
        }); 
    }

    $scope.onSubmit = function(){
        $scope.processing = true;
        
        DBService.postCall({subjects : $scope.subjects},'/vendor/answers/store').then(function(data){
            if (data.success) {
                alert(data.message);
            }else{
                bootbox.alert(data.message);
            }
            $scope.processing = false;
        });

    }


});

app.controller('esgUniversalSearchCtrl', function($scope,$http,DBService){

    $scope.init = function(){
        DBService.postCall({},'/universal-search/init').then(function(data){
            
        });
    }
});

app.controller('AuditTrailCtrl', function($scope,$http,DBService){
    $scope.filter = {
        page_id : '',
        start_date : "",
        to_date : "",
        ques_id : "",
        
    };
    $scope.parameter_values = [];
    $scope.param_ids = [];
    $scope.init = function(){
        DBService.postCall($scope.filter,'/admin/audit-trail/init').then(function(data){
            $scope.parameter_values = data.parameter_values;
            $scope.param_ids = data.param_ids;
            $scope.questions = data.questions;
        });
    }
});

app.controller('workStatusCtrl', function($scope,$http,DBService){
    
    $scope.users = [];
    $scope.user = {};
    $scope.dataSet = [];
    $scope.companies = [];
    $scope.report_types = [];
    $scope.init_process = false;
    $scope.loading = false;
    $scope.access_details = {
        user_id : 0,
        financial_year: 2025,
        quarter_session: 0,
        company_id:'',
    };
    
    $scope.init = function(){
        $scope.init_process = true;
        DBService.postCall({},'/user_management/work-status-init').then(function(data){
            if(data.success){
                $scope.users = data.users;
                $scope.report_types = data.report_types;
                $scope.init_process = false;
            } else {
                $scope.init_process = false;
            }
        }); 
    }

    $scope.changeAccessDetails = function(){
        $scope.loading = true;
        $scope.access_details.company_id = '';
        $scope.getAccessDetails();
        
    }    

    $scope.getAccessDetails = function(){
        $scope.loading = true;
        if($scope.access_details.financial_year > 0 && $scope.access_details.user_id){
            DBService.postCall($scope.access_details ,'/user_management/get-work-status').then(function(data){
                if (data.success) {
                    $scope.dataSet = data.dataSet;
                    $scope.user = data.user;
                    $scope.companies = data.companies;
                    $scope.loading = false;
                }
            });
        } else {
            $scope.dataSet = [];
            $scope.loading = false;
        }
    }

});


