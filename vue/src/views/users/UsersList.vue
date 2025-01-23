<template>
    <h1>Usuários</h1>
    <ul class="header">
        <div>Nome</div>
        <div>Email</div>
        <div>Atualização</div>
        <div>Tipo</div>
        <div>Ações</div>
    </ul>
    <ul v-if="$store.state.userMod.users.length">
        <iCard
            v-for="el in $store.state.userMod.users" 
            :key="el.id"
            :id="el.id"
            :name="el.name"
            :email="el.email"
            :createdAt="el.created_at"
            :updatedAt="el.updated_at"
            :isAdmin="el.type === 'admin'"
            @del="del"
            @edit="edit"
        />
    </ul>
</template>

<script>
import iCard from "@/components/users/iCard.vue";
import mixins from "@/common/mixins";
import { endpoints } from "@/common/consts";


export default {
    mixins: [mixins],
    beforeCreate() {
        window.scrollTo(0,0);
        this.$store.dispatch("userMod/storeUsers");
    },
    components: {
        iCard, 
    },
    methods: {
        del(id) {
            const continues = confirm("Tem certeza que deseja excluir?");
            if (!continues) return;
            this.$store.dispatch("userMod/delUser", id);
        },

        edit(id) {
            this.$router.push(endpoints.routes.USER_EDIT.replace(":id", id));
        }
    },
};
</script>
<style scoped>
h1 {
    text-align: center;
    margin-bottom: 30px;
    margin-top: 20px;
}

.header {
    display: grid;
    grid-template-columns: 25% 25% 20% 10% 20%;
    grid-auto-rows: 30px;
    grid-gap: 10px;
    margin-bottom: 10px;
    text-align: center;
    padding: 0 20px;
}
</style>    {{ $store.state.userMod.users }}