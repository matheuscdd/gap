<template>
    <h1>Clientes</h1>
    <ul class="header">
        <div>Nome</div>
        <div>CNPJ</div>
        <div>Endereço</div>
        <div>Celular</div>
        <div>Ações</div>
    </ul>
    <ul v-if="!$store.state.loading && $store.state.clientMod.clients.length">
        <iCard
            v-for="el in $store.state.clientMod.clients" 
            :key="el.id"
            :id="el.id"
            :name="el.name"
            :CNPJ="formatCNPJ(el.CNPJ)"
            :CEP="formatCEP(el.CEP)"
            :address="el.address"
            :cellphone="formatCellphone(el.cellphone)"
            :createdAt="el.created_at"
            @edit="edit"
            @del="del"
        />
    </ul>
    <iLoading v-show="$store.state.loading"/>
    <iNoResults v-show="!$store.state.loading && !$store.state.clientMod.clients.length"/>
</template>

<script>
import iCard from "@/components/clients/iCard.vue";
import mixins from "@/common/mixins";
import { endpoints } from "@/common/consts";
import { formatCEP, formatCNPJ, formatCellphone, formatField } from "@/common/utils";
import iNoResults from "@/components/common/iNoResults.vue";
import iLoading from "@/components/common/iLoading.vue";

export default {
    mixins: [mixins],
    async beforeCreate() {
        window.scrollTo(0,0);
        this.$store.commit("setStartLoading");
        await this.$store.dispatch("clientMod/storeClients");
        this.$store.commit("setStopLoading");
    },
    components: {
        iCard, 
        iNoResults,
        iLoading,
    },

    methods: {
        async del(id) {
            const continues = await this.$store.state.iChoice.open("Tem certeza que deseja tentar excluir esse cliente? Caso ele já esteja sendo utilizado no sistema não será possível");
            if (!continues) return;
            this.$store.dispatch("clientMod/delClient", id);
        },
        edit(id) {
            this.$router.push(endpoints.routes.CLIENT_EDIT.replace(":id", id));
        },
        formatCNPJ,
        formatCEP,
        formatCellphone,
        formatField,
    },
    
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

h1 {
    text-align: center;
    margin-bottom: 30px;
    margin-top: 20px;
}

ul {
    max-width: 100vw;
    overflow-x: hidden;
}
</style>