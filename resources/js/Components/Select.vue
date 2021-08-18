<template>
    <multiselect ref="input" v-bind="$attrs"></multiselect>
</template>

<script>
    import Multiselect from '@vueform/multiselect';

    export default {
        components: { Multiselect },

        methods: {
            focus() {
                this.$refs.input.focus()
            }
        }
    }
</script>

