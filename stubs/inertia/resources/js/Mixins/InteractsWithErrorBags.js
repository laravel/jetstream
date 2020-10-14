export default {
    methods: {
        hasErrors(bag) {
            return this.$page.props.errorBags[bag] && Object.keys(this.$page.props.errorBags[bag]).length > 0;
        },

        errorFor(key, bag = 'default') {
            if (!this.hasErrors(bag) || !this.$page.props.errorBags[bag][key] || this.$page.props.errorBags[bag][key].length == 0) {
                return;
            }

            return this.$page.props.errorBags[bag][key][0];
        },
    },
};
