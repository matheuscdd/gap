<template>
    <h1>Entregas</h1>
    <div class="filters">
        <div class="container-status">
            <div>Status</div>
            <div>
                <input id="open" value="open" type="radio" name="status" v-model="status">
                <label for="open">Abertas</label>
            </div>
            <div>
                <input id="finished" value="finished" type="radio" name="status" v-model="status">
                <label for="finished">Fechadas</label>
            </div>
        </div>
        <div class="container-id">
            <iSearch
                name="id"
                icon="file-contract-solid"
                label="Número"
                :errors="[]"
                v-model="id"
                :opts="idsOpts"
            />
        </div>
        <div class="container-client">
            <iSearch
                :name="deliveryForm.CLIENT.NAME"
                :icon="deliveryForm.CLIENT.ICON"
                :label="deliveryForm.CLIENT.LABEL"
                :errors="[]"
                v-model="client"
                :opts="clientsOpts"
            />
        </div>
    </div>
    <ul class="header">
        <div>Número</div>
        <div>Faturamento</div>
        <div>Cliente</div>
        <div>Chegada</div>
        <div>Entrega</div>
        <div>Dias restantes</div>
        <div>Origem</div>
        <div>Destino</div>
        <div>Ações</div>
    </ul>
    <ul 
        v-if="!$store.state.loading && $store.state.deliveryMod.deliveries.length"
        v-show="filter().length"
    >
        <iCard
            v-for="el in filter()" 
            :key="el.id"
            :id="el.id"
            :client_name="$store.state.clientMod.clients.find(client => client.id === el.client).name"
            :provider_city="formatField(el.provider_city, 20)"
            :receipt_date="el.receipt_date"
            :delivery_address="el.delivery_address"
            :revenue="el.revenue"
            :delivery_date="el.delivery_date"
            :createdAt="el.created_at"
            :updatedAt="el.updated_at"
            :completed="el.completed"
            :has_partials="el.has_partials"
            :finished="el.finished"
            :received="el.received"
            :total="el.total"
            @del="del"
            @receive="receive"
            @edit="edit"
            @createPartial="createPartial"
            @finish="finish"
            @view="view"
        />
    </ul>
    <iLoading v-show="$store.state.loading"/>
    <iNoResults v-show="!$store.state.loading && !filter().length"/>
</template>
<script>
import { endpoints } from "@/common/consts";
import iSearch from "@/components/common/iSearch.vue";
import iCard from "@/components/deliveries/iFull.vue";
import mixins from "@/common/mixins";
import { formatField, itemgetter } from "@/common/utils";
import iNoResults from "@/components/common/iNoResults.vue";
import iLoading from "@/components/common/iLoading.vue";

export default {
    data: () => ({
        status: "open",
        clientsOpts: [],
        client: "",
        id: "",
        idsOpts: [],
    }),
    mixins: [mixins],
    components: {
        iCard,
        iNoResults,
        iSearch,
        iLoading,
    },
    async beforeCreate() {
        window.scrollTo(0,0);
        this.$store.commit("setStartLoading");
        await Promise.all([
            this.$store.dispatch("clientMod/storeClients"),
            this.$store.dispatch("deliveryMod/storeFull"),
        ]);
        this.$store.commit("setStopLoading");
        // TODO - globalizar função
        this.clientsOpts = this.$store.state.clientMod.clients.map(el => ({id: el.id, name:`${el.name} - ${el.CNPJ}`}));
        this.idsOpts = this.$store.state.deliveryMod.deliveries.map(itemgetter("id")).map(String).map(id => ({id: id, name:id}));
    },
    methods: {
        async del(id) {
            const continues = await this.$store.state.iChoice.open("Tem certeza que deseja excluir essa entrega?");
            if (!continues) return;
            this.$store.dispatch("deliveryMod/delFull", id);
        },

        async receive(id) {
            const continues = await this.$store.state.iChoice.open("Ao assegurar a chegada dessa entrega na transportadora, você confirma que a data de chegada está correta e que já está no estoque?");
            if (!continues) return; 
            this.$store.dispatch("deliveryMod/receiveFull", id);
        },

        edit(id) {
            this.$router.push(endpoints.routes.DELIVERY_EDIT_FULL_FULL.replace(":id", id));
        },

        async finish(id) {
            const continues = await this.$store.state.iChoice.open("Tem certeza que deseja finalizar essa entrega e todas as suas parciais caso existam? Essa ação não poderá ser desfeita");
            if (!continues) return;
            await this.$store.dispatch("deliveryMod/finishFull", id);
        },

        createPartial(id) {
            this.$router.push(endpoints.routes.DELIVERY_CREATE_PARTIAL.replace(":id", id));
        },

        view(id) {
            this.$router.push(endpoints.routes.DELIVERY_VIEW_FULL.replace(":id", id));
        },

        filter() {
            return this.$store.state.deliveryMod.deliveries
                .filter(el => this.id ? Number(this.id) === el.id : true)
                .filter(el => this.status === "finished" ? el.finished : !el.finished)
                .filter(el => this.client ? el.client === this.client : true);
        },

        formatField,
    }
};
</script>
<style scoped>
.filters {
    display: grid;
    grid-auto-rows: 80px;
    grid-template-columns: 160px 270px 270px;
}

.header {
    font-size: 12px;
    display: grid;
    grid-template-columns: 5% 10% 11% 8% 8% 8% 10% 10% 20%;
    grid-auto-rows: 30px;
    grid-gap: 10px;
    margin-bottom: 5px;
    text-align: center;
    margin-left: 20px
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

@media (max-width: 1100px) {
    .header {
        display: none;
    }
}
</style>