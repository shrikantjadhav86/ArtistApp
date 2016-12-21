angular.module('starter.blogControllers', [])

        .controller('BlogCtrl', function ($scope, $rootScope, $ionicModal, $http, $timeout, $window) {


            $scope.addBlog = function () {
                console.log('Add Blog', $rootScope.currentBlogData);
                var authToken = $window.sessionStorage.getItem("auth_token");
                var userId = $window.sessionStorage.getItem("user_id");
                $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
                $http({
                    method: "post",
                    url: $window.baseUrl + "/blog",
                    headers: "{ 'Content-Type': 'application/x-www-form-urlencoded' }",
                    data: "auth_token=" + authToken + "&user_id=" + userId + "&blog_title=" + $rootScope.currentBlogData.blog_title + "&blog_description=" + $rootScope.currentBlogData.blog_description

                }).success(function (data) {
                    $rootScope.currentBlogData = data;
                    console.log('Blog Data', $rootScope.currentBlogData);
                    $scope.getBlog();
                    $window.location.href = '#/app/blogs';
                }).error(function (data) {
                    alert(data);
                });
            };
            
            // Delete Blog and reload events
            $scope.deleteBlog = function (id) {
                console.log('Delete Blog', $rootScope.currentBlogData);
                console.log('Add Blog', $rootScope.currentBlogData);
                var authToken = $window.sessionStorage.getItem("auth_token");
                var userId = $window.sessionStorage.getItem("user_id");
                $http({
                    method: "get",
                    url: $window.baseUrl + "/blog/delete/" + id + "?auth_token=" + authToken + "&user_id=" + userId

                }).success(function (data) {
                    $rootScope.currentBlogData = data;
                    console.log('Blog Data1', $rootScope.currentBlogData);
                    //$scope.getBlog();
                    $window.location.href = '#/app/blogs';
                }).error(function (data) {

                    console.log("error message = " + data);
                });
            };
            
            //Show edit page for Blog
            $scope.showEdit = function (Blog) {
                $rootScope.currentBlogData = Blog;
                console.log('Show Edit', $rootScope.currentBlogData);
            };

            $scope.viewBlog = function (Blog) {
                $rootScope.currentBlogData = Blog;
                console.log('View Blog', $rootScope.currentBlogData);
            };

            /* Edit Blog Data */
            $scope.editBlog = function (id) {
                console.log('Add Blog', $rootScope.currentBlogData);
                var authToken = $window.sessionStorage.getItem("auth_token");
                var userId = $window.sessionStorage.getItem("user_id");
                $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";
                $http({
                    method: "post",
                    url: $window.baseUrl + "/blog/edit/" + id,
                    headers: "{ 'Content-Type': 'application/x-www-form-urlencoded' }",
                    data: "auth_token=" + authToken + "&user_id=" + userId + "&blog_title=" + $rootScope.currentBlogData.blog_title + "&blog_description=" + $rootScope.currentBlogData.blog_description
                }).success(function (data) {
                    $rootScope.currentBlogData = data;
                    console.log('Blog Data', $rootScope.currentBlogData);
                    $scope.getBlog();
                    $window.location.href = '#/app/blogs';
                }).error(function (data) {
                    alert(data);
                    console.log("error message = " + data);
                });
                console.log('Edit Blog', $scope.currentBlogData);

            };

        })

