var Sonos = Vue.extend({
    template: `
        <div>
            <p>Welcome to the Kantoortuin SONOS API!</p>
            Add new: <input v-model="spotifySong" v-on:keyup.enter="searchSong">
            <table class="table">
                <tr class="search-result-item" v-for="track in spotifyData.tracks.items">
                  <td>
                  <span v-for="artist in track.artists">
                    {{ artist.name }}
                    </span>
                  </td>
                  </div>
                  <td>{{ track.name }}</td>
                  <td><span v-on:click="playSong(track.uri)"><button class="icon-button">Play next</button></span></div>
                </tr>
                </table>
        </div>`,
        methods: {
            searchSong: function () {
              this.$http.get('https://api.spotify.com/v1/search?q=' + this.spotifySong + '&type=track', function(tracks)
                {
                    this.spotifyData = tracks;
                });
            },
            playSong: function (song) {

              var url = 'http://192.168.1.28:5005/Marketing/spotify/next/:arg';
              var arg = {arg: song};
              Vue.http.jsonp(url, arg, function (){ });
            },
        },
    ready: function () {

    }
})
