var Dashboard = Vue.extend({
    data: function () {
        return {
            'appState': appState,
        };
    },
    template: `<p>Dashboard &ndash; {{ appState.username }}</p>`,
    methods: {
    },
    ready: function () {
    }
})
