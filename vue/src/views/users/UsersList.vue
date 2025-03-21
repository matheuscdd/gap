<template>
    <h1>Usuários</h1>
    <ul v-if="!$store.state.loading && $store.state.userMod.users.length">
        <iCard
            v-for="el in $store.state.userMod.users" 
            :key="el.id"
            :id="el.id"
            :name="el.name"
            :email="el.email"
            :photo="el.photo"
            :createdAt="el.created_at"
            :updatedAt="el.updated_at"
            :isAdmin="el.type === 'admin'"
            @del="del"
            @edit="edit"
        />
    </ul>
    <iLoading v-show="$store.state.loading"/>
</template>

<script>
import iCard from "@/components/users/iCard.vue";
import mixins from "@/common/mixins";
import { endpoints } from "@/common/consts";
import iLoading from "@/components/common/iLoading.vue";

export default {
    mixins: [mixins],
    async beforeCreate() {
        window.scrollTo(0,0);
        this.$store.commit("setStartLoading");
        await this.$store.dispatch("userMod/storeUsers");
        this.$store.commit("setStopLoading");
    },
    components: {
        iCard, 
        iLoading,
    },
    methods: {
        async del(id) {
            const continues = await this.$store.state.iChoice.open("Tem certeza que deseja excluir?");
            if (!continues) return;
            this.$store.dispatch("userMod/delUser", id);
        },

        edit(id) {
            this.$router.push(endpoints.routes.USER_EDIT.replace(":id", id));
        },
    },
};
</script>
<style scoped>
h1 {
    text-align: center;
    margin-bottom: 30px;
    margin-top: 20px;
}

ul {
    display: flex;
    gap: 15px;
    flex-direction: column;
}
</style>