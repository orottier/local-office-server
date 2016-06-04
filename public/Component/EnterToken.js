var EnterToken = Vue.extend({
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
                Vue.http.headers.common['X-Authorization'] = 'Bearer ' + this.appState.token;
                this.errorMsg = 'Authorization has been set, checking...';
                this.$http.get('/api/status')
                    .then(
                        function(result) {
                            if (result.data.loggedIn && result.data.loggedIn === this.appState.username) {
                                router.go('/dashboard');
                            } else {
                                this.errorMsg = 'Could not verify this token!';
                                document.getElementById('tokenInput').focus();
                            }
                        }
                    );
            } else {
                document.getElementById('tokenInput').focus();
            }
        }
    }
})

