<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
              Team Phone Numbers
            </h2>
        </template>

        <div v-if="numbers.length > 0" class="container mx-auto my-12">
          <div class="flex flex-col">
            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
              <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                  <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Phone Number
                      </th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                      </th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        SMS/MMS Capable
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
                    <tr v-for="number in numbers">

                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                          <div>
                            <div class="text-sm font-medium text-gray-900">
                              {{ number.friendly_name }}
                            </div>
                          </div>
                        </div>
                      </td>

                      <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ number.status }}</div>
                      </td>

                      <td class="px-6 py-4 whitespace-nowrap flex space-x-2">
                        <template v-for="capability of [ 'sms', 'mms' ]">
                          <span class="text-sm flex text-center mr-3" v-if="number.capabilities[capability]">
                            {{ capability.toUpperCase() }}
                            <svg class="ml-1.5" width="20" height="20" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve"> <g> <g> <path d="M256,0C114.844,0,0,114.844,0,256s114.844,256,256,256s256-114.844,256-256S397.156,0,256,0z M402.207,182.625 L217.75,367.083c-4.167,4.167-9.625,6.25-15.083,6.25c-5.458,0-10.917-2.083-15.083-6.25L88.46,267.958 c-4.167-4.165-4.167-10.919,0-15.085l15.081-15.082c4.167-4.165,10.919-4.165,15.086,0l84.04,84.042L372.04,152.458 c4.167-4.165,10.919-4.165,15.086,0l15.081,15.082C406.374,171.706,406.374,178.46,402.207,182.625z"/> </g> </g> </svg>
                          </span>

                          <span v-else class="text-sm flex text-center mr-3">
                            {{ capability.toUpperCase() }}
                            <svg wclass="ml-1.5" width="20" height="20" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 51.976 51.976" style="enable-background:new 0 0 51.976 51.976;" xml:space="preserve"> <g> <path d="M44.373,7.603c-10.137-10.137-26.632-10.138-36.77,0c-10.138,10.138-10.137,26.632,0,36.77s26.632,10.138,36.77,0 C54.51,34.235,54.51,17.74,44.373,7.603z M36.241,36.241c-0.781,0.781-2.047,0.781-2.828,0l-7.425-7.425l-7.778,7.778 c-0.781,0.781-2.047,0.781-2.828,0c-0.781-0.781-0.781-2.047,0-2.828l7.778-7.778l-7.425-7.425c-0.781-0.781-0.781-2.048,0-2.828 c0.781-0.781,2.047-0.781,2.828,0l7.425,7.425l7.071-7.071c0.781-0.781,2.047-0.781,2.828,0c0.781,0.781,0.781,2.047,0,2.828 l-7.071,7.071l7.425,7.425C37.022,34.194,37.022,35.46,36.241,36.241z"/> </g> </svg>
                          </span>
                        </template>
                      </td>

                      <td class="px-6 whitespace-nowrap text-sm">
                        <secondary-button v-if="number.external || ! number.external && ! number.id" @click.prevent="$inertia.visit( route( 'phone-numbers.import-number', number.sid ) )">
                          Import
                        </secondary-button>

                        <span v-else>
                          <jet-button v-if="number.id" @click.prevent="$inertia.delete( route( 'phone-numbers.destroy', number.id ) )">
                            Remove
                          </jet-button>
                        </span>

<!--                        <secondary-button class="ml-3" @click.prevent="$inertia.visit( route( 'phone-numbers.release', number.sid ) )">-->
<!--                          Release-->
<!--                        </secondary-button>-->
                      </td>

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
                        <h1 class="text-5xl font-extrabold mb-4 text-grey-900">Oh no!</h1>
                        <p>You don't have any phone numbers in your Twilio account ready to import.</p>
                    </div>
                </div>
            </div>
        </div>
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
    name: "Import",

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

  props: [ 'numbers' ],

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
    }
}
</script>

<style scoped>

</style>
