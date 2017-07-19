'use strict';

sevenMonitor.factory('CometService', function ($q, $http, $rootScope, $timeout) {
    $rootScope.lastMessage = 0;
    $rootScope.polling = false;
    var cometPoll = function () {

        $http({method: 'GET', url: '/protected/comet/' + $rootScope.lastMessage})
            .success(function (data, status, headers, config) {
                for (var i in data) {
                    $rootScope.lastMessage = data[i].index;
                    //console.log((new Date()) +  ' received ' + data[i].messageTarget +  'comet message(' + $rootScope.lastMessage + ') ' + JSON.stringify(data[i].data) );
                    $rootScope.$broadcast(data[i].messageTarget, data[i]);
                }
                $timeout(function () {
                    cometPoll();
                }, 1000);
            })
            .error(function (data, status) {
                if (typeof $rootScope.authorized != 'undefined') {
                    $rootScope.$emit('status_error', 'CometService error(' + status + '): ' + data);
                    $rootScope.authorized = undefined;
                }
                $timeout(function () {
                    cometPoll();
                }, 10000);
            });
    };
    var start = function () {
        if (!$rootScope.polling) {
            $rootScope.polling = true;
            cometPoll();
        }
    };

    return {
        start: start
    }
});