<template>
    <h1>Visualizar Orçamento</h1>
    <iPDF ref="pdf"/>
    <div class="btns">
        <div>
            <span>
                {{ budgetForm.COST.LABEL }} 
            </span>
            <span>
                {{ $store.state.budgetMod.budget.cost?.toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'})  }}
            </span>
        </div>
        <button @click="approve" class="approve" type="button">
            Aprovar
        </button>
        <button @click="$refs.pdf.generate" class="download" type="button">
            Baixar
        </button>
    </div>
</template>

<script>
import iPDF from "@/components/budgets/pdf/iPDF.vue";
import mixins from "@/common/mixins";
import { endpoints } from "@/common/consts";


export default {
    data: () => ({
        budget: {}
    }),
    mixins: [mixins],
    components: {
        iPDF,
    },
    methods: {
        approve() {
            this.$store.commit("deliveryMod/storeBudget", structuredClone(this.$store.state.budgetMod.budget));
            this.$router.push(endpoints.routes.DELIVERY_CREATE_FULL);
        }
    },
    async beforeCreate() {
        window.scrollTo(0, 0);
        const id = this.$route.params.id;
        await Promise.all([
            this.$store.dispatch("budgetMod/storeBudget", id),
            this.$store.dispatch("stockTypeMod/storeStockTypes"),
        ]);
        await this.$store.dispatch("clientMod/storeClient", this.$store.state.budgetMod.budget.client);
    },
};
</script>
<style scoped>
h1 {
    text-align: center;
    margin-bottom: 40px;
    margin-top: 30px;
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

.btns {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 20px;
    gap: 15px;
}

.btns button, .btns div {
    border-radius: 5px;
    display: flex;
    align-items: center;
    color: white;
    justify-content: center;
}

.btns button {
    font-size: 16px;
    border: transparent;
    color: white;
    cursor: pointer;
    padding: 11px 10px;
}

.btns div {
    text-transform: capitalize;
    flex-direction: column;
    background-color: var(--red-2);
    padding: 9px 10px;
    font-size: 12px;
}

.btns button:hover:not(:disabled), .btns button:active {
    transition: 0.3s;
    filter: brightness(2);
}

.btns .download {
    background-color: var(--blue-1);
}

.btns .approve {
    background-color: var(--green-2);
}

</style>