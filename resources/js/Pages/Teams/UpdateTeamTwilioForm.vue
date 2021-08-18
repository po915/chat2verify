<template>
    <div>
        <jet-form-section @submitted="updateTeamName">
            <template #title>
                Team Twilio
            </template>

            <template #description>
                <p>A shared Twilio account for the members of your team.</p>
                <p class="mt-5">You can find your Twilio account ID and authentication token <a class="underline" href="www.twilio.com/console" target="_blank">here</a>.</p>
            </template>

            <template #form>
                <!-- Twilio Account ID -->
                <div class="col-span-6 sm:col-span-4">
                    <jet-label for="account_id" value="Twilio Account ID" />

                    <jet-input id="account_id"
                               type="text"
                               class="mt-1 block w-full"
                               v-model="form.twilio_account_id"
                               :disabled="! permissions.canUpdateTeam" />

                    <jet-input-error :message="form.errors.twilio_account_id" class="mt-2" />
                </div>

                <!-- Twilio Authentication Token -->
                <div class="col-span-6 sm:col-span-4">
                    <jet-label for="auth_token" value="Twilio Authentication Token" />

                    <jet-input id="auth_token"
                               type="text"
                               class="mt-1 block w-full"
                               v-model="form.twilio_auth_token"
                               :disabled="! permissions.canUpdateTeam" />

                    <jet-input-error :message="form.errors.twilio_auth_token" class="mt-2" />
                </div>
            </template>

            <template #actions v-if="permissions.canUpdateTeam">
                <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                    Saved.
                </jet-action-message>

                <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Save and check connection
                </jet-button>
            </template>
        </jet-form-section>
    </div>
</template>

<script>
    import JetActionMessage from '@/Jetstream/ActionMessage'
    import JetButton from '@/Jetstream/Button'
    import JetFormSection from '@/Jetstream/FormSection'
    import JetInput from '@/Jetstream/Input'
    import JetInputError from '@/Jetstream/InputError'
    import JetLabel from '@/Jetstream/Label'

    export default {
        components: {
            JetActionMessage,
            JetButton,
            JetFormSection,
            JetInput,
            JetInputError,
            JetLabel,
        },

        props: ['team', 'permissions'],

        data() {
            return {
                form: this.$inertia.form({
                    twilio_account_id: this.team.twilio_account_id,
                    twilio_auth_token: this.team.twilio_auth_token,
                })
            }
        },

        methods: {
            updateTeamName() {
                this.form.put(route('teams.update', this.team), {
                    errorBag: 'updateTeamTwilio',
                    preserveScroll: true
                });
            },
        },
    }
</script>
