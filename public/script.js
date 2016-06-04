var appState = {
    username: '',
    token: ''
}

var RequestToken = Vue.extend({
    data: function () {
        return {
            'appState': appState,
            'errorMsg': '',
        };
    },
    template: `<p>Leave your Slack username to get access</p>
        <form v-on:submit.prevent="requestToken">
            <p v-if="errorMsg">{{ errorMsg }}</p>
            <input id='loginInput' v-model="appState.username">
            <button action='submit'>Submit</button>
        </form>`,
    methods: {
        requestToken: function() {
            if (this.appState.username) {
                this.errorMsg = '';
                this.$http.post('/api/request-token',
                    {'username': this.appState.username}
                ).then(
                    function(result) {
                        if (result.data.status === 'success') {
                            router.go('/enter');
                        } else {
                            this.errorMsg = 'Something went wrong!';
                            document.getElementById('loginInput').focus();
                        }
                    }
                );
            } else {
                document.getElementById('loginInput').focus();
            }
        }
    },
    ready: function () {
        document.getElementById('loginInput').focus();
    }
})

var EnterApp = Vue.extend({
    data: function () {
        return {
            'appState': appState,
            'errorMsg': '',
        };
    },
    template: `<p>An access token has been sent to @{{ appState.username }} on Slack, please enter it here:</p>
        <form v-on:submit.prevent="enterToken">
            <p v-if="errorMsg">{{ errorMsg }}</p>
            <input id='tokenInput' v-model="appState.token">
            <button action='submit'>Submit</button>
        </form>
        <a v-link="{ path: '/' }">Try again</a>`,
    methods: {
        enterToken: function() {
            if (this.appState.token) {
                Vue.http.headers.common['Authorization'] = 'Bearer ' + this.appState.token;
                this.errorMsg = 'Authorization has been set';
            } else {
                document.getElementById('tokenInput').focus();
            }
        }
    }
})

var App = Vue.extend({});

var router = new VueRouter();

router.map({
    '/': {
        component: RequestToken
    },
    '/enter': {
        component: EnterApp
    }
})

router.start(App, '#app')
