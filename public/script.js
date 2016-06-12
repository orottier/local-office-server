var appState = {
    username: '',
    token: '',
    logged_in: false,
    log: [],
};

var App = Vue.extend({});

var router = new VueRouter();

router.map({
    '/': {
        component: RequestToken
    },
    '/enter': {
        component: EnterToken
    },
    '/dashboard': {
        component: Dashboard,
        auth: true
    },
    '/sonos': {
        component: Sonos,
    }
});

router.beforeEach(function (transition) {
    if (transition.to.auth) {
        if (!appState.logged_in) {
            transition.abort();
        }
        transition.next();
    } else {
        if (appState.logged_in) {
            transition.abort();
        }
        transition.next();
    }
});

Vue.http.interceptors.push({

    request: function (request) {
        if (DEBUG) {
            var info = request.method + ' ' + request.url;
            appState.log.unshift({type: '🔼', data: request, info: info});
        }
        return request;
    },

    response: function (response) {
        var info = response.request.method + ' ' + response.request.url + ': ' + response.status + ' ' + response.statusText;
        var show = true;
        if (!response.status) {
            info += ' request failed';
        } else if (response.status >= 400) {
            info += response.data.error.message;
        } else {
            show = DEBUG;
        }
        if (show) {
            appState.log.unshift({type: '🔽', data: response, info: info});
        }
        return response;
    }

});

router.start(App, '#app');
