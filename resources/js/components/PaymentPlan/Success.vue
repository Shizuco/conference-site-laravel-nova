<template>
    <v-app>
        <v-row class="shrink">
            <auth style="height: 80px; width: 800px"></auth>
        </v-row>
    </v-app>
</template>

<script>

export default {
    data: () => ({
        plans: []
    }),
    mounted() {
        this.$store.dispatch('ajaxSuccess', this.$route.query.session_id)
    }
}
</script>