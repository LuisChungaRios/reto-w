const mixinErrors =  {

    methods: {
        hasError(key, errors) {
            if (typeof errors === 'object') {
                return (Object.keys(errors)).includes(key)
            }
        },
        getFirstErrors(key, errors) {
            if (typeof errors === 'object' && Object.keys(errors).length > 0 && this.hasError(key,errors)) {
                return errors[key][0]
            }
        }
    }
}

export default mixinErrors;
