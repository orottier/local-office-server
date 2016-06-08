var RequestToken = Vue.extend({
    data: function () {
        return {
            'appState': appState,
            'errorMsg': '',
            'cta': 'Submit',
            'working': false
        };
    },
    template: `
        <div>
            <p>Leave your Slack username to get access</p>
            <form v-on:submit.prevent="requestToken">
                <p v-if="errorMsg">{{ errorMsg }}</p>
                <input id='loginInput' v-model="appState.username">
                <button action='submit' :disabled="working">{{ cta }}</button>
            </form>
        </div>`,
    methods: {
        requestToken: function() {
            if (this.appState.username) {
                this.working = true;
                this.cta = 'Loading...';
                this.errorMsg = '';
                this.$http.post('/api/request-token',
                    {'username': this.appState.username}
                ).then(
                    function(result) {
                        if (result.data.status === 'success') {
                            router.go('/enter');
                        } else {
                            this.working = false;
                            this.cta = 'Submit';
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
