var EnterToken = Vue.extend({
    data: function () {
        return {
            'appState': appState,
            'errorMsg': '',
            'cta': 'Submit',
            'working': false,
        };
    },
    template: `
        <div>
            <p>An access token has been sent to @{{ appState.username }} on Slack, please enter it here:</p>
            <form v-on:submit.prevent="enterToken">
                <p v-if="errorMsg">{{ errorMsg }}</p>
                <input id='tokenInput' v-model="appState.token">
                <button action='submit' :disabled="working">{{ cta }}</button>
            </form>
            <a v-link="{ path: '/' }">Try again</a>
        </div>`,
    methods: {
        lockForm: function() {
            this.working = true;
            this.cta = 'Loading...';
        },
        freeForm: function(errorMsg) {
            this.working = false;
            this.cta = 'Submit';
            this.errorMsg = errorMsg;
            document.getElementById('tokenInput').focus();
        },
        enterToken: function() {
            if (this.appState.token) {
                this.lockForm();
                Vue.http.headers.common['X-Authorization'] = 'Bearer ' + this.appState.token;
                this.errorMsg = 'Authorization has been set, checking...';
                this.$http.get('/api/status')
                    .then(
                        function(result) {
                            if (result.data.logged_in && result.data.logged_in === this.appState.username) {
                                this.appState.logged_in = true;
                                sessionStorage.setItem('appState', JSON.stringify(appState));
                                router.go('/dashboard');
                            } else {
                                this.freeForm('Could not verify this token!');
                            }
                        }, function (error) {
                            this.freeForm('Something went wrong!');
                        }
                    );
            } else {
                this.freeForm();
            }
        }
    },
    ready: function () {
        this.freeForm();
    }
})
