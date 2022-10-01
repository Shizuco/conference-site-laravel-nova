<template>
    <v-app>
        <v-row class="shrink">
            <auth style="height: 80px; width: 800px"></auth>
        </v-row>
        <v-row justify="center" style="width: 700px !important; margin-left: 400px !important">
            <v-row justify="left">
                <v-col v-for="(plan, index) in plans" :value="plan.id" :key="plan.id">
                    <v-card width="250px" elevation="3">
                        <v-card-title>
                            <h2>{{plan.name}}</h2>
                        </v-card-title>
                        <v-card-subtitle>
                            ${{plan.cost}}.00/month
                        </v-card-subtitle>
                        <v-card-text>
                            <h3>Decription: </h3>{{plan.description}}
                        </v-card-text>
                        <v-card-actions>
                            <v-btn>
                                <router-link class="white--text" style="text-decoration: none; color: inherit;"
                                    :to="{ name: 'PlanPayPage', params: {plan: plan.name} }">
                                    Update
                                </router-link>
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>
        </v-row>
    </v-app>
</template>

<script>

export default {
    data: () => ({
        plans: []
    }),
    mounted() {
        this.$store.dispatch('ajaxGetPlans').then(() => {
            this.plans = this.$store.getters.getPlans
        })
    }
}
</script>