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
        />
    </ul>
</template>
f
<script>
import iCard from "@/components/users/iCard.vue";
import mixins from "@/common/mixins";


export default {
    mixins: [mixins],
    beforeCreate() {
        this.$store.dispatch("userMod/storeUsers");
    },
    components: {
        iCard, 
    },
    methods: {
        del(selUserId) {
            console.log(selUserId);
        }
    }
};
</script>