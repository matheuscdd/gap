<template>
    <h1>Criar Entrega Parcial</h1>
    <form @submit.prevent="create">
        <section>
            <div>
                <iInput 
                    label="Número"
                    placeholder="Indefinido"
                    readonly="true"
                    icon="file-contract-solid"
                    type="text"
                    name="identifier"
                    :errors="[]"
                />
                <iInput 
                    :label="deliveryForm.DELIVERY_DATE.LABEL"
                    :placeholder="deliveryForm.DELIVERY_DATE.PLACEHOLDER"
                    :icon="deliveryForm.DELIVERY_DATE.ICON"
                    :type="deliveryForm.DELIVERY_DATE.TYPE"
                    :name="deliveryForm.DELIVERY_DATE.NAME"
                    :errors="delivery_date.errors"
                    v-model="delivery_date.value"
                    @validate="verifyDelivery"
                />
                <iInput 
                    :label="deliveryForm.DELIVERY_ADDRESS.LABEL"
                    :placeholder="deliveryForm.DELIVERY_ADDRESS.PLACEHOLDER"
                    :icon="deliveryForm.DELIVERY_ADDRESS.ICON"
                    :maxlength="limits.delivery.delivery_address.max"
                    :type="deliveryForm.DELIVERY_ADDRESS.TYPE"
                    :name="deliveryForm.DELIVERY_ADDRESS.NAME"
                    :errors="delivery_address.errors"
                    v-model="delivery_address.value"
                    @validate="verifyDelivery"
                />
            </div>
            <div>
                <iInput 
                    :label="deliveryForm.DRIVER.LABEL"
                    :placeholder="deliveryForm.DRIVER.PLACEHOLDER"
                    :icon="deliveryForm.DRIVER.ICON"
                    :type="deliveryForm.DRIVER.TYPE"
                    :name="deliveryForm.DRIVER.NAME"
                    :errors="driver.errors"
                    v-model="driver.value"
                    @validate="verifyDelivery"
                />
                <iSelect
                    :opts="unloadedOpts"
                    :label="deliveryForm.UNLOADED.LABEL"
                    :placeholder="deliveryForm.UNLOADED.PLACEHOLDER"
                    :name="deliveryForm.UNLOADED.NAME"
                    :errors="unloaded.errors"
                    :icon="deliveryForm.UNLOADED.ICON"
                    v-model="unloaded.value"
                />
                <iInput 
                    v-show="unloaded.value === 'carrier'"
                    :label="deliveryForm.UNLOADING_COST.LABEL"
                    :placeholder="deliveryForm.UNLOADING_COST.PLACEHOLDER"
                    :icon="deliveryForm.UNLOADING_COST.ICON"
                    :type="deliveryForm.UNLOADING_COST.TYPE"
                    :name="deliveryForm.UNLOADING_COST.NAME"
                    :errors="unloading_cost.errors"
                    v-model="unloading_cost.value"
                    @validate="verifyDelivery"
                />
            </div>
        </section>
        <div class="container-stocks">
            <iStock v-for="el in stocks"
                :key="el.id"
                :id="el.id"
                v-model:type="el.type"
                v-model:name="el.name"
                v-model:quantity="el.quantity"
                v-model:weight="el.weight"
                v-model:extra="el.extra"
                v-model:ignore="el.ignore"
                :tot="stocks.length"
                :only-less="true"
                :max="max[el.id]"
                @del="removeStock"
                 ref="stocks"
            />
        </div>
        <section>
            <button
                class="bButton"
                type="submit"
                :style="{backgroundColor: 'var(--green-2)'}"
            >
                Salvar
            </button>
        </section>
    </form>
</template>
<script>
import { getUUID, prepareDataDelivery } from "@/common/utils";
import mixins from "@/common/mixins";
import iSelect from "@/components/common/iSelect.vue";
import iStock from "@/components/common/iStock.vue";
import iInput from "@/components/common/iInput.vue";
import { verifyDelivery } from "@/common/validators";

export default {
    data: () => ({
        stocks: [{
            id: getUUID(),
            type: 1,
            name: "",
            quantity: "",
            weight: "",
            extra: null
        }],
        max: {},
        [mixins.data().delivery.keys.DELIVERY_DATE]: {
            errors: [],
            value: "",
        },
        [mixins.data().delivery.keys.DELIVERY_ADDRESS]: {
            errors: [],
            value: "",
        },
        [mixins.data().delivery.keys.UNLOADING_COST]: {
            errors: [],
            value: "",
        },
        [mixins.data().delivery.keys.DRIVER]: {
            errors: [],
            value: "",
        },
        [mixins.data().delivery.keys.UNLOADED.THIS]: {
            errors: [],
            value: "carrier",
        },
        clientsOpts: [],
        unloadedOpts: [
            {
                id: "client",
                name: "Cliente",
            },
            {
                id: "carrier",
                name: "Transportadora",
            },
        ],
    }),
    mixins: [mixins],
    components: {
        iInput,
        iSelect,
        iStock,
    },
    methods: {
        verifyDelivery,
        removeStock(id) {
            this.stocks = this.stocks.filter(el => el.id !== id);
        },
        async create() {
            prepareDataDelivery(this, "createPartial", verifyDelivery, {id: this.$route.params.id});
        }
    },

    async beforeCreate() {
        window.scrollTo(0,0);
        const id = this.$route.params.id;
        await this.$store.dispatch("deliveryMod/storeDelivery", id);
        await this.$store.dispatch("stockTypeMod/storeStockTypes");
        await this.$store.dispatch("clientMod/storeClients");
        // TODO - transformar numa função
        this.clientsOpts = this.$store.state.clientMod.clients.map(el => ({id: el.id, text: `${el.name} - ${el.CNPJ}`, value: `${el.name} - ${el.CNPJ}`}));
        const delivery = this.$store.state.deliveryMod.delivery;
        this.stocks = delivery.available.map(el => ({...el, ignore: false}));
        structuredClone(delivery.available)
            .forEach(stock => this.max[stock.id] = {weight: stock.weight, quantity: stock.quantity});
    }
};
</script>
<style scoped>
h1 {
    text-align: center;
    margin-bottom: 30px;
    margin-top: 120px;
}

form section {
    display: flex;
    gap: 20px;
    justify-content: center;
}

.container-stocks {
    display: flex;
    flex-direction: column;
    align-items: center;
}
</style>