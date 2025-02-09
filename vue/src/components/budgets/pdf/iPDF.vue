<template>
    <div :class="{'container-section': true, generating }" ref="container">
        <section>
            <header>
                <div class="container-trademark">
                    <img :src="logo"/>
                </div>
                <h1>Orçamento</h1>
            </header>
            <div class="header-budget">
                <iField
                    :generating="generating"  
                    class="carrier_telephone"
                    label="Telefone"
                    :value="formatCellphone(CARRIER_TELEPHONE)"
                />
                <iField
                    :generating="generating" 
                    class="carrier_cellphone"
                    label="Celular"
                    :value="formatCellphone(CARRIER_CELLPHONE)"
                />
                <iField
                    :generating="generating" 
                    class="carrier_address"
                    label="Endereço"
                    :value="CARRIER_ADDRESS"
                />
                <iField
                    :generating="generating" 
                    class="carrier_CNPJ"
                    label="CNPJ"
                    :value="formatCNPJ(CARRIER_CNPJ)"
                />
                <iField
                    :generating="generating" 
                    class="carrier_CEP"
                    label="CEP"
                    :value="formatCEP(CARRIER_CEP)"
                />
                <iField
                    :generating="generating" 
                    class="carrier_name"
                    label="Nome"
                    :value="CARRIER_COMPLETE_NAME"
                />
                <iField
                    :generating="generating" 
                    class="number"
                    label="Número"
                    :value="$store.state.budgetMod.budget.id"
                />
                <iField
                    :generating="generating" 
                    class="delivery_date"
                    label="previsão de entrega"
                    :value="handleDate($store.state.budgetMod.budget.delivery_date).toLocaleDateString('pt-BR')"
                />
                <iField
                    :generating="generating"
                    class="revenue"
                    :label="budgetForm.REVENUE.LABEL"
                    :value="$store.state.budgetMod.budget.revenue?.toLocaleString('pt-BR', {style: 'currency', currency: 'BRL'})"
                />
                <iField
                    :generating="generating" 
                    class="delivery_address"
                    :label="budgetForm.DELIVERY_ADDRESS.LABEL"
                    :value="$store.state.budgetMod.budget.delivery_address"
                />
                <iField
                    :generating="generating" 
                    class="provider_name"
                    :label="budgetForm.PROVIDER_NAME.LABEL"
                    :value="$store.state.budgetMod.budget.provider_name"
                />
                <iField
                    :generating="generating" 
                    class="provider_city"
                    :label="budgetForm.PROVIDER_CITY.LABEL"
                    :value="$store.state.budgetMod.budget.provider_city"
                />
                <iField
                    :generating="generating" 
                    class="payment_date"
                    :label="budgetForm.PAYMENT_DATE.LABEL"
                    :value="$store.state.budgetMod.budget.payment_date ? handleDate($store.state.budgetMod.budget.payment_date).toLocaleDateString('pt-BR') : 'Pendente'"
                />
                <iField
                    :generating="generating" 
                    class="updated_at"
                    label="atualizado em"
                    :value="handleDate($store.state.budgetMod.budget.updated_at).toLocaleDateString('pt-BR')"
                />
                <iField
                    :generating="generating" 
                    class="emission_at"
                    label="emitido em"
                    :value="new Date().toLocaleDateString('pt-BR')"
                />
                <iField
                    :generating="generating"
                    class="unloaded"
                    :label="budgetForm.UNLOADED.LABEL"
                    :value="$store.state.budgetMod.budget.unloaded === 'client' ? 'Cliente' : 'Transportadora'"
                />
                <iField
                    :generating="generating"
                    class="payment_status"
                    :label="budgetForm.PAYMENT_STATUS.LABEL"
                    :value="$store.state.budgetMod.budget.payment_status === 'paid' ? 'Pago' : 'Pendente'"
                />
                <iField
                    :generating="generating"
                    class="payment_method"
                    :label="budgetForm.PAYMENT_METHOD.LABEL"
                    :value="$store.state.budgetMod.budget.payment_method === 'pix' ? 'Pix' : 'Boleto'"
                />
                <iField
                    :generating="generating"
                    class="total_weight"
                    label="Peso Total"
                    :value="$store.state.budgetMod.budget.stocks?.map(itemgetter('weight')).reduce((a, c) => a + c , 0) + ' kg'"
                />
                <iField
                    :generating="generating"
                    class="total_quantity"
                    label="Total de elementos"
                    :value="$store.state.budgetMod.budget.stocks?.map(itemgetter('quantity')).reduce((a, c) => a + c , 0)"
                />
                <iField
                    :generating="generating"
                    class="total_pack"
                    label="Total de pacotes"
                    :value="$store.state.budgetMod.budget.stocks?.length"
                />
                <iField
                    :generating="generating"
                    class="client_name"
                    :label="clientForm.NAME.LABEL"
                    :value="$store.state.clientMod.client?.name"
                />
                <iField
                    :generating="generating"
                    class="client_CNPJ"
                    :label="clientForm.CNPJ.LABEL"
                    :value="formatCNPJ($store.state.clientMod.client?.CNPJ || '')"
                />
                <iField
                    :generating="generating"
                    class="client_address"
                    :label="clientForm.ADDRESS.LABEL"
                    :value="$store.state.clientMod.client?.address"
                />
                <iField
                    :generating="generating"
                    class="client_cellphone"
                    :label="clientForm.CELLPHONE.LABEL"
                    :value="formatCellphone($store.state.clientMod.client?.cellphone || '')"
                />
                <iField
                    :generating="generating"
                    class="client_CEP"
                    :label="clientForm.CEP.LABEL"
                    :value="formatCEP($store.state.clientMod.client?.CEP || '')"
                />
                <div class="carrier_title">transportadora</div>
                <div class="client_title">cliente</div>
                <div class="budget_title">serviço</div>
            </div>
            <main>
                <h2>Pacotes</h2>
                <div class="header-stocks">
                    <div>Número</div>
                    <div>Nome</div>
                    <div>Largura | Altura</div>
                    <div>Tipo</div>
                    <div>Quantidade</div>
                    <div>Peso</div>
                </div>
                <ul v-if="$store.state.budgetMod.budget.stocks?.length && $store.state.stockTypeMod.stockTypes.length">
                    <iStock
                        v-for="(el, i) in $store.state.budgetMod.budget.stocks"
                        :key="el.id"
                        :name="el.name"
                        :quantity="el.quantity"
                        :weight="el.weight"
                        :type="$store.state.stockTypeMod.stockTypes.find(({ id }) => id === el.type).name"
                        :extra="el.extra"
                        :sequence="i+1"
                        :generating="generating"
                    />
                </ul>
            </main>
            <footer>
                <h2>Observações</h2>
                <hr/>
                <hr/>
                <hr/>
                <hr/>
            </footer>
        </section>
    </div>
