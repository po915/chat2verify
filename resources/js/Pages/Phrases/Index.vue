<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Responses
            </h2>
        </template>

        <div v-if="phrases.length > 0" class="container mx-auto my-12">
            <div class="flex justify-between">
                <div class="py-2 flex space-x-5">
                    <inertia-link :href="route('responses.index', { filter: 'all' })" class="block text-blue-400" href="#">
                        All
                    </inertia-link>

                    <inertia-link :href="route('responses.index', { filter: 'team' })" class="block text-blue-400" href="#">
                        My Team
                    </inertia-link>

                    <inertia-link :href="route('responses.index', { filter: 'public' })" class="block text-blue-400" href="#">
                        Public
                    </inertia-link>
                </div>

                <jet-button class="mb-4" @click.prevent="create.showModal = true">Create response</jet-button>
            </div>


            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Name
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Title
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Category
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Action
                                    </th>
<!--                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>-->
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="phrase in phrases">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ phrase.friendly_name }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ trim( phrase.message, 60 ) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap flex space-x-2">
                                        <svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="folders" class="svg-inline--fa fa-folders w-4 h-4" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path fill="currentColor" d="M592 64H400L345.37 9.37c-6-6-14.14-9.37-22.63-9.37H176c-26.51 0-48 21.49-48 48v80H48c-26.51 0-48 21.49-48 48v288c0 26.51 21.49 48 48 48h416c26.51 0 48-21.49 48-48v-80h80c26.51 0 48-21.49 48-48V112c0-26.51-21.49-48-48-48zM480 464c0 8.84-7.16 16-16 16H48c-8.84 0-16-7.16-16-16V176c0-8.84 7.16-16 16-16h80v176c0 26.51 21.49 48 48 48h304v80zm128-128c0 8.84-7.16 16-16 16H176c-8.84 0-16-7.16-16-16V48c0-8.84 7.16-16 16-16h146.74l54.63 54.63c6 6 14.14 9.37 22.63 9.37h192c8.84 0 16 7.16 16 16v224z"></path></svg>
                                        <span v-if="phrase.publicized_at" class="block text-sm">Public templates</span>
                                        <span v-else class="block text-sm">My canned responses</span>
                                    </td>
                                    <td  class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <secondary-button @click.prevent="copyPhrase(phrase)" v-if="phrase.publicized_at">
                                            Copy & Edit
                                        </secondary-button>

                                        <secondary-button v-else>
                                            Edit
                                        </secondary-button>
                                    </td>
<!--                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <secondary-button>
                                            Edit
                                        </secondary-button>
                                    </td>-->
                                </tr>

                                <!-- More items... -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="h-full">
            <div class="h-full max-w-7xl mx-auto py-10 sm:px-6 lg:px-8  flex justify-center items-center">
                <div class="flex mx-auto my-auto">
                    <img class="max-w-sm" src="/img/no-phrases.png" />
                    <div class="px-16 py-20">
                        <h1 class="text-5xl font-extrabold mb-4 text-grey-900">Oops!</h1>
                        <p>You haven't yet created any responses.</p>

                        <div class="mt-12 flex space-x-2">
                            <button @click.prevent="create.showModal = true" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">Create response</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <dialog-modal max-width="xl" :closeable="true" @close="create.showModal = false" :show="create.showModal">
            <template #title>Create response</template>

            <template #content>
                <form method="post" @submit.prevent="createResponse" class="pt-3 pb-4">
                    <div class="col-span-6 sm:col-span-4">
                        <jet-label for="shortcode" value="Shortcode" />
                        <jet-input id="shortcode" type="text" class="mt-1 block w-full" v-model="create.form.shortcode" />
                        <jet-input-error :message="create.form.errors.shortcode" class="mt-2" />
                    </div>

                    <div class="col-span-6 sm:col-span-4 mt-3">
                        <jet-label for="friendly_name" value="Friendly Name" />
                        <jet-input id="friendly_name" type="text" class="mt-1 block w-full" v-model="create.form.friendly_name" />
                        <jet-input-error :message="create.form.errors.friendly_name" class="mt-2" />
                    </div>

                    <div class="col-span-6 sm:col-span-4 mt-3">
                        <jet-label for="message" value="Message" />
                        <jet-textarea id="message" type="text" class="mt-1 block w-full" v-model="create.form.message" />
                        <jet-input-error :message="create.form.errors.message" class="mt-2" />
                    </div>
                </form>
            </template>

            <template #footer>
                <secondary-button @click.prevent="create.showModal = false">Cancel</secondary-button>
                <jet-button @click.prevent="createResponse" class="ml-4">
                    Create
                </jet-button>
            </template>
        </dialog-modal>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout';
import DialogModal from "@/Jetstream/DialogModal";
import JetButton from '@/Jetstream/Button';
import SecondaryButton from "@/Jetstream/SecondaryButton";
import JetLabel from '@/Jetstream/Label';
import JetInput from '@/Jetstream/Input';
import JetInputError from '@/Jetstream/InputError';
import JetTextarea from '@/Components/Textarea';

export default {
    name: "Index",

    components: {
        AppLayout,
        JetButton,
        DialogModal,
        SecondaryButton,
        JetLabel,
        JetInput,
        JetInputError,
        JetTextarea,
    },

    props: [ 'phrases' ],

    data() {
        return {
            create: {
                showModal: false,
                form: this.$inertia.form({
                    friendly_name: '',
                    shortcode: '',
                    message: '',
                })
            }
        };
    },

    methods: {
        createResponse() {
            this.create.form.post( route( 'responses.store' ), {
                onSuccess: () => {
                    this.create.showModal = false;
                    this.create.form.reset();
                }
            } );
        },

        copyPhrase(phrase) {
            this.create.form.friendly_name = phrase.friendly_name;
            this.create.form.shortcode = phrase.shortcode;
            this.create.form.message = phrase.message;
            this.create.showModal = true;
        },

        trim: function (string, length) {
            return string.length > length ? string.substring(0, length) + "..." : string;
        }
    }
}
</script>

<style scoped>

</style>
