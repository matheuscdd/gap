<template>
    <h1>Recuperar senha</h1>
    <form @submit.prevent="lost">
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
            <div class="btn">
                
                <button 
                    class="bButton"
                    type="submit"
                    :style="{backgroundColor: 'var(--green-2)'}"
                >
                    Enviar
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
    }),
    methods: {
        lost() {
            const data = { email: this.email };
            const errors = [];
            Object.keys(data).forEach(key => errors.push(verifyUser(key, this)));
            if (errors.flat().filter(Boolean).length) return this.$store.state.iChoice.open("Ajuste os erros antes de continuar", true);
            this.$store.dispatch("userMod/lostPassword", getValues(data));
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