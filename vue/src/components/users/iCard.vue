<template>
    <li>
        <div :class="userForm.NAME.NAME">{{ name }}</div>
        <div :class="userForm.EMAIL.NAME">{{ email }}</div>
        <div class="createdAt">{{ createdAt.toLocaleString("pt-BR") }}</div>
        <div class="updatedAt">{{ updatedAt.toLocaleString("pt-BR") }}</div>
        <div>
            <button
                @click="edit"
                :disabled="((getNow() - createdAt) / (1000 * 60)) > 60"
            >
                Edit
            </button>
        </div>
        <div>
            <button 
                @click="del" 
                :disabled="$store.state.userMod.logged.id === id"
            >
                Delete
            </button>
        </div>
    </li>
</template>
<script>
import mixins from "@/common/mixins";
import { getNow } from "@/common/utils";


export default {
    mixins: [mixins],
    props: [
        "id",
        "name",
        "email",
        "createdAt",
        "updatedAt",
        "isAdmin",
    ],
    methods: {
        edit() {
            this.$emit("edit", this.id);
        },

        del() {
            this.$emit("del", this.id);
        },
        getNow,
    }
};
</script>

<style scoped>
li {
    padding: 20px;
    background-color: rgb(214, 214, 214);
    display: grid;
    grid-template-columns: 25% 25% 20% 20% 5% 5%;
    grid-auto-rows: 30px;
    grid-gap: 10px;
    margin-bottom: 10px;
}

li > div {
    line-height: 2;
    width: max-content
}

.name {
    font-weight: 500;
}
</style>