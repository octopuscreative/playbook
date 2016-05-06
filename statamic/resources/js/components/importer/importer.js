module.exports = {

    props: ['importer'],

    data: function() {
        return {
            json: null,
            importing: false,
            success: false
        }
    },

    methods: {
        upload: function () {
            this.importing = true;
            this.$http.post(cp_url('import/'+this.importer), { json: this.json }).success(function (response) {
                if (response.success) {
                    this.importing = false;
                    this.success = true;
                }
            });
        }
    },

    ready: function () {

    }
};
