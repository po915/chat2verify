<template>
    <div>
<!--        <jet-nav-link :href="route('dashboard')" :active="route().current('dashboard')">
            Dashboard
        </jet-nav-link>-->

        <jet-nav-link :href="route('conversations.index')" :active="route().current('conversations.index')">
            Conversations
        </jet-nav-link>

        <jet-nav-link :href="route('responses.index')" :active="route().current('responses.index')">
            Canned Responses
        </jet-nav-link>
    </div>
</template>

<script>
    import JetNavLink from '@/Jetstream/NavLink';

    export default {
        name: "Navigation",

        components: {
            JetNavLink,
        },
    }
</script>

<style scoped>

</style>
