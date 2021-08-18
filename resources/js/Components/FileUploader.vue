<template>
    <form ref="form" method="post" enctype="application/x-www-form-urlencoded" :action="url">
        <div v-bind="getRootProps()">
            <input v-bind="getInputProps()" >
            <p v-if="isDragActive">Drop the files here ...</p>
            <p v-else>Drag 'n' drop some files here, or click to select files</p>
        </div>
        <button @click="open">open</button>
        <button type="submit">Upload</button>
    </form>
</template>

<script>
import { useDropzone } from 'vue3-dropzone';
import { useForm } from '@inertiajs/inertia-vue3';

export default {
    name: 'FileUploader',

    props: {
        url: {
            type: String,
        }
    },

    methods: {
        submit() {

        }
    },

    setup() {
        function onDrop(acceptFiles, rejectReasons) {
            console.log(acceptFiles)
            console.log(rejectReasons)
        }

        const { getRootProps, getInputProps, ...rest } = useDropzone({ onDrop });

        const form = useForm({
            email: null,
        })

        return {
            form,
            getRootProps,
            getInputProps,
            ...rest
        }
    }
}
</script>

<style scoped>

</style>
