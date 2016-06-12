var appState;
function resetAppState()
{
    appState = {
        username: '',
        token: '',
        logged_in: false
    };
    sessionStorage.setItem('appState', JSON.stringify(appState));
}

var debugLog = [];

var cookie = sessionStorage.getItem('appState');
if (cookie) {
    appState = JSON.parse(cookie);
} else {
    resetAppState();
}

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
            debugLog.unshift({type: '🔼', data: request, info: info});
        }
        return request;
    },

    response: function (response) {
        var info = response.request.method + ' ' + response.request.url + ': ' + response.status + ' ' + response.statusText;
        var show = true;
        if (!response.status) {
            info += ' request failed';
        } else if (response.status >= 400) {
            info += ', ' + response.data.error.message;
            if (response.status == 401) {
                resetAppState();
                sessionStorage.removeItem('appState');
                router.go('/');
            }
        } else {
            show = DEBUG;
        }
        if (show) {
            debugLog.unshift({type: '🔽', data: response, info: info});
        }
        return response;
    }

});

router.start(App, '#app');
if (appState.logged_in) {
    Vue.http.headers.common['X-Authorization'] = 'Bearer ' + appState.token;
    router.go('/dashboard');
}
