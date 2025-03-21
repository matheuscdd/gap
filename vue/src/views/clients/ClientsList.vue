<template>
    <h1>Clientes</h1>
    <div v-if="!$store.state.loading && $store.state.clientMod.clients.length">
        <vTable
            height="60vh"
            fixed-header
        >
            <thead>
            <tr>
                <th>Nome</th>
                <th>CNPJ</th>
                <th>Endereço</th>
                <th>Celular</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody>
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
            </tbody>
        </vTable>
    </div>
    
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
h1 {
    text-align: center;
    margin-bottom: 30px;
    margin-top: 20px;
}
</style>