</template>
<script>
import mixins from "@/common/mixins";
import iField from "./iField.vue";
import { formatCellphone, formatCEP, formatCNPJ, handleDate, itemgetter } from "@/common/utils";
import iStock from "./iStock.vue";

export default {
    components: {
        iField,
        iStock,
    },
    props: [
        "logo",
        "generating",
        "CARRIER_CEP",
        "CARRIER_TELEPHONE",
        "CARRIER_CELLPHONE",
        "CARRIER_ADDRESS",
        "CARRIER_CNPJ",
        "CARRIER_COMPLETE_NAME",
        "CARRIER_PARCIAL_NAME",
    ],
    mixins: [mixins],
    methods: {
        
        handleDate,
        itemgetter,
        formatCellphone,
        formatCNPJ,
        formatCEP,
    },
};
</script>
<style scoped>
.container-section {
    padding: 40px;
    box-sizing: border-box;
    background-color: #FFF;
    border-radius: 10px;
    box-shadow:
        0px 0px 1.3px rgba(0, 0, 0, 0.025),
        0px 0px 3.1px rgba(0, 0, 0, 0.036),
        0px 0px 5.8px rgba(0, 0, 0, 0.045),
        0px 0px 10.3px rgba(0, 0, 0, 0.054),
        0px 0px 19.2px rgba(0, 0, 0, 0.065),
        0px 0px 46px rgba(0, 0, 0, 0.09)
    ;
}

.container-section.generating {
    width: 1240px;
    height: 1840px;
}

section {
    border: 1px solid var(--gray-1);
}

main {
    text-transform: uppercase;
    padding: 20px 10px;
    border: 1px solid var(--gray-1);
    height: 650px;
}

main h2 {
    text-align: center;
    margin-bottom: 20px;
    font-size: 20px;
}

header {
    border: 1px solid var(--gray-1);
    display: flex;
    align-items: center;
    padding: 15px;
    gap: 10px;
}

header h1 {
    text-transform: uppercase;
    width: 100%;
}

footer {
    border: 1px solid var(--gray-1);
    text-transform: uppercase;
    padding: 10px 0;
}

footer h2 {
    font-size: 16px;
    padding-bottom: 3px;
}

