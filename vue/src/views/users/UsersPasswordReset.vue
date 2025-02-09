<template>
    <h1>Redefinição de senha</h1>
    <form @submit.prevent="reset">
        <section>
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
            <iInput 
                :label="userForm.CONFIRM_PASSWORD.LABEL"
                :placeholder="userForm.CONFIRM_PASSWORD.PLACEHOLDER"
                :icon="userForm.CONFIRM_PASSWORD.ICON"
                :type="userForm.CONFIRM_PASSWORD.TYPE"
                :name="userForm.CONFIRM_PASSWORD.NAME"
                :errors="confirmPassword.errors"
                :maxlength="limits.user.password.max"
                v-model="confirmPassword.value"
                @validate="verifyUser"
            />
            <div class="btn">
                <button 
                    class="bButton"
                    type="submit"
                    :style="{backgroundColor: 'var(--yellow-1)'}"
                >
                    Resetar
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
import { consts } from "@/common/consts";



export default {
    mixins: [mixins],
    components: {
        iInput,
    },
    data: () => ({
        [mixins.data().cUser.keys.PASSWORD]: {
            errors: [],
            value: ""
        },
        [mixins.data().cUser.keys.CONFIRM_PASSWORD]: {
            errors: [],
            value: ""
        },
    }),
    methods: {
        reset() {
            const { password, confirmPassword } = this;
            const data = { password, confirmPassword };
            const errors = [];
            Object.keys(data).forEach(key => errors.push(verifyUser(key, this)));
            if (errors.flat().filter(Boolean).length) return this.$store.state.iChoice.open("Ajuste os erros antes de continuar", true);
            this.$store.dispatch("userMod/resetPassword", {...getValues(data), key: this.$route.params.key});
        },
        verifyUser
    },
    mounted() {
        console.log(this.$route.query.key);
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