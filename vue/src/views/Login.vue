<template>
    <h1>Login</h1>
    {{ $store.state.userMod.user }}
    <form>
        <iInput 
            :label="trans.EMAIL"
            placeholder="Digite seu email"
            icon="envelope-solid"
            type="text"
            :name="keys.EMAIL"
            :errors="email.errors"
            v-model="email.value"
            @validate="verifyUser"
        />
        <iInput 
            :label="trans.PASSWORD"
            placeholder="Digite sua senha"
            icon="key-solid"
            type="password"
            :name="keys.PASSWORD"
            :errors="password.errors"
            v-model="password.value"
            @validate="verifyUser"
        />
        <button type="button" @click="login">Logar</button>
    </form>

</template>

<script>
import iInput from "@/components/common/iInput.vue";
import { verifyUser } from "@/common/validators";
import mixins from "@/common/mixins";
import { getErrors, getValues } from "@/common/utils";


export default {
    mixins: [mixins],
    components: {
        iInput
    },
    data: () => ({
        [mixins.data().keys.EMAIL]: {
            value: "",
            errors: []
        },
        [mixins.data().keys.PASSWORD]: {
            value: "",
            errors: []
        },
    }),
    methods: {
        login() {
            const { email, password } = this;
            const data = { email, password };
            if (getErrors(data)) return alert("Ajuste os erros antes de continuar");
            this.$store.dispatch("userMod/storeLogin", getValues(data));
        },
        verifyUser
    },
};
</script>