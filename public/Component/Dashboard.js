var Dashboard = Vue.extend({
    data: function () {
        return {
            'appState': appState,
            'userData': {}
        };
    },
    template: `<p>Dashboard &ndash; {{ appState.username }}</p>
        <p>Your MAC addresses for identification:</p>
        Add new: <input v-model="newMacAddress" v-on:keyup.enter="addMacAddress">
        <ul>
            <li v-for="mac in userData.mac_addresses">
                <span>{{ mac }}</span>
                <button v-on:click="removeAddress($index)">X</button>
            </li>
        </ul>
        `,
    methods: {
        addMacAddress: function () {
            var text = this.newMacAddress.trim();
            if (text) {
                this.userData.mac_addresses.push(text);
                this.newMacAddress = '';
                this.syncAddresses();
            }
        },
        removeAddress: function (index) {
            this.userData.mac_addresses.splice(index, 1);
            this.syncAddresses();
        },
        syncAddresses: function () {
            this.$http.post('/api/me/addresses', {addresses: this.userData.mac_addresses}).then(
                function (result) {}
            );
        }
    },
    ready: function () {
        this.$http.get('/api/me').then(
            function(result) {
                console.log(result);
                this.userData = result.data;
            }
        );
    }
})
