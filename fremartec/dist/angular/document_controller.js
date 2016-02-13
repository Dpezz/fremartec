angular.module('validate',[])
.config(function($interpolateProvider){
    $interpolateProvider.startSymbol('{[{').endSymbol('}]}')
})

.controller('form', function($scope,$http){

    /* get economic */
    $http.get('http://mindicador.cl/api')
    .success(function(data) {
        //console.log(data)
        $scope.indices = data
    })
    .error(function() {
        console.log('Error al consumir la API!');
    });

    /* get time *//*
    $http.get('http://api.meteored.cl/index.php?api_lang=cl&localidad=18213&affiliate_id=uke5a638dcvo')
    .success(function(data) {
        //console.log(data)
        //$scope.clima = data
    })
    .error(function() {
        console.log('Error al consumir la API!');
    });*/

    /* get categorys*/
    
    $http.get('/bundles/fremartec/dist/angular/category.json')
    .success(function(data){
        $scope.categorys = data.categorys
    })
    .error(function(error){
        console.log(error)
    });

    /* Documents */
    $scope.change = function(){
        $scope.posiciones = $scope.category.files
    }

    $scope.setId = function(id, title){
        $scope.id = id
        $scope.title = title
    }

    $scope.delete = function(){
        $http.post('../documents/'+$scope.id+'/delete')
        .success(function(result){
            //console.log(result)
            window.location = "";
        })
        .error(function(error){
            console.log(error)
            window.location = "";
        })
    }

    $scope.download = function(id){
        window.open("download/"+id)
    }

    $scope.save = function(){
        $http.post('../email/'+$scope.email+"/"+$scope.id)
        .success(function(res){
            //console.log(result)
            $scope.visible = true
            //download(res);
        })
        .error(function(error){
            window.location = "";
        })

        window.open("download/"+$scope.title+"/"+$scope.id);
    }

});