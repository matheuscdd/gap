<template>
    <h1>Motoristas</h1>
    <ul v-if="!$store.state.loading && $store.state.driverMod.drivers.length">
        <iCard
            v-for="el in $store.state.driverMod.drivers" 
            :key="el.id"
            :id="el.id"
            :name="el.name"
            :photo="el.photo"
            :CPF="formatCPF(el.CPF)"
            :deliveries="el.deliveries"
            :createdAt="el.created_at"
            :updatedAt="el.updated_at"
            @del="del"
            @edit="edit"
        />
    </ul>
    <iNoResults v-show="!$store.state.loading && !$store.state.driverMod.drivers.length"/>
    <iLoading v-show="$store.state.loading"/>
</template>

<script>
import iCard from "@/components/drivers/iCard.vue";
import mixins from "@/common/mixins";
import { endpoints } from "@/common/consts";
import { formatCPF } from "@/common/utils";
import iNoResults from "@/components/common/iNoResults.vue";
import iLoading from "@/components/common/iLoading.vue";

export default {
    mixins: [mixins],
    async beforeCreate() {
        window.scrollTo(0,0);
        this.$store.commit("setStartLoading");
        await this.$store.dispatch("driverMod/storeDrivers");
        this.$store.commit("setStopLoading");
    },
    components: {
        iCard,
        iNoResults,
        iLoading,
    },
    methods: {
        async del(id) {
            const continues = await this.$store.state.iChoice.open("Tem certeza que deseja excluir?");
            if (!continues) return;
            this.$store.dispatch("driverMod/delDriver", id);
        },

        edit(id) {
            this.$router.push(endpoints.routes.DRIVER_EDIT.replace(":id", id));
        },
        formatCPF,
    },
};
</script>
<style scoped>
h1 {
    text-align: center;
    margin-bottom: 30px;
    margin-top: 20px;
}

ul {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
}
</style>