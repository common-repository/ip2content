export default {
    methods: {
        scrollToError(form) {
            const firstErrorInput = form.inputs.find(
                (item) =>
                    item.messagesToDisplay.length || item.errorMessages.length
            );

            if (firstErrorInput) {
                this.$vuetify.goTo(firstErrorInput.$el, {
                    offset: 150,
                });
            }
        },
    },
};
