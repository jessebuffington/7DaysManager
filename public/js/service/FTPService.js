'use strict';

sevenMonitor.factory('FTPService', function ($q, $http) {

    var isConnected = function () {
        return $http.get('/protected/ftp');
    };

    var connect = function () {
        return $http.post('/protected/ftp');
    };

    var disconnect = function () {
        return $http.delete('/protected/ftp');
    };
    return {
        isConnected: isConnected,
        connect: connect,
        disconnect: disconnect
    };
});