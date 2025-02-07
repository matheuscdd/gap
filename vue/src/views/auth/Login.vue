<template>
    <h1>Login</h1>
    <form @submit.prevent="login">
        <section>
            <iInput 
                :label="userForm.EMAIL.LABEL"
                :placeholder="userForm.EMAIL.PLACEHOLDER"
                :icon="userForm.EMAIL.ICON"
                :type="userForm.EMAIL.TYPE"
                :name="userForm.EMAIL.NAME"
                :errors="email.errors"
                :maxlength="limits.user.email.max"
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
                :maxlength="limits.user.password.max"
                v-model="password.value"
                @validate="verifyUser"
            />
            <div class="btn">
                <button 
                    class="bButton"
                    type="submit"
                    :style="{backgroundColor: 'var(--green-2)'}"
                >
                    Entrar
                </button>
            </div>
        </section>
    </form>

</template>

<script>
import iInput from "@/components/common/iInput.vue";
import { verifyUser } from "@/common/validators";
import mixins from "@/common/mixins";
import { getValues } from "@/common/utils";


export default {
    mixins: [mixins],
    components: {
        iInput,
    },
    data: () => ({
        [mixins.data().cUser.keys.EMAIL]: {
            value: "",
            errors: []
        },
        [mixins.data().cUser.keys.PASSWORD]: {
            value: "",
            errors: []
        },
    }),
    methods: {
        login() {
            const { email, password } = this;
            const data = { email, password };
            const errors = [];
            Object.keys(data).forEach(key => errors.push(verifyUser(key, this)));
            if (errors.flat().filter(Boolean).length) return alert("Ajuste os erros antes de continuar");
            this.$store.dispatch("userMod/storeLogin", getValues(data));
        },
        verifyUser
    },
    beforeCreate() {
        window.scrollTo(0,0);
    }
};
</script>
<style scoped>
h1 {
    text-align: center;
    margin-bottom: 30px;
    margin-top: 20px;
}

form {
    display: flex;
    gap: 20px;
    justify-content: center;
}

.btn {
    margin-top: 25px;
    display: flex;
    justify-content: center;
}
</style>