footer hr {
    height: 1px;
    background-color: var(--gray-1);
    border: none;
    margin-bottom: 21px;
}

.container-trademark {
    display: flex;
    justify-content: center;
    align-items: center;
    min-width: 200px;
    max-width: 200px;
}

.container-trademark img {
    height: 135px;
}

.header-stocks {
    display: grid;
    grid-template-rows: 20px;
    grid-template-columns: 10% 40% 15% 15% 10% 10%;
    margin-bottom: 8px;
    border-bottom: 1px solid var(--gray-1);
    padding-bottom: 5px;
    font-size: 10px;
}

.header-stocks div {
    display: flex;
    align-items: center;
    margin-left: 10px;
}

.header-stocks div:not(:last-child) {
    border-right: 1px solid var(--gray-1);
}

.client_title, .carrier_title, .budget_title  {
    display: flex;
    justify-content: center;
    align-items: center;
    text-transform: uppercase;
    font-weight: 500;
    padding: 10px;
    border: 1px solid var(--gray-1);
}

.number {
    grid-area: num;
}

.updated_at {
    grid-area: upa;
}

.payment_status {
    grid-area: pas;
}

.delivery_date {
    grid-area: ded;
}

.revenue {
    grid-area: rev;
}

.delivery_address {
    grid-area: dea;
}

.provider_name {
    grid-area: prn;
}

.provider_city {
    grid-area: prc;
}

.payment_date {
    grid-area: pad;
}

.unloaded {
    grid-area: unl;
}

.payment_method {
    grid-area: pam;
}

.total_weight {
    grid-area: tow;
}

.total_quantity {
    grid-area: toq;
}

.total_pack {
    grid-area: top;
}

.client_name {
    grid-area: cln;
}

.client_CNPJ {
    grid-area: clc;
}

.client_address {
    grid-area: cla;
}

.client_cellphone {
    grid-area: cle;
}

.client_CEP {
    grid-area: clp;
}

.carrier_CEP {
    grid-area: cap;
}

.carrier_CNPJ {
    grid-area: cac;
}

.carrier_name {
    grid-area: can;
}

.carrier_telephone {
    grid-area: cah;
}

.carrier_cellphone {
    grid-area: cae;
}

.carrier_address {
    grid-area: caa;
}

.emission_at {
    grid-area: ema;
}

.client_title {
    grid-area: clt;
}

.carrier_title {
    grid-area: cat;
}

.budget_title {
    grid-area: but;
}

.header-budget {
    display: grid;
    grid-template-columns: repeat(12, 1fr);
    grid-template-rows: 60px repeat(3, 48px) 60px repeat(3, 48px) 65px 48px 60px repeat(2, 48px) 65px;
    grid-template-areas: 
        'cat cat cat cat cat cat cat cat cat cat cat cat'
        'can can can can can can can can can can can can'
        'caa caa caa caa caa caa caa caa caa caa caa caa'
        'cac cac cac cah cah cah cae cae cae cap cap cap'
        'but but but but but but but but but but but but'
        'num num ded ded rev rev unl unl ema ema upa upa'
        'prc prc prc prc prc prc prc prc prc prc prc prc'
        'prn prn prn prn prn prn prn prn prn prn prn prn'
        'dea dea dea dea dea dea dea dea dea dea dea dea'
        'pad pad pas pas pam pam top top tow tow toq toq'
        'clt clt clt clt clt clt clt clt clt clt clt clt'
        'cln cln cln cln cln cln cln cln cln cln cln cln'
        'clc clc clc clc cle cle cle cle clp clp clp clp'
        'cla cla cla cla cla cla cla cla cla cla cla cla'
    ;
}

@media (max-width: 1100px) {
    .container-section:not(.generating) .header-budget {
        grid-template-columns: repeat(2, 1fr);
        grid-template-rows: 60px repeat(4, 48px) 60px repeat(5, 48px) 65px repeat(3, 48px) 60px repeat(4, 48px) 65px;
        grid-template-areas: 
            'cat cat'
            'can can'
            'caa caa'
            'cac cah'  
            'cae cap'
            'but but'
            'num ded'
            'rev unl'
            'ema upa'
            'prc prc'
            'prn prn'
            'dea dea'
            'pad pas' 
            'pam top'
            'tow toq'
            'clt clt'
            'cln cln'
            'clc clc'
            'cle cle'
            'clp clp'
            'cla cla'
        ;
    }

    .container-section:not(.generating) .header-stocks {
        font-size: 7px;
    }

    .container-section:not(.generating) .header-stocks div {
        margin-left: 3px;
    }
}

</style>