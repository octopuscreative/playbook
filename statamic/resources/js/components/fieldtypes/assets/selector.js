module.exports = {

    template: require('./selector.template.html'),

    props: {
        show: {
            type: Boolean,
            default: false,
            twoWay: true
        },
        container: String,
        folder: String,
        selected: Array
    },

    data: function() {
        return {
            loading: true
        }
    },

    methods: {

        select: function() {
            this.$dispatch('assets.selected', this.selected);
            this.close();
        },

        close: function() {
            this.show = false;
        }

    },

    ready: function() {
        this.$on('asset-listing.loading-complete', function() {
            this.loading = false;
        });
    }

};
