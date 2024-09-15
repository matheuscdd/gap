<template>
    <h1>Criar cliente</h1>
    <form @submit.prevent="create">
        <iInput 
            :label="clientForm.NAME.LABEL"
            :placeholder="clientForm.NAME.PLACEHOLDER"
            :icon="clientForm.NAME.ICON"
            :type="clientForm.NAME.TYPE"
            :name="clientForm.NAME.NAME"
            :maxlength="limits.client.name.max"
            :errors="name.errors"
            v-model="name.value"
            @validate="verifyClient"
        />
        <iInput 
            :label="clientForm.CNPJ.LABEL"
            :placeholder="clientForm.CNPJ.PLACEHOLDER"
            :icon="clientForm.CNPJ.ICON"
            :type="clientForm.CNPJ.TYPE"
            :name="clientForm.CNPJ.NAME"
            :maxlength="limits.client.CNPJ.size"
            :errors="CNPJ.errors"
            v-model="CNPJ.value"
            @validate="verifyClient"
        />
        <iInput 
            :label="clientForm.CEP.LABEL"
            :placeholder="clientForm.CEP.PLACEHOLDER"
            :icon="clientForm.CEP.ICON"
            :type="clientForm.CEP.TYPE"
            :name="clientForm.CEP.NAME"
            :maxlength="limits.client.CEP.size"
            :errors="CEP.errors"
            v-model="CEP.value"
            @validate="verifyClient"
        />
        <iInput 
            :label="clientForm.ADDRESS.LABEL"
            :placeholder="clientForm.ADDRESS.PLACEHOLDER"
            :icon="clientForm.ADDRESS.ICON"
            :type="clientForm.ADDRESS.TYPE"
            :name="clientForm.ADDRESS.NAME"
            :maxlength="limits.client.address.max"
            :errors="address.errors"
            v-model="address.value"
            @validate="verifyClient"
        />
        <iInput 
            :label="clientForm.CELLPHONE.LABEL"
            :placeholder="clientForm.CELLPHONE.PLACEHOLDER"
            :icon="clientForm.CELLPHONE.ICON"
            :type="clientForm.CELLPHONE.TYPE"
            :name="clientForm.CELLPHONE.NAME"
            :maxlength="limits.client.cellphone.size"
            :errors="cellphone.errors"
            v-model="cellphone.value"
            @validate="verifyClient"
        />
        <button type="submit">Salvar</button>
    </form>
</template>

<script>
import mixins from "@/common/mixins";
import { verifyClient } from "@/common/validators";
import iInput from "@/components/common/iInput.vue";
import { getErrors, getValues } from "@/common/utils";

export default {
    data: () => ({
        [mixins.data().client.keys.NAME]: {
            errors: [],
            value: ""
        },
        [mixins.data().client.keys.CNPJ]: {
            errors: [],
            value: ""
        },
        [mixins.data().client.keys.CEP]: {
            errors: [],
            value: ""
        },
        [mixins.data().client.keys.ADDRESS]: {
            errors: [],
            value: ""
        },
        [mixins.data().client.keys.CELLPHONE]: {
            errors: [],
            value: ""
        },
    }),

    mixins: [mixins],
    components: {
        iInput
    },
    methods: {
        create() {
            const { CNPJ, CEP, name, address, cellphone } = this;
            const data = { CNPJ, CEP, name, address, cellphone };
            if (getErrors(data)) return alert("Ajuste os erros antes de continuar");
            console.log({...data});
            this.$store.dispatch("clientMod/createClient", getValues(data));
        },
        verifyClient
    },
    beforeCreate() {
        window.scrollTo(0,0);
    }
};
</script>