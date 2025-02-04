<template>
    <h1>Editar Entrega Completa</h1>
    <form @submit.prevent="edit">
        <section>
            <div>
                <iInput 
                    label="Número"
                    :placeholder="this.$route.params.id"
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
                <iInput 
                    :label="deliveryForm.PROVIDER_NAME.LABEL"
                    :placeholder="deliveryForm.PROVIDER_NAME.PLACEHOLDER"
                    :icon="deliveryForm.PROVIDER_NAME.ICON"
                    :maxlength="limits.delivery.provider_name.max"
                    :type="deliveryForm.PROVIDER_NAME.TYPE"
                    :name="deliveryForm.PROVIDER_NAME.NAME"
                    :errors="provider_name.errors"
                    v-model="provider_name.value"
                    @validate="verifyDelivery"
                />
            </div>
            <div>
                <iInput 
                    :label="deliveryForm.PROVIDER_CITY.LABEL"
                    :placeholder="deliveryForm.PROVIDER_CITY.PLACEHOLDER"
                    :icon="deliveryForm.PROVIDER_CITY.ICON"
                    :maxlength="limits.delivery.provider_city.max"
                    :type="deliveryForm.PROVIDER_CITY.TYPE"
                    :name="deliveryForm.PROVIDER_CITY.NAME"
                    :errors="provider_city.errors"
                    v-model="provider_city.value"
                    @validate="verifyDelivery"
                />
                <iInput 
                    :label="deliveryForm.PAYMENT_DATE.LABEL"
                    :placeholder="deliveryForm.PAYMENT_DATE.PLACEHOLDER"
                    :icon="deliveryForm.PAYMENT_DATE.ICON"
                    :type="deliveryForm.PAYMENT_DATE.TYPE"
                    :name="deliveryForm.PAYMENT_DATE.NAME"
                    :errors="payment_date.errors"
                    v-model="payment_date.value"
                    @validate="verifyDelivery"
                />
                <iInput 
                    :label="deliveryForm.REVENUE.LABEL"
                    :placeholder="deliveryForm.REVENUE.PLACEHOLDER"
                    :icon="deliveryForm.REVENUE.ICON"
                    :type="deliveryForm.REVENUE.TYPE"
                    :name="deliveryForm.REVENUE.NAME"
                    :errors="revenue.errors"
                    v-model="revenue.value"
                    @validate="verifyDelivery"
                />
                <iInput 
                    :label="deliveryForm.RECEIPT_DATE.LABEL"
                    :placeholder="deliveryForm.RECEIPT_DATE.PLACEHOLDER"
                    :icon="deliveryForm.RECEIPT_DATE.ICON"
                    :type="deliveryForm.RECEIPT_DATE.TYPE"
                    :name="deliveryForm.RECEIPT_DATE.NAME"
                    :errors="receipt_date.errors"
                    v-model="receipt_date.value"
                    @validate="verifyDelivery"
                />
            </div>
            <div>
                <iSearch
                    :name="deliveryForm.CLIENT.NAME"
                    :icon="deliveryForm.CLIENT.ICON"
                    :label="deliveryForm.CLIENT.LABEL"
                    :errors="client.errors"
                    v-model="client.value"
                    :opts="clientsOpts"
                    @validate="verifyDelivery"
                    :edit="true"
                />
                <iSelect
                    :opts="paymentStatusOpts"
                    :label="deliveryForm.PAYMENT_STATUS.LABEL"
                    :placeholder="deliveryForm.PAYMENT_STATUS.PLACEHOLDER"
                    :name="deliveryForm.PAYMENT_STATUS.NAME"
                    :errors="payment_status.errors"
                    :icon="deliveryForm.PAYMENT_STATUS.ICON"
                    v-model="payment_status.value"
                />
                <iSelect
                    :opts="paymentMethodOpts"
                    :label="deliveryForm.PAYMENT_METHOD.LABEL"
                    :placeholder="deliveryForm.PAYMENT_METHOD.PLACEHOLDER"
                    :name="deliveryForm.PAYMENT_METHOD.NAME"
                    :errors="payment_method.errors"
                    :icon="deliveryForm.PAYMENT_METHOD.ICON"
                    v-model="payment_method.value"
                />
                <iInput 
                    :label="deliveryForm.INVOICE.LABEL"
                    :placeholder="deliveryForm.INVOICE.PLACEHOLDER"
                    :icon="deliveryForm.INVOICE.ICON"
                    :type="deliveryForm.INVOICE.TYPE"
                    :name="deliveryForm.INVOICE.NAME"
                    :maxlength="limits.delivery.invoice.size"
                    :nullable="true"
                    :errors="invoice.errors"
                    v-model="invoice.value"
                    @validate="verifyDelivery"
                />
            </div>
            <div>
                <iInput 
                    :label="deliveryForm.TRAVEL_COST.LABEL"
                    :placeholder="deliveryForm.TRAVEL_COST.PLACEHOLDER"
                    :type="deliveryForm.TRAVEL_COST.TYPE"
                    :name="deliveryForm.TRAVEL_COST.NAME"
                    :errors="travel_cost.errors"
                    :icon="deliveryForm.TRAVEL_COST.ICON"
                    v-model="travel_cost.value"
                    @validate="verifyDelivery"
                />
                <iSearch
                    :label="deliveryForm.DRIVER.LABEL"
                    :name="deliveryForm.DRIVER.NAME"
                    :icon="deliveryForm.DRIVER.ICON"
                    :errors="driver.errors"
                    v-model="driver.value"
                    :opts="driversOpts"
                    @validate="verifyDelivery"
                    :edit="true"
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
                :tot="stocks.length"
                @add="createStock"
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
import iSearch from "@/components/common/iSearch.vue";
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
        [mixins.data().delivery.keys.INVOICE]: {
            errors: [],
            value: "",
        },
        [mixins.data().delivery.keys.CLIENT]: {
            errors: [],
            value: "",
        },
        [mixins.data().delivery.keys.DELIVERY_DATE]: {
            errors: [],
            value: "",
        },
        [mixins.data().delivery.keys.DELIVERY_ADDRESS]: {
            errors: [],
            value: "",
        },
        [mixins.data().delivery.keys.PROVIDER_NAME]: {
            errors: [],
            value: "",
        },
        [mixins.data().delivery.keys.PROVIDER_CITY]: {
            errors: [],
            value: "",
        },
        [mixins.data().delivery.keys.PAYMENT_DATE]: {
            errors: [],
            value: "",
        },
        [mixins.data().delivery.keys.REVENUE]: {
            errors: [],
            value: "",
        },
        [mixins.data().delivery.keys.TRAVEL_COST]: {
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
        [mixins.data().delivery.keys.RECEIPT_DATE]: {
            errors: [],
            value: "",
        },
        [mixins.data().delivery.keys.UNLOADED.THIS]: {
            errors: [],
            value: "carrier",
        },
        [mixins.data().delivery.keys.PAYMENT_STATUS.THIS]: {
            errors: [],
            value: "pending",
        },
        [mixins.data().delivery.keys.PAYMENT_METHOD.THIS]: {
            errors: [],
            value: "ticket",
        },
        clientsOpts: [],
        driversOpts: [],
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
        paymentStatusOpts: [
            {
                id: "paid",
                name: "Pago",
            },
            {
                id: "pending",
                name: "Pendente",
            },
        ],
        paymentMethodOpts: [
            {
                id: "pix",
                name: "Pix",
            },
            {
                id: "ticket",
                name: "Boleto",
            },
        ],
    }),
    mixins: [mixins],
    components: {
        iInput,
        iSearch,
        iSelect,
        iStock,
    },
    methods: {
        verifyDelivery,
        createStock() { 
            this.stocks.push({
                id: getUUID(),
                type: 1,
                name: "",
                quantity: "",
                weight: "",
                extra: null
            });
        },
        removeStock(id) {
            this.stocks = this.stocks.filter(el => el.id !== id);
        },
        async edit() {
            prepareDataDelivery(this, "editFull", verifyDelivery, {id: this.$route.params.id});
        }
    },

    async beforeCreate() {
        window.scrollTo(0,0);
        const id = this.$route.params.id;
        await Promise.all([
            this.$store.dispatch("deliveryMod/storeDelivery", id),
            this.$store.dispatch("stockTypeMod/storeStockTypes"),
            this.$store.dispatch("clientMod/storeClients"),
            this.$store.dispatch("driverMod/storeDrivers"),
        ]);
        this.clientsOpts = this.$store.state.clientMod.clients
            .map(el => ({id: el.id, name:`${el.name} - ${el.CNPJ}`}));
        this.driversOpts = this.$store.state.driverMod.drivers
            .map(el => ({id: el.id, name:`${el.name} - ${el.CPF}`})); 
        const delivery = this.$store.state.deliveryMod.delivery;
        this.stocks = delivery.stocks;
        Object
            .values(this.delivery.keys)
            .filter(Boolean)
            .map(key => key?.THIS || key)
            .forEach(key => this[key].value = delivery[key]);
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