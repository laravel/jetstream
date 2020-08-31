export default {
    methods: {
        hasErrors(bag) {
            return this.$page.errorBags[bag] && Object.keys(this.$page.errorBags[bag]).length > 0;
        },

        errorFor(key, bag = 'default') {
            if (!this.hasErrors(bag) || !this.$page.errorBags[bag][key] || this.$page.errorBags[bag][key].length == 0) {
                return;
            }

            return this.$page.errorBags[bag][key][0];
        },
    },
};
