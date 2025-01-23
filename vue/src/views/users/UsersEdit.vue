<template>
    <h1>Editar Usuário</h1>
    <form @submit.prevent="edit">
        <section>
            <iInput 
                :label="userForm.NAME.LABEL"
                :placeholder="userForm.NAME.PLACEHOLDER"
                :icon="userForm.NAME.ICON"
                :type="userForm.NAME.TYPE"
                :name="userForm.NAME.NAME"
                :errors="name.errors"
                :maxlength="limits.user.name"
                v-model="name.value"
                @validate="verifyUser"
            />
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
            <div class="btn">
                <button 
                    class="bButton" 
                    type="submit"
                    :style="{backgroundColor: 'var(--green-2)'}"
                >
                Salvar
                </button>
            </div>
        </section>
    </form>
</template>

<script>
import mixins from "@/common/mixins";
import iInput from "@/components/common/iInput.vue";
import { verifyUser } from "@/common/validators";
import { getValues } from "@/common/utils";


export default {
    mixins: [mixins],
    data: () => ({
        [mixins.data().user.keys.EMAIL]: {
            errors: [],
            value: ""
        },
        [mixins.data().user.keys.NAME]: {
            errors: [],
            value: ""
        },
    }),

    components: { iInput },

    mounted() {
        this.fill();
    },

    methods: {
        async fill() {
            const id = this.$route.params.id;
            await this.$store.dispatch("userMod/storeUser", id);
            const user = this.$store.state.userMod.user;
            this.name.value = user.name;
            this.email.value = user.email;
        },

        edit() {
            const { name, email } = this;
            const data = { name };
            if (this.$store.state.userMod.user.email !== email.value) {
                data.email = email;
            }
            const errors = [];
            Object.keys(data).forEach(key => errors.push(verifyUser(key, this)));
            if (errors.flat().filter(Boolean).length) return alert("Ajuste os erros antes de continuar");
            const continues = confirm("Esta operação não poderá ser desfeita. Deseja continuar?");
            if (!continues) return;
            this.$store.dispatch("userMod/editUser", {
                ...getValues(data), 
                id: this.$route.params.id
            });
        },
        verifyUser
    },
    beforeCreate() {
        window.scrollTo(0,0);
        this.$store.dispatch("userMod/storeUsers");
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