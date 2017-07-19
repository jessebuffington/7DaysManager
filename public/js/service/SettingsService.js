'use strict';

sevenMonitor.factory('SettingsService', function ($q, $http) {

    var readSettings = function () {
        return $http.get('/protected/settings');
    };
    var updateSettings = function (id, value) {
        return $http.put('/protected/settings', {id: id, value: value});
    };

    var readUsers = function () {
        return $http.get('/protected/users');
    };
    var updateUser = function (user) {
        return $http.put('/protected/users', user);
    };

    var readConnections = function () {
        return $http.get('/protected/settings/connections');
    };
    var updateConnection = function (connection) {
        return $http.put('/protected/settings/connections', connection);
    };

    var uptime = function (connection) {
        return $http.get('/protected/settings/uptime', connection);
    };

    var latestSteam = function () {
        return $http.get('/protected/server/news/steam');
    };

    var latestTumblr = function () {
        return $http.get('/protected/server/news/tumblr');
    };

    return {
        readSettings: readSettings,
        updateSettings: updateSettings,
        readUsers: readUsers,
        updateUser: updateUser,
        readConnections: readConnections,
        updateConnection: updateConnection,
        uptime: uptime,
        latestSteam: latestSteam,
        latestTumblr: latestTumblr
    };

});