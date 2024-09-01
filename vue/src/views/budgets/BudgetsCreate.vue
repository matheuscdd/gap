<template>
    <h1>Criar Orçamento</h1>
    <form>
        <iInput 
            :label="budgetForm.DELIVERY_DATE.LABEL"
            :placeholder="budgetForm.DELIVERY_DATE.PLACEHOLDER"
            :icon="budgetForm.DELIVERY_DATE.ICON"
            :type="budgetForm.DELIVERY_DATE.TYPE"
            :name="budgetForm.DELIVERY_DATE.NAME"
            :errors="delivery_date.errors"
            v-model="delivery_date.value"
            @validate="verifyClient"
        />
        <iInput 
            :label="budgetForm.DELIVERY_ADDRESS.LABEL"
            :placeholder="budgetForm.DELIVERY_ADDRESS.PLACEHOLDER"
            :icon="budgetForm.DELIVERY_ADDRESS.ICON"
            :maxlength="limits.budget.delivery_address.max"
            :type="budgetForm.DELIVERY_ADDRESS.TYPE"
            :name="budgetForm.DELIVERY_ADDRESS.NAME"
            :errors="delivery_address.errors"
            v-model="delivery_address.value"
            @validate="verifyClient"
        />
        <iInput 
            :label="budgetForm.PROVIDER_NAME.LABEL"
            :placeholder="budgetForm.PROVIDER_NAME.PLACEHOLDER"
            :icon="budgetForm.PROVIDER_NAME.ICON"
            :maxlength="limits.budget.provider_name.max"
            :type="budgetForm.PROVIDER_NAME.TYPE"
            :name="budgetForm.PROVIDER_NAME.NAME"
            :errors="provider_name.errors"
            v-model="provider_name.value"
            @validate="verifyClient"
        />
        <iInput 
            :label="budgetForm.PROVIDER_CITY.LABEL"
            :placeholder="budgetForm.PROVIDER_CITY.PLACEHOLDER"
            :icon="budgetForm.PROVIDER_CITY.ICON"
            :maxlength="limits.budget.provider_city.max"
            :type="budgetForm.PROVIDER_CITY.TYPE"
            :name="budgetForm.PROVIDER_CITY.NAME"
            :errors="provider_city.errors"
            v-model="provider_city.value"
            @validate="verifyClient"
        />
        <iInput 
            :label="budgetForm.PAYMENT_DATE.LABEL"
            :placeholder="budgetForm.PAYMENT_DATE.PLACEHOLDER"
            :icon="budgetForm.PAYMENT_DATE.ICON"
            :type="budgetForm.PAYMENT_DATE.TYPE"
            :name="budgetForm.PAYMENT_DATE.NAME"
            :errors="payment_date.errors"
            v-model="payment_date.value"
            @validate="verifyClient"
        />
        <iInput 
            :label="budgetForm.REVENUE.LABEL"
            :placeholder="budgetForm.REVENUE.PLACEHOLDER"
            :icon="budgetForm.REVENUE.ICON"
            :type="budgetForm.REVENUE.TYPE"
            :name="budgetForm.REVENUE.NAME"
            :errors="revenue.errors"
            v-model="revenue.value"
            @validate="verifyClient"
        />
        <iInput 
            :label="budgetForm.COST.LABEL"
            :placeholder="budgetForm.COST.PLACEHOLDER"
            :icon="budgetForm.COST.ICON"
            :type="budgetForm.COST.TYPE"
            :name="budgetForm.COST.NAME"
            :errors="cost.errors"
            v-model="cost.value"
            @validate="verifyClient"
        />
       <iSearch
            :name="budgetForm.CLIENT.NAME"
            :icon="budgetForm.CLIENT.ICON"
            :label="budgetForm.CLIENT.LABEL"
            :errors="client.errors"
            v-model="client.value"
            :opts="clientsOpts"
            @validate="verifyClient"
       />
       <iSelect
            :opts="unloadedOpts"
            :label="budgetForm.UNLOADED.LABEL"
            :placeholder="budgetForm.UNLOADED.PLACEHOLDER"
            :name="budgetForm.UNLOADED.NAME"
            :errors="unloaded.errors"
            icon="dolly-solid"
            v-model="unloaded.value"
       />
       <iSelect
            :opts="paymentStatusOpts"
            :label="budgetForm.PAYMENT_STATUS.LABEL"
            :placeholder="budgetForm.PAYMENT_STATUS.PLACEHOLDER"
            :name="budgetForm.PAYMENT_STATUS.NAME"
            :errors="payment_status.errors"
            icon="money-bill-transfer-solid"
            v-model="payment_status.value"
       />
       <iSelect
            :opts="paymentMethodOpts"
            :label="budgetForm.PAYMENT_METHOD.LABEL"
            :placeholder="budgetForm.PAYMENT_METHOD.PLACEHOLDER"
            :name="budgetForm.PAYMENT_METHOD.NAME"
            :errors="payment_method.errors"
            icon="cash-register-solid"
            v-model="payment_method.value"
       />
       <iStock v-for="el in stocks"
            :key="el.id"
            :id="el.id"
            v-model:type="el.type"
            v-model:name="el.name"
            v-model:quantity="el.quantity"
            v-model:weight="el.weight"
            :tot="stocks.length"
            @add="createStock"
            @del="removeStock"
       />
        <button type="button" @click="create">Salvar</button>
    </form>
