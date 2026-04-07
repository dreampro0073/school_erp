var app = angular.module('app', [
	'jcs-autoValidate',
  'ngFileUpload',
  'selectize'
]);

angular.module('jcs-autoValidate')
    .run([
    'validator',
    'defaultErrorMessageResolver',
    function (validator, defaultErrorMessageResolver) {
        validator.setFirstInvalidElementScrollingOnSubmit(true);
        validator.setFocusInputError(true);

        defaultErrorMessageResolver.getErrorMessages().then(function (errorMessages) {
          errorMessages['patternInt'] = 'Please fill a numeric value';
          errorMessages['patternFloat'] = 'Please fill a numeric/decimal value';
        });
    }
]);

app.directive('convertToNumber', function() {
  return {
    require: 'ngModel',
    link: function(scope, element, attrs, ngModel) {
      ngModel.$parsers.push(function(val) {
        return val != null ? parseInt(val, 10) : null;
      });
      ngModel.$formatters.push(function(val) {
        return val != null ? '' + val : null;
      });
    }
  };
});

app.directive("modernPagination", function () {

   return {

      scope: {
         currentPage: "=",
         totalPages: "=",
         totalRecords: "=",
         onPageChange: "&"
      },

      template: `

<div class="dt-layout-row theme-layout-row">
   <div class="dt-layout-cell dt-start ">
      <div class="dt-info" aria-live="polite" id="dataTable_info" role="status"Total Records: {{totalRecords}}>Total Records: {{totalRecords}}</div>
   </div>
   <div class="dt-layout-cell dt-end ">

      <div class="dt-paging paging_full_numbers">

        <button class="dt-paging-button" ng-click="changePage(1)" ng-disabled="currentPage==1">
        «
        </button>

        <button class="dt-paging-button" ng-click="changePage(currentPage-1)" ng-disabled="currentPage==1">
        ‹
        </button>

        <button class="dt-paging-button"
        ng-repeat="p in pages"
        ng-click="changePage(p)"
        ng-class="{'current':p==currentPage}">
        {{p}}
        </button>

        <button class="dt-paging-button" ng-click="changePage(currentPage+1)" ng-disabled="currentPage==totalPages">
        ›
        </button>

        <button class="dt-paging-button" ng-click="changePage(totalPages)" ng-disabled="currentPage==totalPages">
        »
        </button>

      </div>
    </div>

</div>
`,

      link: function (scope) {

         scope.pages = [];

         scope.$watchGroup(["currentPage", "totalPages"], function () {
            generatePages();
         });

         function generatePages() {

            scope.pages = [];

            let start = Math.max(1, scope.currentPage - 2);
            let end = Math.min(scope.totalPages, start + 4);

            for (let i = start; i <= end; i++) {
               scope.pages.push(i);
            }

         }

         scope.changePage = function (page) {

            if (page >= 1 && page <= scope.totalPages) {
               scope.onPageChange({
                  page: page
               });
            }

         };

      }

   };

});


// app.directive("modernPagination",function(){

// return{

// scope:{
// currentPage:"=",
// totalPages:"=",
// totalRecords:"=",
// onPageChange:"&"
// },

// template:`

// <div class="pagination-container">

// <div>
// Total Records: {{totalRecords}}
// </div>

// <div class="pagination-buttons">

// <button ng-click="changePage(1)" ng-disabled="currentPage==1">
// First
// </button>

// <button ng-click="changePage(currentPage-1)" ng-disabled="currentPage==1">
// Prev
// </button>

// <button
// ng-repeat="p in pages"
// ng-click="changePage(p)"
// ng-class="{'active':p==currentPage}">
// {{p}}
// </button>

// <button ng-click="changePage(currentPage+1)" ng-disabled="currentPage==totalPages">
// Next
// </button>

// <button ng-click="changePage(totalPages)" ng-disabled="currentPage==totalPages">
// Last
// </button>

// </div>

// </div>
// `,

// link:function(scope){

// scope.pages=[];

// scope.$watchGroup(["currentPage","totalPages"],function(){
// generatePages();
// });

// function generatePages(){

// scope.pages=[];

// let start=Math.max(1,scope.currentPage-2);
// let end=Math.min(scope.totalPages,start+4);

// for(let i=start;i<=end;i++){
// scope.pages.push(i);
// }

// }

// scope.changePage=function(page){

// if(page>=1 && page<=scope.totalPages){
// scope.onPageChange({page:page});
// }

// };

// }

// };

// });
