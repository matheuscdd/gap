<template>
    <h1>Visualizar Orçamento</h1>
    <iPDF 
        ref="ipdf"
        :logo="logo"
        :generating="generating"
        :CARRIER_CEP="CARRIER_CEP" 
        :CARRIER_TELEPHONE="CARRIER_TELEPHONE"
        :CARRIER_CELLPHONE="CARRIER_CELLPHONE"
        :CARRIER_ADDRESS="CARRIER_ADDRESS"
        :CARRIER_CNPJ="CARRIER_CNPJ"
        :CARRIER_COMPLETE_NAME="CARRIER_COMPLETE_NAME"
        :CARRIER_PARCIAL_NAME="CARRIER_PARCIAL_NAME"
    />
    <div class="btns">
        <div>
            <span>
                {{ budgetForm.COST.LABEL }} 
            </span>
            <span>
                {{ $store.state.budgetMod.budget.cost?.toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'})  }}
            </span>
        </div>
        <button @click="edit" class="edit" type="button">
            Editar
        </button>
        <button @click="approve" class="approve" type="button">
            Aprovar
        </button>
        <button @click="generate" class="download" type="button">
            Baixar
        </button>
    </div>
</template>

<script>
import iPDF from "@/components/budgets/pdf/iPDF.vue";
import mixins from "@/common/mixins";
import { endpoints } from "@/common/consts";
import { jsPDF } from "jspdf";
import { renderFileReader, sleep  } from "@/common/utils";

export default {
    data: () => ({
        logo: "",
        generating: false,
        CARRIER_CEP: process.env.VUE_APP_CARRIER_CEP,
        CARRIER_TELEPHONE: process.env.VUE_APP_CARRIER_TELEPHONE,
        CARRIER_CELLPHONE: process.env.VUE_APP_CARRIER_CELLPHONE,
        CARRIER_ADDRESS: process.env.VUE_APP_CARRIER_ADDRESS,
        CARRIER_CNPJ: process.env.VUE_APP_CARRIER_CNPJ,
        CARRIER_COMPLETE_NAME: process.env.VUE_APP_CARRIER_COMPLETE_NAME,
        CARRIER_PARCIAL_NAME: process.env.VUE_APP_CARRIER_PARCIAL_NAME,
    }),
    mixins: [mixins],
    components: {
        iPDF,
    },
    methods: {
        edit() {
            this.$router.push(endpoints.routes.BUDGET_EDIT.replace(":id", this.$route.params.id));
        },
        approve() {
            this.$store.commit("deliveryMod/storeBudget", structuredClone(this.$store.state.budgetMod.budget));
            this.$router.push(endpoints.routes.DELIVERY_CREATE_FULL);
        },
        async generate() {
            this.$store.state.iChoice.open(
                "Seu PDF está sendo gerado. Por favor, aguarde...",
                true,
            );

            this.generating = true;
            const container = this.$refs.ipdf.$refs.container;
            const dimensions = {
                width: 1240,
                height: 1840,
                page: "a4",
                unit: "px",
                orientation: "p",
            };
            const img = await this.$html2canvas(container, { 
                width: dimensions.width,
                height: dimensions.height,
                type: "dataURL",  
                scale: 2
            });

            const pdf = new jsPDF(
                dimensions.orientation,
                dimensions.unit,
                dimensions.page,
            );
            pdf.addImage({
                imageData: img,
                width: 1240 / 2.78,
                height: 1840 / 2.9,
                format: "png",
                x: 0,
                y: 0,
                compression: "SLOW",
            });
            pdf.setProperties({
                title: `${this.CARRIER_PARCIAL_NAME} - Orçamento Digital`,
                author: this.CARRIER_PARCIAL_NAME,
                subject: `Orçamento Nº ${this.$route.params.id}`,
                keywords: `PDF, orçamento, ${this.CARRIER_PARCIAL_NAME}`,
                creator: this.CARRIER_PARCIAL_NAME,
                producer: "jsPDF",
                creationDate: this.$store.state.budgetMod.budget.created_at.toLocaleString("pt-BR"),
            });
            pdf.save(`orçamento_${this.$store.state.budgetMod.budget.id}.pdf`);
            this.generating = false;
            this.$store.state.iChoice.update("Seu PDF já está disponível!");
            await sleep(1000);
            this.$store.state.iChoice.cancel();
        },
    },
    async beforeCreate() {
        window.scrollTo(0, 0);
        const id = this.$route.params.id;
        await Promise.all([
            this.$store.dispatch("budgetMod/storeBudget", id),
            this.$store.dispatch("stockTypeMod/storeStockTypes"),
        ]);
        await this.$store.dispatch("clientMod/storeClient", this.$store.state.budgetMod.budget.client);
        
        const pathLogo = require("@/assets/common/brazil-green.png");
        const response = await fetch(pathLogo);
        const bin = await response.blob();
        const img = await renderFileReader(bin);
        this.logo = img;
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

.btns .edit {
    background-color: var(--yellow-1);
}

</style>