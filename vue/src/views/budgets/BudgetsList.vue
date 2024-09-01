<template>
    <h1>Orçamentos</h1>
    <ul v-if="$store.state.budgetMod.budgets.length">
        <iCard
            v-for="el in $store.state.budgetMod.budgets" 
            :key="el.id"
            :id="el.id"
            :client_name="$store.state.clientMod.clients.find(client => client.id === el.client).name"
            :provider_city="el.provider_city"
            :provider_name="el.provider_name"
            :delivery_address="el.delivery_address"
            :revenue="el.revenue"
            :delivery_date="el.delivery_date"
            :createdAt="el.created_at"
            :updatedAt="el.updated_at"
            @del="del"
            @edit="edit"
        />
    </ul>
</template>
<script>
import { sleep } from "@/common/utils";
import iCard from "@/components/budgets/iCard.vue";
import client from "@/store/client";



export default {
    async beforeCreate() {
        await this.$store.dispatch("clientMod/storeClients");
        await this.$store.dispatch("budgetMod/storeBudgets");
    },
    components: {
        iCard
    }
};
</script>
<style scoped>
ul {
    display: grid;
    grid-template-columns: repeat(3, 400px);
    grid-auto-rows: 360px;
    row-gap: 10px;
    column-gap: 10px;
}
</style>