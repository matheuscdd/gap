<template>
    <h1>Clientes</h1>
    <ul class="header">
        <div>Nome</div>
        <div>CNPJ</div>
        <div>Endereço</div>
        <div>Celular</div>
        <div>Ações</div>
    </ul>
    <ul v-if="$store.state.clientMod.clients.length">
        <iCard
            v-for="el in $store.state.clientMod.clients" 
            :key="el.id"
            :id="el.id"
            :name="el.name"
            :CNPJ="el.CNPJ"
            :CEP="el.CEP"
            :address="el.address"
            :cellphone="el.cellphone"
            @edit="edit"
        />
    </ul>
</template>

<script>
import iCard from "@/components/clients/iCard.vue";
import mixins from "@/common/mixins";
import { endpoints } from "@/common/consts";


export default {
    mixins: [mixins],
    beforeCreate() {
        this.$store.dispatch("clientMod/storeClients");
    },
    components: {
        iCard, 
    },

    methods: {
        edit(id) {
            this.$router.push(endpoints.routes.CLIENT_EDIT.replace(":id", id));
        }
    }
};
</script>
<style scoped>
.header {
    display: grid;
    grid-template-columns: 25% 25% 25% 15% 10%;
    grid-auto-rows: 30px;
    grid-gap: 10px;
    margin-bottom: 10px;
    text-align: center;
    padding: 0 20px;
}

ul {
    max-width: 100vw;
    overflow-x: hidden;
}
</style>