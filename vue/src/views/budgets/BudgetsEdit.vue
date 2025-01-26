<template>
    <h1>Editar Orçamento</h1>
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
                    :label="budgetForm.DELIVERY_DATE.LABEL"
                    :placeholder="budgetForm.DELIVERY_DATE.PLACEHOLDER"
                    :icon="budgetForm.DELIVERY_DATE.ICON"
                    :type="budgetForm.DELIVERY_DATE.TYPE"
                    :name="budgetForm.DELIVERY_DATE.NAME"
                    :errors="delivery_date.errors"
                    v-model="delivery_date.value"
                    @validate="verifyBudget"
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
                    @validate="verifyBudget"
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
                    @validate="verifyBudget"
                />
                </div>
                <div>
                <iInput 
                    :label="budgetForm.PROVIDER_CITY.LABEL"
                    :placeholder="budgetForm.PROVIDER_CITY.PLACEHOLDER"
                    :icon="budgetForm.PROVIDER_CITY.ICON"
                    :maxlength="limits.budget.provider_city.max"
                    :type="budgetForm.PROVIDER_CITY.TYPE"
                    :name="budgetForm.PROVIDER_CITY.NAME"
                    :errors="provider_city.errors"
                    v-model="provider_city.value"
                    @validate="verifyBudget"
                />
                <iInput 
                    :label="budgetForm.PAYMENT_DATE.LABEL"
                    :placeholder="budgetForm.PAYMENT_DATE.PLACEHOLDER"
                    :icon="budgetForm.PAYMENT_DATE.ICON"
                    :type="budgetForm.PAYMENT_DATE.TYPE"
                    :name="budgetForm.PAYMENT_DATE.NAME"
                    :errors="payment_date.errors"
                    v-model="payment_date.value"
                    @validate="verifyBudget"
                />
                <iInput 
                    :label="budgetForm.REVENUE.LABEL"
                    :placeholder="budgetForm.REVENUE.PLACEHOLDER"
                    :icon="budgetForm.REVENUE.ICON"
                    :type="budgetForm.REVENUE.TYPE"
                    :name="budgetForm.REVENUE.NAME"
                    :errors="revenue.errors"
                    v-model="revenue.value"
                    @validate="verifyBudget"
                />
                <iInput 
                    :label="budgetForm.COST.LABEL"
                    :placeholder="budgetForm.COST.PLACEHOLDER"
                    :icon="budgetForm.COST.ICON"
                    :type="budgetForm.COST.TYPE"
                    :name="budgetForm.COST.NAME"
                    :errors="cost.errors"
                    v-model="cost.value"
                    @validate="verifyBudget"
                />
                </div>
                <div>
                    <iSearch
                        :name="budgetForm.CLIENT.NAME"
                        :icon="budgetForm.CLIENT.ICON"
                        :label="budgetForm.CLIENT.LABEL"
                        :errors="client.errors"
                        v-model="client.value"
                        :opts="clientsOpts"
                        @validate="verifyBudget"
                        :edit="true"
                    />
                    <iSelect
                        :opts="unloadedOpts"
                        :label="budgetForm.UNLOADED.LABEL"
                        :placeholder="budgetForm.UNLOADED.PLACEHOLDER"
                        :name="budgetForm.UNLOADED.NAME"
                        :errors="unloaded.errors"
                        :icon="budgetForm.UNLOADED.ICON"
                        v-model="unloaded.value"
                    />
                    <iSelect
                        :opts="paymentStatusOpts"
                        :label="budgetForm.PAYMENT_STATUS.LABEL"
                        :placeholder="budgetForm.PAYMENT_STATUS.PLACEHOLDER"
                        :name="budgetForm.PAYMENT_STATUS.NAME"
                        :errors="payment_status.errors"
                        :icon="budgetForm.PAYMENT_STATUS.ICON"
                        v-model="payment_status.value"
                    />
                    <iSelect
                        :opts="paymentMethodOpts"
                        :label="budgetForm.PAYMENT_METHOD.LABEL"
                        :placeholder="budgetForm.PAYMENT_METHOD.PLACEHOLDER"
                        :name="budgetForm.PAYMENT_METHOD.NAME"
                        :errors="payment_method.errors"
                        :icon="budgetForm.PAYMENT_METHOD.ICON"
                        v-model="payment_method.value"
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
import iInput from "@/components/common/iInput.vue";
import { verifyBudget } from "@/common/validators";
import { getUUID, prepareDataBudget } from "@/common/utils";
import mixins from "@/common/mixins";
import iSearch from "@/components/common/iSearch.vue";
import iSelect from "@/components/common/iSelect.vue";
import iStock from "@/components/common/iStock.vue";


export default {
    data: () => ({
        stocks: [],
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
            value: "carrier",
        },
        [mixins.data().budget.keys.PAYMENT_STATUS.THIS]: {
            errors: [],
            value: "pending",
        },
        [mixins.data().budget.keys.PAYMENT_METHOD.THIS]: {
            errors: [],
            value: "ticket",
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
        verifyBudget,
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
            prepareDataBudget(this, "edit", verifyBudget, {id: this.$route.params.id});
        }
    },
    async beforeCreate() {
        window.scrollTo(0,0);
        const id = this.$route.params.id;
        await Promise.all([
            this.$store.dispatch("budgetMod/storeBudget", id),
            this.$store.dispatch("stockTypeMod/storeStockTypes"),
            this.$store.dispatch("clientMod/storeClients"),
        ]);
        this.clientsOpts = this.$store.state.clientMod.clients.map(el => ({id: el.id, text: `${el.name} - ${el.CNPJ}`}));
        const { budget } = this.$store.state.budgetMod;
        this.stocks = budget.stocks;
        // TODO trocar pelas keys dos consts
        const keys = ["client", "delivery_date", "delivery_address", "provider_name", "provider_city", "payment_date", "revenue", "cost", "unloaded", "payment_status", "payment_method"];
        keys.forEach(key => this[key].value = budget[key]);
    },
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