<template>
    <h1>Usuários</h1>
    <ul v-if="$store.state.users.length">
        <iCard
            v-for="el in $store.state.users" 
            :key="el.id"
            :id="el.id"
            :name="el.name"
            :email="el.email"
            :createdAt="el.created_at"
            :updatedAt="el.updated_at"
            :isAdmin="el.type === ADMIN"
        />
    </ul>
</template>

<script>
import iCard from "@/components/users/iCard.vue";
import consts from "@/common/mixins";


export default {
    mixins: [consts],
    beforeCreate() {
        this.$store.dispatch("storeUsers");
    },
    components: {
        iCard, 
    },
};
</script>