var myApp = angular.module('myApp', ['rzModule','ui.bootstrap','ngSanitize']);
//controller for q/a
myApp.controller('EngageController', ['$scope','$http','$location', '$rootScope', '$timeout', '$uibModal', function($scope, $http, $location, $rootScope, $timeout,$uibModal ) {
	$scope.answerA = {};
	$scope.selfgradeA = {};
	$scope.radioB = '';
	$scope.selfgrade = '';
    $scope.answertext = 'Type your answer here';
	$scope.prevQuest = '';
	$scope.savedAns = '';
	$scope.questioncount = 0;
	$scope.gradecount = 0;
	$scope.suggestAns= {};
	$scope.questions={};
	$scope.headings=['Emerging','Proficient','Mastered'];
	$scope.currentpage=$location.absUrl();
	$scope.totalquestions=0;
	$scope.finished = 0;
    var baseURL = window.location.protocol+"//"+window.location.host;
	//get the users answers if there are any and load the answer array
    var urltoget = baseURL+"/wp-content/themes/hueman-child/AngularAPI/processAngular.php?currentpage="+$scope.currentpage+"&action=getUserAnswers";
					 $http.get(urltoget)
					 .success(function (result) {
					//************************* load user answers and set finished variable***************************************
				     $scope.answerA=result.useranswerarray;
					 $scope.selfgradeA = result.userselfscore;
					 $scope.finished =result.finished;
						//get the questions from the database 
							var urltoget = baseURL+"/wp-content/themes/hueman-child/AngularAPI/processAngular.php?currentpage="+$scope.currentpage+"&action=getQuestions";
							  $http.get(urltoget)
								.success(function (result) {
									  $scope.totalquestions = result.totalquestions;
									  for (var i=1; i <= $scope.totalquestions; i++){
									  var questionvar = 'q'+i;
									  $scope.questions[i-1] =result[questionvar];
									 }
								  //INSIDE SUCCESS LOAD THE suggested ANSWERS Array ---this is the rubric
								   var urltoget = baseURL+"/wp-content/themes/hueman-child/AngularAPI/processAngular.php?currentpage="+$scope.currentpage+"&action=getAnswers&totalquestions="+$scope.totalquestions;
											 $http.get(urltoget)
											 .success(function (result) {
											 $scope.suggestAns=result.answerarray;
											})
								  
								   })
								.error(function (data, status) {
									  console.log("status" + status);
								});
	
						//set divs on screen
						$scope.answer = false;
						$scope.results = false;
						$scope.question = true;
						$scope.course_navigation=false;
						        //change settings if the user is finished
								if ($scope.finished > 0){
									 $scope.results = true;
									 $scope.answer = false;
									 $scope.question = false;
									 $scope.course_navigation=true;
								}
				
                    //save answer function ***************************************************************								
					 $scope.saveAnswer = function(){
								$scope.savedAns = $scope.answertext;
								$scope.answerA[$scope.questioncount] = $scope.savedAns;
								$scope.prevQuest = $scope.questions[$scope.questioncount];
								$scope.question = false;
								$scope.answer = true;
								$scope.questioncount = $scope.questioncount + 1;
							}
					//Show questions function ************************************************************
					$scope.showQuestion = function(){
								var numquestions = Object.keys($scope.questions).length;
								if($scope.questioncount < numquestions){
								$scope.selfgrade = $scope.radioB;
								console.log("This is" +$scope.selfgrade );
								$scope.selfgradeA[$scope.gradecount] = $scope.selfgrade;
								$scope.gradecount = $scope.gradecount + 1;
								$scope.answertext = 'Type your answer here.';
								$scope.question = true;
								$scope.answer = false;
								$scope.radioB = -1;	
								}
								else{
									$scope.selfgrade = $scope.radioB;
									$scope.selfgradeA[$scope.gradecount] = $scope.selfgrade;
									$scope.course_navigation=true;
									 //save answers now
									var urltoget = baseURL+"/wp-content/themes/hueman-child/AngularAPI/processAngular.php/";
									var parameters = {
										currentpage:$scope.currentpage,
										action:'saveAnswers',
										selfgrades: JSON.stringify($scope.selfgradeA),
										answers:JSON.stringify($scope.answerA),
										questions:JSON.stringify($scope.questions),
										numquestions:numquestions,
									};
									var config = {
										params: parameters
									};
									$http.get(urltoget,config)
											 .success(function (result) {
											console.log("Num inserted was " + result.numinserted + " end");
										    
											})
											.error(function (data, status) {
											 console.log("status" + status);
											});
								           $scope.answer = false;
									        $scope.results = true;
								
								}
							}
						//*********************************************end the rest of the functionality					 
					})//end success on getting users already saved answers 
		
                  .error(function (data, status) {
                   console.log("status" + status);
             });
}]);
