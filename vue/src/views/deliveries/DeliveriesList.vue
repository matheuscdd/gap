<template>
    <h1>Entregas</h1>
    <section>
        <ul v-if="$store.state.deliveryMod.deliveries.length">
            <iCard
                v-for="el in $store.state.deliveryMod.deliveries" 
                :key="el.id"
                :id="el.id"
                :client_name="$store.state.clientMod.clients.find(client => client.id === el.client).name"
                :provider_city="el.provider_city"
                :receipt_date="el.receipt_date"
                :delivery_address="el.delivery_address"
                :revenue="el.revenue"
                :delivery_date="el.delivery_date"
                :createdAt="el.created_at"
                :updatedAt="el.updated_at"
                @del="del"
                @edit="edit"
            />
        </ul>
    </section>
</template>
<script>
import iCard from "@/components/deliveries/iCard.vue";

export default {
    components: {
        iCard,
    },

    async beforeCreate() {
        window.scrollTo(0,0);
        await this.$store.dispatch("clientMod/storeClients");
        await this.$store.dispatch("deliveryMod/storeDeliveries");
    },
};
</script>
<style scoped>
h1 {
    text-align: center;
    margin-bottom: 30px;
    margin-top: 20px;
}

section {
    display: flex;
    justify-content: center;
}
</style>