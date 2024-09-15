<template>
    <h1>Editar cliente</h1>
    <form @submit.prevent="edit">
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

    mounted() {
        this.fill();
    },
    methods: {
        async fill() {
            const id = this.$route.params.id;
            await this.$store.dispatch("clientMod/storeClient", id);
            const client = this.$store.state.clientMod.client;
            for (const key in client) {
                if (!this[key]) continue;
                this[key].value = client[key];
            }
        },
        
        edit() {
            const { CNPJ, CEP, name, address, cellphone } = this;
            const data = { CEP, name, address };
            const { client } = this.$store.state.clientMod;
            if (client.cellphone !== cellphone.value) {
                data.cellphone = cellphone;
            }
            if (client.CNPJ !== CNPJ.value) {
                data.CNPJ = CNPJ;
            }
            if (getErrors(data)) return alert("Ajuste os erros antes de continuar");
            this.$store.dispatch("clientMod/editClient", {
                ...getValues(data), 
                id: this.$route.params.id
            });
        },
        verifyClient
    },
    beforeCreate() {
        window.scrollTo(0,0);
    }
};
</script>