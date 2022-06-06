var app = angular.module('myapp', [])

app.controller('mycontroller', function($scope, $http){ 
    $http.get('http://dev.sippd.payakumbuhkota.go.id:8080/index.php/apiopd/opd')
    .then(function(response){
        $scope.alldata = response.data;
    }, function(err){
        console.log(err)
    })
})

// var app = angular.module('myapp', ['ui.bootstrap'])

// app.controller('mycontroller', function($scope, $http, $modal, $log){ 
//     $http.get('http://dev.sippd.payakumbuhkota.go.id:8080/index.php/apiopd/opd')
//     .then(function(response){
//         $scope.alldata = response.data;
//     }, function(err){
//         console.log(err)
//     })
  
//     $scope.showForm = function () {
//     $scope.message = "Show Form Button Clicked";
//     console.log($scope.message);

//     var modalInstance = $modal.open({
//         templateUrl: "/Opd/opd_form",
//         controller: ModalInstanceCtrl,
//         scope: $scope,
//         resolve: {
//             userForm: function () {
//                 return $scope.userForm;
//             }
//         }
//     });

//     modalInstance.result.then(function (selectedItem) {
//             $scope.selected = selectedItem;
//         }, function () {
//             $log.info('Modal dismissed at: ' + new Date());
//         });
//     }
// })

// var ModalInstanceCtrl = function ($scope, $modalInstance, userForm) {
//     $scope.form = {}
//     $scope.submitForm = function () {
//         if ($scope.form.userForm.$valid) {
//             console.log('user form is in scope');
//             $modalInstance.close('closed');
//         } else {
//             console.log('userform is not in scope');
//         }
//     };

//     $scope.cancel = function () {
//         $modalInstance.dismiss('cancel');
//     };
// };