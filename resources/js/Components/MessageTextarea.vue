<template>
    <div>
        <form method="post" @submit.prevent="send" class="flex flex-row items-center px-4">
            <div ref="textarea" class="flex-row items-center w-full border rounded-lg bg-white px-1">
                <div class="w-full">
                    <textarea v-if="tab === 'reply'" v-model="forms.message.message" rows="2" class="py-4 focus:outline-none focus:ring-0 focus:border-0 focus:border-transparent rounded-tr-lg rounded-tl-lg border border-transparent w-full focus:outline-none text-sm" placeholder="Type your message..."></textarea>
                    <textarea v-else-if="tab === 'note'" v-model="forms.note.note" rows="2" class="py-4 focus:outline-none focus:ring-0 focus:border-0 focus:border-transparent rounded-tr-lg rounded-tl-lg border border-transparent w-full focus:outline-none text-sm" placeholder="Add a note..."></textarea>
                </div>
                <div class="flex flex-row">
                    <!-- Attach Image -->
                    <!--            <input multiple ref="attachmentUpload" @change="assignFiles" type="file" hidden />-->
                    <!--            <button type="button" @click.prevent="$refs.attachmentUpload.click()" class="flex items-center justify-center h-10 w-8 text-gray-400 ml-1 mr-2">-->
                    <!--                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">-->
                    <!--                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>-->
                    <!--                </svg>-->
                    <!--            </button>-->
                </div>

                <div class="w-full flex justify-between border-t border-gray-200">
                    <div class="flex">
                        <a @click.prevent="tab = 'reply'" href="#" :class="{ 'text-blue-700 font-semibold border-blue-600': tab === 'reply', 'text-gray-600 font-normal border-gray-200': tab !== 'reply' }" class="inline-block border-t px-8 py-3 text-sm">
                            Reply
                        </a>

                        <a @click.prevent="tab = 'note'" href="#" :class="{ 'text-blue-700 font-semibold border-blue-600': tab === 'note', 'text-gray-600 font-normal border-gray-200': tab !== 'note' }" class="inline-block border-t px-8 py-3 text-sm">
                            Private Note
                        </a>
                    </div>

                    <div class="flex">
                        <input multiple ref="attachmentUpload" @change="assignFiles" type="file" hidden />
                        <button type="button" @click.prevent="$refs.attachmentUpload.click()" class="flex items-center justify-center h-10 w-8 text-gray-400 ml-1 mr-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <div class="ml-6">
                <button type="submit" class="flex items-center justify-center h-10 w-10 rounded bg-gray-200 hover:bg-gray-300 text-blue-800 text-white">
                    <svg class="w-5 h-5 transform rotate-90 -mr-px" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </div>

        </form>

        <span class="block px-4 pt-4 pb-6 text-sm text-gray-400">Hint: type / to access canned responses.</span>

        <div id="suggestion-tooltip" :class="{ 'show': show_suggestions && focused }" class="shadow rounded bg-white max-h-72 overflow-y-auto" role="tooltip" ref="suggestions">
            <div @click.prevent="forms.message.message = result.message" v-for="result in results" class="cursor-pointer hover:bg-gray-800 hover:text-white border-t border-gray-200 px-4">
                <div class="flex py-3 px-4 items-center">
                    <div class="w-1/5 pr-4">
                        <span class="block text-sm font-bold">/ {{ result.shortcode }}</span>
                    </div>
                    <div class="w-1/2 px-4">
                        <span class="block">{{ result.friendly_name }}</span>
                        <span class="block text-sm opacity-75">{{ result.message }}</span>
                    </div>
                    <div class="w-1/4 pl-4 flex space-x-2">
                        <svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="folders" class="svg-inline--fa fa-folders w-4 h-4" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><path fill="currentColor" d="M592 64H400L345.37 9.37c-6-6-14.14-9.37-22.63-9.37H176c-26.51 0-48 21.49-48 48v80H48c-26.51 0-48 21.49-48 48v288c0 26.51 21.49 48 48 48h416c26.51 0 48-21.49 48-48v-80h80c26.51 0 48-21.49 48-48V112c0-26.51-21.49-48-48-48zM480 464c0 8.84-7.16 16-16 16H48c-8.84 0-16-7.16-16-16V176c0-8.84 7.16-16 16-16h80v176c0 26.51 21.49 48 48 48h304v80zm128-128c0 8.84-7.16 16-16 16H176c-8.84 0-16-7.16-16-16V48c0-8.84 7.16-16 16-16h146.74l54.63 54.63c6 6 14.14 9.37 22.63 9.37h192c8.84 0 16 7.16 16 16v224z"></path></svg>
                        <span v-if="result.publicized_at" class="block text-sm">Public templates</span>
                        <span v-else class="block text-sm">My canned responses</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import { createPopper } from '@popperjs/core';
    import { debounce } from "debounce";
    import axios from 'axios';

    export default {
        props: ['conversation'],

        data() {
            return {
                popper: null,
                results: [],
                tab: 'reply',
                focused: true,

                forms: {
                    message: this.$inertia.form({
                        message: null,
                        attachment: [],
                    }),
                    note: this.$inertia.form({
                        note: null,
                    }),
                },
            };
        },

        computed: {
            show_suggestions: function() {
                const show = this.forms.message.message && this.forms.message.message.charAt(0) === '/';

                if ( show ) {
                    this.popper.update();

                    this.search( this.forms.message.message.substring(1) );
                }

                return show;
            },
        },

        mounted() {
            const button = this.$refs.textarea;
            const tooltip = this.$refs.suggestions;

            const popper = createPopper(button, tooltip, {
                placement: 'top',
                modifiers: [
                    {
                        name: "sameWidth",
                        enabled: true,
                        fn: ({ state }) => {
                            state.styles.popper.width = `${state.rects.reference.width}px`;
                        },
                        phase: "beforeWrite",
                        requires: ["computeStyles"],
                    }
                ]
            });

            this.popper = popper;
        },

        methods: {
            focus() {
                this.$refs.input.focus()
            },

            send() {
                if ( this.tab === 'reply' ) {
                    // send message
                    this.forms.message.post( route( 'conversations.messages.store', this.conversation.id ), {
                        onSuccess: () => {
                            this.forms.message.reset();
                        }
                    } )
                } else {
                    // submit note
                    this.forms.note.post( route( 'conversations.notes.store', this.conversation.id ), {
                        onSuccess: () => {
                            this.forms.note.reset();
                        }
                    } );
                }
            },

            assignFiles() {
                this.forms.message.attachment = this.$refs.attachmentUpload.files;
            },

            search: debounce(function( term ) {
                axios.get( this.route( 'responses.search', {
                    search_term: term,
                } ) ).then(({data}) => {
                    this.results = data.phrases;
                });
            }, 200),
        },
    }
</script>

