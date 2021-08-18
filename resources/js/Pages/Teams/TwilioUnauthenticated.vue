<template>
    <app-layout>
        <div class="h-full">
            <div class="h-full max-w-7xl mx-auto py-10 sm:px-6 lg:px-8  flex justify-center items-center">
                <div class="flex mx-auto my-auto">
                    <img class="max-w-sm" src="/img/twilio-unauthenticated.png" />
                    <div class="px-16 py-20">
                        <h1 class="text-5xl font-extrabold mb-4 text-grey-900">Oops!</h1>
                        <p>It looks as though you have attempted an action that requires an active Twilio account. Please review your {{ !user.current_team.personal_team ? "team's" : '' }} Twilio account settings.</p>

                        <div class="mt-12 flex space-x-2">
                            <inertia-link :href="route('dashboard')" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">Go Home</inertia-link>
                            <inertia-link :href="route('teams.show', $page.props.user.current_team_id)" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150">Manage Twilio Settings</inertia-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import TeamMemberManager from './TeamMemberManager'
    import AppLayout from '@/Layouts/AppLayout'
    import DeleteTeamForm from './DeleteTeamForm'
    import JetSectionBorder from '@/Jetstream/SectionBorder'
    import UpdateTeamNameForm from './UpdateTeamNameForm'
    import UpdateTeamTwilioForm from "@/Pages/Teams/UpdateTeamTwilioForm";
    import ApplicationMark from "@/Jetstream/ApplicationMark";
    import SecondaryButton from "@/Jetstream/SecondaryButton";

    export default {
        components: {
            SecondaryButton,
            ApplicationMark,
            UpdateTeamTwilioForm,
            AppLayout,
            DeleteTeamForm,
            JetSectionBorder,
            TeamMemberManager,
            UpdateTeamNameForm,
        },

        props: [ 'user' ],
    }
</script>
