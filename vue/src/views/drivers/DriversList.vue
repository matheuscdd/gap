<template>
    <h1>Motoristas</h1>
    <ul class="header">
        <div>Nome</div>
        <div>CPF</div>
        <div>Entregas realizadas</div>
        <div>Atualização</div>
        <div>Ações</div>
    </ul>
    <ul v-if="$store.state.driverMod.drivers.length">
        <iCard
            v-for="el in $store.state.driverMod.drivers" 
            :key="el.id"
            :id="el.id"
            :name="el.name"
            :CPF="formatCPF(el.CPF)"
            :deliveries="el.deliveries"
            :createdAt="el.created_at"
            :updatedAt="el.updated_at"
            @del="del"
            @edit="edit"
        />
    </ul>
</template>

<script>
import iCard from "@/components/drivers/iCard.vue";
import mixins from "@/common/mixins";
import { endpoints } from "@/common/consts";
import { formatCPF } from "@/common/utils";


export default {
    mixins: [mixins],
    beforeCreate() {
        window.scrollTo(0,0);
        this.$store.dispatch("driverMod/storeDrivers");
    },
    components: {
        iCard, 
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

.header {
    display: grid;
    grid-template-columns: 25% 25% 10% 20% 20%;
    grid-auto-rows: 30px;
    grid-gap: 10px;
    margin-bottom: 10px;
    text-align: center;
    padding: 0 20px;
}
</style>