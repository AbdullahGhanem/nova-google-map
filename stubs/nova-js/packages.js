export const FormField = {
    props: ['resourceName', 'resourceId', 'field'],
    methods: {
        setInitialValue() {},
        fill(formData) {},
    },
    mounted() {
        this.setInitialValue()
    },
}

export const HandlesValidationErrors = {
    props: ['errors'],
    computed: {
        errorClasses() {
            return ''
        },
    },
}
