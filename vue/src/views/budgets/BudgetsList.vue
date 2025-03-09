<template>
    <h1>Orçamentos</h1>
    <div class="filters">
        <div class="container-id">
            <iSearch
                name="id"
                icon="file-contract-solid"
                label="Id"
                :errors="[]"
                v-model="id"
                :opts="idsOpts"
            />
        </div>
        <div class="container-client">
            <iSearch
                :name="budgetForm.CLIENT.NAME"
                :icon="budgetForm.CLIENT.ICON"
                :label="budgetForm.CLIENT.LABEL"
                :errors="[]"
                v-model="client"
                :opts="clientsOpts"
            />
        </div>
    </div>
    <section>
        <ul 
            v-if="!$store.state.loading && $store.state.budgetMod.budgets.length" 
            v-show="filter().length"
        >
            <iCard
                v-for="el in filter()" 
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
                @view="view"
            />
        </ul>
        <iLoading v-show="$store.state.loading"/>
        <iNoResults v-show="!$store.state.loading && !filter().length"/>
    </section>
</template>
<script>
import iCard from "@/components/budgets/iCard.vue";
import { endpoints } from "@/common/consts";
import mixins from "@/common/mixins";
import iSearch from "@/components/common/iSearch.vue";
import { itemgetter } from "@/common/utils";
import iNoResults from "@/components/common/iNoResults.vue";
import iLoading from "@/components/common/iLoading.vue";

export default {
    data: () => ({
        client: "",
        id: "",
        idsOpts: [],
        clientsOpts: [],
    }),
    mixins: [mixins],
    async beforeCreate() {
        window.scrollTo(0,0);
        this.$store.commit("setStartLoading");
        await Promise.all([
            this.$store.dispatch("clientMod/storeClients"),
            this.$store.dispatch("budgetMod/storeBudgets"),
        ]);
        this.$store.commit("setStopLoading");
        // TODO - globalizar função
        this.clientsOpts = this.$store.state.clientMod.clients.map(el => ({id: el.id, name: `${el.name} - ${el.CNPJ}`, value: `${el.name} - ${el.CNPJ}`}));
        this.idsOpts = this.$store.state.budgetMod.budgets.map(itemgetter("id")).map(String).map(id => ({id: id, name: id, value: id}));
    },
    components: {
        iCard,
        iSearch,
        iNoResults,
        iLoading,
    },
    methods: {
        edit(id) {
            this.$router.push(endpoints.routes.BUDGET_EDIT.replace(":id", id));
        },
        view(id) {
            this.$router.push(endpoints.routes.BUDGET_VIEW.replace(":id", id));
        },
        async del(id) {
            const continues = await this.$store.state.iChoice.open("Tem certeza que deseja excluir esse orçamento?");
            if (!continues) return;
            this.$store.dispatch("budgetMod/delBudget", id);
        },
        filter() {
            return this.$store.state.budgetMod.budgets
                .filter(el => this.id ? Number(this.id) === el.id : true)
                .filter(el => this.client ? el.client === this.client : true);
        }
    }
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

ul {
    display: grid;
    grid-template-columns: repeat(3, 400px);
    grid-auto-rows: 360px;
    row-gap: 10px;
    column-gap: 10px;
}

.filters {
    display: grid;
    grid-auto-rows: 80px;
    grid-template-columns: 270px 270px;
}
</style>