<template>
    <h1>Usuários</h1>
    <ul v-if="$store.state.userMod.users.length">
        <iCard
            v-for="el in $store.state.userMod.users" 
            :key="el.id"
            :id="el.id"
            :name="el.name"
            :email="el.email"
            :createdAt="el.created_at"
            :updatedAt="el.updated_at"
            :isAdmin="el.type === ADMIN"
            @del="del"
            @edit="edit"
        />
    </ul>
</template>
f
<script>
import iCard from "@/components/users/iCard.vue";
import mixins from "@/common/mixins";
import { endpoints } from "@/common/consts";


export default {
    mixins: [mixins],
    beforeCreate() {
        this.$store.dispatch("userMod/storeUsers");
    },
    components: {
        iCard, 
    },

    methods: {
        del(id) {
            confirm("Tem certeza que deseja excluir?") &&  this.$store.dispatch("userMod/delUser", id);
        },

        edit(id) {
            this.$router.push(endpoints.routes.USER_EDIT.replace(":id", id));
        }
    }
};
</script>