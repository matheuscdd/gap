<template>
    <h1>Login</h1>
    <form @submit.prevent="login">
        <iInput 
            :label="userForm.EMAIL.LABEL"
            :placeholder="userForm.EMAIL.PLACEHOLDER"
            :icon="userForm.EMAIL.ICON"
            :type="userForm.EMAIL.TYPE"
            :name="userForm.EMAIL.NAME"
            :errors="email.errors"
            :maxlength="limits.user.email"
            v-model="email.value"
            @validate="verifyUser"
        />
        <iInput 
            :label="userForm.PASSWORD.LABEL"
            :placeholder="userForm.PASSWORD.PLACEHOLDER"
            :icon="userForm.PASSWORD.ICON"
            :type="userForm.PASSWORD.TYPE"
            :name="userForm.PASSWORD.NAME"
            :errors="password.errors"
            :maxlength="limits.user.password"
            v-model="password.value"
            @validate="verifyUser"
        />
        <button 
            class="bButton"
            type="submit"
            :style="{backgroundColor: 'var(--green-2)'}"
        >
            Entrar
        </button>
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
        iInput,
    },
    data: () => ({
        [mixins.data().user.keys.EMAIL]: {
            value: "",
            errors: []
        },
        [mixins.data().user.keys.PASSWORD]: {
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
    beforeCreate() {
        window.scrollTo(0,0);
    }
};
</script>