</template>

<script>
import iInput from "@/components/common/iInput.vue";
import { verifyBudget, verifyStock } from "@/common/validators";
import { getErrors, getUUID, getValues } from "@/common/utils";
import mixins from "@/common/mixins";
import iSearch from "@/components/common/iSearch.vue";
import iSelect from "@/components/common/iSelect.vue";
import iStock from "@/components/common/iStock.vue";


export default {
    data: () => ({
        stocks: [{
            id: getUUID(),
            type: 1,
            name: "",
            quantity: "",
            weight: "",
        }],
        [mixins.data().budget.keys.CLIENT]: {
            errors: [],
            value: "",
        },
        [mixins.data().budget.keys.DELIVERY_DATE]: {
            errors: [],
            value: "",
        },
        [mixins.data().budget.keys.DELIVERY_ADDRESS]: {
            errors: [],
            value: "",
        },
        [mixins.data().budget.keys.PROVIDER_NAME]: {
            errors: [],
            value: "",
        },
        [mixins.data().budget.keys.PROVIDER_CITY]: {
            errors: [],
            value: "",
        },
        [mixins.data().budget.keys.PAYMENT_DATE]: {
            errors: [],
            value: "",
        },
        [mixins.data().budget.keys.REVENUE]: {
            errors: [],
            value: "",
        },
        [mixins.data().budget.keys.COST]: {
            errors: [],
            value: "",
        },
        [mixins.data().budget.keys.UNLOADED.THIS]: {
            errors: [],
            value: "",
        },
        [mixins.data().budget.keys.PAYMENT_STATUS.THIS]: {
            errors: [],
            value: "",
        },
        [mixins.data().budget.keys.PAYMENT_METHOD.THIS]: {
            errors: [],
            value: "",
        },
        clientsOpts: [],
        unloadedOpts: [
            {
                value: "client",
                text: "Cliente",
                icon: "building-solid"
            },
            {
                value: "carrier",
                text: "Transportadora",
                icon: "truck-moving-solid"
            },
        ],
        paymentStatusOpts: [
            {
                value: "paid",
                text: "Pago",
                icon: "circle-check-solid"
            },
            {
                value: "pending",
                text: "Pendente",
                icon: "circle-question-solid"
            },
        ],
        paymentMethodOpts: [
            {
                value: "pix",
                text: "Pix",
                icon: "pix-solid"
            },
            {
                value: "ticket",
                text: "Boleto",
                icon: "barcode-solid"
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
        verifyBudget,
        createStock() { 
            this.stocks.push({
                id: getUUID(),
                type: 1,
                name: "",
                quantity: "",
                weight: "",
            });
        },
        removeStock(id) {
            this.stocks = this.stocks.filter(el => el.id !== id);
        },
        create() {
            const data = {
                stocks: this.stocks.map(({type, name, quantity, weight }) => ({type, name, quantity, weight })),
                client: this.client.value,
                delivery_address: this.delivery_address.value,
                delivery_date: this.delivery_date.value,
                provider_name: this.provider_name.value,
                provider_city: this.provider_city.value,
                payment_date: this.payment_date.value,
                revenue: this.revenue.value,
                cost: this.cost.value,
                unloaded: this.unloaded.value,
                payment_status: this.payment_status.value,
                payment_method: this.payment_method.value,
            };
            console.log(data);
            this.$store.dispatch("budgetMod/createBudget", data);
        }
    },
    async beforeCreate() {
        await this.$store.dispatch("stockTypeMod/storeStockTypes");
        await this.$store.dispatch("clientMod/storeClients");
        this.clientsOpts = this.$store.state.clientMod.clients.map(el => ({id: el.id, text: `${el.name} - ${el.CNPJ}`, value: `${el.name} - ${el.CNPJ}`}));
    }
};
</script>